@extends('layouts.app')
<style>
    div b {
        padding-top: 10%; /* This is likely causing the large gap */
        padding-left: 10%;
        font-weight: 700;
        font-size: 20px;
    }

    div{
        border-radius: 20px;

    }

    .circle {
        width: 40px;
        height: 40px;
        background-color: #2D6C2B;
        border: none;
        border-radius: 50%;
        display: flex; /* Added to center content */
        align-items: center; /* Added to center content */
        justify-content: center; /* Added to center content */
        color: white;
        font-size: 20px;
        text-align: center;
        margin-top: 10px;
        margin-right: 20px;
        margin-left: 20px;
        margin-bottom: 12px;
    }
    .explanation {
        display: none;
        padding-top: 10px;
        text-align: justify;
        transition: all 0.3s ease-in-out;
        padding-left: 20px;
        padding-bottom: 20px;
        padding-right: 20px;
    }

    /* Adjust icon color to green */
    .pagination .page-link i {
        color: green;
        font-size: 1.5rem; /* Adjust font size if needed */
    }

    /* Adjust the size of the navbar */
    .pagination {
        font-size: 1rem; /* Set a medium font size */
    }

    .pagination .page-item {
        margin: 0 5px;
    }

    .pagination .page-link {
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 10px 15px;
        background-color: #f8f9fa;
        color: green;
    }

    .pagination .page-link:hover {
        background-color: #e9ecef;
    }

</style>
@section('title', 'FAQ')
@section('caption', 'Frequently Asked Question')
@section('content')

    <div class="pt-4" style="width: 97%;">
        <div class="bg-white" style="box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);" >
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <b style="padding-top: 0; padding-left: 20px;">What is Ecomap Selangor (ECOSEL)?</b>
                <button onclick="toggleExplanation('explanation1')" class="circle">
                    <i class="fi fi-rr-plus"></i>
                </button>
            </div>
            <p id="explanation1" class="explanation">
                EcoMap Selangor is our interactive mapping and management system designed to oversee and facilitate agricultural and ecological activities across various flats in the state of Selangor. The platform integrates geographic information systems (GIS) to provide real-time data on plantation progress, flat activities, and environmental management. This tool aims to enhance sustainable practices and streamline the management of agricultural projects within the region.
            </p>
        </div>
    </div>
    <br>

    <div class="pt-4" style="width: 97%;">
        <div class="bg-white" style="box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);" >
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <b style="padding-top: 0; padding-left: 20px;">What is a flat in this system?</b>
                <button onclick="toggleExplanation('explanation2')" class="circle">
                    <i class="fi fi-rr-plus"></i>
                </button>
            </div>
            <p id="explanation2" class="explanation">
                A flat refers to an individual unit within our system that can engage in various activities such as planting or cultivating different types of plants. Each flat may have its own unique set of plantations or agricultural projects.
            </p>
        </div>
    </div>
    <br>

    <div class="pt-4" style="width: 97%;">
        <div class="bg-white" style="box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);" >
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <b style="padding-top: 0; padding-left: 20px;">How can I register a new flat to the system?</b>
                <button onclick="toggleExplanation('explanation3')" class="circle">
                    <i class="fi fi-rr-plus"></i>
                </button>
            </div>
            <p id="explanation3" class="explanation">
                To add a new flat, navigate to the 'Flat Information' section and click on 'Add New Flat'. Fill in the required details such as name, location, and any other necessary information and submit the form.
            </p>
        </div>
    </div>
    <br>

    <div class="pt-4" style="width: 97%;">
        <div class="bg-white" style="box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <b style="padding-top: 0; padding-left: 20px;">What types of plants can be cultivated in the flats?</b>
                <button onclick="toggleExplanation('explanation4')" class="circle">
                    <i class="fi fi-rr-plus"></i>
                </button>
            </div>
            <p id="explanation4" class="explanation">
                Our system supports a diverse range of plants. You can view the list of available plants by visiting the 'Plant Information' section. If you wish to add a new type of plant, navigate to 'Add New Plant' under the 'Plant' menu.
            </p>
        </div>
    </div>
    <br>

    <div class="pt-4" style="width: 97%;">
        <div class="bg-white" style="box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);" >
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <b style="padding-top: 0; padding-left: 20px;">How do I view the plantation progress by individual flat?</b>
                <button onclick="toggleExplanation('explanation5')" class="circle">
                    <i class="fi fi-rr-plus"></i>
                </button>
            </div>
            <p id="explanation5" class="explanation">
                To update the progress, go to the 'Flat Inventory' section, select the specific flat and plantation you want to update, and modify the progress status or any other details as needed.
            </p>
        </div>
    </div>
    <br>


    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-end pe-4 pt-2">
            <li class="page-item">
                <a class="page-link" href="#"><i class="fi fi-rr-angle-small-left"></i></a>
            </li>
            <li class="page-item">
                <a class="page-link" href="{{route('FAQ_2')}}"><i class="fi fi-rr-angle-small-right"></i></a>
            </li>
        </ul>
    </nav>


    <script>
        function toggleExplanation(id) {
            const explanation = document.getElementById(id);
            if (explanation.style.display === 'none' || explanation.style.display === '') {
                explanation.style.display = 'block';
            } else {
                explanation.style.display = 'none';
            }
        }
    </script>

@endsection
