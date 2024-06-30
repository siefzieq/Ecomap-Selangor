<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="/favicon.png">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">


    @vite(['resources/js/app.js'])

    <style>
        body {
            background-image: url('/urban.webp');
            background-size: cover;
            background-repeat: no-repeat;
            /* Ensure the background is fixed and does not move with content scrolling */
            background-attachment: fixed;
        }

        @keyframes slideInFromRight {
            0% {
                right: 5%;
                opacity: 1;
            }
            100% {
                right: 0;
                opacity: 1;
            }
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 0;
        }

        .header-title {
            position: absolute;
            top: 35vh;
            left:25%;
            transform: translateX(-50%);
            color: #FFFFFF;
            text-align: left;
            font-family: 'Inter', sans-serif;
            font-size: 4vw; /* Use vw for responsive font size */
            font-weight: 700;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.7);
        }

        @media (max-width: 768px) {
            .header-title {
                font-size: 6vw; /* Increase font size for smaller screens */
                top: 15vh; /* Adjust the vertical position for smaller screens */
            }
        }

        @media (max-width: 480px) {
            .header-title {
                font-size: 8vw; /* Further increase font size for very small screens */
                top: 10vh; /* Further adjust the vertical position for very small screens */
            }
        }

        .container {
            position: absolute;
            right: 0;
            top: 0;
            bottom: 0;
            width: 30%;
            background: white;
            padding: 20px;
            box-shadow: -5px 0 10px rgba(0, 0, 0, 0.3);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            animation: slideInFromRight 1s ease-out forwards; /* Animation applied here */
        }

        .login-box {
            width: 80%; /* Adjust the width as needed, relative to its parent container */
            /* Rest of your login box styles */
        }
        .header-title {
            position: absolute;
            top: 35vh;
            left:25%;
            transform: translateX(-50%);
            color: #FFFFFF;
            text-align: left;
            font-family: 'Inter', sans-serif;
            font-size: 4vw; /* Use vw for responsive font size */
            font-weight: 700;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.7);
        }

        @media (max-width: 768px) {
            .header-title {
                font-size: 6vw; /* Increase font size for smaller screens */
                top: 15vh; /* Adjust the vertical position for smaller screens */
            }
        }

        @media (max-width: 480px) {
            .header-title {
                font-size: 8vw; /* Further increase font size for very small screens */
                top: 10vh; /* Further adjust the vertical position for very small screens */
            }
        }
        .input-group {
            position: relative;
            display: flex;
        }

        .input-group-append {
            margin-left: -45px; /* Adjust based on your border if necessary */
        }

        .input-group .input-group-append .btn {
            color: #2D6C2B; /* Adjust the color to fit your design */
            background-color: #FFFFFF !important;
        }

    </style>

</head>
<body>
<div class="header-title">
    <p id="p-typed"></p>
    <h4 id="h4-typed"></h4>
</div>
<!-- Login part -->
<div class="container">
    <img src="/logo.png" width="150px" style="padding-bottom: 20px">
    <div class="login-box" style="font-family: 'Inter', sans-serif;">
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group">
                <label style="color:#2D6C2B; display: block;"><b>{{ __('Name') }}</b></label>
                <input id="name" type="text" style="border-radius: 10px; height: 50px;" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="Enter your name" required autocomplete="name" autofocus>
                @error('name')
                <span class="invalid-feedback" role="alert">
                   <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="form-group">
                <label style="color:#2D6C2B; display: block;"><b>{{ __('Email Address') }}</b></label>
                <input id="email" type="email" style="border-radius: 10px; height: 50px;" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Enter your company email address" required autocomplete="email">
                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="form-group">
                <label style="color:#2D6C2B; display: block;"><b>Password</b></label>
                <div class="input-group" >
                    <input id="password" type="password" class="form-control" name="password" placeholder="Must have at least 8 characters" style="height: 6vh;border-radius: 10px" required>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary toggle-password" type="button">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <small class="text-danger" id="passwordError"></small>
            </div>

            <div class="form-group">
                <label style="color:#2D6C2B; display: block;"><b>Confirm Password</b></label>
                <input id="password-confirm" type="password" style="border-radius: 10px; height: 50px;" class="form-control" name="password_confirmation" placeholder="Enter the confirm password" required autocomplete="new-password">
                <small class="text-danger" id="confirmPasswordError"></small>
            </div>

            <button type="submit" class="btn text-white form-control" style="background-color:#2D6C2B; margin-top: 10px; border-radius: 10px; height: 50px; width: 100%;">
                {{ __('Register') }}
            </button>
        </form>
    </div>
</div>

