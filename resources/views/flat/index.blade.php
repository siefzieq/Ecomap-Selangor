@extends('layouts.app')
<style>
    h4 {
        padding-top: 10px;
        padding-bottom: 10px;
    }

    .custom-table {
        border-collapse: collapse;
        width: 100%;
        margin-top:10px

    }

    .custom-table td {
        padding: 10px !important;
        text-align: center;
        border-bottom: 1px solid #ddd;
        margin-top: 5px;
        height: 55px;
    }

    .custom-table tr:nth-child(even) td {
        background-color: #FFFFFF;
    }

    .custom-table tr:nth-child(odd) td {
        background-color: #F7F7F7;
    }

    .custom-table th{
        padding: 20px !important;
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
        grid-template-rows: 2fr 1fr;
        grid-gap: 20%;
        height: 25%;
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


    #map {
        width: 100%;
        height: 100%;
        z-index: 1; /* Ensure this is lower than the navbar's z-index */
        position: relative; /* Set position to relative or absolute based on layout */
    }
    .btn.btn-category {
        border-color: #2D6C2B !important;
        color: #2D6C2B !important;
    }

    .btn.btn-category:hover{
        background-color: #2D6C2B !important; /* Darker shade on hover/active for better visibility */
        color: #FFFFFF !important; /* Ensure text color is white */
    }


</style>

@section('title', 'Flat')
@section('caption', 'Flat Location')
@section('content')
    <div class="pt-3 pb-2 d-flex justify-content-end align-items-center">
        <div class="pe-5 d-flex align-items-center gap-2">
            <input type="search" id="searchBox" class="form-control bg-white" style="display: inline-block; width: auto;" placeholder="Search by flat name" oninput="performSearch()">
            <button class="btn btn-category"  onclick="performSearch()">Search</button>
        </div>
    </div>
    @php($i=1)
    <div class="graphBox pt-4">
        <div class="graph" id="map" style="width: 100%; height:75vh;"></div><br><br>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


    <script>
        var map = L.map('map').setView([3.183, 101.337], 10); // Show map of Selangor
        var markers = []; // Array to store markers

        @foreach($flats as $f)
        @if($f->latitude && $f->longitude)
        var marker = L.marker([{{ $f->latitude }}, {{ $f->longitude }}]).addTo(map);
        var popupContent = `<b>{{ $f->name }}</b><br>{{ $f->city }}<br>
                        <form action="{{ route('flat.destroy', $f) }}" method="POST" style="margin-top: 10px;">
                            @csrf
        @method('DELETE')
        <a href="{{ route('flat.show', $f->id) }}" class="btn btn-primary popup-btn" style="text-decoration: none; color: white;">
                                Show
                            </a>
                            <a href="{{ route('flat.edit', $f->id) }}" class="btn btn-secondary popup-btn" style="text-decoration: none; color: white;">
                                Edit
                            </a>
                            <button type="submit" class="btn btn-danger popup-btn" onclick="return confirm('Confirm DELETE? this {{ $f->name }} record ')">
                                Delete
                            </button>
                        </form>`;
        marker.bindPopup(popupContent);
        markers.push({marker: marker, name: "{{ $f->name }}"}); // Store marker with name
        @endif
        @endforeach

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(map);

        function performSearch() {
            var searchTerm = document.getElementById('searchBox').value.toLowerCase();
            markers.forEach(function(entry) {
                if (entry.name.toLowerCase().includes(searchTerm)) {
                    entry.marker.addTo(map); // Show marker if it matches search term
                } else {
                    entry.marker.remove(); // Remove marker from map if it does not match search term
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('searchBox').addEventListener('input', performSearch);
        });
    </script>
@endsection
