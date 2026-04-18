@extends('layouts.dashboard')

@section('title', 'My Reviews')

@section('content')
<div class="reviews-wrapper">

    {{-- ── Page Header ─────────────────────────────────────────────────────── --}}
    <div class="page-header animate-fade-down">
        <div class="header-content">
            <h1 class="premium-title"><i class="fas fa-star"></i> My Reviews</h1>
            <p class="text-muted">See what your patients think about your care.</p>
        </div>
    </div>

    {{-- ── Summary Cards ───────────────────────────────────────────────────── --}}
    <div class="summary-grid animate-fade-in">

        {{-- Average Rating Card --}}
        <div class="summary-card glass-card">
            <div class="summary-icon rating-icon">
                <i class="fas fa-star"></i>
            </div>
            <div class="summary-body">
                <span class="summary-label">Average Rating</span>
                <div class="big-rating">
                    <span class="rating-number">{{ $avgRating ?? '—' }}</span>
                    <span class="rating-max">/5</span>
                </div>
                <div class="stars-row">
                    @for ($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star {{ $avgRating && $i <= round($avgRating) ? 'star-filled' : 'star-empty' }}"></i>
                    @endfor
                </div>
            </div>
        </div>

        {{-- Total Reviews Card --}}
        <div class="summary-card glass-card">
            <div class="summary-icon count-icon">
                <i class="fas fa-comments"></i>
            </div>
            <div class="summary-body">
                <span class="summary-label">Total Reviews</span>
                <div class="big-rating">
                    <span class="rating-number">{{ $totalReviews }}</span>
                </div>
                <p class="summary-sub">Verified patient reviews</p>
            </div>
        </div>

        {{-- Distribution Card --}}
        <div class="summary-card glass-card distribution-card">
            <span class="summary-label" style="margin-bottom:.75rem">Rating Breakdown</span>
            @for ($star = 5; $star >= 1; $star--)
                @php
                    $count = $distribution[$star] ?? 0;
                    $pct   = $totalReviews > 0 ? round(($count / $totalReviews) * 100) : 0;
                @endphp
                <div class="dist-row">
                    <span class="dist-label">{{ $star }} <i class="fas fa-star" style="color:#f59e0b;font-size:.75rem"></i></span>
                    <div class="dist-bar-track">
                        <div class="dist-bar-fill {{ $star <= 2 ? 'fill-low' : 'fill-high' }}"
                             style="width:{{ $pct }}%"></div>
                    </div>
                    <span class="dist-count">{{ $count }}</span>
                </div>
            @endfor
        </div>
    </div>

    {{-- ── Sort Controls ───────────────────────────────────────────────────── --}}
    @if($totalReviews > 0)
    <div class="sort-bar animate-fade-in">
        <span class="sort-label"><i class="fas fa-sliders-h"></i> Sort by:</span>
        <div class="sort-pills">
            <a href="{{ route('doctor.reviews.index', ['sort' => 'latest']) }}"
               class="sort-pill {{ $sort === 'latest' ? 'active' : '' }}">
                <i class="fas fa-clock"></i> Latest
            </a>
            <a href="{{ route('doctor.reviews.index', ['sort' => 'highest']) }}"
               class="sort-pill {{ $sort === 'highest' ? 'active' : '' }}">
                <i class="fas fa-arrow-up"></i> Highest
            </a>
            <a href="{{ route('doctor.reviews.index', ['sort' => 'lowest']) }}"
               class="sort-pill {{ $sort === 'lowest' ? 'active' : '' }}">
                <i class="fas fa-arrow-down"></i> Lowest
            </a>
        </div>
    </div>

    {{-- ── Reviews List ─────────────────────────────────────────────────────── --}}
    <div class="reviews-list">
        @foreach($reviews as $index => $review)
            <div class="review-card glass-card animate-stagger {{ $review->isLowRating() ? 'low-rating-card' : '' }}"
                 style="--order:{{ $index }}">

                {{-- Low rating badge --}}
                @if($review->isLowRating())
                    <div class="low-rating-badge">
                        <i class="fas fa-exclamation-triangle"></i> Low Rating
                    </div>
                @endif

                {{-- Patient Info --}}
                <div class="review-header">
                    <div class="patient-info">
                        <img src="{{ $review->patient->user->profile_image
                                ? asset('storage/' . $review->patient->user->profile_image)
                                : 'https://ui-avatars.com/api/?name=' . urlencode($review->patient->user->name) . '&background=0D9488&color=fff&size=80' }}"
                             alt="Patient" class="patient-avatar">
                        <div>
                            <div class="patient-name">{{ $review->patient->user->name }}</div>
                            <div class="verified-badge">
                                <i class="fas fa-check-circle"></i> Verified Patient
                            </div>
                        </div>
                    </div>
                    <div class="review-meta">
                        <div class="review-stars">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= $review->rating ? 'star-filled' : 'star-empty' }}"></i>
                            @endfor
                            <span class="rating-text">{{ $review->rating }}/5</span>
                        </div>
                        <div class="review-date">
                            <i class="fas fa-calendar-alt"></i>
                            {{ $review->created_at->format('M d, Y') }}
                        </div>
                    </div>
                </div>

                {{-- Comment --}}
                @if($review->comment)
                    <div class="review-comment">
                        <i class="fas fa-quote-left quote-icon"></i>
                        <p>{{ $review->comment }}</p>
                    </div>
                @else
                    <div class="review-comment no-comment">
                        <i class="fas fa-minus-circle"></i> No written comment.
                    </div>
                @endif

                {{-- Doctor Reply --}}
                <div class="reply-section">
                    @if($review->doctor_reply)
                        <div class="existing-reply">
                            <div class="reply-header">
                                <i class="fas fa-reply"></i>
                                <strong>Your Reply</strong>
                                <span class="reply-date">{{ $review->updated_at->format('M d, Y') }}</span>
                            </div>
                            <p class="reply-text">{{ $review->doctor_reply }}</p>
                            <button class="btn-edit-reply" onclick="toggleReplyForm({{ $review->id }})">
                                <i class="fas fa-pen"></i> Edit Reply
                            </button>
                        </div>
                    @endif

                    <div id="reply-form-{{ $review->id }}"
                         class="reply-form {{ $review->doctor_reply ? 'hidden' : '' }}">
                        <form action="{{ route('doctor.reviews.reply', $review) }}" method="POST">
                            @csrf
                            <div class="reply-input-wrap">
                                <textarea name="doctor_reply" rows="3"
                                    placeholder="Write a professional reply to this review…"
                                    class="reply-textarea">{{ $review->doctor_reply }}</textarea>
                                <div class="reply-actions">
                                    @if($review->doctor_reply)
                                        <button type="button" class="btn-cancel-reply"
                                                onclick="toggleReplyForm({{ $review->id }})">Cancel</button>
                                    @endif
                                    <button type="submit" class="btn-post-reply">
                                        <i class="fas fa-paper-plane"></i>
                                        {{ $review->doctor_reply ? 'Update Reply' : 'Post Reply' }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        @endforeach
    </div>

    {{-- ── Pagination ───────────────────────────────────────────────────────── --}}
    <div class="pagination-wrap">
        {{ $reviews->links() }}
    </div>

    @else
        <div class="empty-state glass-card animate-fade-in">
            <div class="empty-icon"><i class="fas fa-star-half-alt"></i></div>
            <h2>No Reviews Yet</h2>
            <p>Reviews will appear here once patients complete their appointments and leave feedback.</p>
        </div>
    @endif
</div>

{{-- ──────────────────────────────────────────── Styles ──────────────────────── --}}
<style>
    :root {
        --primary:  #0d9488;
        --primary2: #10b981;
        --purple:   #7c3aed;
        --gold:     #f59e0b;
        --red:      #ef4444;
        --text:     #1e293b;
        --muted:    #64748b;
        --glass-bg: rgba(255,255,255,0.75);
        --glass-br: rgba(255,255,255,0.5);
    }

    .reviews-wrapper { max-width: 960px; margin: 0 auto; padding: 2rem 1rem; }

    .premium-title {
        font-size: 2.2rem; font-weight: 800;
        background: linear-gradient(135deg,#0d9488,#10b981);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        margin-bottom: .25rem;
    }
    .premium-title i { font-size: 1.6rem; }
    .text-muted { color: var(--muted); font-size: .95rem; }
    .page-header { margin-bottom: 2rem; }

    /* Glass card */
    .glass-card {
        background: var(--glass-bg);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid var(--glass-br);
        border-radius: 20px;
        padding: 1.5rem;
        transition: all .35s cubic-bezier(.4,0,.2,1);
    }
    .glass-card:hover { box-shadow: 0 20px 40px rgba(0,0,0,.06); transform: translateY(-3px); }

    /* Summary grid */
    .summary-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1.4fr;
        gap: 1.25rem;
        margin-bottom: 2rem;
    }
    .summary-card { display: flex; align-items: flex-start; gap: 1rem; }
    .summary-icon {
        width: 52px; height: 52px; border-radius: 16px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem; color: white;
    }
    .rating-icon { background: linear-gradient(135deg,#f59e0b,#f97316); }
    .count-icon  { background: linear-gradient(135deg,#0d9488,#10b981); }
    .summary-body { flex: 1; }
    .summary-label { font-size: .7rem; text-transform: uppercase; letter-spacing: .08em; color: var(--muted); font-weight: 700; }
    .big-rating { display: flex; align-items: baseline; gap: .2rem; margin: .25rem 0; }
    .rating-number { font-size: 2.4rem; font-weight: 800; color: var(--text); line-height: 1; }
    .rating-max { font-size: 1rem; color: var(--muted); font-weight: 600; }
    .stars-row { display: flex; gap: 3px; margin-top: .25rem; }
    .summary-sub { font-size: .8rem; color: var(--muted); margin-top: .5rem; }

    /* Distribution */
    .distribution-card { flex-direction: column; }
    .dist-row { display: flex; align-items: center; gap: .6rem; margin-bottom: .4rem; }
    .dist-label { font-size: .8rem; font-weight: 600; color: var(--muted); width: 28px; text-align: right; }
    .dist-bar-track { flex: 1; height: 8px; background: #e2e8f0; border-radius: 99px; overflow: hidden; }
    .dist-bar-fill { height: 100%; border-radius: 99px; transition: width .6s ease; }
    .fill-high { background: linear-gradient(90deg,#0d9488,#10b981); }
    .fill-low  { background: linear-gradient(90deg,#ef4444,#f97316); }
    .dist-count { font-size: .75rem; font-weight: 700; color: var(--text); width: 24px; }

    /* Stars */
    .star-filled { color: #f59e0b; }
    .star-empty  { color: #e2e8f0; }

    /* Sort bar */
    .sort-bar {
        display: flex; align-items: center; gap: 1rem;
        margin-bottom: 1.5rem; flex-wrap: wrap;
    }
    .sort-label { font-size: .85rem; font-weight: 700; color: var(--muted); }
    .sort-pills { display: flex; gap: .5rem; }
    .sort-pill {
        text-decoration: none; padding: .45rem 1rem; border-radius: 99px;
        font-size: .8rem; font-weight: 600; color: var(--muted);
        background: white; border: 1px solid #e2e8f0;
        display: flex; align-items: center; gap: .35rem;
        transition: all .2s;
    }
    .sort-pill:hover { border-color: var(--primary); color: var(--primary); }
    .sort-pill.active { background: linear-gradient(135deg,#0d9488,#10b981); color: white; border-color: transparent; }

    /* Reviews list */
    .reviews-list { display: flex; flex-direction: column; gap: 1.25rem; }

    /* Review card */
    .review-card { position: relative; }
    .low-rating-card { border-color: rgba(239,68,68,.3); background: rgba(254,242,242,.8); }
    .low-rating-badge {
        position: absolute; top: 1rem; right: 1rem;
        background: linear-gradient(135deg,#ef4444,#f97316);
        color: white; font-size: .7rem; font-weight: 700;
        padding: .25rem .75rem; border-radius: 99px;
        display: flex; align-items: center; gap: .35rem;
    }

    /* Review header */
    .review-header {
        display: flex; justify-content: space-between; align-items: flex-start;
        margin-bottom: 1rem; flex-wrap: wrap; gap: .75rem;
    }
    .patient-info { display: flex; align-items: center; gap: .75rem; }
    .patient-avatar { width: 48px; height: 48px; border-radius: 14px; object-fit: cover; border: 2px solid white; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
    .patient-name { font-weight: 700; color: var(--text); font-size: .95rem; margin-bottom: .25rem; }
    .verified-badge { display: flex; align-items: center; gap: .3rem; font-size: .72rem; font-weight: 700; color: #0d9488; background: rgba(13,148,136,.1); padding: .15rem .6rem; border-radius: 99px; width: fit-content; }
    .review-meta { text-align: right; }
    .review-stars { display: flex; align-items: center; gap: 3px; justify-content: flex-end; margin-bottom: .3rem; }
    .rating-text { font-size: .8rem; font-weight: 700; color: var(--muted); margin-left: .25rem; }
    .review-date { font-size: .75rem; color: var(--muted); display: flex; align-items: center; gap: .3rem; justify-content: flex-end; }

    /* Comment */
    .review-comment {
        background: #f8fafc; border-radius: 12px; padding: 1rem;
        color: var(--text); font-size: .9rem; line-height: 1.6;
        position: relative; margin-bottom: 1rem;
    }
    .quote-icon { color: #cbd5e1; font-size: 1.2rem; margin-right: .5rem; }
    .no-comment { color: var(--muted); font-style: italic; display: flex; align-items: center; gap: .5rem; }

    /* Reply section */
    .reply-section { border-top: 1px solid #f1f5f9; padding-top: 1rem; margin-top: .25rem; }
    .existing-reply { background: rgba(13,148,136,.05); border-left: 3px solid var(--primary); border-radius: 0 12px 12px 0; padding: .875rem 1rem; margin-bottom: .75rem; }
    .reply-header { display: flex; align-items: center; gap: .5rem; color: var(--primary); font-size: .85rem; margin-bottom: .5rem; }
    .reply-date { margin-left: auto; font-size: .75rem; color: var(--muted); }
    .reply-text { color: var(--text); font-size: .88rem; line-height: 1.6; margin: 0; }
    .btn-edit-reply { margin-top: .6rem; background: none; border: 1px solid #e2e8f0; color: var(--muted); font-size: .75rem; font-weight: 600; padding: .3rem .75rem; border-radius: 8px; cursor: pointer; transition: all .2s; }
    .btn-edit-reply:hover { border-color: var(--primary); color: var(--primary); }

    .reply-form.hidden { display: none; }
    .reply-textarea {
        width: 100%; background: #f8fafc; border: 1px solid #e2e8f0;
        border-radius: 10px; padding: .75rem; font-size: .875rem;
        resize: vertical; font-family: inherit; transition: border .2s;
        box-sizing: border-box;
    }
    .reply-textarea:focus { outline: none; border-color: var(--primary); background: white; box-shadow: 0 0 0 3px rgba(13,148,136,.1); }
    .reply-actions { display: flex; justify-content: flex-end; gap: .5rem; margin-top: .5rem; }
    .btn-post-reply { background: linear-gradient(135deg,#0d9488,#10b981); color: white; border: none; padding: .5rem 1.25rem; border-radius: 10px; font-weight: 600; font-size: .85rem; cursor: pointer; display: flex; align-items: center; gap: .4rem; transition: all .2s; }
    .btn-post-reply:hover { transform: translateY(-1px); box-shadow: 0 6px 12px rgba(13,148,136,.3); }
    .btn-cancel-reply { background: #f1f5f9; color: var(--muted); border: none; padding: .5rem 1rem; border-radius: 10px; font-weight: 600; font-size: .85rem; cursor: pointer; }

    /* Pagination */
    .pagination-wrap { margin-top: 2rem; display: flex; justify-content: center; }
    .pagination-wrap .pagination { display: flex; gap: .35rem; list-style: none; padding: 0; }
    .pagination-wrap .page-item .page-link { padding: .45rem .85rem; border-radius: 8px; font-size: .85rem; font-weight: 600; text-decoration: none; color: var(--muted); background: white; border: 1px solid #e2e8f0; transition: all .2s; }
    .pagination-wrap .page-item.active .page-link { background: linear-gradient(135deg,#0d9488,#10b981); color: white; border-color: transparent; }
    .pagination-wrap .page-item .page-link:hover { border-color: var(--primary); color: var(--primary); }

    /* Empty state */
    .empty-state { text-align: center; padding: 4rem 2rem; }
    .empty-icon { font-size: 4rem; color: #cbd5e1; margin-bottom: 1rem; }
    .empty-state h2 { font-size: 1.5rem; font-weight: 700; color: var(--text); margin-bottom: .5rem; }
    .empty-state p { color: var(--muted); font-size: .95rem; }

    /* Animations */
    @keyframes fadeDown { from { opacity:0; transform:translateY(-10px); } to { opacity:1; transform:translateY(0); } }
    @keyframes fadeIn   { from { opacity:0; } to { opacity:1; } }
    @keyframes staggerIn{ from { opacity:0; transform:translateY(15px); } to { opacity:1; transform:translateY(0); } }
    .animate-fade-down { animation: fadeDown .4s ease-out; }
    .animate-fade-in   { animation: fadeIn .5s ease-out; }
    .animate-stagger   { animation: staggerIn .4s ease-out both; animation-delay: calc(var(--order) * 0.06s); }

    @media (max-width: 768px) {
        .summary-grid { grid-template-columns: 1fr 1fr; }
        .distribution-card { grid-column: span 2; }
        .review-header { flex-direction: column; }
        .review-meta { text-align: left; }
        .review-stars { justify-content: flex-start; }
    }
    @media (max-width: 480px) {
        .summary-grid { grid-template-columns: 1fr; }
        .distribution-card { grid-column: auto; }
    }
</style>

<script>
    function toggleReplyForm(id) {
        const form = document.getElementById('reply-form-' + id);
        form.classList.toggle('hidden');
    }
</script>
@endsection
