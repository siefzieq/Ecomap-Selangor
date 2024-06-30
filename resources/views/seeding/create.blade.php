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

@section('title', 'Add Seeding Distribution')
@section('caption', 'Add Seeding Distribution')
@section('content')
    <div style="background-color: #ffffff; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.15); position: relative; margin-top: 3%; left: -0.5%; width: 95%; height: 65%; overflow: hidden;">
        <div style="height: calc(100% - 20px); overflow-y: auto;">
            <form method="POST" action="{{route('seeding.store')}}" style="width: 100%;">
                @csrf
                <label class="form-label">Date</label>
                <input type="date" class="form-control @error('date') is-invalid @enderror" id="date" name="date" placeholder="DD/MM/YYYY" style="width: 95%;" required><br>
                @error('date')
                <div class="alert alert-danger" style="width: 95%">{{ $message }}</div>
                @enderror
                <label class="form-label">Plant</label>
                <select class="form-select form-control" id="plant_id" name="plant_id" style="width: 95%;" required>
                    <option selected disabled>Choose the plant</option>
                    @foreach($plants as $p)
                        <option value="{{$p->id}}">{{$p->name}}</option>
                    @endforeach
                </select><br>

                <label class="form-label" >Distributed To</label>
                <select class="form-select form-control" id="flat_id" name="flat_id" style="width: 95%" >
                    <option selected disabled>Choose the flat</option>
                    @foreach($flats as $f)
                        <option value="{{$f->id}}">{{$f->name}}</option>
                    @endforeach
                </select><br>

                <label class="form-label">Quantity</label>
                <input type="number" class="form-control" id="quantity" name="quantity" placeholder="0" min="0" style="width: 95%;"><br>


                <div class="d-flex justify-content-end gap-2" style="padding-top: 10px; padding-right: 60px">
                    <a href="{{route('plantInventory.index')}}" role="button" type="reset" class="btn btn-outline">Cancel</a>
                    <button type="submit" class="btn text-white" style="background-color:#2D6C2B; border-radius: 8px; height: 38px; width: 80px;">Add</button>
                </div>
            </form>
        </div>

    </div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault(); // Temporarily prevent the form from submitting to check values
            console.log(document.getElementById('flat_id').value);
            // After checking, remove e.preventDefault() to allow form submission
        });

    </script>
@endsection
