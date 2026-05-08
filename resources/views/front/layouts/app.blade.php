<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sweet Home - Smart Property Management on a Budget</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            color: #333;
            background-color: #f8f9fc;
        }

        .navbar {
            padding: 1rem 1.5rem;
            background: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: #3b82f6 !important;
        }

        .btn-primary {
            background-color: #3b82f6;
            border: none;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
        }

        .btn-primary:hover {
            background-color: #2563eb;
        }

        .btn-outline-primary {
            color: #3b82f6;
            border-color: #3b82f6;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
        }

        .btn-outline-primary:hover {
            background-color: #eff6ff;
            color: #2563eb;
        }

        /* Hero Section */
        .hero {
            padding: 80px 0;
            background: linear-gradient(135deg, #ffffff 0%, #f1f5f9 100%);
        }

        .hero h1 {
            font-size: 2.75rem;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 20px;
        }

        .hero span {
            color: #3b82f6;
        }

        .hero p {
            font-size: 1.1rem;
            color: #64748b;
            margin-bottom: 35px;
        }

        /* Features Section */
        .features {
            padding: 60px 0;
        }

        .feature-card {
            padding: 35px 25px;
            border-radius: 12px;
            background: #ffffff;
            box-shadow: 0 4px 15px rgba(148, 163, 184, 0.08);
            transition: transform 0.25s ease;
            height: 100%;
            border: 1px solid #e2e8f0;
        }

        .feature-card:hover {
            transform: translateY(-5px);
        }

        .icon-box {
            width: 55px;
            height: 55px;
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-bottom: 20px;
        }

        /* Roles Section */
        .roles-section {
            background: #ffffff;
            border-top: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
        }

        .roles-section img {
            border-radius: 12px;
        }

        .footer {
            background: #0f172a;
            color: #ffffff;
            padding: 40px 0;
        }

        @media (max-width: 991px) {
            .hero {
                text-align: center;
                padding: 60px 0;
            }

            .hero .d-flex {
                justify-content: center;
            }
        }
    </style>
    @stack('styles')
</head>

<body>


<!-- Header section Starts here -->
    @include('front.layouts.partials.navbar')
<!-- Header section Ends here -->

    @yield('content')


    @include('front.layouts.partials.footer')


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>