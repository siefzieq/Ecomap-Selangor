<?php

namespace App\Http\Controllers;

use App\Models\Expenses;
use App\Models\Flat;
use App\Models\FlatProgress;
use App\Models\Plant;
use App\Models\Plantation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    {
        $plants = Plantation::select('planting_type', DB::raw('count(*) as count'))
            ->groupBy('planting_type')
            ->get()
            ->map(function ($item) {
                return [
                    'name' => ucfirst($item->planting_type), // Capitalize the first letter
                    'y' => $item->count
                ];
            });


        $expenses = Expenses::select(
            DB::raw('total, DATE_FORMAT(date, "%Y-%m") as month')
        )
            ->get()
            ->groupBy('month')
            ->map(function ($item, $key) {
                return [
                    'name' => Carbon::createFromFormat('Y-m', $key)->format('F'), // Format as 'Month Year'
                    'y' => $item->sum('total')   // Sum of expenses for the month
                ];
            })
            ->sortBy(function ($item) {
                return Carbon::parse($item['name']); // Sort by month
            })
            ->values(); // Reset keys after sorting

        $expensesbyFlats = Expenses::select('flat_id', 'flats.name as flat_name', DB::raw('SUM(total) as total_expenses'))
            ->join('flats', 'flats.id', '=', 'expenses.flat_id')
            ->whereNull('expenses.deleted_at')  // Ensure you handle soft deletes if applicable
            ->groupBy('flat_id', 'flats.name')  // Group by both flat_id and flat_name
            ->orderByDesc('total_expenses')
            ->take(10)
            ->get();

        $stagesByFlats = FlatProgress::select('plants.name as plant_name', 'stage', DB::raw('COUNT(DISTINCT flat_id) as flats_count'))
            ->join('plantations', 'plantations.id', '=', 'flat_progress.plantation_id')
            ->join('plants', 'plants.id', '=', 'plantations.plant_id') // Join to fetch plant names
            ->groupBy('plants.name', 'stage')
            ->get();





        return view('home', [
            'plants' => $plants,
            'expenses' => $expenses,
            'expensesFlats' => $expensesbyFlats,
            'stagesByFlats' => $stagesByFlats
        ]);

    }



}
