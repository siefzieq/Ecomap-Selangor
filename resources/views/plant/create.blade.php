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

@section('title', 'Add New Plant')
@section('caption', 'Add New Plant')
@section('content')
    <div style="background-color: #ffffff; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.15); position: relative; margin-top: 3%; left: -0.5%; width: 96%; height: 70%; overflow: hidden;">
        <div style="height: calc(100% - 20px); overflow-y: auto;">

            <form method="POST" action="{{route('plant.store')}}"  enctype="multipart/form-data" style="width: 100%;">
                @csrf
                    <label class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Name of the plant"  oninput="fetchPlantInfo()" style="width: 95%;" autocomplete="off" required><br>

                    <label class="form-label">Category</label>
                    <select class="form-select form-control" id="category" name="category" style="width: 95%;" required>
                        <option selected disabled>Choose the plant category</option>
                        <option value="sayur berdaun">sayur berdaun</option>
                        <option value="sayur buah">sayur buah</option>
                    </select><br>

                    <label class="form-label">Stocks</label>
                    <input type="number" class="form-control" id="stocks" name="stocks" placeholder="0" style="width: 95%;" autocomplete="off">
                    <small><b style="color: #2D6C2B">Note : If register new plant, enter 0</b></small><br><br>


                    <label class="form-label">Remarks</label>
                    <textarea class="form-control" id="description" name="description" rows="4" placeholder="Description of the plant" style="width: 95%;"></textarea><br>

                    <label class="form-label">Image</label>
                    <input type="file" class="form-control" id="image_path" name="image_path" style="width: 95%;"><br>


                    <div class="d-flex justify-content-end gap-2" style="padding-top: 30px; padding-right: 60px">
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

        function fetchPlantInfo() {
            let plantName = $('#name').val();

            if (plantName.length > 0) {
                $.ajax({
                    url: '{{ route('plant.fetch-info') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        name: plantName
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#category').val(response.category);
                            $('#description').val(response.description);
                            toastr.options.closeButton = true;
                            toastr.options.progressBar = true;
                            toastr.options.preventDuplicates = true;
                            toastr.options.positionClass = "toast-bottom-right";
                            toastr.warning('This plant information is already stored in the database.');
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


            $('#name').on('input', fetchPlantInfo);


    </script>
@endsection
