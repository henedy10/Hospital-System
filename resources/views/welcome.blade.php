<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modern Hospital - Your Health, Our Priority</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased text-medical-dark">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 glass-header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center gap-2">
                    <div
                        class="w-10 h-10 bg-medical-primary rounded-xl flex items-center justify-center text-white font-bold text-2xl shadow-lg shadow-medical-primary/20">
                        H
                    </div>
                    <span class="text-xl font-bold tracking-tight text-medical-dark uppercase">Medi<span
                            class="text-medical-primary">Core</span></span>
                </div>

                <div class="hidden md:flex items-center gap-8 text-sm font-medium">
                    <a href="#" class="text-medical-dark/70 hover:text-medical-primary transition-colors">Home</a>
                    <a href="#Services" class="text-medical-dark/70 hover:text-medical-primary transition-colors">Services</a>
                    <a href="#" class="text-medical-dark/70 hover:text-medical-primary transition-colors">Doctors</a>
                    <a href="#" class="text-medical-dark/70 hover:text-medical-primary transition-colors">Contact</a>
                </div>

                <div class="flex items-center gap-4">
                    @if (Route::has('login'))
                        @auth
                            @php
                                $dashboardRoute = 'login';
                                if (Auth::user()->role === 'doctor')
                                    $dashboardRoute = 'doctor.dashboard';
                                elseif (Auth::user()->role === 'nurse')
                                    $dashboardRoute = 'nurse.dashboard';
                                elseif (Auth::user()->role === 'patient')
                                    $dashboardRoute = 'patient.dashboard';
                            @endphp
                            <a href="{{ route($dashboardRoute) }}"
                                class="px-6 py-2.5 bg-medical-primary text-white rounded-full text-sm font-semibold hover:bg-medical-primary/90 transition-all shadow-lg shadow-medical-primary/25">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}"
                                class="text-sm font-semibold text-medical-primary hover:text-medical-primary/80 px-4 py-2 transition-colors">Log
                                in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="px-6 py-2.5 bg-medical-primary text-white rounded-full text-sm font-semibold hover:bg-medical-primary/90 transition-all shadow-lg shadow-medical-primary/25">Book
                                    Appointment</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden hero-gradient">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <div
                        class="inline-flex items-center gap-2 px-3 py-1 bg-medical-secondary/10 text-medical-secondary rounded-full text-xs font-bold tracking-wider uppercase mb-6">
                        <span class="w-2 h-2 bg-medical-secondary rounded-full animate-pulse"></span>
                        Trusted Healthcare Excellence
                    </div>
                    <h1 class="text-5xl lg:text-7xl font-bold text-medical-dark leading-tight mb-8">
                        Advanced Care for Your <span class="text-medical-primary italic">Healthy</span> Future
                    </h1>
                    <p class="text-lg text-medical-dark/60 mb-10 max-w-lg leading-relaxed">
                        Experience world-class medical services with our team of professional specialists. We provide
                        the highest standard of personalized healthcare.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('register') }}"
                            class="px-8 py-4 bg-medical-primary text-white rounded-full font-bold hover:translate-y-[-2px] transition-all shadow-xl shadow-medical-primary/30">Get
                            Started Today</a>
                        <a href="#Services"
                            class="px-8 py-4 bg-white border border-medical-dark/10 rounded-full font-bold hover:bg-gray-50 transition-all">View
                            Our Specialities</a>
                    </div>

                    <div class="mt-12 flex items-center gap-6">
                        <div class="flex -space-x-4">
                            @foreach($doctors as $doctor)
                                @if($doctor->profile_image)
                                    <img src="{{ asset('storage/' . $doctor->profile_image) }}" alt="{{ $doctor->name }}" class="w-12 h-12 rounded-full border-4 border-white object-cover bg-gray-200">
                                @else
                                    <div class="w-12 h-12 rounded-full border-4 border-white bg-medical-primary text-white flex items-center justify-center font-bold text-lg">
                                        {{ strtoupper(substr($doctor->name, 0, 1)) }}
                                    </div>
                                @endif
                            @endforeach
                            @if(count($doctors) < 3)
                                @for($i = 0; $i < (3 - count($doctors)); $i++)
                                    <div class="w-12 h-12 rounded-full border-4 border-white bg-gray-200"></div>
                                @endfor
                            @endif
                        </div>
                        <div class="text-sm">
                            <div class="font-bold">{{ $totalDoctors }}+ Experts</div>
                            <div class="text-medical-dark/50">Are here to help you</div>
                        </div>
                    </div>
                </div>

                <div class="relative">
                    <div class="absolute -top-20 -right-20 w-96 h-96 bg-medical-primary/5 rounded-full blur-3xl"></div>
                    <div class="absolute -bottom-20 -left-20 w-96 h-96 bg-medical-secondary/5 rounded-full blur-3xl">
                    </div>
                    <div class="relative animate-float">
                        <div
                            class="rounded-3xl overflow-hidden shadow-2xl shadow-medical-dark/10 border-8 border-white">
                            <img src="https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?q=80&w=1453&auto=format&fit=crop"
                                alt="Medical Team" class="w-full h-auto">
                        </div>

                        <!-- Floating Card -->
                        <div
                            class="absolute -bottom-8 -left-8 bg-white p-6 rounded-2xl shadow-2xl glass-header max-w-[200px] border border-white/50">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 bg-medical-accent/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-medical-accent" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="text-xs font-bold text-medical-dark/50">Happy Patients</div>
                            </div>
                            <div class="text-2xl font-bold text-medical-dark leading-none">{{ $totalPatients }}+</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="py-24 bg-white relative" id="Services">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-medical-secondary font-bold text-sm tracking-widest uppercase mb-4">Our Specialities
                </h2>
                <h3 class="text-4xl lg:text-5xl font-bold text-medical-dark">Medical Services for You</h3>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                @forelse ($specialties as $specialty)
                    <div
                        class="group p-8 rounded-3xl bg-medical-bg hover:bg-medical-primary transition-all duration-500 hover:translate-y-[-8px] hover:shadow-2xl hover:shadow-medical-primary/40">
                        <div
                            class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mb-8 shadow-sm group-hover:scale-110 transition-transform">
                            <!-- Use generic medical icon across all specialities -->
                            <svg class="w-8 h-8 text-medical-primary group-hover:text-medical-secondary" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                </path>
                            </svg>
                        </div>
                        <h4 class="text-xl font-bold mb-4 group-hover:text-white transition-colors">
                            {{ $specialty->specialty }}</h4>
                        <p class="text-medical-dark/50 group-hover:text-white/70 transition-colors mb-6">World-class
                            {{ strtolower($specialty->specialty) }} care with advanced diagnostic and treatment options.</p>
                        <a href="#"
                            class="text-medical-primary font-bold group-hover:text-white inline-flex items-center gap-2">
                            Learn More
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12 text-medical-dark/50">
                        More specialities coming soon.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-medical-dark py-20 text-white overflow-hidden relative">
        <div class="absolute top-0 right-0 w-96 h-96 bg-medical-primary/10 rounded-full blur-[120px]"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-4 gap-12 mb-20">
                <div class="lg:col-span-2">
                    <div class="flex items-center gap-2 mb-8">
                        <div
                            class="w-10 h-10 bg-medical-primary rounded-xl flex items-center justify-center text-white font-bold text-2xl">
                            H
                        </div>
                        <span class="text-xl font-bold tracking-tight uppercase">Medi<span
                                class="text-medical-primary">Core</span></span>
                    </div>
                    <p class="text-white/40 max-w-sm mb-8 leading-relaxed">
                        Leading the way in medical excellence. We provide comprehensive healthcare services through
                        innovation and compassion.
                    </p>
                    <div class="flex gap-4">
                        <a href="#"
                            class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-medical-primary transition-colors text-white/40 hover:text-white">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24 text-current">
                                <path
                                    d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
                            </svg>
                        </a>
                        <a href="#"
                            class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-medical-primary transition-colors text-white/40 hover:text-white">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24 text-current">
                                <path
                                    d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                            </svg>
                        </a>
                    </div>
                </div>

                <div>
                    <h5 class="text-lg font-bold mb-8">Quick Links</h5>
                    <ul class="space-y-4">
                        <li><a href="#" class="text-white/40 hover:text-medical-primary transition-colors">Find a
                                Doctor</a></li>
                        <li><a href="#" class="text-white/40 hover:text-medical-primary transition-colors">Medical
                                Services</a></li>
                        <li><a href="#" class="text-white/40 hover:text-medical-primary transition-colors">Our
                                Departments</a></li>
                        <li><a href="#" class="text-white/40 hover:text-medical-primary transition-colors">Contact
                                Us</a></li>
                    </ul>
                </div>

                <div>
                    <h5 class="text-lg font-bold mb-8">Newsletter</h5>
                    <p class="text-white/40 mb-6 font-medium">Get the latest health tips and news.</p>
                    <div class="relative">
                        <input type="email" placeholder="Email Address"
                            class="w-full bg-white/5 border border-white/10 rounded-full px-6 py-4 focus:outline-none focus:border-medical-primary transition-colors">
                        <button
                            class="absolute right-2 top-2 bottom-2 bg-medical-primary px-4 rounded-full hover:bg-medical-primary/90 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div
                class="pt-8 border-t border-white/10 flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-white/20">
                <p>&copy; {{ date('Y') }} MediCore. All rights reserved.</p>
                <div class="flex gap-8">
                    <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>
