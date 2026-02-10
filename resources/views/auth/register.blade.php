<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | {{ config('app.name', 'NewsFeed') }}</title>
    <link rel="icon" href="{{ asset('assets/images/logo_bg.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/design-system.css') }}">
    
    <style>
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .auth-container {
            background: var(--color-bg-primary);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-xl);
            max-width: 500px;
            width: 100%;
            padding: 48px;
        }
        
        .auth-header {
            text-align: center;
            margin-bottom: 32px;
        }
        
        .auth-logo {
            width: 60px;
            height: 60px;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
            font-weight: var(--font-weight-bold);
        }
        
        .auth-header h1 {
            font-size: var(--font-size-3xl);
            font-weight: var(--font-weight-bold);
            color: var(--color-text-primary);
            margin-bottom: 8px;
        }
        
        .auth-header p {
            color: var(--color-text-secondary);
            font-size: var(--font-size-base);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            font-weight: var(--font-weight-medium);
            color: var(--color-text-primary);
            margin-bottom: 8px;
            font-size: var(--font-size-sm);
        }
        
        .form-control {
            border: 1px solid var(--color-border);
            border-radius: var(--radius-md);
            padding: 12px 16px;
            font-size: var(--font-size-base);
            transition: all var(--transition-base);
        }
        
        .form-control:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px var(--color-primary-50);
            outline: none;
        }
        
        .input-group {
            position: relative;
        }
        
        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--color-text-secondary);
            z-index: 10;
        }
        
        .input-group .form-control {
            padding-left: 44px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
            border: none;
            border-radius: var(--radius-md);
            padding: 14px;
            font-weight: var(--font-weight-semibold);
            font-size: var(--font-size-base);
            width: 100%;
            transition: all var(--transition-base);
            box-shadow: var(--shadow-md);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }
        
        .auth-footer {
            text-align: center;
            margin-top: var(--spacing-lg);
            padding-top: var(--spacing-lg);
            border-top: 1px solid var(--color-border);
        }
        
        .auth-footer a {
            color: var(--color-primary);
            text-decoration: none;
            font-weight: var(--font-weight-medium);
        }
        
        .auth-footer a:hover {
            text-decoration: underline;
        }
        
        .alert {
            border-radius: var(--radius-md);
            border: none;
            padding: 12px 16px;
            margin-bottom: 20px;
        }
        
        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--color-text-secondary);
            cursor: pointer;
            z-index: 10;
        }
        
        .password-toggle:hover {
            color: var(--color-primary);
        }
        
        .row {
            margin: 0 -10px;
        }
        
        .row > div {
            padding: 0 10px;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-header">
            <div class="auth-logo">
                <i class="fas fa-user-plus"></i>
            </div>
            <h1>Create Account</h1>
            <p>Join us and start sharing your thoughts</p>
        </div>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('register') }}" method="POST" id="registerForm">
            @csrf
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="first_name" class="form-label">First Name</label>
                        <div class="input-group">
                            <i class="fas fa-user input-icon"></i>
                            <input type="text" 
                                   class="form-control @error('first_name') is-invalid @enderror" 
                                   id="first_name" 
                                   name="first_name" 
                                   value="{{ old('first_name') }}" 
                                   placeholder="John"
                                   required 
                                   autofocus>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="last_name" class="form-label">Last Name</label>
                        <div class="input-group">
                            <i class="fas fa-user input-icon"></i>
                            <input type="text" 
                                   class="form-control @error('last_name') is-invalid @enderror" 
                                   id="last_name" 
                                   name="last_name" 
                                   value="{{ old('last_name') }}" 
                                   placeholder="Doe"
                                   required>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <div class="input-group">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           placeholder="john@example.com"
                           required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           id="password" 
                           name="password" 
                           placeholder="At least 8 characters"
                           required>
                    <button type="button" class="password-toggle" onclick="togglePassword('password')">
                        <i class="fas fa-eye" id="passwordToggleIcon"></i>
                    </button>
                </div>
            </div>
            
            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <div class="input-group">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" 
                           class="form-control" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           placeholder="Confirm your password"
                           required>
                    <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                        <i class="fas fa-eye" id="passwordConfirmationToggleIcon"></i>
                    </button>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-user-plus me-2"></i> Create Account
            </button>
        </form>
        
        <div class="auth-footer">
            <p style="color: var(--color-text-secondary); font-size: var(--font-size-sm); margin: 0;">
                Already have an account? 
                <a href="{{ route('login.form') }}">Sign in here</a>
            </p>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const toggleIcon = document.getElementById(fieldId === 'password' ? 'passwordToggleIcon' : 'passwordConfirmationToggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
