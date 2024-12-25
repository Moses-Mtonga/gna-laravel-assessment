<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @vite(['resources/css/dashboard.css'])
</head>

<body>

    <div class="sidebar">
        <div class="logo">
            <img src="{{ asset('images/gna-logo.png') }}" alt="Company Logo" style="width: 100px; height: auto;">
        </div>
        {{-- <p class="company-name">Good Nature Agro</p> --}}
        <nav class="nav flex-column">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <span class="material-icons">dashboard</span> Dashboard
            </a>

            @php
                $activeModules = app('getActiveModules')();
            @endphp

            @if (isset($activeModules['FarmSupport']))
                <a class="nav-link {{ request()->routeIs('farmsupport.index') ? 'active' : '' }}"
                    href="{{ route('farmsupport.index') }}">
                    <span class="material-icons">handshake</span> Farmer Support
                </a>
            @endif
            @if (isset($activeModules['LoanManagement']))
                <a class="nav-link {{ request()->routeIs('loanmanagement.index') ? 'active' : '' }}"
                    href="{{ route('loanmanagement.index') }}">
                    <span class="material-icons">account_balance</span> Loans
                </a>
            @endif

            <a class="nav-link {{ request()->routeIs('modules.index') ? 'active' : '' }}"
                href="{{ route('modules.index') }}">
                <span class="material-icons">settings</span> Settings
            </a>
        </nav>
    </div>
    <div class="main-content">
        <div class="top-nav">
            <div>
                <h3 class="text-muted">
                    @php
                        $routeName = request()->route()->getName();
                        $tabNames = [
                            'dashboard' => 'Dashboard',
                            'farmers.index' => 'Farmers',
                            'loans.index' => 'Loans',
                            'modules.index' => 'Settings',
                            'farmsupport.index' => 'Farmer Support',
                        ];
                    @endphp
                    {{ $tabNames[$routeName] ?? 'Dashboard' }}
                </h3>
            </div>
            <div>
                <a href="#" class="btn btn-outline-secondary btn-sm">Profile</a>
                <a href="#" class="btn btn-outline-secondary btn-sm">Logout</a>
            </div>
        </div>
        <div class="content">
            <div class="container mt-4">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>
            @yield('content')
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- lets remove the alart --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-dismiss alerts after 5 seconds
            setTimeout(function() {
                let alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    let bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
</body>

</html>
