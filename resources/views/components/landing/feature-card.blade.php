@props(['title', 'description', 'icon', 'color' => 'medical-primary'])

<div class="group p-8 rounded-3xl bg-white border border-gray-100 shadow-premium hover:shadow-2xl transition-all duration-500">
    <div class="w-14 h-14 bg-{{ $color }}/10 text-{{ $color }} rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
        {!! $icon !!}
    </div>
    <h4 class="text-xl font-bold text-medical-dark mb-3">{{ $title }}</h4>
    <p class="text-gray-500 text-sm leading-relaxed">{{ $description }}</p>
</div>
