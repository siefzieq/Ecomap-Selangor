@extends('layouts.app')
<style>
    h4 {
        padding-top: 10px;
        padding-bottom: 10px;
    }

    .graphBox {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        padding: 20px;
        height: 50%;
        margin-top: 60px;
    }

    .btn.btn-outline {
        border-color: #2D6C2B;
        color: #2D6C2B;
    }

    .btn.btn-outline:hover {
        background-color: #2D6C2B;
        color: #ffffff; /* Change text color when hovered */
    }

    .graphBox1 {
        display: grid;
        grid-template-rows: repeat(2, 1fr);
        gap: 20px;
        padding: 20px;

    }

    .graph {
        background: #FFFFFF;
        padding: 20px;
        box-shadow: 0 7px 25px rgba(0,0,0,0.08);
        border-radius: 20px;
        width: 100%;
        height: 100%;
    }

    strong,small{
        padding-left: 10px;
    }

    .user-photo-wrapper {
        display: flex;
        justify-content: center;
        position: -ms-device-fixed;
    }

    .user-photo {
        position: relative;
        width: 150px;
        height: 150px;
        border-radius: 50%;
        background-color: #f0f0f0;
        overflow: hidden;
        cursor: pointer;
        display: flex;
        justify-content: center;
        align-items: center;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.25);
    }
    .user-photo img {
        height: 100%;
        width: 100%;
        object-fit: cover;
        border-radius: 50%; /* Ensures the image keeps the circular shape */
    }
    .user-photo:hover::after {
        content: 'Choose Photo';
        position: absolute;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        color: white;
        font-size: 16px;
        border-radius: 50%;
    }
    input[type="file"] {
        display: none;
    }
</style>

@section('title', 'Update Profile')
@section('caption', 'Update Profile')
@section('content')

    <form method="POST" enctype="multipart/form-data" action="{{ route('profile.update') }}">
        @csrf
        @method('PUT')
        <div class="graphBox">
            <div class="graph">
                <div class="user-photo-wrapper" onclick="document.getElementById('photo-upload').click()">
                    <div class="user-photo">
                        <img id="userImage" src="{{ asset(Auth::user()->profile_image_path) ?? asset('path/to/default/profile/picture.png') }}">
                    </div>
                </div>
                <input id="photo-upload" type="file" name="profile_image_path" onchange="readURL(this);" accept="image/*">
                <strong style="font-size: 24px; font-weight: 700;">
                    <center>
                        <input type="text" class="form-control bg-transparent @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}" style="padding: 8px 20px; width:100%" required>
                        @error('name')
                        <div style="color: red;">{{ $message }}</div>
                        @enderror
                    </center>
                </strong>
                <strong style="font-size: 16px; font-weight: 700;">
                    <center>
                        <input type="text" class="form-control bg-transparent @error('position') is-invalid @enderror" name="position" value="{{ old('position', $user->position) }}" style="padding: 8px 20px; width:100%">
                        @error('position')
                        <div style="color: red;">{{ $message }}</div>
                        @enderror
                    </center>
                </strong>
            </div>
            <div class="graphBox1">
                <div class="graph">
                    <strong style="font-size: 24px; font-weight: 700;">Email</strong><br>
                    <input type="text" class="form-control bg-transparent @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" style="padding: 8px 20px; width:100%" required>
                    @error('email')
                    <div style="color: red;">{{ $message }}</div>
                    @enderror
                </div>
                <div class="graph">
                    <strong style="font-size: 24px; font-weight: 700">Department</strong><br>
                    <input type="text" class="form-control bg-transparent @error('department') is-invalid @enderror" name="department" value="{{ old('department', $user->department) }}" style="padding: 8px 20px;">
                    @error('department')
                    <div style="color: red;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="graphBox1">
                <div class="graph">
                    <strong style="font-size: 24px; font-weight: 700">Phone number</strong><br>
                    <input type="text" class="form-control bg-transparent @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone', $user->phone) }}" style="padding: 8px 20px;" required autocomplete="off">
                    @error('phone')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="graph">
                    <strong style="font-size: 24px; font-weight: 700">Role</strong><br>
                    @if (Auth::user()->role == 0)
                        <input type="text" class="form-control bg-disabled" name="phone" value="Admin" style="padding: 8px 20px" disabled>
                    @else
                        <input type="text" class="form-control bg-disabled" name="phone" value="Basic user" style="padding: 8px 20px" disabled>
                    @endif
                </div>
            </div>
            <div style="position: fixed; right: 50px; bottom: 75px;">
                <div class="d-grid gap-2 d-md-block">
                    <a href="{{ route('profile') }}" role="button" type="reset" class="btn btn-outline">Cancel</a>
                    <button type="submit" class="btn text-white" style="background-color:#2D6C2B; border-radius: 8px; height: 38px; width: 80px;">Save</button>
                </div>
            </div>
        </div>
    </form>

    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('userImage').setAttribute('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
