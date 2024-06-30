@extends('layouts.app')

<style>
    form {
        padding-top: 50px;
        padding-left: 40px;
    }

    input, textarea, select {
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

@section('title', 'Add New Plantation')
@section('caption', 'Add New Plantation')
@section('content')
    <div style="background-color: #ffffff; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.15); position: relative; margin-top: 3%; left: -0.5%; width: 96%; height: 70%; overflow: hidden;">
        <div style="height: calc(100% - 20px); overflow-y: auto;">
            <form method="POST" action="{{route('plantation.store')}}" style="width: 100%;">
                @csrf
                <label class="form-label">Plant</label>
                <select class="form-select form-control" id="plant_id" name="plant_id" style="width: 95%;" required>
                    <option selected disabled>Choose the plant</option>
                    @foreach($plants as $p)
                        <option value="{{$p->id}}">{{$p->name}}</option>
                    @endforeach
                </select><br>

                <label class="form-label">Planting Type</label>
                <select class="form-select form-control" id="planting_type" name="planting_type" style="width: 95%;" required>
                    <option selected disabled>Choose the plant category</option>
                    <option value="fertigation">Fertigation</option>
                    <option value="conventional">Conventional</option>
                    <option value="aquaponic">Aquaponic</option>
                </select><br>

                <label class="form-label">Seeding duration (days)</label>
                <input type="number" class="form-control" id="seeding_duration" name="seeding_duration" step="1" placeholder="0" style="width: 95%;" required min="0" oninput="checkInput(this)"><br>

                <label class="form-label">Harvesting duration (days)</label>
                <input type="number" class="form-control" id="harvesting_duration" name="harvesting_duration" step="1" placeholder="0" style="width: 95%;" required min="0" oninput="checkInput(this)"><br>

                <label class="form-label">Completion duration (days)</label>
                <input type="number" class="form-control" id="completion_duration" name="completion_duration" step="1" placeholder="0" style="width: 95%;" required min="0" oninput="checkInput(this)"><br>

                <div class="d-flex justify-content-end gap-2" style="padding-top: 10px; padding-right: 60px">
                    <a href="{{route('plant.index')}}" role="button" type="reset" class="btn btn-outline">Cancel</a>
                    <button type="submit" class="btn text-white" style="background-color:#2D6C2B; border-radius: 8px; height: 38px; width: 80px;">Add</button>
                </div>
            </form>
        </div>
    </div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        function checkInput(input) {
            if (input.value < 0) {
                input.setCustomValidity('Duration must be a positive number.');
            } else {
                input.setCustomValidity('');
            }
        }

        function fetchPlantInfo() {
            let plantId = $('#plant_id').val();
            let plantingType = $('#planting_type').val();

            if (plantId && plantingType) {
                $.ajax({
                    url: '{{ route('plantation.fetch-info') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        plant_id: plantId,
                        planting_type: plantingType
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#category').val(response.category);
                            $('#description').val(response.description);
                            toastr.options.closeButton = true;
                            toastr.options.progressBar = true;
                            toastr.options.preventDuplicates = true;
                            toastr.options.positionClass = "toast-bottom-right";
                            toastr.warning('This plantation information is already stored in the database.');
                            // Disable the submit button
                            $('button[type="submit"]').prop('disabled', true);
                        } else {
                            $('#category').val('').find('option:first').prop('selected', true);
                            $('#description').val('').attr('placeholder', 'Description of the plant');
                            $('button[type="submit"]').prop('disabled', false);
                        }
                    }
                });
            } else {
                $('#category').val('').find('option:first').prop('selected', true);
                $('#description').val('').attr('placeholder', 'Description of the plant');
            }
        }

        $(document).ready(function() {
            $('#plant_id, #planting_type').on('input', fetchPlantInfo);
        });
    </script>
@endsection
