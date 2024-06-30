@extends('layouts.app')
<style>

    form{
        padding-top: 50px;
        padding-left: 40px;
    }

    input, textarea, select{
        background-color: #F7F7F7 !important;
    }

    .btn.btn-outline {
        border-color: #2D6C2B;
        color: #2D6C2B;
    }

    .btn.btn-outline:hover {
        background-color: #2D6C2B;
        color: #ffffff; /* Change text color when hovered */
    }


</style>

@section('title', 'Edit Flat Information')
@section('caption', 'Edit Flat Information')
@section('content')
    <div style="background-color: #ffffff; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.15); position: relative; margin-top: 3%; left: -0.5%; width: 96%; height: 70%; overflow: hidden;">
        <div style="height: calc(100% - 20px); overflow-y: auto;">

            <form method="POST" action="{{route('flat.update',$flat)}}"  enctype="multipart/form-data" style="width: 100%;">
                @csrf
                @method('PATCH')
                <label class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Name of the plant"  style="width: 95%;" value="{{$flat->name}}"><br>

                <label class="form-label">Longitude</label>
                <input type="text" class="form-control" id="longitude" name="longitude" placeholder="Name of the plant"  style="width: 95%;" value="{{$flat->longitude}}"><br>

                <label class="form-label">Latitude</label>
                <input type="text" class="form-control" id="latitude" name="latitude" placeholder="Name of the plant"  style="width: 95%;" value="{{$flat->latitude}}"><br>

                <label class="form-label">City</label>
                <div class="input-group mb-3" style="width: 95%;">
                    <input type="text" class="form-control" id="cityValue" placeholder="City"  style="width: 95%;"  value="{{$flat->city}}"}} disabled>
                    <input type="hidden" id="city" name="city" placeholder="City" style="width: 95%;" value="{{$flat->city}}"><br>
                </div>

                <div class="d-flex justify-content-end gap-2" style="padding-top: 30px; padding-right: 60px">
                    <a href="{{route('plant.index')}}" role="button" type="reset" class="btn btn-outline">Cancel</a>
                    <button type="submit" class="btn text-white" style="background-color:#2D6C2B; border-radius: 8px; height: 38px; width: 80px;">Save</button>
                </div>
            </form>
        </div>

    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

@endsection
