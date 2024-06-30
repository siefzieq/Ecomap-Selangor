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
        margin-top: 5px;
        height: 55px;
        background-color: #FFFFFF !important;
    }

    .custom-table th {
        padding: 8px;
        border-bottom: 1px solid #ddd;
        height: 55px;
        background-color: #FFFFFF!important;
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

@section('title', 'Plant Inventory')
@section('caption', 'Stock In')
@section('content')

    <div class="container-fluid pt-3 pb-2">
        <div class="d-flex justify-content-between">
            <!-- Filters Section -->
            <div class="filters d-flex align-items-center">
                <div class="filter-label me-3">
                    <label for="month-filter" style="color: #2d3748;">Filters:</label>
                </div>
                <select id="month-filter" class="form-select me-3" style="width: 80%" onchange="applyFilter()">
                    <option value="">Month</option>
                    <option value="January" {{ request('month') == 'January' ? 'selected' : '' }}>Jan</option>
                    <option value="February" {{ request('month') == 'February' ? 'selected' : '' }}>Feb</option>
                    <option value="March" {{ request('month') == 'March' ? 'selected' : '' }}>Mar</option>
                    <option value="April" {{ request('month') == 'April' ? 'selected' : '' }}>Apr</option>
                    <option value="May" {{ request('month') == 'May' ? 'selected' : '' }}>May</option>
                    <option value="June" {{ request('month') == 'June' ? 'selected' : '' }}>Jun</option>
                    <option value="July" {{ request('month') == 'July' ? 'selected' : '' }}>Jul</option>
                    <option value="August" {{ request('month') == 'August' ? 'selected' : '' }}>Aug</option>
                    <option value="September" {{ request('month') == 'September' ? 'selected' : '' }}>Sep</option>
                    <option value="October" {{ request('month') == 'October' ? 'selected' : '' }}>Oct</option>
                    <option value="November" {{ request('month') == 'November' ? 'selected' : '' }}>Nov</option>
                    <option value="December" {{ request('month') == 'December' ? 'selected' : '' }}>Dec</option>
                </select>
                <select id="plant-filter" class="form-select me-3" style="width: 150px" onchange="applyFilter()">
                    <option value="">Plants</option>
                    @foreach($plants as $p)
                        <option value="{{ $p->id }}" {{ request('plant') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            <!-- Search Box -->
            <div class="search-box d-flex align-items-center pe-5">
                <i class="fi fi-rr-search search-icon"></i>
                <input type="search" id="searchBox" class="form-control bg-white" placeholder="Search by name" value="{{ request('search') }}" oninput="applyFilter()">
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
        <div class="graph" style="margin-left: 0px; margin-bottom: 5%; width: 95%; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); ">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <strong style="font-size: 20px; font-weight: 700">Plant Stock In</strong>
                    <p><small>List of plants stock in</small></p>
                </div>
                <a href="{{route('plantInventory.create')}}" class="circle" data-bs-toggle="tooltip" data-bs-title="Add Record" style="text-decoration: none; color: white; width:35px; height:35px; background-color: #2D6C2B">
                    <i class="fi fi-rr-plus"></i>
                </a>
            </div>
            <table class="table custom-table" style="width: 100%;">
                <tr>
                    <th style="padding-left: 3%">Date</th>
                    <th>Plant</th>
                    <th style="padding-left: 3%">Category</th>
                    <th>Stock in</th>
                    <th>Updated by</th>
                </tr>
                @foreach($inventory as $iv)
                    <tr>
                        <td style="padding-left: 3%">{{ date('d/m/Y', strtotime($iv->date)) }}</td>
                        <td>{{ ucwords(strtolower($iv->plant->name ?? NULL)) }}</td>
                        <td style="padding-left: 3%">{{ ucwords(strtolower($iv->plant->category ?? NULL)) }}</td>
                        <td>{{ ucfirst(strtolower($iv->in_stock)) }}</td>
                        <td>{{ $iv->updater->name ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </table>
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <li class="page-item {{ $inventory->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $inventory->previousPageUrl() }}">Previous</a>
                    </li>
                    @foreach ($inventory->getUrlRange(1, $inventory->lastPage()) as $page => $url)
                        <li class="page-item {{ $inventory->currentPage() == $page ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach
                    <li class="page-item {{ $inventory->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $inventory->nextPageUrl() }}">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/modules/solid-gauge.js"></script>
    <script src="https://code.highcharts.com/modules/timeline.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const inventoryData = @json($inventoryData);

            console.log('Raw Inventory Data:', inventoryData);

            const monthOrder = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            const plants = Array.from(new Set(@json($inventory->pluck('plant.name'))));

            function sortData(data) {
                return monthOrder.reduce((sorted, month) => {
                    if (data[month] !== undefined) {
                        sorted[month] = data[month];
                    }
                    return sorted;
                }, {});
            }

            const sortedInventoryData = sortData(inventoryData);
            const labels = Object.keys(sortedInventoryData);

            console.log('Sorted Inventory Data:', sortedInventoryData);

            // Prepare series data for Highcharts
            const seriesData = plants.map(plant => {
                return {
                    name: plant,
                    data: labels.map(label => sortedInventoryData[label][plant] !== undefined ? sortedInventoryData[label][plant] : 0)
                };
            });

            console.log('Series Data for Highcharts:', seriesData);

            Highcharts.chart('container', {
                colors: ['#D32D41', '#6AB187', '#F9C70C', '#4B0082', '#B3C100', '#FFD1DC', '#CB99C9', '#FFB347', '#AEC6CF', '#B39EB5'], // Custom colors
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Monthly Plant Stock Levels'
                },
                subtitle: {
                    text: 'Tracking Stock Quantities by Plant Type'
                },
                xAxis: {
                    categories: labels
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Stock Count'
                    }
                },
                tooltip: {
                    pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b><br/>',
                    shared: true
                },
                plotOptions: {
                    column: {
                        stacking: 'normal',  // Changed from 'percent' to 'normal' for absolute values
                    }
                },
                series: seriesData
            });

            window.applyFilter = function() {
                const month = document.getElementById('month-filter').value;
                const plant = document.getElementById('plant-filter').value;
                const search = document.getElementById('searchBox').value;

                const params = new URLSearchParams({
                    month: month,
                    plant: plant,
                    search: search
                }).toString();

                window.location.href = `{{ route('plantInventory.index') }}?${params}`;
            }
        });
    </script>



@endsection
