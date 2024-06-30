@extends('layouts.app')
<style>

    .custom-table td{
        background-color: #FFFFFF !important;
        padding: 8px;
        text-align: center;
        margin-top: 5px;
        height: 55px;
    }

    .custom-table th {
        padding: 8px;
        text-align: center;
        border-bottom: 1px solid #ddd;
        height: 55px;
        background-color: #F7F7F7 !important;
        color: #2D6C2B !important;
    }

    .graphBox {
        position: relative;
        justify-content: center; /* Center the graph horizontally */
        align-items: center; /* Center the graph vertically */
        width: 98%;
        padding-right: 20px;
        padding-top: 10px;
        padding-bottom: 40px;
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
        justify-content: center;
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
    }

    .expenses {
        width: 93%;
        background-color: #F7F7F7;
        margin: 0 auto;
        border-radius: 8px;
        padding: 16px;
        margin-left: 0;
        margin-top: 20px;
        margin-bottom: 30px;
    }
    .expense-header {
        font-size: 1.2em;
        margin-bottom: 8px;
        color: #2d3748 !important;
    }
    .expense-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        font-size: 14px;
        background-color: #F7F7F7;
    }

    .expense-item1 {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
        font-size: 14px;
        background-color: #FFFFFF;
        border-radius: 10px;
        padding: 16px;
        margin-left: 0;
        margin-top: 0;
    }

    .expense-item .description {
        display: flex;
        align-items: center;
    }
    .expense-item1 .amount {
        color: red;
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

    .btn.btn-category {
        border-color: #2D6C2B !important;
        color: #2D6C2B !important;
    }

    .btn.btn-category:hover{
        background-color: #2D6C2B !important; /* Darker shade on hover/active for better visibility */
        color: #FFFFFF !important; /* Ensure text color is white */
    }

    .btn.btn-category.active {
        background-color: #2D6C2B !important;  /* A darker or different green when active */
        color: #ffffff !important;
        border: 2px solid #0f381f;  /* Optional: adds a border to highlight the active button */
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

@section('title', 'Expenses')
@section('caption', 'Expenses')

@section('content')
    <div class="pt-3 pb-2 d-flex justify-content-between align-items-center">
        <div class="filter-container d-flex align-items-center">
            <label style="color: #2d3748;margin-left: 10px; margin-right: 15px;">Filter:</label>
            <select id="month-filter" class="form-select" style="width: 80%" onchange="applyMonthFilter()">
                <option value="">Months</option>
                <option value="January">Jan</option>
                <option value="February">Feb</option>
                <option value="March">Mar</option>
                <option value="April">Apr</option>
                <option value="May">May</option>
                <option value="June">Jun</option>
                <option value="July">Jul</option>
                <option value="August">Aug</option>
                <option value="September">Sep</option>
                <option value="October">Oct</option>
                <option value="November">Nov</option>
                <option value="December">Dec</option>
            </select>
        </div>
        <div class="pe-5 d-flex align-items-center gap-2 search-box">
            <i class="fi fi-rr-search search-icon"></i>
            <input type="search" id="searchBox" class="form-control bg-white" placeholder="Type to filter the list" oninput="performSearch()">
        </div>
    </div>

    <div class="graphBox">
        <div class="graph">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                </div>
                <a href="{{ route('expenses.create') }}" class="circle" data-bs-toggle="tooltip" data-bs-title="Add Document" style="text-decoration: none; color: white; width: 35px; height: 35px; background-color: #2D6C2B">
                    <i class="fi fi-rr-plus"></i>
                </a>
            </div>
            <div id="bar"></div>
        </div>
    </div>

    <div class="d-flex gap-2">
        <button class="btn btn-category filter-btn" data-category="all" style="border-radius: 20px;" autofocus>All</button>
        <button class="btn btn-category filter-btn" data-category="setup_infrastructure" style="border-radius: 20px;">Setup Infrastructure</button>
        <button class="btn btn-category filter-btn" data-category="equipment" style="border-radius: 20px;">Equipment & Tools</button>
        <button class="btn btn-category filter-btn" data-category="operational" style="border-radius: 20px;">Operational Expenses</button>
        <button class="btn btn-category filter-btn" data-category="maintenance" style="border-radius: 20px;">Maintenance</button>
    </div>

    <div class="expenses">
        @foreach($expensesList as $date => $data)
            <div class="expense-group" data-date="{{ \Carbon\Carbon::parse($date)->format('d F Y') }}">
                <div class="expense-item">
                    <div class="expense-header">{{ \Carbon\Carbon::parse($date)->format('d F Y') }}</div>
                    <div class="expense-header">- RM {{ number_format($data['total'], 2) }}</div>
                </div>
                @foreach($data['items'] as $exp)
                    <div class="expense-item1" data-category="{{ $exp->category }}">
                        <div class="description">
                            <div style="font-weight: 700; font-size: 18px">{{ $exp->flat->name ?? NULL }}</div>
                            <div style="font-weight: 500">{{ $exp->item_name }}</div>
                            <div>{{ $exp->category }} - {{ $exp->type }}</div>
                            <div>Quantity : {{ $exp->quantity }}</div>
                        </div>
                        <div>
                            <div class="amount">- RM {{ number_format($exp->total, 2) }}</div><br>
                        </div>
                    </div>
                @endforeach
                <hr>
            </div>
        @endforeach
    </div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const initialData = @json($expensesData);
            console.log("Initial Data for Chart:", initialData);
            initChart(initialData);
            document.getElementById('month-filter').addEventListener('change', applyMonthFilter);
            document.getElementById('searchBox').addEventListener('input', performSearch);
            setTimeout(() => {
                const allButton = document.querySelector('button[data-category="all"]');
                if (allButton) {
                    allButton.focus();
                    allButton.classList.add('active');  // Add 'active' class on load
                }
            }, 100);

            // Add event listeners to filter buttons
            document.querySelectorAll('.filter-btn').forEach(button => {
                button.addEventListener('click', function() {
                    applyCategoryFilter(this.getAttribute('data-category'));
                });
            });

            // Initially apply 'all' category filter
            applyCategoryFilter('all');
        });

        function applyMonthFilter() {
            const selectedMonth = document.getElementById('month-filter').value.toLowerCase();
            const expenseGroups = document.querySelectorAll('.expense-group');
            const filteredData = {};
            const count = { 'all': 0, 'setup_infrastructure': 0, 'equipment': 0, 'operational': 0, 'maintenance': 0 };

            expenseGroups.forEach(group => {
                const dateText = group.getAttribute('data-date');
                const groupMonth = new Date(dateText).toLocaleString('default', { month: 'long' }).toLowerCase();
                const isVisible = selectedMonth === "" || groupMonth === selectedMonth;
                group.style.display = isVisible ? 'block' : 'none';

                if (isVisible) {
                    const items = group.querySelectorAll('.expense-item1');
                    items.forEach(item => {
                        const flatName = item.querySelector('.description > div:first-child').textContent.trim();
                        const category = item.getAttribute('data-category');
                        const amountText = item.querySelector('.amount').textContent.replace(/RM|\s|,/g, '').trim();
                        const total = parseFloat(amountText);

                        if (!filteredData[flatName]) {
                            filteredData[flatName] = {};
                        }
                        if (!filteredData[flatName][category]) {
                            filteredData[flatName][category] = 0;
                        }
                        filteredData[flatName][category] += total;
                        item.querySelector('.amount').textContent = `RM ${Math.abs(total).toFixed(2)}`;

                        // Updating the category counts for visible items
                        count[category] += 1;  // Increment count for the category of the current item
                        count['all'] += 1;  // Increment the overall count
                    });
                }
            });

            updateChart(filteredData);
            updateCategoryCounts(count);  // Update the category counts on the UI
        }

        function updateChart(data) {
            const formattedData = formatChartData(data);
            Highcharts.chart('bar', {
                chart: {
                    type: 'bar'
                },
                title: {
                    text: 'Expenses by Flat and Category'
                },
                xAxis: {
                    categories: Object.keys(data),
                    title: {
                        text: 'Flats'
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Total Expenses (RM)'
                    }
                },
                legend: {
                    reversed: true
                },
                plotOptions: {
                    series: {
                        stacking: 'normal'
                    }
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.y:.2f}</b><br/>Total: RM <b>{point.stackTotal:.2f}</b>'
                },
                series: formattedData
            });
        }

        function initChart(data) {
            updateChart(data);
        }

        function formatChartData(data) {
            // Extract all unique categories
            const categories = Object.keys(data).reduce((acc, flatName) => {
                const flatCategories = Object.keys(data[flatName]);
                flatCategories.forEach(category => {
                    if (!acc.includes(category)) {
                        acc.push(category);
                    }
                });
                return acc;
            }, []);

            // Initialize series data with all categories set to 0
            const seriesData = {};
            Object.keys(data).forEach(flatName => {
                seriesData[flatName] = {};
                categories.forEach(category => {
                    seriesData[flatName][category] = 0;
                });
            });

            // Fill series data with actual values
            Object.keys(data).forEach(flatName => {
                Object.entries(data[flatName]).forEach(([category, value]) => {
                    seriesData[flatName][category] = value;
                });
            });

            // Convert series data to Highcharts format
            const series = categories.map(category => {
                return {
                    name: category,
                    data: Object.keys(seriesData).map(flatName => seriesData[flatName][category])
                };
            });

            return series;
        }


        function applyCategoryFilter(category) {
            const count = { 'all': 0, 'setup_infrastructure': 0, 'equipment': 0, 'operational': 0, 'maintenance': 0 };
            const expenseGroups = document.querySelectorAll('.expense-group');

            expenseGroups.forEach(group => {
                const items = group.querySelectorAll('.expense-item1');
                let visibleItemsCount = 0; // Track the number of visible items in this group

                items.forEach(item => {
                    const itemCategory = item.getAttribute('data-category');
                    const isVisible = category === 'all' || itemCategory === category;
                    item.style.display = isVisible ? 'flex' : 'none';

                    if (isVisible) {
                        visibleItemsCount++;
                        count['all']++;
                        count[itemCategory]++;
                    }
                });

                // Set the display of the entire group based on if any items are visible
                group.style.display = visibleItemsCount > 0 ? 'block' : 'none';
            });

        }

        function performSearch() {
            const searchTerm = document.getElementById('searchBox').value.toLowerCase();
            const expenseGroups = document.querySelectorAll('.expense-group');
            const count = { 'all': 0, 'setup_infrastructure': 0, 'equipment': 0, 'operational': 0, 'maintenance': 0 };

            expenseGroups.forEach(group => {
                let visibleItemsCount = 0;
                const expenseItems = group.querySelectorAll('.expense-item1');

                expenseItems.forEach(item => {
                    const descriptionText = item.querySelector('.description').textContent.toLowerCase();
                    const isVisible = descriptionText.includes(searchTerm);
                    item.style.display = isVisible ? 'flex' : 'none';

                    if (isVisible) {
                        visibleItemsCount++;
                        count['all']++;
                        const category = item.getAttribute('data-category');
                        if (category) count[category]++;
                    }
                });

                group.style.display = visibleItemsCount > 0 ? 'block' : 'none';
            });

            updateCategoryCounts(count);
        }

        function updateCategoryCounts(count) {
            document.querySelector('button[data-category="all"]').textContent = `All (${count['all']})`;
            document.querySelector('button[data-category="setup_infrastructure"]').textContent = `Setup Infrastructure (${count['setup_infrastructure']})`;
            document.querySelector('button[data-category="equipment"]').textContent = `Equipment & Tools (${count['equipment']})`;
            document.querySelector('button[data-category="operational"]').textContent = `Operational (${count['operational']})`;
            document.querySelector('button[data-category="maintenance"]').textContent = `Maintenance (${count['maintenance']})`;
        }

        window.onload = function() {
            performSearch();
        };
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.btn.btn-category.filter-btn');

            buttons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove 'active' class from all buttons
                    buttons.forEach(btn => btn.classList.remove('active'));

                    // Add 'active' class to the clicked button
                    this.classList.add('active');
                });
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                const allButton = document.querySelector('button[data-category="all"]');
                if (allButton) {
                    allButton.focus();
                }
            }, 100); // Delay of 100 milliseconds
        });
    </script>
@endsection
