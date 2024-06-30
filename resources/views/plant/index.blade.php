@extends('layouts.app')
<style>

    .custom-table {
        border-collapse: collapse;
        width: 100%;
        margin-top:10px

    }

    .custom-table td {
        text-align: center;
        background-color: #FFFFFF!important;
        margin-top: 5px;
        height: 55px;
    }


    .custom-table th{
        text-align: center;
        border-bottom: 1px solid #ddd;
        height: 55px;
        background-color: #FFFFFF!important;
        color: #2D6C2B !important;

    }

    th{
        padding-top: 30px !important;
        padding-bottom: 25px !important;
    }

    .graphBox{
        position:relative;
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

    .graphBox1{
        position:relative;
        width: 100%;
        padding-right: 60px;
        padding-top: 2%;
        margin-left: 0;
        display: grid;
        grid-template-columns: 1fr;
        flex-wrap: wrap;
    }

    .graphBox {
        align-items: start; /* This ensures that all items in the flexbox start from the same line */
    }

    .graph{
        position:relative;
        background: #FFFFFF;
        padding: 20px 20px;
        width: 100%;
        box-shadow: 0 7px 25px rgba(0,0,0,0.08);
        border-radius: 20px;
        flex-wrap: wrap;
    }

    strong,small{
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
        font-size: 24px; /* Large font size for the number */
        color: #333;
    }

    .stat-label {
        font-size: 16px; /* Smaller font size for the label */
        color: #666;
    }

    .stat-container {
        display: flex;
        align-items: center; /* Center items vertically */
        justify-content: start;
        background: #FFFFFF;
        padding: 10px;
        border-radius: 20px; /* Rounded corners for the container */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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
    .pagination {
        display: flex;
        list-style-type: none;
        padding: 0;
    }

    .pagination .page-item.disabled .page-link {
        color: #ccc;
    }

    .pagination .page-item.active .page-link {
        background-color: #007bff;
        color: white;
    }

    .pagination .page-link {
        padding: 8px 16px;
        margin: 0 4px;
        border: 1px solid #ddd;
    }

    .pagination .page-link:hover {
        background-color: #f8f9fa;
    }

</style>

@section('title', 'Plant')
@section('caption', 'Plant Information')
@section('content')
    <div class="pt-3 pb-2 d-flex justify-content-between align-items-center">
        <!-- Start of the form that submits filters to the server -->
        <form method="GET" action="{{ route('plant.index') }}" class="w-100 d-flex justify-content-between align-items-center">
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
                <i class="fi fi-rr-carrot"></i>
            </div>
            <div class="stat-text">
                <strong style="font-size: 32px; font-weight: 700" class="stat-number">{{$plant_count}}</strong>
                <p><small style="color:#4a5568" class="fw-medium stat-label">Plant Records</small></p>
            </div>
        </div>
        <div class="graph stat-container" style="height: 85%;">
            <div class="circle" style="background-color:  #FF6961">
                <i class="fi fi-rr-tomato"></i>
            </div>
            <div class="stat-text">
                <strong style="font-size: 32px; font-weight: 700" class="stat-number">{{$plant_berbuah}}</strong>
                <p><small style="color:#4a5568" class="fw-medium stat-label">Sayur Buah</small></p>
            </div>
        </div>
        <div class="graph stat-container" style="height: 85%;">
            <div class="circle" style="background-color: #50874d">
                <i class="fi fi-rr-lettuce"></i>
            </div>
            <div class="stat-text">
                <strong style="font-size: 32px; font-weight: 700" class="stat-number">{{$plant_berdaun}}</strong>
                <p><small style="color:#4a5568" class="fw-medium stat-label">Sayur Berdaun</small></p>
            </div>
        </div>

    </div>
    @php($i=1)
    <div class="graphBox1">
        <div class="graph" style="margin-left: 0px;margin-bottom: 5%; width: 100%; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); ">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <strong style="font-size: 20px; font-weight: 700">Plant Information</strong>
                    <p><small>List of registered plants</small></p>
                </div>
                <a href="{{route('plant.create')}}"  class="circle" data-bs-toggle="tooltip" data-bs-title="Add Record" style="text-decoration: none; color: white; width:35px; height:35px; background-color: #2D6C2B">
                    <i class="fi fi-rr-plus"></i>
                </a>
            </div>
            <table class="table custom-table" style="width: 100%;">
                <tr>
                    <th>No.</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Remarks</th>
                    <th>Image</th>
                    <th>Action</th>
                    <th></th>
                </tr>
                @foreach($plants as $index => $plant)
                    <tr data-category="{{ strtolower($plant->category) }}">
                        <td>{{ $plants->firstItem() + $index }}.</td>
                        <td>{{ ucwords(strtolower($plant->name)) }}</td>
                        <td>{{ ucwords(strtolower($plant->category)) }}</td>
                        <td>{{ $plant->description ?? 'N/A' }}</td>
                        <td><img src="{{ asset($plant->image_path ?? 'default.png') }}" width="50" height="50" alt="{{ $plant->name }}"></td>
                        <td>
                            <form action="{{route('plant.destroy', $plant)}}" method="POST">
                                @csrf
                                @method('DELETE')
                                <a href="{{ route('plant.edit', $plant->id) }}" class="btn btn-secondary" style="text-decoration: none; color: white;">
                                    Edit
                                </a>
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Confirm DELETE? this {{$plant->name}} record ')">
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
                    <li class="page-item {{ $plants->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $plants->previousPageUrl() }}">Previous</a>
                    </li>
                    @foreach ($plants->getUrlRange(1, $plants->lastPage()) as $page => $url)
                        <li class="page-item {{ $plants->currentPage() == $page ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach
                    <li class="page-item {{ $plants->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $plants->nextPageUrl() }}">Next</a>
                    </li>
                </ul>
            </nav>

        </div>
    </div>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        document.getElementById('category').onchange = function() {
            this.form.submit();
        };
        document.getElementById('searchBox').oninput = function() {
            this.form.submit();
        };
    </script>




@endsection
