@extends('layouts.app')
<style>

    h4{
        padding-top: 10px;
        padding-bottom: 30px;
    }

    form{
        padding-top: 35px;
        padding-left: 40px;
    }

    .btn.btn-outline {
        border-color: #2D6C2B;
        color: #2D6C2B;
    }

    .btn.btn-outline:hover {
        background-color: #2D6C2B;
        color: #ffffff; /* Change text color when hovered */
    }

    input,select{
        background-color: #F7F7F7 !important;
    }




</style>

@section('title', 'Edit Plant')
@section('caption', 'Edit Plant Information')
@section('content')

    <div style="background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            position: fixed;
            top: 150px;
            width: 1100px;
            height: 500px;
            overflow: hidden;">

        <div style="height: calc(100% - 20px); overflow-y: auto;">
            <form method="POST" action="{{route("plantation.update",$plantation)}}" style="width: 100%;">
                @csrf
                @method('PATCH')
                <label class="form-label">Plant</label>
                <select class="form-select form-control" id="plant_id" name="plant_id" style="width: 95%;"  required>
                    <option selected disabled>Choose the plant</option>
                    @foreach($plants as $p)
                        <option value="{{$p->id}}" data-ready-stock="{{$p->ready_stock}}" {{ $p->id == $plantation->plant_id ? 'selected' : '' }} >{{$p->name}}</option>
                    @endforeach
                </select><br>

                <label class="form-label">Planting Type</label>
                <select class="form-select form-control" id="planting_type" name="planting_type" style="width: 95%;" >
                    <option selected disabled>Choose the plant category</option>
                    <option value="fertigation" {{ $plantation->planting_type=='fertigation' ? 'selected' : '' }}>Fertigation </option>
                    <option value="conventional"  {{ $plantation->planting_type=='conventional' ? 'selected' : '' }}>Convenional</option>
                    <option value="aquaponic"  {{ $plantation->planting_type=='aquaponic' ? 'selected' : '' }}>Aquaponic</option>
                </select><br>

                <label class="form-label">Seeding duration (days)</label>
                <input type="number" class="form-control" id="seeding_duration" name="seeding_duration" step=1 placeholder="0" style="width: 95%;" value="{{$plantation->seeding_duration}}"><br>

                <label class="form-label">Harvesting duration (days)</label>
                <input type="number" class="form-control" id="harvesting_duration" name="harvesting_duration" step=1 placeholder="0" style="width: 95%;" value="{{$plantation->harvesting_duration}}"><br>

                <label class="form-label">Completion duration (days)</label>
                <input type="number" class="form-control" id="completion_duration" name="completion_duration" step=1 placeholder="0" style="width: 95%;" value="{{$plantation->completion_duration}}"><br>

                <div class="d-flex justify-content-end gap-2" style="padding-top: 10px; padding-right: 60px">
                    <a href="{{route('plant.index')}}" role="button" type="reset" class="btn btn-outline">Cancel</a>
                    <button type="submit" class="btn text-white" style="background-color:#2D6C2B; border-radius: 8px; height: 38px; width: 80px;">Save</button>
                </div>
            </form>

        </div>
    </div>




@endsection


