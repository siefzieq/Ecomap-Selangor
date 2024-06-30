<?php

namespace App\Http\Controllers;

use App\Models\Flat;
use App\Models\FlatProgress;
use App\Models\Plant;
use App\Models\Plantation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FlatProgressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $plants=Plant::all();
        // Retrieve filters
        $month = $request->input('month');
        $plant = $request->input('plant');
        $search = $request->input('search');

        // Apply filters to the query
        $flatProgressQuery = FlatProgress::with(['flat', 'plantation.plant']);

        if ($month) {
            $flatProgressQuery->whereMonth('start_date', Carbon::parse($month)->month);
        }

        if ($plant) {
            $flatProgressQuery->whereHas('plantation.plant', function ($query) use ($plant) {
                $query->where('id', $plant);
            });
        }

        if ($search) {
            $flatProgressQuery->where(function ($query) use ($search) {
                $query->whereHas('flat', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%$search%");
                })->orWhereHas('plantation.plant', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%$search%");
                })->orWhere('stage', 'LIKE', "%$search%");
            });
        }

        // Get paginated results
        $flatProgressPage = $flatProgressQuery->paginate(10);

        // Get all data for the charts
        $flatProgresses = $flatProgressQuery->get();

        // Prepare data for the chart
        $flatsPerStage = [
            'Seeding' => 0,
            'Harvesting' => 0,
            'Completed' => 0,
        ];

        foreach ($flatProgresses as $progress) {
            $stage = $progress->stage ?? 'Unknown';  // Handle potential null values gracefully
            if (isset($flatsPerStage[$stage])) {
                $flatsPerStage[$stage]++;
            }
        }

        $stageData = [];
        foreach ($flatsPerStage as $stage => $count) {
            $stageData[] = ['stage' => $stage, 'count' => $count];
        }

        return view('flatInventory.index', compact('flatProgressPage', 'plants','stageData'));
    }




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $flatProgress=FlatProgress::all();
        $flats = Flat::all();
        $plants = Plant::all();
        $plantation = Plantation::all();
        $plantationData = Plantation::with('plant')->get()->groupBy('planting_type');

        return view('flatInventory.create', compact('flats','flatProgress','plants','plantation','plantationData'));
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        //return $request;
        $validated = $request->validate([
            'start_date' => 'required|date|before_or_equal:today',
            'flat_id' => 'required|integer|exists:flats,id',
            'planting_type' => 'required|min:6|in:fertigation,conventional,aquaponic',
            'plantation_id' => 'required|integer|exists:plantations,id',
            'area_planted' => 'required|numeric',
            'stage' => 'required|string|in:seeding,harvesting,completion',
        ]);

        $start_date = Carbon::parse($validated['start_date']);
        $plantation_id = $validated['plantation_id'];
        $stage = $validated['stage'];
        $duration_column = $stage . '_duration';
        $duration = Plantation::where('id', $plantation_id)->pluck($duration_column)->first();

// Check if duration is null
        if (is_null($duration)) {
            throw new \Exception('Invalid planting type or plantation ID');
        }

        $expected_date = $start_date->copy()->addDays($duration);

        $flatProgress = new FlatProgress([
            'start_date' => $start_date,
            'expected_date' => $expected_date,
            'flat_id' => $validated['flat_id'],
            'planting_type' => $validated['planting_type'],
            'plantation_id' => $validated['plantation_id'],
            'area_planted' => $validated['area_planted'],
            'stage' => $validated['stage'],
            'progress_status' => 'Ongoing',
            'updated_by' => auth()->user()->id
        ]);

        $flatProgress->save();

        // Redirect or return response
        return redirect()->route('flatInventory.index')->with('success', 'Flat progress added successfully!');
    }



    /**
     * Display the specified resource.
     */
    public function show(FlatProgress $flatProgress)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $flatProgress = FlatProgress::find($id);
        $flats = Flat::all();
        $plants = Plant::all();
        $plantation = Plantation::all();
        $plantationData = Plantation::with('plant')->get()->groupBy('planting_type');


        return view('flatInventory.edit', compact('flats','flatProgress','plants','plantation','plantationData'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate the request data
        //return $request;
        $validated = $request->validate([
            'start_date' => 'date',
            'flat_id' => 'integer|exists:flats,id',
            'planting_type' => 'min:6|in:fertigation,conventional,aquaponic',
            'plantation_id' => 'integer|exists:plantations,id',
            'area_planted' => 'numeric',
            'stage' => 'string|in:seeding,harvesting,completion',
            'updated_by' => auth()->user()->id
        ]);

        $start_date = Carbon::parse($validated['start_date']);
        $plantation_id = $validated['plantation_id'];
        $stage = $validated['stage'];
        $duration_column = $stage . '_duration';
        $duration = Plantation::where('id', $plantation_id)->pluck($duration_column)->first();

// Check if duration is null
        if (is_null($duration)) {
            throw new \Exception('Invalid planting type or plantation ID');
        }

        $expected_date = $start_date->copy()->addDays($duration);

        $flatProgress = FlatProgress::find($id);
        $flatProgress->update([
            'start_date' => $start_date,
            'expected_date' => $expected_date,
            'flat_id' => $validated['flat_id'],
            'planting_type' => $validated['planting_type'],
            'plantation_id' => $validated['plantation_id'],
            'area_planted' => $validated['area_planted'],
            'stage' => $validated['stage'],
            'progress_status' => 'Not started',
            'updated_by' => auth()->user()->id,
        ]);


        // Redirect or return response
        return redirect()->route('flatInventory.index')->with('success', 'Flat progress added successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $flatProgress = FlatProgress::find($id);

        if ($flatProgress) {
            $flatProgress->delete();
            return redirect()->route('flatInventory.index')->with('success', 'Record deleted successfully');
        }

        return redirect()->route('flatInventory.index')->with('error', 'Record not found');
    }
}
