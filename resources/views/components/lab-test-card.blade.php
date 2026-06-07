@props(['test'])

<div class="card-lift bg-white rounded-2xl border border-slate-100 p-6 shadow-card group" data-cat="{{ $test->category }}" data-price="{{ $test->price }}" data-rating="{{ $test->rating_cache }}" data-name="{{ $test->name }}">
  <div class="flex items-start justify-between mb-4">
    <div class="w-12 h-12 rounded-2xl {{ $test->icon_bg_color }} flex items-center justify-center group-hover:opacity-80 transition-opacity">
      <i class="fa-solid {{ $test->icon_class }} {{ $test->icon_color }} text-xl"></i>
    </div>
    <span class="badge {{ $test->badge_bg }} {{ $test->badge_text }} px-2.5 py-1 rounded-lg border {{ $test->badge_border }}">{{ $test->badge_label }}</span>
  </div>
  <h3 class="font-bold text-slate-900 text-lg mb-2">{{ $test->name }}</h3>
  <p class="text-slate-500 text-sm leading-relaxed mb-4">{{ $test->description }}</p>
  @if($test->preparation_notes)
    <div class="text-amber-600 text-xs font-semibold bg-amber-50 border border-amber-100 rounded-xl px-3 py-2 mb-4 flex items-start gap-2">
      <i class="fa-solid fa-circle-info mt-0.5 flex-shrink-0"></i>
      <span>{{ $test->preparation_notes }}</span>
    </div>
  @endif
  <div class="flex items-center gap-4 text-sm text-slate-500 mb-5">
    <span class="flex items-center gap-1"><i class="fa-regular fa-clock text-blue-400"></i> {{ $test->duration_label }}</span>
    <span class="flex items-center gap-1"><i class="fa-solid fa-star star-filled text-xs"></i> {{ $test->rating_cache }} ({{ $test->review_count_cache }} reviews)</span>
  </div>
  <div class="flex items-center justify-between">
    <p class="text-2xl font-extrabold text-slate-900">${{ $test->price }} 
      @if($test->hasDiscount())
        <span class="text-slate-400 text-sm font-normal line-through">${{ $test->original_price }}</span>
      @endif
    </p>
    <button onclick="openBookingModal({{ $test->id }}, '{{ addslashes($test->name) }}', '{{ addslashes($test->preparation_notes) }}')" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-semibold rounded-xl transition-all hover:scale-105 active:scale-95 shadow-sm hover:shadow-blue-500/30">
      Book Now
    </button>
  </div>
</div>
