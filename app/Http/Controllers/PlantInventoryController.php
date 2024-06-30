<?php

namespace App\Http\Controllers;

use App\Models\Flat;
use App\Models\Plant;
use App\Models\PlantInventory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PlantInventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $month = $request->query('month');
        $plantId = $request->query('plant');
        $search = $request->query('search');

        $query = PlantInventory::query();

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

        // Sort by date in descending order
        $query->orderBy('date', 'desc');

        $inventory = $query->paginate(10);
        $plants = Plant::all(); // Assuming you need all plants for the filter

        // Group data for the chart
        $inventoryData = $inventory->groupBy(function($item) {
            return Carbon::parse($item->date)->format('M');
        })->map(function($item) {
            return $item->groupBy('plant.name')->map(function($plantItems) {
                return $plantItems->sum('in_stock');
            });
        });

        return view('plantInventory.index', compact('inventory', 'plants', 'inventoryData', 'month', 'plantId', 'search'));
    }






    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $flats=Flat::all();
        $plants=Plant::all();
        return view('plantInventory.create',compact('plants','flats'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'date' => 'required|date|before_or_equal:today',
            'plant_id' => 'required|integer|exists:plants,id',
            'in_stock' => 'integer|min:0|nullable',
        ]);


        // Create a new inventory record
        $inventory = new PlantInventory([
            'date' => $validated['date'],
            'plant_id' => $validated['plant_id'],
            'in_stock' => $validated['in_stock'],
            'updated_by' => auth()->user()->id,
        ]);

        $inventory->save();


        // Update the stocks column in the plants table
        $plant = Plant::find($validated['plant_id']);
        if ($plant) {
            $plant->stocks += $validated['in_stock'];
            $plant->save();
        }

        return redirect()->route('plantInventory.index')->with('toastr', json_encode([
            'type' => 'success',
            'message' => 'Record added successfully'
        ]));
    }





    /**
     * Display the specified resource.
     */
    public function show(PlantInventory $plantInventory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PlantInventory $plantInventory)
    {
        return view('plantInventory.create',compact('plantInventory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PlantInventory $plantInventory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PlantInventory $plantInventory)
    {
        $plantInventory->delete();
        return redirect()->route('plantInventory.index');
    }
}
