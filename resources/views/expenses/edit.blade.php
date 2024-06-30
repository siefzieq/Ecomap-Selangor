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

@section('title', 'Edit Expenses')
@section('caption', 'Edit Expenses')

@section('content')
    <div style="background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            position: fixed;
            top: 180px;
            width: 1100px;
            height: 450px;
            overflow: hidden;">

        <div style="height: calc(100% - 20px); overflow-y: auto;">
            <form method="POST" action="{{route('expenses.update',$expenses)}}" style="width: 100%;">
                @csrf
                @method('PATCH')
                <label class="form-label">Date</label>
                <input type="date" class="form-control" id="date" name="date" placeholder="DD/MM/YYYY" style="width: 95%;" value="{{$expenses->date}}"><br>

                <label class="form-label">Flat</label>
                <select class="form-select form-control" id="flat_id" name="flat_id" style="width: 95%;" required>
                    <option selected disabled>Choose the flat</option>
                    @foreach($flats as $f)
                        <option value="{{$f->id}}" {{ $expenses->flat_id==$f->id ? 'selected' : '' }}>{{$f->name}}</option>
                    @endforeach

                </select><br>

                <label class="form-label">Item</label>
                <input type="text" class="form-control" id="item_name" name="item_name" placeholder="Enter the item name" style="width: 95%;" value="{{$expenses->item_name}}"><br>

                <label class="form-label">Category</label>
                <select class="form-select form-control" id="category" name="category" style="width: 95%;" required>
                    <option selected disabled>Choose the category</option>
                    <option value="setup_infrastructure" {{ $expenses->category=='setup_infrastructure' ? 'selected' : '' }} >Setup & Infrastructure</option>
                    <option value="equipment" {{ $expenses->category=='equipment' ? 'selected' : '' }}>Equipment & Tools</option>
                    <option value="operational" {{ $expenses->category=='operational' ? 'selected' : '' }}>Operational Expenses</option>
                    <option value="maintenance" {{ $expenses->category=='maintenance' ? 'selected' : '' }}>Maintenance</option>
                </select><br>

                <label class="form-label">Type</label>
                <select class="form-select form-control" id="type" name="type" style="width: 95%;" required>
                    <option selected disabled>Choose the type</option>
                    <option value="site preparation"{{ $expenses->type=='site preparation' ? 'selected' : '' }}>Site preparation</option>
                    <option value="growing medium" {{ $expenses->type=='growing medium' ? 'selected' : '' }}>Growing medium</option>
                    <option value="planting equipment" {{ $expenses->type=='planting equipment' ? 'selected' : '' }}>Planting equipment</option>
                    <option value="harvesting tools" {{ $expenses->type=='harvesting tools' ? 'selected' : '' }}>Harvesting Tools</option>
                    <option value="irrigation system" {{ $expenses->type=='irrigation system' ? 'selected' : '' }}>Irrigation system</option>
                    <option value="lighting" {{ $expenses->type=='lighting' ? 'selected' : '' }}>Lighting</option>
                    <option value="seeds" {{ $expenses->type=='seeds' ? 'selected' : '' }}>Seeds</option>
                    <option value="fertilizers" {{ $expenses->type=='fertilizers' ? 'selected' : '' }}>Fertilizers</option>
                    <option value="pesticides"{{ $expenses->type=='pesticides' ? 'selected' : '' }}>Pesticides</option>
                    <option value="water & utilities" {{ $expenses->type=='water & utilities' ? 'selected' : '' }}>Water & utilities</option>
                    <option value="repairs" {{ $expenses->type=='repairs' ? 'selected' : '' }}>Repairs</option>
                </select><br>

                <label class="form-label" id="plant_label">Plant</label>
                <select class="form-select form-control" id="plant_id" name="plant_id" style="width: 95%;">
                    <option selected disabled>Choose the plant</option>
                    @foreach($plants as $p)
                        <option value="{{$p->id}} {{ $expenses->plant_id==$p->id ? 'selected' : '' }}">{{$p->name}}</option>
                    @endforeach
                </select><br>

                <div style="display: flex; justify-content: space-between;">
                    <div style="width: 50%;">
                        <label class="form-label">Amount</label>
                        <input type="number" class="form-control" id="amount" name="amount"  style="width: 90%;"  min= 0.00 step="0.01" value="{{$expenses->amount}}"><br>
                    </div>
                    <div style="width: 50%;">
                        <label class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="quantity" name="quantity"  style="width: 90%;" value="{{$expenses->quantity}}"><br>
                    </div>
                </div>

                <label class="form-label">Total</label>
                <div class="input-group mb-3" style="width: 95%;">
                    <span class="input-group-text" id="inputGroup-sizing-default" style="background-color: #FFFFFF">RM</span>
                    <input type="number" class="form-control" id="totalValue" value="{{$expenses->total}}" disabled>
                    <!-- Hidden input field to store the numerical value of the total -->
                    <input type="hidden" id="total" name="total" value="{{$expenses->total}}">
                </div>

                <div style="position: fixed; right: 50px; bottom: 120px;">

                    <div class="d-grid gap-2 d-md-block">
                        <button type="reset" class="btn btn-outline">Cancel</button>
                        <button type="submit" class="btn text-white" style="background-color:#2D6C2B; border-radius: 8px; height: 38px; width: 80px;">Add</button>
                    </div>
                </div>

            </form>
        </div>
    </div>

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
            const amount = document.getElementById('amount');
            const quantity = document.getElementById('quantity');
            const Total = document.getElementById('total');
            const TotalValue = document.getElementById('totalValue');


            function calculateTotal() {
                const Quantity = parseInt(quantity.value) || 0;
                const Amount = parseFloat(amount.value) || 0;
                const total = Quantity*Amount;
                TotalValue.value = total.toFixed(2);
                Total.value = total.toFixed(2);
            }

            amount.addEventListener('input', calculateTotal);
            quantity.addEventListener('input', calculateTotal);
        });



    </script>
@endsection


