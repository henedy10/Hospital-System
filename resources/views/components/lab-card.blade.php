@props(['lab'])

<div class="card-lift bg-white rounded-2xl border border-slate-100 shadow-card overflow-hidden group">
  <!-- Cover -->
  <div class="h-28 bg-gradient-to-br {{ $lab->cover_gradient }} relative flex items-center justify-center">
    <i class="fa-solid {{ $lab->icon_class }} text-white/20 text-8xl absolute -bottom-4 -right-4"></i>
    <div class="w-16 h-16 rounded-2xl bg-white shadow-lg flex items-center justify-center absolute -bottom-8 left-6">
      <i class="fa-solid {{ $lab->icon_class }} {{ $lab->icon_color }} text-2xl"></i>
    </div>
    @if($lab->is_premium)
      <span class="absolute top-3 right-3 badge bg-blue-500 text-white px-2 py-1 rounded-lg text-xs">
        <i class="fa-solid fa-award"></i> Premium
      </span>
    @elseif($lab->is_verified)
      <span class="absolute top-3 right-3 badge bg-emerald-500 text-white px-2 py-1 rounded-lg text-xs">
        <i class="fa-solid fa-circle-check"></i> Verified
      </span>
    @endif
  </div>
  <div class="pt-12 p-6">
    <h3 class="font-bold text-slate-900 text-lg mb-1">{{ $lab->name }}</h3>
    @if($lab->doctor)
      <p class="text-slate-500 text-xs mb-1 flex items-center gap-1">
        <i class="fa-solid fa-user-doctor text-blue-500"></i>
        Linked: {{ $lab->doctor->user->name }} · {{ $lab->doctor->specialty }}
      </p>
    @endif
    <p class="{{ $lab->icon_color }} text-sm font-medium mb-3">{{ $lab->specialty }}</p>
    <div class="flex items-center gap-2 mb-2">
      <div class="flex gap-0.5">
        @for($i = 1; $i <= 5; $i++)
          @if($i <= round($lab->rating_cache))
            <i class="fa-solid fa-star star-filled text-xs"></i>
          @elseif($i - 0.5 == round($lab->rating_cache * 2) / 2)
             <i class="fa-solid fa-star-half-stroke star-filled text-xs"></i>
          @else
            <i class="fa-regular fa-star star-empty text-xs"></i>
          @endif
        @endfor
      </div>
      <span class="font-bold text-slate-900 text-sm">{{ number_format($lab->rating_cache, 1) }}</span>
      <span class="text-slate-400 text-sm">({{ $lab->review_count_cache }} reviews)</span>
    </div>
    <p class="text-slate-500 text-sm flex items-center gap-1 mb-4">
      <i class="fa-solid fa-location-dot {{ $lab->icon_color }} text-xs"></i> {{ $lab->location }}, {{ $lab->distance_km }} km away
    </p>
    <div class="flex gap-2">
      <button class="flex-1 px-4 py-2.5 {{ str_replace('text', 'bg', $lab->icon_color) }} hover:opacity-90 text-white text-sm font-semibold rounded-xl transition-all hover:scale-105 active:scale-95">
        View Profile
      </button>
      <button class="px-4 py-2.5 border border-slate-200 hover:border-slate-300 text-slate-600 hover:text-slate-800 text-sm font-medium rounded-xl transition-all">
        <i class="fa-regular fa-bookmark"></i>
      </button>
    </div>
  </div>
</div>
