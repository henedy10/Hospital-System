<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $specialty }} - MediCore</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="antialiased text-medical-dark bg-slate-50/50" x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 transition-all duration-300" 
         :class="scrolled ? 'bg-white/80 backdrop-blur-md shadow-lg py-3' : 'bg-white py-6 shadow-sm'">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 bg-medical-primary rounded-xl flex items-center justify-center text-white font-bold text-2xl shadow-lg shadow-medical-primary/20 group-hover:scale-110 transition-transform">
                        H
                    </div>
                    <span class="text-xl font-bold tracking-tight text-medical-dark uppercase">Medi<span class="text-medical-primary">Core</span></span>
                </a>

                <div class="hidden md:flex items-center gap-8 text-sm font-bold">
                    <a href="{{ route('home') }}" class="text-medical-dark hover:text-medical-primary transition-colors">Home</a>
                    <a href="{{ route('home') }}#Specializations" class="text-medical-dark/70 hover:text-medical-primary transition-colors">Specializations</a>
                    <a href="{{ route('home') }}#Doctors" class="text-medical-dark/70 hover:text-medical-primary transition-colors">Doctors</a>
                </div>

                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ route(Auth::user()->role . '.dashboard') }}" class="px-6 py-2.5 bg-medical-primary text-white rounded-full text-sm font-bold hover:bg-medical-primary/90 transition-all shadow-lg shadow-medical-primary/25">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-bold text-medical-dark hover:text-medical-primary px-4 py-2 transition-colors">Login</a>
                        <a href="{{ route('register') }}" class="px-6 py-2.5 bg-medical-primary text-white rounded-full text-sm font-bold hover:bg-medical-primary/90 transition-all shadow-lg shadow-medical-primary/25">Book Now</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Header Section -->
    <section class="relative pt-48 pb-32 overflow-hidden hero-gradient">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-medical-primary/5 rounded-full blur-[100px] animate-pulse-slow"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-white rounded-[2rem] mb-10 shadow-2xl border border-gray-50 scroll-reveal">
                <i class="fas fa-hand-holding-medical text-4xl text-medical-primary"></i>
            </div>
            <h1 class="text-6xl md:text-8xl font-bold text-medical-dark mb-8 tracking-tight scroll-reveal" style="transition-delay: 100ms">
                {{ $specialty }} <span class="text-gradient">Department</span>
            </h1>
            <p class="text-xl text-gray-400 max-w-3xl mx-auto leading-relaxed font-medium scroll-reveal" style="transition-delay: 200ms">
                Experience world-class {{ strtolower($specialty) }} care with advanced diagnostic and treatment options. Our highly trained specialists are dedicated to your health.
            </p>
        </div>
    </section>

    <!-- Doctors Grid -->
    <section class="py-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row justify-between items-end mb-24 gap-12 scroll-reveal">
                <div class="space-y-6">
                    <h2 class="text-medical-primary font-bold text-sm tracking-[0.3em] uppercase">Department Specialists</h2>
                    <h3 class="text-4xl lg:text-6xl font-bold text-medical-dark tracking-tight leading-tight">Meet Our Expert Team</h3>
                </div>
                <div class="flex items-center gap-5 bg-white p-3 rounded-[1.5rem] shadow-premium">
                    <div class="w-14 h-14 bg-medical-primary/10 rounded-2xl flex items-center justify-center text-medical-primary font-bold text-xl">
                        {{ $doctors->count() }}
                    </div>
                    <div class="pr-8">
                        <div class="text-sm font-bold text-medical-dark">Top Specialists</div>
                        <div class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Ready to help you</div>
                    </div>
                </div>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-10 scroll-reveal" style="transition-delay: 300ms">
                @forelse ($doctors as $doctor)
                    @php
                        $docData = [
                            'id' => $doctor->id,
                            'name' => $doctor->user->name,
                            'image' => $doctor->user->profile_image,
                            'specialty' => $doctor->specialty,
                            'experience' => $doctor->experience_years,
                            'bio' => $doctor->bio,
                            'rating' => $doctor->average_rating ?? 0,
                            'reviews_count' => $doctor->total_reviews,
                        ];
                    @endphp
                    <div class="hover-glow transition-all duration-500 rounded-[2.5rem]">
                        <x-landing.doctor-card :doctor="$docData" />
                    </div>
                @empty
                    <div class="col-span-full text-center py-32 bg-white rounded-[4rem] border border-gray-50 shadow-premium">
                        <div class="w-24 h-24 bg-gray-50 rounded-[2rem] flex items-center justify-center mx-auto mb-8">
                            <i class="fas fa-user-md text-4xl text-gray-300 opacity-50"></i>
                        </div>
                        <h3 class="text-3xl font-bold text-medical-dark mb-4 tracking-tight">No Specialists Found</h3>
                        <p class="text-gray-400 max-w-md mx-auto font-medium">We are currently updating our specialist roster for this department. Please check back later.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Other Departments -->
    @if($otherSpecialties->count() > 0)
    <section class="py-32 bg-white relative overflow-hidden">
        <div class="absolute bottom-0 right-0 w-[400px] h-[400px] bg-medical-secondary/5 rounded-full blur-[100px] opacity-50"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-end mb-24 gap-8 scroll-reveal">
                <div class="space-y-6">
                    <h2 class="text-medical-primary font-bold text-sm tracking-[0.3em] uppercase">Explore More</h2>
                    <h3 class="text-4xl lg:text-6xl font-bold text-medical-dark tracking-tight">Other Departments</h3>
                </div>
                <a href="{{ route('home') }}#Specializations" class="group px-8 py-4 bg-medical-primary text-white rounded-2xl font-bold flex items-center gap-3 hover:translate-y-[-5px] hover:shadow-2xl hover:shadow-medical-primary/40 transition-all duration-300">
                    View All Departments
                    <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 scroll-reveal" style="transition-delay: 200ms">
                @foreach($otherSpecialties as $other)
                    <a href="{{ route('specialty.show', $other->specialty) }}" class="group p-10 rounded-[2.5rem] bg-slate-50 hover:bg-medical-primary transition-all duration-700 hover:-translate-y-3 hover:shadow-2xl hover:shadow-medical-primary/30">
                        <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mb-8 shadow-sm group-hover:scale-110 transition-transform duration-500">
                            <i class="fas fa-stethoscope text-2xl text-medical-primary"></i>
                        </div>
                        <h4 class="text-xl font-bold text-medical-dark group-hover:text-white mb-4 transition-colors tracking-tight">{{ $other->specialty }}</h4>
                        <p class="text-sm text-gray-400 group-hover:text-white/70 transition-colors font-medium leading-relaxed">Specialized care and expert consultations for your health.</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-medical-primary rounded-xl flex items-center justify-center text-white font-bold text-2xl">
                    H
                </div>
                <span class="text-xl font-bold tracking-tight text-medical-dark uppercase">Medi<span class="text-medical-primary">Core</span></span>
            </div>
            <p class="text-gray-400 text-sm font-bold uppercase tracking-widest">&copy; {{ date('Y') }} MediCore. All rights reserved.</p>
        </div>
    </footer>

    <script>
        const observerOptions = { threshold: 0.1 };
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);
        document.querySelectorAll('.scroll-reveal').forEach((el) => observer.observe(el));
    </script>
</body>
</html>