</body>
<script>
    function typeEffect(element, text, delay = 100) {
        let charIndex = 0;
        const typeInterval = setInterval(() => {
            if (charIndex < text.length) {
                element.innerHTML += text.charAt(charIndex);
                charIndex++;
            } else {
                clearInterval(typeInterval);
            }
        }, delay);
    }

    window.onload = () => {
        const pText = "ECOMAP SELANGOR";
        const h4Text = "ELEVATING URBAN FARMING WITH SMART SOLUTIONS";
        const pElement = document.getElementById('p-typed');
        const h4Element = document.getElementById('h4-typed');

        typeEffect(pElement, pText, 100);
        setTimeout(() => typeEffect(h4Element, h4Text, 35), pText.length * 100);
    };
    function validatePassword() {
        var password = document.getElementById('password');
        var confirmPassword = document.getElementById('password-confirm');
        var passwordError = document.getElementById('passwordError');
        var confirmPasswordError = document.getElementById('confirmPasswordError');

        // Clear previous errors
        passwordError.textContent = '';
        confirmPasswordError.textContent = '';

        var valid = true;

        // Password length validation
        if (password.value.length < 8) {
            passwordError.textContent = 'Password must be at least 8 characters long.';
            valid = false;
        }

        // Check for uppercase letter
        if (!/[A-Z]/.test(password.value)) {
            passwordError.textContent = 'Password must contain at least one uppercase letter.';
            valid = false;
        }

        // Check for lowercase letter
        if (!/[a-z]/.test(password.value)) {
            passwordError.textContent = 'Password must contain at least one lowercase letter.';
            valid = false;
        }

        // Check for a number
        if (!/[0-9]/.test(password.value)) {
            passwordError.textContent = 'Password must contain at least one number.';
            valid = false;
        }

        // Check for a special character
        if (!/[\W_]/.test(password.value)) {
            passwordError.textContent = 'Password must contain at least one special character.';
            valid = false;
        }

        // Check if passwords match
        if (password.value !== confirmPassword.value) {
            confirmPasswordError.textContent = 'Passwords do not match.';
            valid = false;
        }

        return valid; // Return the status of the validation
    }

    document.querySelector('form').addEventListener('submit', function(event) {
        if (!validatePassword()) {
            event.preventDefault(); // Prevent form submission if validation fails
        }
    });
    document.querySelectorAll('.toggle-password').forEach(function(button) {
        button.addEventListener('click', function() {
            let passwordInput = this.previousElementSibling; // assuming the input is right before the button in the DOM
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                this.innerHTML = '<i class="fas fa-eye-slash"></i>'; // change icon to "eye-slash"
            } else {
                passwordInput.type = 'password';
                this.innerHTML = '<i class="fas fa-eye"></i>'; // change icon back to "eye"
            }
        });
    });
    function validatePassword() {
        var password = document.getElementById('password');
        var confirmPassword = document.getElementById('password-confirm');
        var passwordError = document.getElementById('passwordError');
        var confirmPasswordError = document.getElementById('confirmPasswordError');

        // Clear previous errors
        passwordError.textContent = '';
        confirmPasswordError.textContent = '';

        var valid = true;

        // Password length validation
        if (password.value.length < 8) {
            passwordError.textContent = 'Password must be at least 8 characters long.';
            valid = false;
        }

        // Check for uppercase letter
        if (!/[A-Z]/.test(password.value)) {
            passwordError.textContent = 'Password must contain at least one uppercase letter.';
            valid = false;
        }

        // Check for lowercase letter
        if (!/[a-z]/.test(password.value)) {
            passwordError.textContent = 'Password must contain at least one lowercase letter.';
            valid = false;
        }

        // Check for a number
        if (!/[0-9]/.test(password.value)) {
            passwordError.textContent = 'Password must contain at least one number.';
            valid = false;
        }

        // Check for a special character
        if (!/[\W_]/.test(password.value)) {
            passwordError.textContent = 'Password must contain at least one special character.';
            valid = false;
        }

        // Check if passwords match
        if (password.value !== confirmPassword.value) {
            confirmPasswordError.textContent = 'Passwords do not match.';
            valid = false;
        }

        return valid; // Return the status of the validation
    }

    document.querySelector('form').addEventListener('submit', function(event) {
        if (!validatePassword()) {
            event.preventDefault(); // Prevent form submission if validation fails
        }
    });
    document.querySelectorAll('.toggle-password').forEach(function(button) {
        button.addEventListener('click', function() {
            let passwordInput = this.closest('.input-group').querySelector('input');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                this.innerHTML = '<i class="fas fa-eye-slash"></i>'; // change icon to "eye-slash"
            } else {
                passwordInput.type = 'password';
                this.innerHTML = '<i class="fas fa-eye"></i>'; // change icon back to "eye"
            }
        });
    });

</script>
</html>
