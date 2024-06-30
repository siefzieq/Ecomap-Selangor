@extends('layouts.app')

<style>
    .custom-table {
        border-collapse: collapse;
        width: 100%;
        margin-top: 10px;
    }

    .custom-table td {
        padding: 8px;
        text-align: center;
        background-color: #FFFFFF !important;
        margin-top: 5px;
        height: 55px;
    }

    .custom-table th {
        padding: 8px;
        text-align: center;
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
        width: 100%;
        padding-right: 60px;
        padding-top: 30px;
        margin-left: 0;
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        grid-gap: 3%;
        height: 25%;
        flex-wrap: wrap;
    }

    .graphBox1 {
        position: relative;
        width: 100%;
        padding-right: 60px;
        padding-top: 2%;
        margin-left: 0;
        display: grid;
        grid-template-columns: 1fr;
        flex-wrap: wrap;
    }

    .graphBox {
        align-items: start;
    }

    .graph {
        position: relative;
        background: #FFFFFF;
        padding: 20px 20px;
        width: 100%;
        box-shadow: 0 7px 25px rgba(0, 0, 0, 0.08);
        border-radius: 20px;
        flex-wrap: wrap;
    }

    strong, small {
        padding-left: 10px;
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

    .stat-text {
        display: flex;
        flex-direction: column;
    }

    .stat-number {
        font-size: 24px;
        color: #333;
    }

    .stat-label {
        font-size: 16px;
        color: #666;
    }

    .stat-container {
        display: flex;
        align-items: center;
        justify-content: start;
        background: #FFFFFF;
        padding: 10px;
        border-radius: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .form-select {
        border-color: #2D6C2B !important;
        color: #2D6C2B !important;
    }

    .search-box {
        position: relative;
        width: auto;
    }

    .search-icon {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        z-index: 10;
        pointer-events: none;
    }

    #searchBox {
        padding-left: 35px;
    }
</style>

@section('title', 'Plantation')
@section('caption', 'Plantation Information')
@section('content')
    <div class="container-fluid pt-3 pb-2">
        <form method="GET" action="{{ route('plantation.index') }}" class="w-100 d-flex justify-content-between align-items-center">
            <div class="filter-container d-flex align-items-center">
                <label style="color: #2d3748; margin-left: 10px; margin-right: 15px;">Filter:</label>
                <select id="category" name="category" class="form-select" style="width: 100%" onchange="this.form.submit()">
                    <option value="">Category</option>
                    <option value="sayur buah" {{ request('category') == 'sayur buah' ? 'selected' : '' }}>Sayur Buah</option>
                    <option value="sayur berdaun" {{ request('category') == 'sayur berdaun' ? 'selected' : '' }}>Sayur Berdaun</option>
                </select>
            </div>
            <div class="pe-5 d-flex align-items-center gap-2 search-box">
                <i class="fi fi-rr-search search-icon" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); z-index: 10;"></i>
                <input type="search" id="searchBox" name="name" class="form-control bg-white" placeholder="Search by plant name" value="{{ request('name') }}" style="padding-left: 35px;" oninput="this.form.submit()" autocomplete="off">
            </div>
        </form>
    </div>

    <div class="graphBox">
        <div class="graph stat-container" style="height: 85%">
            <div class="circle">
                <i class="fi fi-ts-bag-seedling"></i>
            </div>
            <div class="stat-text">
                <strong style="font-size: 32px; font-weight: 700" class="stat-number">{{ $plant_fertigation }}</strong>
                <p><small style="color:#4a5568" class="fw-medium stat-label">Fertigation plants</small></p>
            </div>
        </div>
        <div class="graph stat-container" style="height: 85%;">
            <div class="circle" style="background-color: #FF6961">
                <img src="/hydroponic.png" style="width: 36px; height: 36px">
            </div>
            <div class="stat-text">
                <strong style="font-size: 32px; font-weight: 700" class="stat-number">{{ $plant_aquaponic }}</strong>
                <p><small style="color:#4a5568" class="fw-medium stat-label">Aquaponic plants</small></p>
            </div>
        </div>
        <div class="graph stat-container" style="height: 85%;">
            <div class="circle" style="background-color: #50874d">
                <img src="/conventional.png" style="width: 36px; height: 36px; color: white">
            </div>
            <div class="stat-text">
                <strong style="font-size: 32px; font-weight: 700" class="stat-number">{{ $plant_conventional }}</strong>
                <p><small style="color:#4a5568" class="fw-medium stat-label">Conventional plants</small></p>
            </div>
        </div>
    </div>
    @php($i=($plantation->currentPage() - 1) * $plantation->perPage() + 1)
    <div class="graphBox1">
        <div class="graph" style="margin-left: 0px;margin-bottom: 5%; width: 100%; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); ">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <strong style="font-size: 20px; font-weight: 700">Plantation Information</strong>
                    <p><small>List of plantation</small></p>
                </div>
                <a href="{{route('plantation.create')}}" class="circle" data-bs-toggle="tooltip" data-bs-title="Add Record" style="text-decoration: none; color: white; width:35px; height:35px; background-color: #2D6C2B">
                    <i class="fi fi-rr-plus"></i>
                </a>
            </div>
            <table class="table custom-table" style="width: 100%;">
                <tr>
                    <th>No.</th>
                    <th>Plant</th>
                    <th>Category</th>
                    <th>Method</th>
                    <th>Seeding</th>
                    <th>Harvesting</th>
                    <th>Completion</th>
                    <th>Action</th>
                    <th></th>
                </tr>
                @foreach($plantation as $p)
                    <tr>
                        <td>{{$i++}}.</td>
                        <td>{{ ucwords(strtolower($p->plant->name ?? NULL)) }}</td>
                        <td>{{ ucwords(strtolower($p->plant->category)) }}</td>
                        <td>{{ ucfirst(strtolower($p->planting_type)) }}</td>
                        <td>{{ $p->seeding_duration ? $p->seeding_duration . ' days' : 'N/A' }}</td>
                        <td>{{ $p->harvesting_duration ? $p->harvesting_duration . ' days' : 'N/A' }}</td>
                        <td>{{ $p->completion_duration ? $p->completion_duration . ' days' : 'N/A' }}</td>
                        <td>
                            <form action="{{route('plantation.destroy',$p)}}" method="POST">
                                @csrf
                                @method('DELETE')
                                <a href="{{ route('plantation.edit', $p->id) }}" class="btn btn-secondary" style="text-decoration: none; color: white;">
                                    Edit
                                </a>
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Confirm DELETE? this {{$p->plant->name ?? NULL}} record ')">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </table>
            <!-- Custom Pagination Links -->
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <li class="page-item {{ $plantation->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $plantation->appends(request()->input())->previousPageUrl() }}">Previous</a>
                    </li>
                    @foreach ($plantation->getUrlRange(1, $plantation->lastPage()) as $page => $url)
                        <li class="page-item {{ $plantation->currentPage() == $page ? 'active' : '' }}">
                            <a class="page-link" href="{{ $plantation->appends(request()->input())->url($page) }}">{{ $page }}</a>
                        </li>
                    @endforeach
                    <li class="page-item {{ $plantation->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $plantation->appends(request()->input())->nextPageUrl() }}">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function debounce(func, wait) {
                let timeout;
                return function () {
                    const context = this, args = arguments;
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func.apply(context, args), wait);
                };
            }

            function applyFilter() {
                updateTableVisibility();
            }

            const debouncedSearch = debounce(function () {
                updateTableVisibility();
            }, 300);

            function performSearch() {
                updateTableVisibility();
            }

            function updateTableVisibility() {
                const searchTerm = document.getElementById('searchBox').value.toLowerCase();
                const plantingTypeFilter = document.getElementById('planting_type').value.toLowerCase();
                const categoryFilter = document.getElementById('category').value.toLowerCase();

                const rows = document.querySelectorAll('.custom-table tr:not(:first-child)');
                rows.forEach(row => {
                    const name = row.cells[1].textContent.toLowerCase();
                    const plantingType = row.getAttribute('data-planting-type').toLowerCase();
                    const category = row.getAttribute('data-category').toLowerCase();

                    const matchesPlantingType = !plantingTypeFilter || plantingType.includes(plantingTypeFilter);
                    const matchesCategory = !categoryFilter || category.includes(categoryFilter);
                    const matchesSearch = !searchTerm || name.includes(searchTerm);

                    row.style.display = (matchesPlantingType && matchesCategory && matchesSearch) ? '' : 'none';
                });
            }

            document.getElementById('planting_type').addEventListener('change', applyFilter);
            document.getElementById('category').addEventListener('change', applyFilter);
            document.getElementById('searchBox').addEventListener('input', debouncedSearch);
        });
    </script>
@endsection
