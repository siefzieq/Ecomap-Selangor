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
                <b style="padding-top: 0; padding-left: 20px;">Can I view the overall progress of all flats?</b>
                <button onclick="toggleExplanation('explanation1')" class="circle">
                    <i class="fi fi-rr-plus"></i>
                </button>
            </div>
            <p id="explanation1" class="explanation">
                Yes, you can view the overall progress of all flats in the 'Dashboard' section where we provide a comprehensive overview of all flats' activities and their current status.
            </p>
        </div>
    </div>
    <br>

    <div class="pt-4" style="width: 97%;">
        <div class="bg-white" style="box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);" >
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <b style="padding-top: 0; padding-left: 20px;">How can I view the overall expenses of flats?</b>
                <button onclick="toggleExplanation('explanation2')" class="circle">
                    <i class="fi fi-rr-plus"></i>
                </button>
            </div>
            <p id="explanation2" class="explanation">
                There are two options. You can view on dashboard or go to the expenses page. Choose the intended month filter and search the flats name to view the expenses individually.
            </p>
        </div>
    </div>
    <br>

    <div class="pt-4" style="width: 97%;">
        <div class="bg-white" style="box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);" >
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <b style="padding-top: 0; padding-left: 20px;">How do I add a new type of plant to the system?</b>
                <button onclick="toggleExplanation('explanation3')" class="circle">
                    <i class="fi fi-rr-plus"></i>
                </button>
            </div>
            <p id="explanation3" class="explanation">
                Go to 'Add New Plant' under the 'Plant' section. Provide the plant name, characteristics, and any other relevant details and submit the information to include it in our system's plant database.
            </p>
        </div>
    <br>

    <div class="pt-4" style="width: 100%;">
        <div class="bg-white" style="box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <b style="padding-top: 0; padding-left: 20px;">How can I view the flats geographically on Ecomap Selangor?</b>
                <button onclick="toggleExplanation('explanation4')" class="circle">
                    <i class="fi fi-rr-plus"></i>
                </button>
            </div>
            <p id="explanation4" class="explanation">
                To view the flats geographically on Ecomap Selangor, follow these simple steps: Select flat, select the flat information page, explore flats, use filter options, and utilize GIS features for in-depth analysis.
            </p>
        </div>
    </div>
    <br>

    <div class="pt-4" style="width: 100%;">
        <div class="bg-white" style="box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);" >
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <b style="padding-top: 0; padding-left: 20px;">How can I upload files in Ecomap Selangor?</b>
                <button onclick="toggleExplanation('explanation5')" class="circle">
                    <i class="fi fi-rr-plus"></i>
                </button>
            </div>
            <p id="explanation5" class="explanation">
                To upload files in Ecomap Selangor, navigate to the 'repository' page, click add button, choose the file type, select the file, click 'Upload', and wait for a confirmation message. Review the uploaded data to ensure accuracy.
            </p>
        </div>
    </div>
    <br>

    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-end pe-1 pt-2">
            <li class="page-item">
                <a class="page-link" href="{{route('FAQ')}}"><i class="fi fi-rr-angle-small-left"></i></a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#"><i class="fi fi-rr-angle-small-right"></i></a>
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
