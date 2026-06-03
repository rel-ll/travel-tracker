<?php

namespace App\Http\Controllers;

use App\Models\Travel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ShareController extends Controller
{
    /**
     * Generate or refresh a share token for a travel record.
     * Expires in 24 hours.
     */
    public function generate(Travel $travel)
    {
        $travel->share_token      = Str::random(40);
        $travel->share_expires_at = Carbon::now()->addHours(24);
        $travel->save();

        $shareUrl = route('travel.share.view', $travel->share_token);

        return response()->json([
            'url'        => $shareUrl,
            'expires_at' => $travel->share_expires_at->format('M d, Y h:i A'),
        ]);
    }

    /**
     * Revoke the share token.
     */
    public function revoke(Travel $travel)
    {
        $travel->share_token      = null;
        $travel->share_expires_at = null;
        $travel->save();

        return back()->with('success', 'Share link has been revoked.');
    }

    /**
     * Public guest view — accessible by anyone with a valid, unexpired token.
     */
    public function view(string $token)
    {
        $travel = Travel::where('share_token', $token)->firstOrFail();

        if (!$travel->share_expires_at || Carbon::now()->isAfter($travel->share_expires_at)) {
            abort(410, 'This share link has expired.');
        }

        return view('travels.guest', compact('travel'));
    }
}
