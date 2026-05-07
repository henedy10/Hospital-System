@props(['specialty', 'count', 'icon'])

<div class="group p-8 rounded-3xl bg-white border border-gray-100 shadow-premium hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
    <div class="w-16 h-16 bg-medical-bg rounded-2xl flex items-center justify-center mb-8 group-hover:bg-medical-primary group-hover:text-white transition-all duration-500">
        {!! $icon !!}
    </div>
    <h4 class="text-xl font-bold text-medical-dark mb-2">{{ $specialty }}</h4>
    <p class="text-sm text-gray-500 mb-6 leading-relaxed">Top-tier care in {{ strtolower($specialty) }} with our team of {{ $count }} dedicated specialists.</p>
    
    <div class="flex items-center justify-between mt-auto">
        <span class="text-xs font-bold text-medical-primary uppercase tracking-widest">{{ $count }} Doctors</span>
        <div class="w-10 h-10 rounded-full bg-medical-bg flex items-center justify-center text-medical-primary group-hover:bg-medical-primary group-hover:text-white transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </div>
    </div>
</div>
