<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use App\Models\Plantation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PlantationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Plantation::query();

        // Apply planting type filter if provided
        if ($request->has('planting_type') && $request->planting_type != '') {
            $query->where('planting_type', $request->planting_type);
        }

        // Apply category filter if provided
        if ($request->has('category') && $request->category != '') {
            $query->whereHas('plant', function ($q) use ($request) {
                $q->where('category', $request->category);
            });
        }

        // Apply search filter if provided
        if ($request->has('name') && $request->name != '') {
            $query->whereHas('plant', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%');
            });
        }

        // Paginate the final filtered results
        $plantation = $query->paginate(5);

        // Get the counts for the statistics (assuming these methods are already defined)
        $plant_fertigation = Plantation::where('planting_type', 'fertigation')->count();
        $plant_aquaponic = Plantation::where('planting_type', 'aquaponic')->count();
        $plant_conventional = Plantation::where('planting_type', 'conventional')->count();

        return view('plantation.index', compact('plantation', 'plant_fertigation', 'plant_aquaponic', 'plant_conventional'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $plants=Plant::all();
        return view('plantation.create',compact('plants'));
    }

    public function fetchPlantationInfo(Request $request)
    {
        $plantId = $request->input('plant_id');
        $plantingType = $request->input('planting_type');

        // Find the plantation by plant_id and planting_type
        $plantation = Plantation::where('plant_id', $plantId)
            ->where('planting_type', $plantingType)
            ->first();

        if ($plantation) {
            // Retrieve the plant's category and description from the related Plant model
            $plant = $plantation->plant;
            return response()->json([
                'status' => 'success',
                'category' => $plant->category,
                'description' => $plant->description
            ]);
        } else {
            return response()->json([
                'status' => 'not_found'
            ]);
        }
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //return $request;
        $validated = $request->validate([
            'plant_id' => 'required|integer|exists:plants,id',
            'planting_type' => 'required|min:6|in:fertigation,conventional,aquaponic',
            'seeding_duration' => 'required|integer|min:0',
            'harvesting_duration' => 'required|integer|min:0',
            'completion_duration' => 'required|integer|min:0',
        ]);

        $plantation = new Plantation([
            'plant_id' => $validated['plant_id'],
            'planting_type' => $validated['planting_type'],
            'seeding_duration' => $validated['seeding_duration'],
            'harvesting_duration' => $validated['harvesting_duration'],
            'completion_duration' => $validated['completion_duration'],
            'updated_by' => auth()->user()->id,
        ]);

        if ($plantation->save()) {
            return redirect()->route('plantation.index')->with('toastr', json_encode([
                'type' => 'success',
                'message' => 'New record added successfully'
            ]));

        } else {
            return redirect()->back()->withInput()->with('toastr', json_encode(['type' => 'error', 'message' => 'Failed to add new record']));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Plantation $plantation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Plantation $plantation)
    {
        $plants=Plant::all();
        return view('plantation.edit',compact('plantation','plants'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Plantation $plantation)
    {
        $validated = $request->validate([
            'plant_id' => 'integer|exists:plants,id',
            'planting_type' => 'min:6|in:fertigation,conventional,aquaponic',
            'seeding_duration' => 'integer|min:0',
            'harvesting_duration' => 'integer|min:0',
            'completion_duration' => 'integer|min:0',
        ]);

        $plantation->update(
            ['plant_id' => $validated['plant_id'],
                'planting_type' => $validated['planting_type'],
                'seeding_duration' => $validated['seeding_duration'],
                'harvesting_duration' => $validated['harvesting_duration'],
                'completion_duration' => $validated['completion_duration'],
                'updated_by' => auth()->user()->id,]);

        if ($plantation->update()) {
            return redirect()->route('plantation.index')->with('toastr', json_encode([
                'type' => 'success',
                'message' => 'Record is updated successfully'
            ]));

        } else {
            return redirect()->back()->withInput()->with('toastr', json_encode(['type' => 'error', 'message' => 'Failed to update record']));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plantation $plantation)
    {
        $plantation->delete();
        return redirect()->route('plantation.index');
    }
}
