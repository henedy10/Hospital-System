@extends('layouts.dashboard')

@section('title', 'My Reviews')

@section('content')
<div style="max-width: 1000px; margin: 0 auto; padding-bottom: 40px;">
    <!-- Welcome Section -->
    <div style="margin-bottom: 32px; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 16px;">
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 800; color: #0f172a; margin-bottom: 8px;">My Reviews ⭐</h1>
            <p style="color: #64748b; font-size: 0.95rem; margin: 0;">See what your patients think about your care.</p>
        </div>
        
        @if($totalReviews > 0)
        <!-- Sort Control -->
        <div style="display: flex; align-items: center; gap: 8px;">
            <span style="font-size: 0.85rem; font-weight: 600; color: #64748b;">Sort by:</span>
            <select onchange="window.location.href=this.value" style="padding: 8px 12px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 0.85rem; font-weight: 600; color: #1e293b; outline: none; cursor: pointer; background: #fff;">
                <option value="{{ route('doctor.reviews.index', ['sort' => 'latest']) }}" {{ $sort === 'latest' ? 'selected' : '' }}>Latest</option>
                <option value="{{ route('doctor.reviews.index', ['sort' => 'highest']) }}" {{ $sort === 'highest' ? 'selected' : '' }}>Highest Rating</option>
                <option value="{{ route('doctor.reviews.index', ['sort' => 'lowest']) }}" {{ $sort === 'lowest' ? 'selected' : '' }}>Lowest Rating</option>
            </select>
        </div>
        @endif
    </div>

    <!-- Stats Header -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 32px;">
        <!-- Average Rating -->
        <div style="background: #fff; border-radius: 16px; padding: 24px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #f1f5f9; display: flex; align-items: flex-start; gap: 20px;">
            <div style="width: 54px; height: 54px; border-radius: 14px; background: #fef3c7; color: #f59e0b; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0;">
                <i class="fas fa-star"></i>
            </div>
            <div>
                <p style="font-size: 0.85rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 4px 0;">Average Rating</p>
                <div style="display: flex; align-items: baseline; gap: 6px;">
                    <h3 style="font-size: 2rem; font-weight: 800; color: #0f172a; margin: 0; line-height: 1;">{{ $avgRating ?? '—' }}</h3>
                    <span style="font-size: 1rem; color: #94a3b8; font-weight: 600;">/5</span>
                </div>
                <div style="display: flex; gap: 4px; margin-top: 8px;">
                    @for ($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star" style="color: {{ $avgRating && $i <= round($avgRating) ? '#f59e0b' : '#e2e8f0' }}; font-size: 0.9rem;"></i>
                    @endfor
                </div>
            </div>
        </div>

        <!-- Total Reviews -->
        <div style="background: #fff; border-radius: 16px; padding: 24px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #f1f5f9; display: flex; align-items: flex-start; gap: 20px;">
            <div style="width: 54px; height: 54px; border-radius: 14px; background: #e0f2fe; color: #0ea5e9; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0;">
                <i class="fas fa-comments"></i>
            </div>
            <div>
                <p style="font-size: 0.85rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 4px 0;">Total Reviews</p>
                <h3 style="font-size: 2rem; font-weight: 800; color: #0f172a; margin: 0; line-height: 1;">{{ $totalReviews }}</h3>
                <p style="font-size: 0.85rem; color: #64748b; margin-top: 8px;">Verified patient feedback</p>
            </div>
        </div>

        <!-- Rating Breakdown -->
        <div style="background: #fff; border-radius: 16px; padding: 24px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #f1f5f9; grid-column: span 1;">
            <p style="font-size: 0.85rem; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 12px 0;">Rating Breakdown</p>
            <div style="display: flex; flex-direction: column; gap: 6px;">
                @for ($star = 5; $star >= 1; $star--)
                    @php
                        $count = $distribution[$star] ?? 0;
                        $pct   = $totalReviews > 0 ? round(($count / $totalReviews) * 100) : 0;
                        $fillColor = $star >= 4 ? '#10b981' : ($star == 3 ? '#f59e0b' : '#ef4444');
                    @endphp
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <span style="font-size: 0.8rem; font-weight: 600; color: #475569; width: 20px; text-align: right;">{{ $star }}<i class="fas fa-star" style="color: #f59e0b; margin-left: 2px; font-size: 0.7rem;"></i></span>
                        <div style="flex: 1; height: 6px; background: #f1f5f9; border-radius: 99px; overflow: hidden;">
                            <div style="height: 100%; width: {{ $pct }}%; background: {{ $fillColor }}; border-radius: 99px;"></div>
                        </div>
                        <span style="font-size: 0.75rem; font-weight: 600; color: #64748b; width: 24px;">{{ $count }}</span>
                    </div>
                @endfor
            </div>
        </div>
    </div>

    <!-- Reviews List -->
    @if($totalReviews > 0)
        <div style="display: flex; flex-direction: column; gap: 20px;">
            @foreach($reviews as $review)
                <div style="background: #fff; border-radius: 16px; padding: 24px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid {{ $review->isLowRating() ? '#fee2e2' : '#f1f5f9' }}; {{ $review->isLowRating() ? 'background: #fef8f8;' : '' }}">
                    
                    @if($review->isLowRating())
                        <div style="display: inline-flex; align-items: center; gap: 6px; background: #fef2f2; color: #ef4444; font-size: 0.75rem; font-weight: 700; padding: 4px 10px; border-radius: 12px; border: 1px solid #fee2e2; margin-bottom: 16px;">
                            <i class="fas fa-exclamation-triangle"></i> Needs Attention
                        </div>
                    @endif

                    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px; margin-bottom: 16px;">
                        <div style="display: flex; align-items: center; gap: 16px;">
                            <img src="{{ $review->patient->user->profile_image ? asset('storage/' . $review->patient->user->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($review->patient->user->name) . '&background=0ea5e9&color=fff' }}" 
                                 alt="Patient" style="width: 48px; height: 48px; border-radius: 12px; object-fit: cover; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                            <div>
                                <h3 style="margin: 0 0 4px 0; font-size: 1rem; font-weight: 700; color: #0f172a;">{{ $review->patient->user->name }}</h3>
                                <span style="display: inline-flex; align-items: center; gap: 4px; font-size: 0.75rem; color: #0ea5e9; font-weight: 600; background: #f0f9ff; padding: 2px 8px; border-radius: 20px;">
                                    <i class="fas fa-check-circle"></i> Verified Patient
                                </span>
                            </div>
                        </div>

                        <div style="text-align: right;">
                            <div style="display: flex; gap: 4px; justify-content: flex-end; margin-bottom: 6px;">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star" style="color: {{ $i <= $review->rating ? '#f59e0b' : '#e2e8f0' }}; font-size: 0.95rem;"></i>
                                @endfor
                            </div>
                            <div style="color: #64748b; font-size: 0.8rem; font-weight: 500;">
                                <i class="fas fa-calendar-alt" style="margin-right: 4px;"></i> {{ $review->created_at->format('M d, Y') }}
                            </div>
                        </div>
                    </div>

                    @if($review->comment)
                        <div style="background: #f8fafc; border-radius: 12px; padding: 16px; position: relative;">
                            <i class="fas fa-quote-left" style="color: #e2e8f0; font-size: 1.5rem; position: absolute; top: 12px; left: 16px; opacity: 0.5;"></i>
                            <p style="margin: 0; color: #334155; font-size: 0.95rem; line-height: 1.6; padding-left: 36px;">{{ $review->comment }}</p>
                        </div>
                    @else
                        <div style="color: #94a3b8; font-size: 0.9rem; font-style: italic; padding: 8px 0;">
                            No written comment provided.
                        </div>
                    @endif

                    <!-- Doctor Reply Section -->
                    <div style="margin-top: 20px; border-top: 1px solid #f1f5f9; padding-top: 20px;">
                        @if($review->doctor_reply)
                            <div style="background: #f0f9ff; border-left: 3px solid #0ea5e9; border-radius: 0 12px 12px 0; padding: 16px;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                    <strong style="color: #0ea5e9; font-size: 0.85rem; display: flex; align-items: center; gap: 6px;">
                                        <i class="fas fa-reply"></i> Your Reply
                                    </strong>
                                    <span style="font-size: 0.75rem; color: #64748b;">{{ $review->updated_at->format('M d, Y') }}</span>
                                </div>
                                <p style="margin: 0 0 12px 0; color: #334155; font-size: 0.9rem; line-height: 1.5;">{{ $review->doctor_reply }}</p>
                                <button type="button" onclick="toggleReplyForm({{ $review->id }})" style="background: none; border: 1px solid #cbd5e1; color: #64748b; font-size: 0.75rem; font-weight: 600; padding: 6px 12px; border-radius: 8px; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.borderColor='#0ea5e9'; this.style.color='#0ea5e9'" onmouseout="this.style.borderColor='#cbd5e1'; this.style.color='#64748b'">
                                    <i class="fas fa-pen" style="margin-right: 4px;"></i> Edit Reply
                                </button>
                            </div>
                        @else
                            <button type="button" onclick="toggleReplyForm({{ $review->id }})" style="background: none; border: 1px dashed #cbd5e1; color: #0ea5e9; font-size: 0.85rem; font-weight: 600; padding: 10px 16px; border-radius: 10px; cursor: pointer; width: 100%; text-align: left; transition: all 0.2s;" onmouseover="this.style.borderColor='#0ea5e9'; this.style.background='#f0f9ff'" onmouseout="this.style.borderColor='#cbd5e1'; this.style.background='transparent'">
                                <i class="fas fa-reply" style="margin-right: 6px;"></i> Write a reply...
                            </button>
                        @endif

                        <div id="reply-form-{{ $review->id }}" style="display: none; margin-top: 16px;">
                            <form action="{{ route('doctor.reviews.reply', $review) }}" method="POST">
                                @csrf
                                <textarea name="doctor_reply" rows="3" placeholder="Write a professional reply to your patient..." style="width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.9rem; color: #334155; margin-bottom: 12px; font-family: inherit; resize: vertical; outline: none; transition: border 0.2s;" onfocus="this.style.borderColor='#0ea5e9'; this.style.boxShadow='0 0 0 3px rgba(14,165,233,0.1)';" onblur="this.style.borderColor='#cbd5e1'; this.style.boxShadow='none';">{{ $review->doctor_reply }}</textarea>
                                <div style="display: flex; gap: 10px; justify-content: flex-end;">
                                    <button type="button" onclick="toggleReplyForm({{ $review->id }})" style="background: #f1f5f9; border: none; color: #64748b; font-size: 0.85rem; font-weight: 600; padding: 8px 16px; border-radius: 8px; cursor: pointer;">Cancel</button>
                                    <button type="submit" style="background: #0ea5e9; border: none; color: #fff; font-size: 0.85rem; font-weight: 600; padding: 8px 16px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: background 0.2s;" onmouseover="this.style.background='#0284c7'" onmouseout="this.style.background='#0ea5e9'">
                                        <i class="fas fa-paper-plane"></i> {{ $review->doctor_reply ? 'Update Reply' : 'Post Reply' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($reviews->hasPages())
            <div style="margin-top: 32px; display: flex; justify-content: center;">
                {{ $reviews->links() }}
            </div>
        @endif
    @else
        <div style="background: #fff; border-radius: 16px; padding: 60px 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #f1f5f9; text-align: center;">
            <div style="background: #f8fafc; width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px auto;">
                <i class="fas fa-star-half-alt" style="font-size: 2rem; color: #cbd5e1;"></i>
            </div>
            <h3 style="margin: 0 0 8px 0; color: #0f172a; font-size: 1.25rem; font-weight: 700;">No Reviews Yet</h3>
            <p style="color: #64748b; font-size: 0.95rem; margin: 0; max-width: 400px; margin: 0 auto;">Patient reviews will appear here once they complete their appointments and leave feedback about their visit.</p>
        </div>
    @endif
</div>

<script>
    function toggleReplyForm(id) {
        const form = document.getElementById('reply-form-' + id);
        if (form.style.display === 'none' || form.style.display === '') {
            form.style.display = 'block';
        } else {
            form.style.display = 'none';
        }
    }
</script>
@endsection
