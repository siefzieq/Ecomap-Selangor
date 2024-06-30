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

@section('title', 'Update Plant Information')
@section('caption', 'Update Plant Information')
@section('content')
    <div style="background-color: #ffffff; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.15); position: relative; margin-top: 3%; left: -0.5%; width: 96%; height: 70%; overflow: hidden;">
        <div style="height: calc(100% - 20px); overflow-y: auto;">

            <form method="POST" action="{{route('plant.update',$plant)}}"  enctype="multipart/form-data" style="width: 100%;">
                @csrf
                @method('PATCH')
                <label class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Name of the plant"  style="width: 95%;" value="{{$plant->name}}"><br>

                <label class="form-label">Category</label>
                <select class="form-select form-control" id="category" name="category" style="width: 95%;" >
                    <option selected disabled>Choose the plant category</option>
                    <option value="sayur berdaun" {{ $plant->category=='sayur berdaun' ? 'selected' : '' }}>sayur berdaun </option>
                    <option value="sayur buah"  {{ $plant->category=='sayur buah' ? 'selected' : '' }}>sayur buah</option>
                </select><br>

                <label class="form-label">Remarks</label>
                <textarea class="form-control" id="description" name="description" rows="4" placeholder="Description of the plant" style="width: 95%;">{{ $plant->description }}</textarea><br>

                <label class="form-label">Image</label>
                @if($plant->image_path)
                    <div>
                        <img src="{{ asset($plant->image_path) }}" alt="Current Image" style="width: 80px; height: auto;">
                    </div>
                @endif
                <input type="file" class="form-control" id="image_path" name="image_path" style="width: 95%;"><br>



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
