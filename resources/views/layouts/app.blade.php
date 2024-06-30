<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="/favicon.png">

    <title>@yield('title', 'ECOMAP SELANGOR')</title>

    <!--CSS -->
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.3.1/mapbox-gl.css" rel="stylesheet">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.3.0/uicons-thin-straight/css/uicons-thin-straight.css'>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.4.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet-search/dist/leaflet-search.min.css" />



    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.3.1/mapbox-gl.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-statistics@7.3.0/dist/simple-statistics.min.js"></script>
    <script src="https://unpkg.com/leaflet-search/dist/leaflet-search.min.js"></script>
    <script src="https://code.highcharts.com/gantt/highcharts-gantt.js"></script>


    <style>
        body {
            background-color: #F7F7F7;
            font-family: "Inter", sans-serif; !important;
            font-size: 14px;
            font-weight: 450;
            flex-wrap: wrap;
        }
        .sidenav {
            height: 100%;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #ffffff;
            padding-top: 40px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        }
        .sidenav a {
            padding: 15px;
            text-decoration: none;
            display: block;
            color: #000;
            margin-left: 30px;
        }
        .sidenav li .submenu {
            list-style: none;
            padding-left: 1rem;
            padding-right: 1rem;
        }
        .sidenav li a:hover {
            background-color: #F7F7F7;
            color: #2D6C2B;
            border-top-left-radius: 30px;
            border-bottom-left-radius: 30px;
        }

        .sidenav li a:hover:after,
        .sidenav li a.active {
            background-color: #F7F7F7;
            color: #2D6C2B;
            border-top-left-radius: 30px;
            border-bottom-left-radius: 30px;
        }

        .sidenav li a.active {
            font-weight: bold !important;
        }
        .fixed-top-container {
            position: fixed;
            width: 137vh;
            top: 20px;
            left: 280px;
            right: 0;
            background-color: #FFFFFF;
            height: 70px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            border-radius: 10px;
            z-index: 1000;
        }
        .profile-info {
            display: flex;
            align-items: center;
        }
        .profile-info img {
            border-radius: 50%;
            margin-left: 10px;
        }

        * {
            margin: 0;
            padding: 0;
        }

        html {
            height: 100%;
        }

        .dropdown-icon {
            transition: transform 0.3s ease;
        }

    </style>
</head>
<body>

<!-- Sidebar -->
<nav class="sidenav card py-2 mb-4">
    <ul class="nav flex-column" id="nav_accordion">
        <a href="{{route('home')}}"><img src="/logo.png" width="120px" style="padding-bottom: 20px"></a>
        <li class="nav-item" style="padding-bottom: 25px">
            <a class="nav-link {{ Request::is('home') ? 'active' : '' }}" href="{{route('home')}}"><i class="fi fi-rr-dashboard-panel"></i>&nbsp&nbsp Dashboard </a>
        </li>
        <p style="color: #B5B1B1; padding-left: 25px; font-size: 12px">MAIN</p>
        <div style="padding-bottom: 10px;">
            <hr style="width: 75%; margin: 0 auto;">
        </div>
        <li class="nav-item has-submenu">
            <a class="nav-link {{Request::is('plant*') ? 'active' : '' }}" href="#"><i class="fi fi-rr-cactus"></i>&nbsp; Plant <i class="fi fi-rr-angle-small-down dropdown-icon"></i></a>
            <ul class="submenu collapse">
                <li><a class="nav-link {{ Request::is('plant') ? 'active' : '' }}" href="{{ route('plant.index') }}">Plant Information</a></li>
                <li><a class="nav-link {{ Request::is('plantation') ? 'active' : '' }}" href="{{ route('plantation.index') }}">Plantation Information</a></li>
                <li class="nav-item has-submenu">
                    <a class="nav-link has-submenu {{ Request::is('plantInventory*') ? 'active' : '' }}" href="#"></i>Plant Inventory <i class="fi fi-rr-angle-small-down dropdown-icon"></i></a>
                    <ul class="submenu collapse">
                        <li><a class="nav-link {{ Request::is('stock in') ? 'active' : '' }}" href="{{route('plantInventory.index')}}">Stock in</a></li>
                        <li><a class="nav-link {{ Request::is('#') ? 'active' : '' }}" href="{{route('seeding.index')}}">Seeding Distribution</a></li>
                    </ul>
                </li>
            </ul>
        </li>
        <li class="nav-item has-submenu">
            <a class="nav-link {{ Request::is('flat*') ? 'active' : '' }}" href="#"><i class="fi fi-rr-apartment"></i>&nbsp&nbsp Flat <i class="fi fi-rr-angle-small-down dropdown-icon"></i></a>
            <ul class="submenu collapse">
                <li><a class="nav-link {{ Request::is('flat/create') ? 'active' : '' }}" href="{{route('flat.create')}}">Add New Flat </a></li>
                <li><a class="nav-link {{ Request::is('flat') ? 'active' : '' }}" href="{{route('flat.index')}}">Flat Location </a></li>
                <li><a class="nav-link {{ Request::is('flatInventory') ? 'active' : '' }}" href="{{route('flatInventory.index')}}">Flat Inventory </a></li>
            </ul>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Request::is('expenses*') ? 'active' : '' }}" href="{{route('expenses.index')}}"><i class="fi fi-rr-expense"></i>&nbsp&nbsp Expenses </a>
        </li>

        <p style="color: #B5B1B1; padding-left: 25px; font-size: 12px; padding-top:25px;">OTHERS</p>
        <div style="padding-bottom: 10px;">
            <hr style="width: 75%; margin: 0 auto;">
        </div>
        <li class="nav-item">
            <a class="nav-link {{ Request::is('repository') ? 'active' : '' }}" href="{{route('repository.index')}}"><i class="fi fi-rr-folder-open"></i>&nbsp&nbsp Repository </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Request::is('faq') ? 'active' : '' }}" href="{{route('FAQ')}}"><i class="fi fi-rr-interrogation"></i>&nbsp&nbsp FAQ </a>
        </li>
    </ul>
