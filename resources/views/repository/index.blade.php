@extends('layouts.app')
<style>
    .custom-table {
        border-collapse: collapse;
        width: 100%;
        margin-top:10px;
    }

    .custom-table td {
        padding: 10px !important;
        text-align: center;
        border-bottom: 1px solid #ddd;
        margin-top: 5px;
        height: 55px;
    }

    .custom-table th {
        padding: 20px !important;
        text-align: center;
        border-bottom: 1px solid #ddd;
        height: 55px;
        background-color: #FFFFFF!important;
        color: #2D6C2B !important;
    }

    .custom-table td {
        text-align: center;
        border-bottom: 1px solid #ddd;
        background-color: #FFFFFF!important;
    }

    th {
        padding-top: 30px !important;
        padding-bottom: 25px !important;
    }

    .graphBox {
        position:relative;
        width: 100%;
        padding-right: 60px;
        padding-top: 30px;
        margin-left: 0;
        display: grid;
        grid-template-columns: 1fr;
        grid-gap: 3%;
        height: 25%;
        flex-wrap: wrap;
    }

    .graphBox {
        align-items: start; /* This ensures that all items in the flexbox start from the same line */
    }

    .graph {
        position:relative;
        background: #FFFFFF;
        padding: 20px 20px;
        width: 100%;
        box-shadow: 0 7px 25px rgba(0,0,0,0.08);
        border-radius: 20px;
        flex-wrap: wrap;
    }

    strong, small {
        padding-left: 10px;
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

    .btn.btn-category:hover {
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

@section('title', 'Repository')
@section('caption','Repository')
@section('content')
    <div class="pt-3 d-flex justify-content-between align-items-center">
        <div class="d-flex gap-2">
            <button class="btn btn-category filter-btn {{ $category === 'all' ? 'active' : '' }}" data-category="all" style="border-radius: 20px;">All</button>
            <button class="btn btn-category filter-btn {{ $category === 'plant-related' ? 'active' : '' }}" data-category="plant-related" style="border-radius: 20px;">Plant-related</button>
            <button class="btn btn-category filter-btn {{ $category === 'flat-related' ? 'active' : '' }}" data-category="flat-related" style="border-radius: 20px;">Flat-related</button>
        </div>
        <div class="pe-5 d-flex align-items-center gap-2 search-box">
            <i class="fi fi-rr-search search-icon"></i>
            <input type="search" id="searchBox" class="form-control bg-white" placeholder="Type to filter the list" value="{{ $search }}" oninput="performSearch()">
        </div>
    </div>
    <div class="graphBox">
        <div class="graph" style="margin-left: 0px; margin-bottom: 5%; width: 100%; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <strong style="font-size: 20px; font-weight: 700">Repository</strong>
                    <p><small>Collection of documents</small></p>
                </div>
                <a href="{{ route('repository.create') }}" class="circle" data-bs-toggle="tooltip" data-bs-title="Add Document" style="text-decoration: none; color: white; width:35px; height:35px; background-color: #2D6C2B">
                    <i class="fi fi-rr-plus"></i>
                </a>
            </div>
            <table class="table custom-table" style="width: 100%;">
                <tr>
                    <th>No.</th>
                    <th>File name</th>
                    <th>File type</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Uploaded by</th>
                    <th>Action</th>
                </tr>
                @foreach($repo as $index => $r)
                    <tr class="repository-item" data-category="{{ strtolower($r->category) }}">
                        <td>{{ $repo->firstItem() + $index }}.</td>
                        <td>
                            @if(in_array($r->file_type, ['image', 'pdf', 'document']))
                                <a href="{{ asset($r->file_path) }}" target="_blank" style="text-decoration: none;">
                                    {{ $r->file_name }}
                                </a>
                            @else
                                {{ $r->file_name }}
                            @endif
                        </td>
                        <td>{{ ucwords(strtolower($r->file_type)) }}</td>
                        <td>{{ $r->description ?? 'N/A' }}</td>
                        <td>{{ ucwords(strtolower($r->category)) }}</td>
                        <td>{{ ucwords(strtolower($r->updater->name ?? 'N/A')) }}</td>
                        <td>
                            <form action="{{ route('repository.destroy', $r) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <a href="{{ route('repository.edit', $r->id) }}" class="btn btn-secondary" style="text-decoration: none; color: white;">Edit</a>
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Confirm DELETE? this {{ $r->name }} record')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </table>
            <!-- Custom Pagination Links -->
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <li class="page-item {{ $repo->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $repo->previousPageUrl() }}">Previous</a>
                    </li>
                    @foreach ($repo->getUrlRange(1, $repo->lastPage()) as $page => $url)
                        <li class="page-item {{ $repo->currentPage() == $page ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach
                    <li class="page-item {{ $repo->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $repo->nextPageUrl() }}">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
    <!-- Enable Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = document.querySelectorAll('.filter-btn');
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                    button.classList.add('active');
                    applyFilter();
                });
            });

            // Initially apply the filter based on the URL parameters
            const category = "{{ $category ?? 'all' }}";
            const search = "{{ $search ?? '' }}";
            document.getElementById('searchBox').value = search;
            document.querySelector(`.filter-btn[data-category="${category}"]`).classList.add('active');
        });

        function applyFilter() {
            const category = document.querySelector('.filter-btn.active').getAttribute('data-category');
            const search = document.getElementById('searchBox').value;
            const url = new URL(window.location.href);
            url.searchParams.set('category', category);
            url.searchParams.set('search', search);
            window.location.href = url.toString();
        }

        function performSearch() {
            applyFilter();
        }
    </script>
@endsection
