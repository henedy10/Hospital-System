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
    @livewireStyles
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
                    @if(Auth::user() && Auth::user()->isDoctor())
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
                        <li class="{{ request()->routeIs('doctor.tasks*') ? 'active' : '' }}"><a
                                href="{{ route('doctor.tasks.index') }}"><i class="fas fa-clipboard-list"></i>
                                <span>Nurse Tasks</span></a></li>
                        <li class="{{ request()->routeIs('chat') ? 'active' : '' }}"><a
                                href="{{ route('chat') }}"><i class="fas fa-comments"></i> <span>Messages</span></a></li>
                        <li class="{{ request()->routeIs('doctor.settings') ? 'active' : '' }}"><a
                                href="{{ route('doctor.settings') }}"><i class="fas fa-cog"></i> <span>Settings</span></a>
                        </li>
                    @elseif(Auth::user() && Auth::user()->isNurse())
                        <li class="{{ request()->routeIs('nurse.dashboard') ? 'active' : '' }}"><a
                                href="{{ route('nurse.dashboard') }}"><i class="fas fa-th-large"></i>
                                <span>Dashboard</span></a></li>
                        <li class="{{ request()->routeIs('nurse.patients*') ? 'active' : '' }}"><a
                                href="{{ route('nurse.patients') }}"><i class="fas fa-user-injured"></i>
                                <span>Patient Care</span></a></li>
                        <li class="{{ request()->routeIs('nurse.tasks') ? 'active' : '' }}"><a
                                href="{{ route('nurse.tasks') }}"><i class="fas fa-tasks"></i>
                                <span>Nursing Tasks</span></a></li>
                        <li class="{{ request()->routeIs('chat') ? 'active' : '' }}"><a
                                href="{{ route('chat') }}"><i class="fas fa-comments"></i> <span>Messages</span></a></li>
                        <li class="{{ request()->routeIs('nurse.settings') ? 'active' : '' }}"><a
                                href="{{ route('nurse.settings') }}"><i class="fas fa-cog"></i> <span>Settings</span></a>
                        </li>
                    @elseif(Auth::user() && Auth::user()->isPatient())
                        <li class="{{ request()->routeIs('patient.dashboard') ? 'active' : '' }}"><a
                                href="{{ route('patient.dashboard') }}"><i class="fas fa-home"></i>
                                <span>Dashboard</span></a></li>
                        <li class="{{ request()->routeIs('patient.appointments') ? 'active' : '' }}"><a
                                href="{{ route('patient.appointments') }}"><i class="fas fa-calendar-check"></i>
                                <span>My Appointments</span></a></li>
                        <li class="{{ request()->routeIs('patient.history') ? 'active' : '' }}"><a
                                href="{{ route('patient.history') }}"><i class="fas fa-history"></i>
                                <span>Medical History</span></a></li>
                        <li class="{{ request()->routeIs('chat') ? 'active' : '' }}"><a
                                href="{{ route('chat') }}"><i class="fas fa-comments"></i> <span>Messages</span></a></li>
                        <li class="{{ request()->routeIs('patient.profile') ? 'active' : '' }}"><a
                                href="{{ route('patient.profile') }}"><i class="fas fa-user-circle"></i>
                                <span>My Profile</span></a></li>
                    @elseif(Auth::user() && Auth::user()->isAdmin())
                        <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><a
                                href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt"></i>
                                <span>Dashboard</span></a></li>
                        <li class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}"><a
                                href="{{ route('admin.users.index') }}"><i class="fas fa-users-cog"></i>
                                <span>Manage Users</span></a></li>
                        <li class="{{ request()->routeIs('chat') ? 'active' : '' }}"><a
                                href="{{ route('chat') }}"><i class="fas fa-comments"></i> <span>Messages</span></a></li>
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
                <div class="user-profile" style="display: flex; align-items: center; gap: 20px;">
                    <!-- Notification Bell -->
                    <div class="notification-dropdown" style="position: relative;">
                        <button id="notificationBtn" style="background: none; border: none; font-size: 1.2rem; cursor: pointer; color: #4b5563; position: relative; padding: 5px;">
                            <i class="fas fa-bell"></i>
                            <span id="notificationBadge" style="display: none; position: absolute; justify-content: center; align-items: center; top: -2px; right: -2px; background: #e11d48; color: white; font-size: 0.65rem; border-radius: 50%; width: 16px; height: 16px; font-weight: bold;">0</span>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div id="notificationMenu" style="display: none; position: absolute; top: 100%; right: 0; background: white; width: 320px; border-radius: 8px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05); margin-top: 10px; z-index: 50; overflow: hidden; border: 1px solid #e5e7eb;">
                            <div style="padding: 12px 16px; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center; background: #f9fafb;">
                                <h3 style="margin: 0; font-size: 0.9rem; font-weight: 600; color: #111827;">Notifications</h3>
                                <button id="markAllReadBtn" style="background: none; border: none; font-size: 0.8rem; color: #0d9488; cursor: pointer; font-weight: 500; display: none;">Mark all as read</button>
                            </div>
                            <div id="notificationList" style="max-height: 350px; overflow-y: auto;">
                                <div style="padding: 16px; text-align: center; color: #6b7280; font-size: 0.9rem;">No new notifications</div>
                            </div>
                        </div>
                    </div>

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
                @isset($slot)
                    {{ $slot }}
                @endisset
            </section>
        </main>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('notificationBtn');
            const menu = document.getElementById('notificationMenu');
            const badge = document.getElementById('notificationBadge');
            const list = document.getElementById('notificationList');
            const markAllBtn = document.getElementById('markAllReadBtn');

            if (!btn) return;

            // Toggle menu
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
            });

            // Close menu on outside click
            document.addEventListener('click', function(e) {
                if (!btn.contains(e.target) && !menu.contains(e.target)) {
                    menu.style.display = 'none';
                }
            });

            // Fetch notifications
            function fetchNotifications() {
                fetch('{{ route("notifications.index") }}', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => {
                    if(!res.ok) throw new Error("Network response was not ok");
                    return res.json();
                })
                .then(data => {
                    if (data.count > 0) {
                        badge.textContent = data.count > 9 ? '9+' : data.count;
                        badge.style.display = 'flex';
                        markAllBtn.style.display = 'block';
                        
                        list.innerHTML = '';
                        data.notifications.forEach(notif => {
                            const item = document.createElement('div');
                            item.style.cssText = 'padding: 12px 16px; border-bottom: 1px solid #f3f4f6; cursor: pointer; transition: background 0.2s;';
                            item.onmouseover = () => item.style.backgroundColor = '#f9fafb';
                            item.onmouseout = () => item.style.backgroundColor = 'transparent';
                            
                            item.innerHTML = `
                                <div style="font-size: 0.85rem; color: #374151; margin-bottom: 4px; line-height: 1.4;">${notif.data.message}</div>
                                <div style="font-size: 0.75rem; color: #9ca3af;">${new Date(notif.created_at).toLocaleDateString()}</div>
                            `;
                            
                            item.addEventListener('click', () => {
                                fetch(`{{ url('/notifications') }}/${notif.id}/read`, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json'
                                    }
                                }).then(() => {
                                    window.location.href = notif.data.url || '#';
                                });
                            });
                            
                            list.appendChild(item);
                        });
                    } else {
                        badge.style.display = 'none';
                        markAllBtn.style.display = 'none';
                        list.innerHTML = '<div style="padding: 16px; text-align: center; color: #6b7280; font-size: 0.9rem;">No new notifications</div>';
                    }
                })
                .catch(err => console.error("Could not fetch notifications:", err));
            }

            markAllBtn.addEventListener('click', () => {
                fetch('{{ route("notifications.readAll") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                }).then(() => fetchNotifications());
            });

            // Initial fetch and polling
            fetchNotifications();
            setInterval(fetchNotifications, 30000);
        });
    </script>
    @livewireScripts
</body>
</html>