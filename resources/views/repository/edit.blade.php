@extends('layouts.app')
<style>

    form{
        padding-top: 40px;
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

@section('title', 'Edit Repository')
@section('caption', 'Edit Repository')
@section('content')
    <div style="background-color: #ffffff; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.15); position: relative; margin-top: 3%; left: -0.5%; width: 96%; height: 70%; overflow: hidden;">
        <div style="height: calc(100% - 20px); overflow-y: auto;">

            <form method="POST" action="{{route('repository.update',$repository)}}"  enctype="multipart/form-data" style="width: 100%;">
                @csrf
                @method("PATCH")
                <label class="form-label">Name</label>
                <input type="text" class="form-control" id="file_name" name="file_name" placeholder="Enter the file name"  style="width: 95%;" value="{{$repository->file_name}}"><br>

                <label class="form-label">Type</label>
                <select class="form-select form-control" id="file_type" name="file_type" style="width: 95%;">
                    <option selected disabled>Choose the file type</option>
                    <option value="document" {{ $repository->file_type=='document' ? 'selected' : '' }}>Document</option>
                    <option value="image"  {{ $repository->file_type=='image' ? 'selected' : '' }}>Image</option>
                </select><br>

                <label class="form-label">Remarks</label>
                <textarea class="form-control" id="description" name="description" rows="4" placeholder="Description of the file" style="width: 95%;" >{{$repository->description}}</textarea><br>

                <label class="form-label">File</label>
                <input type="file" class="form-control" id="file_path" name="file_path" style="width: 95%;">{{$repository->file}}<br>

                <label class="form-label">Category</label>
                <select class="form-select form-control" id="category" name="category" style="width: 95%;" required>
                    <option selected disabled>Choose the file category</option>
                    <option value="plant-related" {{ $repository->category=='plant-related' ? 'selected' : '' }}>Plant-related</option>
                    <option value="flat-related"  {{ $repository->category=='flat-related' ? 'selected' : '' }}>Flat-related</option>
                </select><br>

                <div class="d-flex justify-content-end gap-2" style="padding-top: 20px; padding-right: 60px; padding-bottom: 20px">
                    <a href="{{route('repository.index')}}" role="button" type="reset" class="btn btn-outline">Cancel</a>
                    <button type="submit" class="btn text-white" style="background-color:#2D6C2B; border-radius: 8px; height: 38px; width: 80px;">Add</button>
                </div>
            </form>
        </div>

    </div>

    <!-- Enable Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

@endsection
