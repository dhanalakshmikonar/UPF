<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
    height: 100vh;
    background: 
        linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)),
        url("{{ asset('img/bg.png') }}") no-repeat center center fixed;
    background-size: cover;
    display: flex;
    justify-content: center;
    align-items: center;
}

        .main-container {
            width: 1000px;
            height: 600px;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            display: flex;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }

        /* LEFT IMAGE */
        .image-section {
            width: 50%;
        }

        .image-section img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* RIGHT LOGIN */
        .login-section {
            width: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 60px;
        }

        .login-box {
            width: 100%;
            max-width: 350px;
        }

        h2 {
            font-size: 28px;
            margin-bottom: 10px;
            color: #1f2937;
        }

        .subtitle {
            font-size: 14px;
            margin-bottom: 30px;
            color: #6b7280;
        }

        label {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
        }

        input {
            width: 100%;
            padding: 10px 12px;
            margin-top: 6px;
            margin-bottom: 18px;
            border-radius: 6px;
            border: 1px solid #d1d5db;
            transition: 0.3s;
        }

        input:focus {
            border-color: #4f8cff;
            outline: none;
            box-shadow: 0 0 0 2px rgba(79,140,255,0.2);
        }

        .form-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            margin-bottom: 20px;
        }

        .form-footer a {
            color: #4f8cff;
            text-decoration: none;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #4f8cff;
            border: none;
            border-radius: 6px;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #3b6edc;
        }

        .options-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.remember-wrapper {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    cursor: pointer;
}

.remember-wrapper input {
    width: 16px;
    height: 16px;
    accent-color: #4f8cff;
}

.forgot-link {
    font-size: 14px;
    color: #4f8cff;
    text-decoration: none;
}

.forgot-link:hover {
    text-decoration: underline;
}

.login-btn {
    width: 100%;
    padding: 12px;
    background: #4f8cff;
    border: none;
    border-radius: 6px;
    color: white;
    font-weight: 600;
    cursor: pointer;
    transition: 0.3s;
}

.login-btn:hover {
    background: #3b6edc;
}

.register-footer {
    margin-top: 10px;
    margin-bottom: 20px;
}

.login-link {
    font-size: 14px;
    color: #4f8cff;
    text-decoration: none;
}

.login-link:hover {
    text-decoration: underline;
}

.register-btn {
    width: 100%;
    padding: 12px;
    background: #4f8cff;
    border: none;
    border-radius: 6px;
    color: white;
    font-weight: 600;
    cursor: pointer;
    transition: 0.3s;
}

.register-btn:hover {
    background: #3b6edc;
}

        @media(max-width: 900px){
            .main-container {
                flex-direction: column;
                width: 90%;
                height: auto;
            }

            .image-section {
                height: 300px;
                width: 100%;
            }

            .login-section {
                width: 100%;
            }

        }
    </style>
</head>
<body>

<div class="main-container">

    <!-- LEFT IMAGE -->
    <div class="image-section">
        <img src="{{ asset('img/upf1.png') }}" alt="Login Image">
    </div>

    <!-- RIGHT LOGIN -->
    <div class="login-section">
        <div class="login-box">
            {{ $slot }}
        </div>
    </div>

</div>

</body>
</html>