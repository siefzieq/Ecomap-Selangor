@extends('layouts.app')
<style>
    .custom-table {
        border-collapse: collapse;
        width: 100%;
        margin-top:10px
    }
    .custom-table td {
        padding: 8px;
        border-bottom: 1px solid #ddd;
        margin-top: 5px;
        height: 55px;
        background-color: #FFFFFF !important;
    }
    .custom-table tr:nth-child(even) td {
        background-color: #FFFFFF;
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
        width: 95%;
        padding-right: 20px;
        padding-top: 25px;
        padding-bottom: 10px;
        margin-left: 0.1px;
        display: grid;
        grid-template-columns: 1fr 1fr;
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
    .progress-container {
        position: relative;
        width: 100%;
    }
    progress {
        width: 100%; /* Full width */
        height: 20px; /* Adjust the height to fit the label */
        appearance: none; /* Remove default appearance */
    }
    progress::-webkit-progress-bar {
        background-color: #D9D9D9; /* Light grey background */
    }
    progress::-webkit-progress-value {
        background-color: #2D6C2B; /* Custom color */
    }
    .progress-label {
        position: absolute;
        width: 100%;
        text-align: center;
        line-height: 20px; /* Adjust line height to vertical center the text */
        color: white; /* Text color */
        top: 0; /* Align text to top of progress bar */
    }
    .filter-container {
        display: flex;
        justify-content: flex-end;
        padding-right: 7%;
        margin-left: 10px;
    }
    .form-select {
        border-color: #2D6C2B !important;
        color: #2D6C2B !important;
        height: 38px !important;
    }
    .circle {
        width: 70px;
        height: 70px;
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

@section('title', 'Flat Inventory')
@section('caption', 'Flat Inventory')
@section('content')
    <div class="container-fluid pt-3 pb-2">
        <form method="GET" action="{{ route('flatInventory.index') }}" class="w-100 d-flex justify-content-between align-items-center">
            <div class="filter-container d-flex align-items-center">
                <div class="filter-label me-3">
                    <label for="month-filter" style="color: #2d3748;">Filters:</label>
                </div>
                <select id="month-filter" name="month" class="form-select me-3" style="width: 85%" onchange="this.form.submit()">
                    <option value="" selected disabled>Months</option>
                    <option value="">All</option>
                    @foreach(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $month)
                        <option value="{{ $month }}" {{ request('month') == $month ? 'selected' : '' }}>{{ $month }}</option>
                    @endforeach
                </select>
                <select id="plant" name="plant" class="form-select" onchange="this.form.submit()">
                    <option value="" selected disabled>Plants</option>
                    <option value="">All</option>
                    @foreach($plants as $p)
                        <option value="{{ $p->id }}" {{ request('plant') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="search-box d-flex align-items-center pe-5">
                <i class="fi fi-rr-search search-icon"></i>
                <input type="search" id="searchBox" name="search" class="form-control bg-white" placeholder="Search by flat name" value="{{ request('search') }}" style="padding-left: 35px;" oninput="this.form.submit()" autocomplete="off">
            </div>
        </form>
    </div>

    <div class="graphBox">
        <div class="graph">
            <div id="pie" style="height: 85%"></div>
        </div>
        <div class="graph">
            <div id="bar" style="height: 88%"></div>
        </div>
    </div>

    <div class="graphBox1 pt-5">
        <div class="graph" style="margin-left: 0px; margin-bottom: 5%; width: 95%; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); ">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <strong style="font-size: 20px; font-weight: 700">Flat Inventory</strong>
                    <p><small>List of flat progress</small></p>
                </div>
                <a href="{{route('flatInventory.create')}}"  class="circle" data-bs-toggle="tooltip" data-bs-title="Add Record" style="text-decoration: none; color: white; width:35px; height:35px; background-color: #2D6C2B">
                    <i class="fi fi-rr-plus"></i>
                </a>
            </div>
            <table class="table custom-table" style="width: 100%;">
                <tr>
                    <th>Flat</th>
                    <th>Plant</th>
                    <th>Stage</th>
                    <th>Start Date</th>
                    <th>Due Date</th>
                    <th>Days Left</th>
                    <th></th>
                </tr>
                @foreach($flatProgressPage as $f)
                    <tr class="container-custom">
                        <td >{{ucwords(strtolower($f->flat->name ?? Null))}}</td>
                        <td>{{$f->plantation->plant->name ?? Null}}</td>
                        <td>{{ucwords(strtoLower($f->stage))}}</td>
                        <td class="start_date">{{ date('d/m/Y', strtotime($f->start_date)) }}</td>
                        <td class="expected_date">{{ date('d/m/Y', strtotime($f->expected_date)) }}</td>
                        <td class="duration"></td>
                    </tr>
                @endforeach
            </table>
            <!-- Custom Pagination Links -->
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <li class="page-item {{ $flatProgressPage->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $flatProgressPage->previousPageUrl() }}">Previous</a>
                    </li>
                    @foreach ($flatProgressPage->getUrlRange(1, $flatProgressPage->lastPage()) as $page => $url)
                        <li class="page-item {{ $flatProgressPage->currentPage() == $page ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach
                    <li class="page-item {{ $flatProgressPage->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $flatProgressPage->nextPageUrl() }}">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
        <br>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script>
        const originalData = @json($flatProgressPage);

        document.addEventListener("DOMContentLoaded", function() {
            console.log('Original data :', originalData);
            initializeCharts(originalData.data);
            updateDurations();
        });

        function updateDurations() {
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach(row => {
                const startDateElement = row.querySelector('.start_date');
                const expectedDateElement = row.querySelector('.expected_date');
                const durationElement = row.querySelector('.duration');

                if (startDateElement && expectedDateElement && durationElement) {
                    const startDate = moment(startDateElement.textContent.trim(), 'DD/MM/YYYY');
                    const expectedDate = moment(expectedDateElement.textContent.trim(), 'DD/MM/YYYY');
                    const now = moment();

                    if (expectedDate.isValid() && startDate.isValid()) {
                        const duration = expectedDate.diff(now, 'days');
                        if (duration >= 0) {
                            durationElement.textContent = duration + " days left";
                        } else {
                            durationElement.textContent = Math.abs(duration) + " days ago";
                        }
                    } else {
                        durationElement.textContent = 'Invalid date';
                    }
                }
            });
        }

        function initializeCharts(data) {
            const barContainer = document.getElementById('bar');
            const pieContainer = document.getElementById('pie');

            Highcharts.chart(barContainer, {
                chart: {
                    type: 'bar'
                },
                title: {
                    text: 'Progress Tracking'
                },
                xAxis: {
                    categories: ['Seeding', 'Harvesting', 'Completed'],
                    title: {
                        text: 'Stages'
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Total Flats'
                    },
                    labels: {
                        overflow: 'justify'
                    }
                },
                plotOptions: {
                    bar: {
                        dataLabels: {
                            enabled: true
                        }
                    }
                },
                credits: {
                    enabled: false
                },
                series: [{
                    name: 'Total Flats',
                    data: getBarChartData(data)
                }]
            });

            Highcharts.chart(pieContainer, {
                chart: {
                    type: 'pie'
                },
                title: {
                    text: 'Types of Plant'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                        }
                    }
                },
                tooltip: {
                    pointFormat: '<b>{point.name}</b>: {point.percentage:.1f} %'
                },
                legend: {
                    enabled: true,
                    align: 'center',
                    verticalAlign: 'bottom',
                    layout: 'horizontal'
                },
                series: [{
                    name: 'Plants',
                    colorByPoint: true,
                    data: getPieChartData(data)
                }]
            });
        }

        function getBarChartData(data) {
            const stages = ['seeding', 'harvesting', 'completed'];
            const stageCounts = stages.map(stage => data.filter(item => item.stage.toLowerCase() === stage).length);
            return stageCounts;
        }

        function getPieChartData(data) {
            const plantCounts = data.reduce((acc, item) => {
                const plantName = item.plantation.plant ? item.plantation.plant.name : 'Unknown';
                acc[plantName] = (acc[plantName] || 0) + 1;
                return acc;
            }, {});

            const totalPlants = Object.values(plantCounts).reduce((sum, count) => sum + count, 0);

            return Object.entries(plantCounts).map(([name, count]) => ({
                name,
                y: (count / totalPlants) * 100
            }));
        }
    </script>
@endsection
