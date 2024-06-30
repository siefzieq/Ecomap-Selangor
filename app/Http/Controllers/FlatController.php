<?php

namespace App\Http\Controllers;

use App\Models\Flat;
use App\Models\FlatProgress;
use Illuminate\Http\Request;

class FlatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $flats=Flat::all();
        return view('flat.index',compact('flats'));
    }

    public function fetchFlatInfo(Request $request)
    {
        $flatName = $request->input('name');
        $flat = Flat::where('name', $flatName)->first();

        if ($flat) {
            return response()->json([
                'status' => 'success',
                'longitude' => $flat->longitude,
                'latitude' => $flat->latitude,
                'city' => $flat->city,
            ]);
        } else {
            return response()->json([
                'status' => 'not_found',
            ]);
        }
    }

    public function create()
    {
        return view('flat.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //return $request;
        $validated = $request->validate([
            'name'=>'required|min:4|string|max:255',
            'address'=>'min:4|string|max:255',
            'longitude' => 'required|between:-180,180',
            'latitude' => 'required|between:-90,90',
            'city' => 'min:4|string|max:255',
        ]);

        $flat = new Flat($validated);

        if ($flat->save()) {
            return redirect()->route('flat.index')->with('toastr', json_encode([
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
    public function show(Flat $flat)
    {
        $progress = FlatProgress::where('flat_id', $flat->id)->get();
        return view('flat.show', compact('flat', 'progress'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Flat $flat)
    {
        return view('flat.edit',compact('flat'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Flat $flat)
    {
        $validated = $request->validate([
            'name'=>'min:4|string|max:255',
            'address'=>'min:4|string|max:255',
            'longitude' => 'between:-180,180',
            'latitude' => 'between:-90,90',
            'city' => 'min:4|string|max:255',
        ]);

        $flat=$flat->update($validated);

        if ($flat){
            return redirect()->route('flat.index')->with('toastr', json_encode([
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
    public function destroy(Flat $flat)
    {
        $flat->delete();
        return redirect()->route('flat.index');
    }
}
