<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $specialty }} - Modern Hospital</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased text-medical-dark bg-gray-50">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 glass-header bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-medical-primary rounded-xl flex items-center justify-center text-white font-bold text-2xl shadow-lg shadow-medical-primary/20">
                        H
                    </div>
                    <span class="text-xl font-bold tracking-tight text-medical-dark uppercase">Medi<span class="text-medical-primary">Core</span></span>
                </a>

                <div class="hidden md:flex items-center gap-8 text-sm font-medium">
                    <a href="{{ route('home') }}" class="text-medical-dark/70 hover:text-medical-primary transition-colors">Home</a>
                    <a href="{{ route('home') }}#Services" class="text-medical-dark/70 hover:text-medical-primary transition-colors">Services</a>
                    <a href="#" class="text-medical-dark/70 hover:text-medical-primary transition-colors">Contact</a>
                </div>

                <div class="flex items-center gap-4">
                    @if (Route::has('login'))
                        @auth
                            @php
                                $dashboardRoute = 'login';
                                if (Auth::user()->role === 'doctor') $dashboardRoute = 'doctor.dashboard';
                                elseif (Auth::user()->role === 'nurse') $dashboardRoute = 'nurse.dashboard';
                                elseif (Auth::user()->role === 'patient') $dashboardRoute = 'patient.dashboard';
                            @endphp
                            <a href="{{ route($dashboardRoute) }}" class="px-6 py-2.5 bg-medical-primary text-white rounded-full text-sm font-semibold hover:bg-medical-primary/90 transition-all shadow-lg shadow-medical-primary/25">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-semibold text-medical-primary hover:text-medical-primary/80 px-4 py-2 transition-colors">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-6 py-2.5 bg-medical-primary text-white rounded-full text-sm font-semibold hover:bg-medical-primary/90 transition-all shadow-lg shadow-medical-primary/25">Book Appointment</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Specialty Header -->
    <section class="relative pt-40 pb-24 overflow-hidden bg-slate-50 border-b border-gray-200">
        <!-- Decorative blobs -->
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-medical-primary/5 rounded-full blur-[100px] translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-medical-secondary/5 rounded-full blur-[80px] -translate-x-1/2 translate-y-1/2"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-white rounded-3xl mb-8 shadow-xl shadow-medical-primary/10 border border-medical-primary/10 relative">
                <div class="absolute inset-0 bg-medical-primary/10 animate-ping rounded-3xl opacity-20"></div>
                <svg class="w-12 h-12 text-medical-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
            </div>
            <h1 class="text-5xl md:text-6xl font-extrabold text-medical-dark mb-6 capitalize tracking-tight">{{ $specialty }} <span class="text-medical-primary font-light">Department</span></h1>
            <p class="text-xl text-medical-dark/60 max-w-3xl mx-auto leading-relaxed">
                Experience world-class {{ strtolower($specialty) }} care with advanced diagnostic and treatment options. Our highly trained specialists are dedicated to providing you with the highest standard of personalized healthcare.
            </p>
        </div>
    </section>

    <!-- Doctors Grid -->
    <section class="py-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center mb-20">
            <h2 class="text-medical-secondary font-bold text-sm tracking-[0.2em] uppercase mb-4 flex items-center justify-center gap-4">
                <span class="w-8 h-[2px] bg-medical-secondary/30"></span>
                Our Specialists
                <span class="w-8 h-[2px] bg-medical-secondary/30"></span>
            </h2>
            <h3 class="text-4xl lg:text-5xl font-bold text-medical-dark">Meet Our {{ ucfirst($specialty) }} Doctors</h3>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-10">
            @forelse ($doctors as $doctor)
                <div class="bg-white rounded-[2rem] p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:-translate-y-3 transition-all duration-500 border border-slate-100 group flex flex-col items-center text-center relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-32 bg-gradient-to-br from-medical-primary/10 to-medical-secondary/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-0"></div>
                    
                    <div class="relative z-10 w-24 h-24 rounded-full overflow-hidden mb-6 border-[4px] border-white shadow-lg group-hover:border-medical-primary/20 transition-colors duration-500">
                        @if($doctor->user->profile_image)
                            <img src="{{ asset('storage/' . $doctor->user->profile_image) }}" alt="{{ $doctor->user->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-medical-primary to-teal-700 text-white flex items-center justify-center text-4xl font-bold">
                                {{ strtoupper(substr($doctor->user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    
                    <h4 class="relative z-10 text-2xl font-bold text-medical-dark mb-1">{{ $doctor->user->name }}</h4>
                    <span class="relative z-10 inline-block px-4 py-1.5 bg-medical-secondary/10 text-medical-secondary font-semibold text-sm rounded-full mb-4 capitalize tracking-wide">{{ $doctor->specialty }} Specialist</span>
                    
                    <!-- Rating -->
                    @if($doctor->average_rating)
                        <div class="flex items-center justify-center gap-1.5 mb-4 z-10 relative">
                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <span class="font-bold text-medical-dark">{{ $doctor->average_rating }}</span>
                            <span class="text-slate-400 text-sm">({{ $doctor->total_reviews }} reviews)</span>
                        </div>
                    @endif

                    <!-- Bio -->
                    @if($doctor->bio)
                        <p class="text-sm text-slate-500 mb-6 line-clamp-3 relative z-10 leading-relaxed">
                            {{ $doctor->bio }}
                        </p>
                    @endif

                    <!-- Latest Feedback -->
                    @if($doctor->feedback->count() > 0)
                        @php
                            $latestFeedback = $doctor->feedback->first();
                        @endphp
                        <div class="w-full bg-slate-50/80 p-4 rounded-2xl mb-6 text-left relative z-10 border border-slate-100/50 hover:bg-slate-50 transition-colors">
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-full bg-medical-primary/10 flex items-center justify-center text-medical-primary font-bold text-xs shrink-0">
                                    {{ strtoupper(substr($latestFeedback->patient->user->name ?? 'A', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="flex items-center gap-0.5 mb-1">
                                        @foreach($latestFeedback->stars as $star)
                                            <svg class="w-3 h-3 {{ $star ? 'text-yellow-400' : 'text-slate-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                        @endforeach
                                    </div>
                                    <p class="text-xs text-slate-600 italic line-clamp-2">"{{ $latestFeedback->comment }}"</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Spacer to keep buttons aligned if no feedback -->
                        <div class="flex-grow"></div>
                    @endif
                    
                    <div class="w-full mt-auto pt-6 border-t border-slate-100 relative z-10">
                        <a href="{{ route('register') }}" class="w-full flex items-center justify-center gap-2 px-6 py-4 bg-slate-50 text-medical-dark font-bold rounded-2xl group-hover:bg-medical-primary group-hover:text-white transition-all duration-300 shadow-sm group-hover:shadow-lg group-hover:shadow-medical-primary/30">
                            <span>Book Appointment</span>
                            <svg class="w-5 h-5 opacity-0 -ml-5 group-hover:opacity-100 group-hover:ml-0 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-20 bg-slate-50 rounded-[3rem] border-2 border-dashed border-slate-200">
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm">
                        <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-700 mb-3">No Specialists Found</h3>
                    <p class="text-slate-500 max-w-md mx-auto">We are currently updating our specialist roster for this department. Please check back later.</p>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Browse Other Departments -->
    @if($otherSpecialties->count() > 0)
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6">
                <div>
                    <h2 class="text-medical-secondary font-bold text-sm tracking-widest uppercase mb-4">Explore More</h2>
                    <h3 class="text-3xl lg:text-4xl font-bold text-medical-dark">Other Departments</h3>
                </div>
                <a href="{{ route('home') }}#Services" class="px-6 py-3 bg-slate-50 text-medical-primary font-bold rounded-full hover:bg-medical-primary hover:text-white transition-all">View All Services</a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($otherSpecialties as $other)
                <a href="{{ route('specialty.show', $other->specialty) }}" class="group p-6 rounded-3xl bg-slate-50 hover:bg-medical-primary transition-all duration-500 hover:-translate-y-2">
                    <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center mb-6 shadow-sm group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-medical-primary group-hover:text-medical-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <h4 class="text-lg font-bold mb-2 group-hover:text-white transition-colors capitalize">{{ $other->specialty }}</h4>
                    <p class="text-sm text-slate-500 group-hover:text-white/70 transition-colors">Specialized care and expert consultations.</p>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Footer -->
    <footer class="bg-medical-dark py-20 text-white overflow-hidden relative mt-auto">
        <!-- Floating gradient background -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-medical-primary/10 rounded-full blur-[120px]"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 bg-medical-primary rounded-xl flex items-center justify-center text-white font-bold text-2xl">
                    H
                </div>
                <span class="text-xl font-bold tracking-tight uppercase">Medi<span class="text-medical-primary">Core</span></span>
            </div>
            <p class="text-white/40 text-sm font-medium">&copy; {{ date('Y') }} MediCore. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
