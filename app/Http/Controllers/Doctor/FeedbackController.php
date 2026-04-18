<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReplyFeedbackRequest;
use App\Models\DoctorFeedback;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;

class FeedbackController extends Controller
{
    /**
     * Show all reviews for the authenticated doctor with sorting & pagination.
     */
    public function index(Request $request): View
    {
        $doctor = auth()->user()->doctor;

        $sort    = $request->get('sort', 'latest');
        $perPage = 10;

        $reviews = DoctorFeedback::with(['patient.user', 'appointment'])
            ->where('doctor_id', $doctor->id)
            ->sortBy($sort)
            ->paginate($perPage)
            ->withQueryString();

        $avgRating    = $doctor->average_rating;
        $totalReviews = $doctor->total_reviews;

        // Rating distribution (1–5 star counts)
        $distribution = DoctorFeedback::where('doctor_id', $doctor->id)
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->pluck('count', 'rating')
            ->toArray();

        return view('doctor.reviews.index', compact(
            'reviews',
            'avgRating',
            'totalReviews',
            'distribution',
            'sort'
        ));
    }

    /**
     * Doctor submits a reply to a specific review.
     */
    public function reply(ReplyFeedbackRequest $request, DoctorFeedback $feedback): RedirectResponse
    {
        Gate::authorize('reply', $feedback);

        $feedback->update([
            'doctor_reply' => $request->doctor_reply,
        ]);

        return back()->with('success', 'Your reply has been posted.');
    }
}
