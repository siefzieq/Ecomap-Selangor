<?php

namespace App\Http\Controllers;

use App\Models\Flat;
use App\Models\Plant;
use App\Models\PlantInventory;
use App\Models\Seeding;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SeedingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $month = $request->query('month');
        $plantId = $request->query('plant');
        $search = $request->query('search');

        $query = Seeding::query();

        if ($month) {
            $query->whereMonth('date', Carbon::parse($month)->month);
        }

        if ($plantId) {
            $query->where('plant_id', $plantId);
        }

        if ($search) {
            $query->whereHas('plant', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        // Add conditions to check that flat_id and quantity are not null/0
        $query->whereNotNull('flat_id')->where('flat_id', '!=', 0)
            ->whereNotNull('quantity')->where('quantity', '!=', 0);

        $seeding = $query->paginate(10);
        $plants = Plant::all(); // Assuming you need all plants for the filter

        // Group data for the chart by plant name and sum quantities
        $inventoryData = $query->get()->groupBy('plant.name')->map(function($item) {
            return $item->sum('quantity');
        });

        return view('seeding.index', compact('seeding', 'plants', 'inventoryData', 'month', 'plantId', 'search'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $plants = Plant::all();
        $flats = Flat::all();
        return view("seeding.create", compact("plants", "flats"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        //return $request;
        $validated = $request->validate([
            'date' => 'required|date|before_or_equal:today',
            'plant_id' => 'required|integer|exists:plants,id',
            'flat_id' => 'required|integer|min:0|exists:flats,id',
            'quantity' => 'required|integer|min:0',
        ]);

        // Retrieve the plant record
        $plant = Plant::find($validated['plant_id']);

        // Check if there is enough stock available
        if ($plant->stocks < $validated['quantity']) {
            return redirect()->back()->withInput()->with('toastr', json_encode([
                'type' => 'error',
                'message' => 'Not enough stock available'
            ]));
        }

        // Create a new seeding record
        $seeding = new Seeding([
            'date' => $validated['date'],
            'plant_id' => $validated['plant_id'],
            'flat_id' => $validated['flat_id'],
            'quantity' => $validated['quantity'],
            'updated_by' => auth()->user()->id,
        ]);

        $seeding->save();

        // Update the stocks column in the plants table
        $plant->stocks -= $validated['quantity'];
        $plant->save();

        return redirect()->route('seeding.index')->with('toastr', json_encode([
            'type' => 'success',
            'message' => 'Record added successfully'
        ]));
    }


    /**
     * Display the specified resource.
     */
    public function show(Seeding $seeding)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Seeding $seeding)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Seeding $seeding)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Seeding $seeding)
    {
        //
    }
}
