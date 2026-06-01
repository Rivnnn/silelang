<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register | SiLelang</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, sans-serif;
        }
        body {
            margin: 0;
            height: 100vh;
            background: linear-gradient(
                to right,
                #ffffff 0%,
                #ffffff 40%,
                #39C6C9 100%
            );
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-card {
            background: #ffffff;
            width: 720px;
            min-height: 420px;
            border-radius: 20px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.25);
            display: flex;
            overflow: hidden;
        }

        .login-left {
            width: 45%;
            background: linear-gradient(160deg, #39C6C9, #7FE3E6);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 18px;
        }

        .login-left img {
            width: 100%;
            max-width: 280px;
            height: auto;
        }
        .login-right {
            width: 55%;
            padding: 45px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-right h3 {
            margin-bottom: 20px;
            color: #2E2E2E;
            font-size: 22px;
            font-weight: 600;
        }
        .login-right input {
            width: 100%;
            padding: 13px;
            margin-bottom: 15px;
            border-radius: 10px;
            border: 1px solid #ddd;
            font-size: 14px;
            transition: 0.3s;
        }

        .login-right input:focus {
            outline: none;
            border-color: #39C6C9;
            box-shadow: 0 0 0 2px rgba(57,198,201,0.25);
        }

        /* Style untuk Pesan Error */
        .alert-error {
            background-color: #fce8e8;
            color: #e02424;
            padding: 10px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 15px;
            border: 1px solid #f8b4b4;
        }
        .alert-error ul {
            margin: 0;
            padding-left: 20px;
        }

        .login-right button {
            width: 100%;
            padding: 13px;
            background: linear-gradient(to right, #39C6C9, #2FB3B6);
            border: none;
            border-radius: 10px;
            color: #fff;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        .login-right button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(57,198,201,0.45);
        }

        .login-right p {
            margin-top: 18px;
            font-size: 14px;
            color: #555;
            text-align: center;
        }

        .login-right a {
            color: #39C6C9;
            text-decoration: none;
            font-weight: bold;
        }

        .login-right a:hover {
            text-decoration: underline;
        }

        @media(max-width: 768px) {
            .login-card {
                flex-direction: column;
                width: 90%;
            }

            .login-left, .login-right {
                width: 100%;
            }

            .login-left img {
                max-width: 200px;
            }
        }
    </style>
</head>
<body>

<div class="login-card">

    <div class="login-left">
        <img src="{{ asset('images/SIL.png') }}" alt="SiLelang Logo">
    </div>

    <div class="login-right">
        <h3>Registrasi Akun</h3>

        @if ($errors->any())
            <div class="alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="/register">
            @csrf
            <input type="text" name="name" placeholder="Nama Lengkap" value="{{ old('name') }}" required>
            <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
            <input type="password" name="password" placeholder="Password" required>
            
            <button type="submit">Daftar</button>
        </form>

        <p>Sudah punya akun? <a href="/login">Login</a></p>
    </div>

</div>

</body>
</html>