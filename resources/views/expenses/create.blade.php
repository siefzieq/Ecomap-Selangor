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

@section('title', 'Add Expenses')
@section('caption', 'Add Expenses')

@section('content')
    <div style="background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            position: fixed;
            top: 180px;
            width: 1100px;
            height: 70%;
            overflow: hidden;">

        <div style="height: calc(100% - 20px); overflow-y: auto;">
            <form method="POST" action="{{route('expenses.store')}}" enctype="multipart/form-data" style="width: 100%;">
                @csrf
                <label class="form-label">Date</label>
                <input type="date" class="form-control  @error('date') is-invalid @enderror" id="date" name="date" placeholder="DD/MM/YYYY" style="width: 95%;" required><br>
                @error('date')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <label class="form-label">Flat</label>
                <select class="form-select form-control" id="flat_id" name="flat_id" style="width: 95%;" required>
                    <option selected disabled>Choose the flat</option>
                    @foreach($flats as $f)
                        <option value="{{$f->id}}">{{$f->name}}</option>
                    @endforeach
                </select><br>

                <label class="form-label">Item</label>
                <input type="text" class="form-control" id="item_name" name="item_name" placeholder="Enter the item name" autocomplete="off" style="width: 95%;" required><br>

                <label class="form-label">Category</label>
                <select class="form-select form-control" id="category" name="category" style="width: 95%;" required>
                    <option selected disabled>Choose the category</option>
                    <option value="setup_infrastructure">Setup & Infrastructure</option>
                    <option value="equipment">Equipment & Tools</option>
                    <option value="operational">Operational Expenses</option>
                    <option value="maintenance">Maintenance</option>
                </select><br>

                <label class="form-label">Type</label>
                <select class="form-select form-control" id="type" name="type" style="width: 95%;" required>
                    <option selected disabled>Choose the type</option>
                    <option value="site preparation">Site preparation</option>
                    <option value="growing medium">Growing medium</option>
                    <option value="planting equipment">Planting equipment</option>
                    <option value="harvesting tools">Harvesting Tools</option>
                    <option value="irrigation system">Irrigation system</option>
                    <option value="lighting">Lighting</option>
                    <option value="seeds">Seeds</option>
                    <option value="fertilizers">Fertilizers</option>
                    <option value="pesticides">Pesticides</option>
                    <option value="water & utilities">Water & utilities</option>
                    <option value="repairs">Repairs</option>
                </select><br>

                <label class="form-label" id="plant_label">Plant</label>
                <select class="form-select form-control" id="plant_id" name="plant_id" style="width: 95%;">
                    <option selected disabled>Choose the plant</option>
                    @foreach($plants as $p)
                        <option value="{{$p->id}}">{{$p->name}}</option>
                    @endforeach
                </select>

                <div style="display: flex; justify-content: space-between;">
                    <div style="width: 50%;">
                        <label class="form-label">Amount</label>
                        <input type="number" class="form-control" id="amount" name="amount" value="0.00" style="width: 90%;"  min= 0.00 step="0.01" required><br>
                    </div>
                    <div style="width: 50%;">
                        <label class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="quantity" name="quantity"  value="0" style="width: 90%;" required><br>
                    </div>
                </div>

                <label class="form-label">Total</label>
                <div class="input-group mb-3" style="width: 95%;">
                    <span class="input-group-text" id="inputGroup-sizing-default" style="background-color: #FFFFFF">RM</span>
                    <input type="number" class="form-control" id="totalValue" disabled>
                    <!-- Hidden input field to store the numerical value of the total -->
                    <input type="hidden" id="total" name="total">
                </div>

                <label class="form-label">Supported Document</label>
                <input type="file" class="form-control" id="file_path" name="file_path" style="width: 95%;"><br>

                <div class="d-flex justify-content-end gap-2" style="padding-top: 30px; padding-right: 60px">
                    <a href="{{route('flatInventory.index')}}" role="button" type="reset" class="btn btn-outline">Cancel</a>
                    <button type="submit" class="btn text-white" style="background-color:#2D6C2B; border-radius: 8px; height: 38px; width: 80px;">Add</button>
                </div>

            </form>
        </div>
    </div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#category').on('change', function() {
                var category = $(this).val();
                var typeOptions = {
                    setup_infrastructure: ['Site preparation', 'Growing Medium'],
                    equipment: ['Planting Equipment', 'Harvesting Tools','Irrigation System', 'Lighting'],
                    operational: ['Seeds', 'Fertilizers', 'Pesticides'],
                    maintenance: ['Water Utilities', 'Repairs']
                };

                $('#type').empty(); // Clear existing options
                $('#type').append('<option selected disabled>Choose the type</option>'); // Add default option

                if(typeOptions[category]) {
                    typeOptions[category].forEach(function(item) {
                        $('#type').append($('<option>', {
                            value: item.toLowerCase(),
                            text : item
                        }));
                    });
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            // Initially hide the plant_id container
            document.getElementById('plant_id').style.display = 'none';
            document.getElementById('plant_label').style.display = 'none';



            function togglePlantVisibility() {
                var category = document.getElementById('category').value;
                var type = document.getElementById('type').value;

                if (category === 'operational' && type === 'seeds') {
                    document.getElementById('plant_id').style.display = 'block';
                    document.getElementById('plant_label').style.display = 'block';

                } else {
                    document.getElementById('plant_id').style.display = 'none';
                    document.getElementById('plant_label').style.display = 'none';


                }
            }

            document.getElementById('category').addEventListener('change', togglePlantVisibility);
            document.getElementById('type').addEventListener('change', togglePlantVisibility);

            // Initial check in case of pre-filled values
            togglePlantVisibility();
        });

        //calculate total
        document.addEventListener("DOMContentLoaded", function() {
            var amount = document.getElementById('amount');
            var quantity = document.getElementById('quantity');
            var Total = document.getElementById('total');
            var TotalValue = document.getElementById('totalValue');


            function calculateTotal() {
                var Quantity = parseInt(quantity.value) || 0;
                var Amount = parseFloat(amount.value) || 0;
                var total = Quantity*Amount;
                TotalValue.value = total.toFixed(2);
                Total.value = total.toFixed(2);
            }

            amount.addEventListener('input', calculateTotal);
            quantity.addEventListener('input', calculateTotal);
        });



    </script>
@endsection


