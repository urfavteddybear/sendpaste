<?php

namespace App\Http\Controllers;

use App\Models\Paste;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PasteController extends Controller
{
    /**
     * Show the home page with paste creation form
     */
    public function index()
    {
        return view('paste.create');
    }

    /**
     * Store a new paste
     */
    public function store(Request $request)
    {
        // Rate limiting
        $key = 'create-paste:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 10)) {
            return back()->withErrors(['content' => 'Too many paste attempts. Please try again later.']);
        }

        $request->validate([
            'content' => 'required|string|max:500000', // 500KB limit
            'title' => 'nullable|string|max:255',
            'language' => 'nullable|string|max:50',
            'expiration' => 'nullable|string|in:1week,1month,3months,6months,1year,never',
            'password' => 'nullable|string|min:4|max:255',
        ]);

        RateLimiter::increment($key);

        // Calculate expiration
        $expiresAt = null;
        switch ($request->expiration) {
            case '1week':
                $expiresAt = now()->addWeek();
                break;
            case '1month':
                $expiresAt = now()->addMonth();
                break;
            case '3months':
                $expiresAt = now()->addMonths(3);
                break;
            case '6months':
                $expiresAt = now()->addMonths(6);
                break;
            case '1year':
                $expiresAt = now()->addYear();
                break;
            case 'never':
            default:
                $expiresAt = null;
                break;
        }

        $paste = new Paste([
            'title' => $request->title,
            'language' => $request->language ?: 'text',
            'expires_at' => $expiresAt,
            'ip_address' => $request->ip(),
        ]);

        // Set content using attribute mutator
        $paste->content = $request->content;

        if ($request->filled('password')) {
            $paste->setPassword($request->password);
        }

        $paste->save();

        return redirect()->route('paste.show', $paste->slug)
            ->with('success', 'Paste created successfully!');
    }

    /**
     * Show a specific paste
     */
    public function show(Request $request, string $slug)
    {
        $paste = Paste::where('slug', $slug)->first();

        if (!$paste) {
            abort(404, 'Paste not found');
        }

        if ($paste->isExpired()) {
            abort(404, 'This paste has expired');
        }

        // Check if password is required
        if ($paste->isPasswordProtected()) {
            if (!session("paste_authenticated_{$paste->slug}")) {
                return view('paste.password', compact('paste'));
            }
        }

        // Increment view count
        $paste->incrementViews();

        return view('paste.show', compact('paste'));
    }

    /**
     * Verify paste password
     */
    public function verifyPassword(Request $request, string $slug)
    {
        $paste = Paste::where('slug', $slug)->first();

        if (!$paste || $paste->isExpired()) {
            abort(404);
        }

        $request->validate([
            'password' => 'required|string',
        ]);

        if ($paste->verifyPassword($request->password)) {
            session(["paste_authenticated_{$paste->slug}" => true]);
            return redirect()->route('paste.show', $slug);
        }

        return back()->withErrors(['password' => 'Invalid password']);
    }

    /**
     * Show raw paste content
     */
    public function raw(string $slug)
    {
        $paste = Paste::where('slug', $slug)->first();

        if (!$paste || $paste->isExpired()) {
            abort(404);
        }

        if ($paste->isPasswordProtected() && !session("paste_authenticated_{$paste->slug}")) {
            abort(403, 'Password required');
        }

        return response($paste->content)
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }

    /**
     * API endpoint to create paste
     */
    public function apiStore(Request $request)
    {
        $key = 'api-create-paste:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 20)) {
            return response()->json(['error' => 'Rate limit exceeded'], 429);
        }

        $request->validate([
            'content' => 'required|string|max:500000',
            'title' => 'nullable|string|max:255',
            'language' => 'nullable|string|max:50',
            'expiration' => 'nullable|string|in:1week,1month,3months,6months,1year,never',
        ]);

        RateLimiter::increment($key);

        $expiresAt = null;
        switch ($request->expiration) {
            case '1week':
                $expiresAt = now()->addWeek();
                break;
            case '1month':
                $expiresAt = now()->addMonth();
                break;
            case '3months':
                $expiresAt = now()->addMonths(3);
                break;
            case '6months':
                $expiresAt = now()->addMonths(6);
                break;
            case '1year':
                $expiresAt = now()->addYear();
                break;
        }

        $paste = Paste::create([
            'title' => $request->title,
            'language' => $request->language ?: 'text',
            'expires_at' => $expiresAt,
            'ip_address' => $request->ip(),
        ]);

        // Set content using attribute mutator
        $paste->content = $request->content;
        $paste->save();

        return response()->json([
            'slug' => $paste->slug,
            'url' => $paste->url,
            'expires_at' => $paste->expires_at?->toISOString(),
        ], 201);
    }
}
