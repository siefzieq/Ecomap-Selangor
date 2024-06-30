<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta charset="UTF-8">
    <meta name="description" content="Ecomap Selangor">
    <meta name="keywords" content="HTML,CSS,XML,JavaScript">
    <meta name="author" content="Hasif Zikry">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ECOMAP SELANGOR</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="/public/favicon.png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="/favicon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    @vite(['resources/js/app.js'])

    <style>
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

        body {
            background-image: url('/ecosel.webp');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-color: #000000;
            font-family: Inter,sans-serif;

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
            position: absolute; /* Use absolute positioning */
            right: 0; /* Align to the right side of the page */
            top: 0; /* Align to the top of the page */
            bottom: 0; /* Align to the bottom of the page */
            width: 30%; /* Width of the sidebar, adjust as needed */
            background: white; /* Background color of the sidebar */
            padding: 20px;
            box-shadow: -5px 0 10px rgba(0, 0, 0, 0.3); /* Shadow on the left edge */
            display: flex;
            flex-direction: column; /* Stack children vertically */
            justify-content: center; /* Center content vertically */
            align-items: center; /* Center content horizontally */
            animation: slideInFromRight 1s ease-out forwards; /* Animation applied here */
        }

        .login-box {
            width: 80%; /* Adjust the width as needed, relative to its parent container */
            /* Rest of your login box styles */
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
    <div class="login-box">
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group" style="padding: 10px 0;">
                <label style="color:#2D6C2B; font-weight: 600">Username</label>
                <input id="email" type="email" style="border-radius: 10px; height: 50px;" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Enter your email address" required autocomplete="off" autofocus>
                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="form-group">
                <label style="color:#2D6C2B; font-weight: 600">Password</label>
                <input id="password" type="password" style="border-radius: 10px; height: 50px;" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Enter your password" required autocomplete="off">
                @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <button type="submit" class="btn text-white form-control" style= "background-color:#2D6C2B; margin-top: 10px; border-radius: 10px; height: 50px;" >
                {{ __('Login') }}
            </button>
        </form>
        <div style="text-align: center; font-size: 14px; padding-top: 30px">
            Don't have an account?<a href="{{route('register')}}" style="color: #2D6C2B;"> Sign up</a>
        </div>
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
</script>
</html>
