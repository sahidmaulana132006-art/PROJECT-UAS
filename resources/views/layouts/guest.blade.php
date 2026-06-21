<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIEK</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #0F172A;
            background-image: radial-gradient(at 0% 0%, hsla(242,100%,70%,0.15) 0px, transparent 50%),
                              radial-gradient(at 100% 100%, hsla(220,100%,70%,0.1) 0px, transparent 50%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .auth-card {
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 1.25rem;
            background-color: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 10px 10px -5px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 480px;
            color: #f1f5f9;
        }

        .auth-logo {
            font-size: 2.25rem;
            font-weight: 800;
            color: #ffffff;
            text-align: center;
            margin-bottom: 1.5rem;
            letter-spacing: 1px;
        }

        .auth-logo i {
            color: #2563EB;
        }

        .form-control {
            background-color: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #ffffff !important;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
        }

        .form-control:focus {
            background-color: rgba(15, 23, 42, 0.8);
            border-color: #2563EB;
            color: #ffffff !important;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.25);
        }

        .form-control::placeholder {
            color: #64748b;
        }

        .btn-primary {
            background-color: #2563EB;
            border-color: #2563EB;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-primary:hover {
            background-color: #1D4ED8;
            border-color: #1D4ED8;
            transform: translateY(-1px);
        }

        .text-muted {
            color: #94a3b8 !important;
        }

        a {
            color: #3B82F6;
            text-decoration: none;
            transition: all 0.2s;
            font-weight: 500;
        }

        a:hover {
            color: #60A5FA;
            text-decoration: underline;
        }

        .invalid-feedback {
            display: block;
            color: #F87171;
            font-size: 0.825rem;
            margin-top: 0.35rem;
        }
    </style>
</head>
<body>

    <div class="auth-card p-4 p-md-5">
        <div class="auth-logo">
            <i class="bi bi-mortarboard-fill me-2"></i>SIEK
        </div>
        
        {{ $slot }}
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
