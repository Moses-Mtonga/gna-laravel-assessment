<!DOCTYPE html>  
<html lang="en">  
<head>  
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <title>Login</title>  
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">  
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">  
    <style>  
        body {  
            background-color: #f5f5f5;  
        }  
        .login-container {  
            max-width: 400px;  
            margin: 50px auto;  
            padding: 20px;            background: linear-gradient(45deg, #36a51a3e, #91d09121);  
            border: none;  
            box-shadow: 0 0 10px rgba(0,0,0,0.1);  
        }  
        .login-header {  
            text-align: center;  
            margin-bottom: 20px;  
            color: rgba(22, 22, 22, 0.799);  
        }  
        .login-form {  
            margin-bottom: 20px;  
        }  
        .login-button {  
            width: 100%;  
            padding: 10px;  
            font-size: 16px;  
            font-weight: bold;  
            color: #fff;  
            background: linear-gradient(45deg, #36a51a3e, #91d09121);  
            border: none;  
            border-radius: 5px;  
            cursor: pointer;  
        }  
        .login-button:hover {  
            background: linear-gradient(45deg, #2e8b57, #8bc34a);  
        }  
        .login-forgot {  
            text-align: center;  
            margin-bottom: 20px;  
            color: rgba(22, 22, 22, 0.799);  
        }  
        .login-register {  
            text-align: center;  
        }  
        .form-label {  
            color: rgba(22, 22, 22, 0.799);  
        }  
        .form-control {  
            border: 1px solid #7ca87e76;  
        }  
    </style>  
</head>  
<body>  
    <div class="login-container">  
        <div class="login-header">  
            <h4>Login to GNA farmers app</h4>  
        </div>  
        <form method="POST" action="{{ route('login') }}" class="login-form">  
            @csrf  
            <div class="mb-3">  
                <label for="email" class="form-label">Email Address</label>  
                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autocomplete="email" autofocus>  
                @error('email')  
                    <span class="invalid-feedback" role="alert">  
                        <strong>{{ $message }}</strong>  
                    </span>  
                @enderror  
            </div>  
            <div class="mb-3">  
                <label for="password" class="form-label">Password</label>  
                <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="current-password">  
                @error('password')  
                    <span class="invalid-feedback" role="alert">  
                        <strong>{{ $message }}</strong>  
                    </span>  
                @enderror  
            </div>  
            <div class="mb-3 form-check">  
                <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>  
                <label class="form-check-label" for="remember">Remember Me</label>  
            </div>  
            <button type="submit" class="login-button">Login</button>  
            @if (Route::has('password.request'))  
                <a href="{{ route('password.request') }}" class="login-forgot">Forgot Your Password?</a>  
            @endif  
        </form>  

    </div>  
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>  
</body>  
</html>