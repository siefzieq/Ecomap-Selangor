@extends('layouts.app')
<style>
    h4 {
        padding-top: 10px;
        padding-bottom: 10px;
    }

    .graphBox {
        display: grid;
        grid-template-columns: repeat(3, 1fr); /* Creates three columns of equal width */
        gap: 20px; /* Space between columns and rows */
        padding: 20px; /* Padding around the entire grid */
        height: 50%;
    }

    .graphBox1 {
        display: grid;
        grid-template-rows: repeat(2, 1fr); /* Creates two rows of equal height */
        gap: 20px; /* Space between rows */
        padding: 20px; /* Padding around the entire grid */

    }

    .graph {
        background: #FFFFFF;
        padding: 20px;
        box-shadow: 0 7px 25px rgba(0,0,0,0.08);
        border-radius: 20px;
        /* Ensure the content scales properly within each grid cell */
        width: 100%;
        height: 100%; /* Adjust height to make sure it fits within grid area, you might need to adjust or remove this depending on content */
    }

    strong,small{
        padding-left: 10px;
    }

    .user-photo-wrapper {
        text-align: center;
        display: flex;
        justify-content: center;
        position: -ms-device-fixed;
    }

    .user-photo {
        position: relative;
        width: 150px;
        height: 150px;
        border-radius: 50%;
        background-color: #f0f0f0;
        overflow: hidden;
        display: flex;
        justify-content: center;
        align-items: center;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.25);
    }

    .user-photo img {
        height: 100%;
        width: 100%;
        object-fit: cover;
        border-radius: 50%; /* Ensures the image keeps the circular shape */
    }


    .btn{
        background-color: #2D6C2B !important;
        color:white !important;
    }


</style>

@section('title', 'My Profile')
@section('caption', 'My Profile')
@section('content')

    <div class="pt-4 pe-5 d-flex justify-content-end">
        <a href="{{ route('profile.edit') }}" class="btn" style="border-radius: 15px">
            <i class="fi fi-rr-user-pen" style="padding-left: 15px"></i> &nbsp; Edit Profile
        </a>
    </div>

    <div class="graphBox">
        <div class="graph pt-5" style="text-align: center;">
            <div class="user-photo-wrapper">
                <div class="user-photo">
                    <img id="userImage" src="{{ asset(Auth::user()->profile_image_path) }}" width="50" height="50" >
                </div>
            </div>
            <br><strong style="font-size: 24px; font-weight: 700; ">{{Auth::user()->name}}</strong><br>
            <strong style="font-size: 16px; font-weight: 350;">{{Auth::user()->position}}</strong>
        </div>
        <div class="graphBox1">
            <div class="graph">
                <strong style="font-size: 24px; font-weight: 700;">Email</strong><br>
                <button style="background-color: rgba(144, 238, 144, 0.25); color: black;  border: none; border-radius: 20px; padding: 8px 20px; display: inline-block; height: 35%" disabled>
                    {{Auth::user()->email}}
                </button>
            </div>
            <div class="graph">
                <strong style="font-size: 24px; font-weight: 700">Department</strong><br>
                <button style="background-color: rgba(144, 238, 144, 0.25); color: black; border: none; border-radius: 20px; padding: 8px 20px; display: inline-block; height: 35%; text-align: start; font-size: 13px"  disabled>
                    {{Auth::user()->department}}
                </button>

            </div>
        </div>
        <div class="graphBox1">
            <div class="graph">
                <strong style="font-size: 24px; font-weight: 700">Phone number</strong><br>
                <button style="background-color: rgba(255, 165, 0, 0.25); color: black; border: none; border-radius: 20px; padding: 8px 20px; display: inline-block; height: 35%;" disabled>
                    {{ Auth::user()->phone }}
                </button>
            </div>
            <div class="graph">
                <strong style="font-size: 24px; font-weight: 700">Role</strong><br>
                <button style="background-color: rgba(255, 165, 0, 0.25); color: black; border: none; border-radius: 20px; padding: 8px 20px; display: inline-block; height: 35%" disabled>
                    @if (Auth::user()->role == 0)
                        Admin
                    @else
                        Basic User
                    @endif
                </button>

            </div>
        </div>

    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
@endsection
