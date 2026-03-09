<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Hospital System</title>
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>

<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <i class="fas fa-hospital-user logo-icon"></i>
                <span class="logo-text">Hospital Sys</span>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    @if(request()->is('doctor*'))
                        <li class="{{ request()->routeIs('doctor.dashboard') ? 'active' : '' }}"><a
                                href="{{ route('doctor.dashboard') }}"><i class="fas fa-home"></i>
                                <span>Dashboard</span></a></li>
                        <li class="{{ request()->routeIs('doctor.appointments') ? 'active' : '' }}"><a
                                href="{{ route('doctor.appointments') }}"><i class="fas fa-calendar-alt"></i>
                                <span>Appointments</span></a></li>
                        <li class="{{ request()->routeIs('doctor.patients') ? 'active' : '' }}"><a
                                href="{{ route('doctor.patients') }}"><i class="fas fa-user-injured"></i>
                                <span>Patients</span></a></li>
                        <li class="{{ request()->routeIs('doctor.reports*') ? 'active' : '' }}"><a
                                href="{{ route('doctor.reports') }}"><i class="fas fa-file-medical"></i>
                                <span>Reports</span></a></li>
                        <li class="{{ request()->routeIs('doctor.settings') ? 'active' : '' }}"><a
                                href="{{ route('doctor.settings') }}"><i class="fas fa-cog"></i> <span>Settings</span></a>
                        </li>
                    @elseif(request()->is('nurse*'))
                        <li class="{{ request()->routeIs('nurse.dashboard') ? 'active' : '' }}"><a
                                href="{{ route('nurse.dashboard') }}"><i class="fas fa-th-large"></i>
                                <span>Dashboard</span></a></li>
                        <li class="{{ request()->routeIs('nurse.patients*') ? 'active' : '' }}"><a
                                href="{{ route('nurse.patients') }}"><i class="fas fa-user-injured"></i>
                                <span>Patient Care</span></a></li>
                        <li class="{{ request()->routeIs('nurse.tasks') ? 'active' : '' }}"><a
                                href="{{ route('nurse.tasks') }}"><i class="fas fa-tasks"></i>
                                <span>Nursing Tasks</span></a></li>
                        <li class="{{ request()->routeIs('nurse.settings') ? 'active' : '' }}"><a
                                href="{{ route('nurse.settings') }}"><i class="fas fa-cog"></i> <span>Settings</span></a>
                        </li>
                    @elseif(request()->is('patient*'))
                        <li class="{{ request()->routeIs('patient.dashboard') ? 'active' : '' }}"><a
                                href="{{ route('patient.dashboard') }}"><i class="fas fa-home"></i>
                                <span>Dashboard</span></a></li>
                        <li class="{{ request()->routeIs('patient.appointments') ? 'active' : '' }}"><a
                                href="{{ route('patient.appointments') }}"><i class="fas fa-calendar-check"></i>
                                <span>My Appointments</span></a></li>
                        <li class="{{ request()->routeIs('patient.history') ? 'active' : '' }}"><a
                                href="{{ route('patient.history') }}"><i class="fas fa-history"></i>
                                <span>Medical History</span></a></li>
                        <li class="{{ request()->routeIs('patient.profile') ? 'active' : '' }}"><a
                                href="{{ route('patient.profile') }}"><i class="fas fa-user-circle"></i>
                                <span>My Profile</span></a></li>
                    @elseif(request()->is('admin*'))
                        <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><a
                                href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt"></i>
                                <span>Dashboard</span></a></li>
                        <li class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}"><a
                                href="{{ route('admin.users.index') }}"><i class="fas fa-users-cog"></i>
                                <span>Manage Users</span></a></li>
                        <li class="{{ request()->routeIs('admin.appointments') ? 'active' : '' }}"><a
                                href="{{ route('admin.appointments') }}"><i class="fas fa-calendar-alt"></i>
                                <span>Appointments</span></a></li>
                    @endif
                </ul>
            </nav>
            <div class="sidebar-footer">
                <form action="{{ route('logout') }}" method="POST" id="logout-form" style="display: none;">
                    @csrf
                </form>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="top-bar">
                <div class="breadcrumb">
                    <span class="muted">Home</span> / Dashboard
                </div>
                <div class="user-profile">
                    <div class="user-info">
                        <span class="user-name">{{ Auth::user()->name }}</span>
                        <span class="user-role">{{ ucfirst(Auth::user()->role) }}</span>
                    </div>
                    <img src="{{ Auth::user()->profile_image ? asset('storage/' . Auth::user()->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=0D9488&color=fff' }}"
                        alt="Avatar" class="avatar">
                </div>
            </header>

            <section class="content-wrapper">
                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <span>{{ session('success') }}</span>
                        <button type="button" class="close-alert" onclick="this.parentElement.remove()">&times;</button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ session('error') }}</span>
                        <button type="button" class="close-alert" onclick="this.parentElement.remove()">&times;</button>
                    </div>
                @endif

                @yield('content')
            </section>
        </main>
    </div>
</body>

</html>