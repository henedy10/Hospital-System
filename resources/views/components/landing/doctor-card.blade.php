@props(['doctor'])

<div class="group bg-white rounded-[2.5rem] p-8 shadow-premium hover:shadow-2xl hover:shadow-medical-primary/10 transition-all duration-500 border border-gray-100 hover:-translate-y-3 flex flex-col h-full">
    <div class="relative mb-8">
        <div class="aspect-[4/5] rounded-[2rem] overflow-hidden bg-gray-50 shadow-inner">
            @if($doctor['image'])
                <img src="{{ asset('storage/' . $doctor['image']) }}" alt="{{ $doctor['name'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
            @else
                <div class="w-full h-full flex items-center justify-center bg-medical-primary/5 text-medical-primary text-6xl font-bold opacity-30">
                    <i class="fas fa-user-md"></i>
                </div>
            @endif
            
            <!-- Hover Overlay -->
            <div class="absolute inset-0 bg-medical-dark/40 backdrop-blur-[2px] opacity-0 group-hover:opacity-100 transition-all duration-500 flex items-center justify-center">
                <div class="bg-white text-medical-dark px-6 py-3 rounded-2xl font-bold transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500 shadow-xl">
                    View Profile
                </div>
            </div>
        </div>
        
        <div class="absolute -bottom-4 right-6 bg-white px-4 py-2 rounded-2xl shadow-xl flex items-center gap-2 border border-gray-50">
            <i class="fas fa-star text-yellow-400 text-sm"></i>
            <span class="text-sm font-bold text-medical-dark">{{ number_format($doctor['rating'], 1) }}</span>
            <span class="text-xs text-gray-400 font-medium">({{ $doctor['reviews_count'] }})</span>
        </div>
    </div>

    <div class="space-y-4 flex-grow">
        <div class="inline-flex items-center gap-2 px-3 py-1 bg-medical-primary/10 text-medical-primary rounded-xl text-xs font-bold uppercase tracking-widest">
            <span class="w-1.5 h-1.5 bg-medical-primary rounded-full"></span>
            {{ $doctor['specialty'] }}
        </div>
        
        <div>
            <h3 class="text-2xl font-bold text-medical-dark group-hover:text-medical-primary transition-colors tracking-tight">Dr. {{ $doctor['name'] }}</h3>
            <p class="text-sm text-gray-500 font-bold mt-1">{{ $doctor['experience'] }} Years Experience</p>
        </div>
        
        <p class="text-gray-400 text-sm line-clamp-3 leading-relaxed font-medium italic">
            "{{ $doctor['bio'] }}"
        </p>
    </div>

    <div class="mt-8 pt-8 border-t border-gray-50 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Available Now</span>
        </div>
        <a href="{{ route('register') }}" class="w-12 h-12 bg-medical-primary text-white rounded-2xl flex items-center justify-center shadow-lg shadow-medical-primary/30 hover:scale-110 transition-transform">
            <i class="fas fa-calendar-check text-lg"></i>
        </a>
    </div>
</div>
