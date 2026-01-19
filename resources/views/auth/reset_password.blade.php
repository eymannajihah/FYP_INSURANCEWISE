<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - InsuranceWise</title>
    <style>
        body {
            font-family: "Poppins", sans-serif;
            background-image: url("{{ asset('image/requestform.jpeg') }}");
            background-repeat: no-repeat;
            background-position: center center;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .container { background: white; padding: 30px; border-radius: 12px; width: 100%; max-width: 400px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #ff69b4; margin-bottom: 20px; }
        input { width: 100%; padding: 12px; margin-bottom: 15px; border-radius: 8px; border: 1px solid #ccc; }
        button { width: 100%; padding: 12px; background: #ff69b4; color: white; border: none; border-radius: 8px; cursor: pointer; }
        .alert { margin-bottom: 15px; padding: 10px; border-radius: 8px; }
        .alert-success { background: #e6ffed; color: #2a7; }
        .alert-danger { background: #ffe6e6; color: #c33; }
        a { color: #ff69b4; text-decoration: none; font-size: 13px; display: block; text-align: center; margin-top: 10px; }
    </style>
</head>
<body>

<div class="container">
    <h2>Reset Password</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="email" name="email" placeholder="Enter your registered email" required>
        <input type="password" name="new_password" placeholder="Enter new password" required>
        <input type="password" name="new_password_confirmation" placeholder="Confirm new password" required>
        <button type="submit">Reset Password</button>
    </form>

    <a href="{{ route('login') }}">Back to Login</a>
</div>

</body>
</html>
