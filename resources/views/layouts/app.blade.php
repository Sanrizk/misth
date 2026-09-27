<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titleTab', 'Misth') - Misth Management</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --primary-color: #2e7d32;
            --primary-light: #60ad5e;
            --primary-dark: #005005;
            --sidebar-bg: #1b1f23;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: var(--sidebar-bg);
            transition: all 0.3s;
            z-index: 1040;
        }
        .sidebar .nav-link {
            color: #c2c7d0;
            padding: 10px 20px;
            border-radius: 4px;
            margin: 4px 10px;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background-color: var(--primary-color);
            color: white;
        }
        .sidebar .nav-link i {
            margin-right: 10px;
        }
        .main-content {
            margin-left: 250px;
            padding-top: 70px; /* space for navbar */
            min-height: 100vh;
            transition: all 0.3s;
        }
        .top-navbar {
            margin-left: 250px;
            height: 60px;
            background: white;
            border-bottom: 1px solid #dee2e6;
            z-index: 1030;
            transition: all 0.3s;
        }
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-content, .top-navbar {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>

    @include('layouts.partials.sidebar')
    @include('layouts.partials.navbar')

    <div class="main-content pt-8 px-4">
        @include('layouts.partials.flash')
        @yield('content')
    </div>


    <script>
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });
    </script>
    @yield('scripts')
</body>
</html>
