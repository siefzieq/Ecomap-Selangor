@extends('layouts.app')
<style>
    .form-select {
        border-color: #2D6C2B !important;
        color: #2D6C2B !important;
        height: 38px !important;
    }

    .custom-table {
        border-collapse: collapse;
        width: 100%;
        margin-top: 10px;
    }

    .custom-table td {
        padding: 8px;
        text-align: center;
        margin-top: 5px;
        height: 55px;
        background-color: #FFFFFF !important;
    }

    .custom-table th {
        padding: 8px;
        text-align: center;
        border-bottom: 1px solid #ddd;
        height: 55px;
        background-color: #FFFFFF !important;
        color: #2D6C2B !important;
    }

    th {
        padding-top: 30px !important;
        padding-bottom: 25px !important;
    }

    .graphBox {
        position: relative;
        justify-content: center; /* Center the graph horizontally */
        align-items: center; /* Center the graph vertically */
        width: 96%;
        padding-right: 20px;
        padding-top: 25px;
        padding-bottom: 10px;
        margin-left: 0.1px;
        display: grid;
        grid-template-columns: 1fr;
        grid-gap: 30px;
        min-height: 200px;
    }

    .graph {
        position: relative;
        background: #FFFFFF;
        padding: 20px 30px;
        width: 100%;
        box-shadow: 0 7px 25px rgba(0, 0, 0, 0.08);
        border-radius: 20px;
        margin: 0 auto; /* Center within the graphBox */
    }

    .circle {
        width: 70px;
        height: 70px;
        background-color: #ff964f;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
        text-align: center;
        margin-right: 20px;
        margin-left: 20px;
        margin-bottom: 12px;
    }

    .form-select {
        border-color: #2D6C2B !important;
        color: #2D6C2B !important;
    }

    .search-box {
        position: relative;
        width: auto; /* Adjust based on your layout */
    }

    .search-icon {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        z-index: 10;
        pointer-events: none; /* Makes the icon non-interactive */
    }

    #searchBox {
        padding-left: 35px; /* Make room for the icon inside the input box */
    }
</style>

@section('title', 'Seeding Distribution')
@section('caption', 'Seeding Distribution')
@section('content')

    <div class="container-fluid pt-3 pb-2">
        <div class="d-flex justify-content-between">
            <!-- Filters Section -->
            <div class="filters d-flex align-items-center">
                <div class="filter-label me-3">
                    <label for="month-filter" style="color: #2d3748;">Filters:</label>
                </div>
                <select id="month-filter" class="form-select me-3" style="width: 50%" onchange="applyFilter()">
                    <option value="" selected disabled>Months</option>
                    <option value="" >All</option>
                    <option value="1">Jan</option>
                    <option value="2">Feb</option>
                    <option value="3">Mar</option>
                    <option value="4">Apr</option>
                    <option value="5">May</option>
                    <option value="6">Jun</option>
                    <option value="7">Jul</option>
                    <option value="8">Aug</option>
                    <option value="9">Sep</option>
                    <option value="10">Oct</option>
                    <option value="11">Nov</option>
                    <option value="12">Dec</option>
                </select>
                <select id="plant-filter" class="form-select me-3" style="width: 150px" onchange="applyFilter()">
                    <option value="" selected disabled>Plants</option>
                    <option value="">All</option>
                    @foreach($plants as $p)
                        <option value="{{ $p->id }}" {{ $plantId == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            <!-- Search Box -->
            <div class="search-box d-flex align-items-center pe-5">
                <i class="fi fi-rr-search search-icon"></i>
                <input type="search" id="searchBox" class="form-control bg-white" placeholder="Search by name" value="{{ $search }}" oninput="performSearch()">
            </div>
        </div>
    </div>
    <div class="graphBox">
        <div class="graph">
            <div id="container"></div>
        </div>
    </div>

    @php($i = 1)
    <div class="pt-5">
        <div class="graph" style="margin-left: 0px; margin-bottom: 5%; width: 95%; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <strong style="font-size: 20px; font-weight: 700">Seeding Distribution</strong>
                    <p><small>List of seeding distribution</small></p>
                </div>
                <a href="{{ route('seeding.create') }}" class="circle" data-bs-toggle="tooltip" data-bs-title="Add Record" style="text-decoration: none; color: white; width: 35px; height: 35px; background-color: #2D6C2B">
                    <i class="fi fi-rr-plus"></i>
                </a>
            </div>
            <table class="table custom-table" style="width: 100%;">
                <tr>
                    <th>Date</th>
                    <th>Plant</th>
                    <th>Category</th>
                    <th>Distributed To</th>
                    <th>Quantity</th>
                    <th>Updated by</th>
                    <th></th>
                </tr>
                @foreach($seeding as $s)
                    <tr>
                        <td>{{ date('d/m/Y', strtotime($s->date)) }}</td>
                        <td>{{ ucfirst(strtolower($s->plant->name ?? 'N/A')) }}</td>
                        <td>{{ ucwords(strtolower($s->plant->category ?? 'N/A')) }}</td>
                        <td>{{ ucwords(strtolower($s->flat->name ?? 'N/A')) }}</td>
                        <td>{{ ucfirst(strtolower($s->quantity ?? 'N/A')) }}</td>
                        <td>{{ optional($s->updater)->name ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </table>
            <!-- Custom Pagination Links -->
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <li class="page-item {{ $seeding->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $seeding->previousPageUrl() }}&month={{ $month }}&plant={{ $plantId }}&search={{ $search }}">Previous</a>
                    </li>
                    @foreach ($seeding->getUrlRange(1, $seeding->lastPage()) as $page => $url)
                        <li class="page-item {{ $seeding->currentPage() == $page ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}&month={{ $month }}&plant={{ $plantId }}&search={{ $search }}">{{ $page }}</a>
                        </li>
                    @endforeach
                    <li class="page-item {{ $seeding->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $seeding->nextPageUrl() }}&month={{ $month }}&plant={{ $plantId }}&search={{ $search }}">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        function applyFilter() {
            const month = document.getElementById('month-filter').value;
            const plant = document.getElementById('plant-filter').value;
            const search = document.getElementById('searchBox').value;
            window.location.href = `{{ route('seeding.index') }}?month=${month}&plant=${plant}&search=${search}`;
        }

        function performSearch() {
            applyFilter();
        }

        document.addEventListener("DOMContentLoaded", function() {
            const unitDistributedData = @json($inventoryData);

            const plants = Object.keys(unitDistributedData);

            console.log("unitDistributedData:", unitDistributedData);
            console.log("plants:", plants);

            // Prepare data for the pie chart
            const pieData = plants.map(plant => {
                const quantity = unitDistributedData[plant] || 0;
                return {
                    name: plant,
                    y: quantity
                };
            });

            console.log("pieData:", pieData);

            Highcharts.chart('container', {
                colors: ['#01BAF2', '#71BF45', '#FAA74B', '#B37CD2', '#FF6961', '#50874d'], // Custom colors
                chart: {
                    type: 'pie'
                },
                title: {
                    text: 'Seeding Distribution by Plant Type'
                },
                subtitle: {
                    text: 'Breakdown of Seed Distribution Across Different Plant Types'
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.y}</b>',
                    valueSuffix: ' seeds'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '{point.name}: {point.y} seeds'
                        },
                        showInLegend: true
                    }
                },
                series: [{
                    name: 'Seeding Distribution',
                    colorByPoint: true,
                    innerSize: '70%',
                    data: pieData
                }]
            });
        });
    </script>

@endsection


