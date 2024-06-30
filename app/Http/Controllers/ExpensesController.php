<?php

namespace App\Http\Controllers;

use App\Models\Expenses;
use App\Models\Flat;
use App\Models\Plant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpensesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $flats=Flat::all();
        $plants=Plant::all();

        $expenses= Expenses::all();

        $expensesData = $expenses->groupBy('flat.name')->map(function ($itemsByFlat) {
            return $itemsByFlat->groupBy('category')->map(function ($itemsByCategory) {
                return $itemsByCategory->sum('total'); // Summing up 'total' for each category
            });
        });

        $expensesList = Expenses::with('flat')
            ->orderBy('date', 'desc')
            ->get()
            ->groupBy(function($item) {
                return Carbon::parse($item->date)->format('d F Y'); // Group by formatted date
            })
            ->map(function($items) {
                return [
                    'items' => $items,
                    'total' => $items->sum('total') // Calculate the total for each date if needed
                ];
            });



        return view('expenses.index',compact('expenses','flats','plants', 'expensesData','expensesList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $flats=Flat::all();
        $plants=Plant::all();
        return view('expenses.create',compact('flats','plants'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'date' => 'required|date|before_or_equal:today',
                'flat_id' => 'required|integer|exists:flats,id',
                'item_name' => 'required|min:4|string',
                'category' => 'required|string|in:setup_infrastructure,equipment,operational,maintenance',
                'type' => 'required|string|in:site preparation,growing medium,planting equipment,harvesting tools,irrigation system,lighting,seeds,fertilizers,pesticides,water & utilities,repairs',
                'plant_id' => 'nullable|integer|exists:plants,id',
                'amount' => 'required|numeric',
                'quantity' => 'required|numeric',
                'total' => 'numeric',
                'file_path' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx,ppt,pptx,xsl,xlsx|max:2048',
            ]);

            \Log::info('Validation successful:', $validated);

            // Use null coalescing operator to handle optional 'plant_id'
            $plant_id = $request->input('plant_id') ?? null;

            // Create the Expenses instance
            $expenses = new Expenses([
                'date' => $validated['date'],
                'flat_id' => $validated['flat_id'],
                'item_name' => $validated['item_name'],
                'category' => $validated['category'],
                'type' => $validated['type'],
                'plant_id' => $plant_id,
                'amount' => $validated['amount'],
                'quantity' => $validated['quantity'],
                'total' => $validated['total'],
            ]);

            \Log::info('Expenses instance created:', $expenses->toArray());

            // Handle file upload if present
            if ($request->hasFile('file_path')) {
                \Log::info('File is present in the request.');

                $file = $request->file('file_path');
                $name = time() . '.' . $file->getClientOriginalExtension();
                \Log::info('Uploaded file details:', [
                    'original_name' => $file->getClientOriginalName(),
                    'extension' => $file->getClientOriginalExtension(),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                ]);

                $storagePath = 'documents';

                \Log::info('Determined storage path: ' . $storagePath);

                $filePath = $file->storeAs($storagePath, $name, 'public');
                \Log::info('Stored file path: ' . $filePath);

                // Set the file_path attribute to the stored file path
                $expenses->file_path = '/storage/' . $filePath;
            } else {
                \Log::info('No file present in the request.');
            }

            \Log::info('Expenses data before saving:', $expenses->toArray());

            // Save the expenses record
            if ($expenses->save()) {
                \Log::info('Expenses record saved successfully.');
                return redirect()->route('expenses.index')->with('toastr', json_encode([
                    'type' => 'success',
                    'message' => 'New record added successfully'
                ]));
            } else {
                \Log::error('Failed to save expenses record.');
                return redirect()->back()->withInput()->with('toastr', json_encode(['type' => 'error', 'message' => 'Failed to add new record']));
            }
        } catch (\Exception $e) {
            \Log::error('Exception caught during saving expenses:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->back()->withInput()->with('toastr', json_encode(['type' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()]));
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Expenses $expenses)
    {
        //
    }

    public function edit($id)
    {
        $expenses = Expenses::find($id);
        $flats=Flat::all();
        $plants=Plant::all();

        return view('expenses.edit', compact('expenses','flats','plants'));
    }

    public function update(Request $request, $id)
    {

        $validated = $request->validate([
            'date' => 'date|',
            'flat_id' => 'integer|exists:flats,id',
            'item_name' => 'min:4|string|max:255',
            'category'=>'string|in:setup_infrastructure,equipment,operational,maintenance',
            'type'=>'string|in:site preparation,growing medium,planting equipment,harvesting tools,irrigation system,lighting,seeds,fertilizers,pesticides,water & utilities,repairs',
            'plant_id' => 'integer|exists:plants,id',
            'amount'=>'numeric',
            'quantity' => 'numeric',
            'total' => 'numeric',
        ]);
        $expenses = Expenses::find($id);
        $expenses->update($validated);

        if ($expenses->update($validated)){
            return redirect()->route('expenses.index')
                ->withSuccess('New record updated successfully');
        }
        else{
            return redirect()->back()
                ->withInput()
                ->withError('Failed to update new record');
        }
    }

    public function destroy($id)
    {
        $expenses = Expenses::find($id);

        if($expenses){
            $expenses->delete();
            return redirect()->route('expenses.index');
        }

    }
}
