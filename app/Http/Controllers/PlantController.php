<?php

namespace App\Http\Controllers;
use App\Models\Expenses;
use Flasher\Toastr\Prime\Toastr;
use Flasher\Prime\Notification\Envelope;
use App\Models\Plant;
use Illuminate\Http\Request;

class PlantController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        // Start the query to filter and paginate plants
        $query = Plant::query();

        // Apply category filter if provided
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        // Apply search filter if provided
        if ($request->has('name') && $request->name != '') {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // Paginate the final filtered results
        $plants = $query->paginate(5);

        // Calculate counts for specific categories or all plants
        $plant_count = Plant::count();
        $plant_berbuah = Plant::where('category', 'sayur buah')->count();
        $plant_berdaun = Plant::where('category', 'sayur berdaun')->count();

        // Pass the data to the view
        return view('plant.index', compact('plants', 'plant_count', 'plant_berbuah', 'plant_berdaun'));
    }


    public function fetchPlantInfo(Request $request)
    {
        $plantName = $request->input('name');
        $plant = Plant::where('name', $plantName)->first();

        if ($plant) {
            return response()->json([
                'status' => 'success',
                'category' => $plant->category,
                'description' => $plant->description,
            ]);
        } else {
            return response()->json([
                'status' => 'not_found',
            ]);
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('plant.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'min:4|string|max:255',
            'category' => 'min:6|in:sayur berdaun,sayur buah',
            'description' => 'nullable|min:4|string|max:255',
            'stocks' => 'nullable|min:0|integer',
            'file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $plant = new Plant([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'description' => $validated['description'],
            'stocks' => $validated['stocks']
        ]);

        if ($request->hasFile('image_path')) {
            $image = $request->file('image_path');
            $name = time() . '.' . $image->getClientOriginalExtension();

            // Store the image in the public disk under the 'images' directory
            $image->storeAs('images', $name, 'public');

            // Set the image_path attribute to the stored image path
            $plant->image_path = '/storage/images/' . $name;
        }

        if ($plant->save($validated)) {
            return redirect()->route('plant.index')->with('toastr', json_encode([
                'type' => 'success',
                'message' => 'New Record added successfully'
            ]));

        } else {
            return redirect()->back()->withInput()->with('toastr', json_encode(['type' => 'error', 'message' => 'Failed to add record']));
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Plant $plant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Plant $plant)
    {
        return view('plant.edit',compact('plant'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Plant $plant)
    {
        $validated = $request->validate([
            'name' => 'min:4|string|max:255',
            'category' => 'min:6|in:sayur berdaun,sayur buah',
            'description' => 'nullable|min:4|string|max:255',
            'stocks' => 'nullable|min:0|integer',
            'file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate image file
        ]);

        $plant->update($validated);
        if ($request->hasFile('image_path')) {
            $image = $request->file('image_path');
            $name = time() . '.' . $image->getClientOriginalExtension();

            // Store the image in the public disk under the 'images' directory
            $image->storeAs('images', $name, 'public');

            // Set the image_path attribute to the stored image path
            $plant->image_path = '/storage/images/' . $name;
        }

        if ($plant->update($validated)) {
            return redirect()->route('plant.index')->with('toastr', json_encode([
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
    public function destroy(Plant $plant)
    {
        $plant->delete();
        return redirect()->route('plant.index');
    }
}