</nav>

<!-- Fixed Top Container -->
<div class="fixed-top-container">
    <!-- Placeholder for any left-aligned content -->
    <div>
        <p style="font-size: 18px; color:#2D6C2B; font-weight: 700; margin-top:20px; margin-bottom: 0;">ECOMAP SELANGOR</p>
        <p style="font-size: 14px; color:#B5B1B1; font-weight: 350;">@yield('caption')</p>
    </div>

    <!-- Profile Information -->
    <div class="profile-info">
        <div>
            <p style="font-size: 18px; color:#2D6C2B; font-weight: 600; margin-top:20px; margin-bottom: 0; margin-left: 20px">{{Auth::user()->name}}</p>
            <p style="font-size: 14px; color:#B5B1B1; font-weight: 350; margin-top: 0; margin-left: 70px">Admin</p>
        </div>
        <div style="position: relative;">
            <button onclick="toggleDropdown()" style="background-color: transparent; border: none; cursor: pointer;">
                <img id="profileImage" src="{{ Auth::user()->profile_image_path ?: '/profile.png' }}" width="45px" height="45px">
            </button>
            <div id="dropdownMenu" style="position: absolute; display: none; background-color: #fff; border-radius: 5px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); right: 0; z-index: 999; width: 200px; margin-top: 10px;">
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <li style="padding: 10px;">
                        <a class="dropdown-item" href="{{route('profile')}}">
                            <i class="fi fi-rr-user-pen" style="margin-left: 20px;"></i>&nbsp&nbsp Profile
                        </a>
                    </li>
                    <li style="padding: 10px;">
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fi fi-rr-exit" style="margin-left: 5px;"></i>&nbsp&nbsp Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Add this form for logout -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<script>
    function toggleDropdown() {
        var dropdownMenu = document.getElementById("dropdownMenu");
        if (dropdownMenu.style.display === "none" || dropdownMenu.style.display === "") {
            dropdownMenu.style.display = "block";
        } else {
            dropdownMenu.style.display = "none";
        }
    }
</script>

<!-- Content -->
<div style="margin-left: 290px; padding-top: 120px;">
    @yield('content')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.sidenav .nav-link').forEach(function(element) {
                element.addEventListener('click', function (e) {
                    let nextEl = element.nextElementSibling;
                    let parentEl = element.parentElement;

                    if (nextEl) {
                        e.preventDefault();
                        let mycollapse = new bootstrap.Collapse(nextEl);

                        if (nextEl.classList.contains('show')) {
                            mycollapse.hide();
                            element.querySelector('.dropdown-icon').classList.remove('rotate');
                        } else {
                            mycollapse.show();
                            element.querySelector('.dropdown-icon').classList.add('rotate');
                            // find other submenus with class=show
                            var opened_submenu = parentEl.parentElement.querySelector('.submenu.show');
                            // if it exists, then close all of them
                            if (opened_submenu) {
                                new bootstrap.Collapse(opened_submenu);
                                opened_submenu.previousElementSibling.querySelector('.dropdown-icon').classList.remove('rotate');
                            }
                        }
                    }
                });
            });
        });
    </script>
</div>
<!-- Before the end of the body section -->
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

@if(session('toastr'))
    <script type="text/javascript">
        var toastrMessage = {!! session('toastr') !!};
        toastr.options.closeButton = true;
        toastr.options.progressBar = true;
        toastr.options.preventDuplicates = true;
        toastr.options.positionClass = "toast-bottom-right";
        toastr[toastrMessage.type](toastrMessage.message);
    </script>
@endif
</body>
<script>
    // JavaScript to handle click events and set active class
    document.querySelectorAll('.sidenav li a').forEach(function(link) {
        link.addEventListener('click', function() {
            // Remove active class from all links
            document.querySelectorAll('.sidenav li a').forEach(function(navLink) {
                navLink.classList.remove('active');
            });

            // Add active class to the clicked link
            this.classList.add('active');
        });
    });
</script>
</html>
