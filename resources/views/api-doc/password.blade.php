<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Documentation - Password Required</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #273572 0%, #213BAE 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .password-container {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            max-width: 450px;
            width: 100%;
        }
        .password-container h2 {
            color: #273572;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .password-container p {
            color: #666;
            margin-bottom: 30px;
        }
        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 16px;
        }
        .form-control:focus {
            border-color: #273572;
            box-shadow: 0 0 0 0.2rem rgba(39, 53, 114, 0.25);
        }
        .btn-primary {
            background: #273572;
            border: none;
            border-radius: 8px;
            padding: 12px 30px;
            font-weight: 600;
            width: 100%;
        }
        .btn-primary:hover {
            background: #213BAE;
        }
        .alert-danger {
            border-radius: 8px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="password-container">
        <h2>🔒 API Documentation</h2>
        <p>Please enter the password to access the API documentation.</p>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif
        
        <form method="POST" action="{{ route('api.doc.authenticate') }}">
            @csrf
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required autofocus>
            </div>
            <button type="submit" class="btn btn-primary">Access Documentation</button>
        </form>
    </div>
</body>
</html>
