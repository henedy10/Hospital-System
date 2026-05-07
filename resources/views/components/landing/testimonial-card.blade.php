@props(['testimonial'])

<div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-premium">
    <div class="flex gap-1 mb-4">
        @for($i = 0; $i < 5; $i++)
            <svg class="w-5 h-5 {{ $i < $testimonial->rating ? 'text-medical-accent' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
            </svg>
        @endfor
    </div>
    <p class="text-gray-600 italic mb-8 leading-relaxed">"{{ $testimonial->comment }}"</p>
    <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-medical-primary/10 flex items-center justify-center text-medical-primary font-bold">
            {{ strtoupper(substr($testimonial->patient->user->name, 0, 1)) }}
        </div>
        <div>
            <h5 class="font-bold text-medical-dark">{{ $testimonial->patient->user->name }}</h5>
            <p class="text-xs text-gray-400">Patient of Dr. {{ $testimonial->doctor->user->name }}</p>
        </div>
    </div>
</div>
