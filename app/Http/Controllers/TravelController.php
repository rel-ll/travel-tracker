<?php

namespace App\Http\Controllers;

use App\Models\Travel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TravelController extends Controller
{
    // ── LIST ─────────────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Travel::query();

        if ($request->filled('mode'))    $query->where('travel_mode', $request->mode);
        if ($request->filled('purpose')) $query->where('purpose', 'like', '%' . $request->purpose . '%');
        if ($request->filled('from'))    $query->whereDate('travel_date', '>=', $request->from);
        if ($request->filled('to'))      $query->whereDate('travel_date', '<=', $request->to);

        $travels = $query->latest('travel_date')->paginate(15)->withQueryString();

        $stats = [
            'total'      => Travel::count(),
            'air'        => Travel::where('travel_mode', 'air')->count(),
            'sea'        => Travel::where('travel_mode', 'sea')->count(),
            'land'       => Travel::where('travel_mode', 'land')->count(),
            'total_cost' => Travel::sum('amount'),
            'total_pax'  => Travel::sum('passengers'),
        ];

        return view('travels.index', compact('travels', 'stats'));
    }

    // ── CREATE FORM ──────────────────────────────────────────────────────────────
    public function create()
    {
        return view('travels.create');
    }

    // ── STORE ─────────────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'travel_mode'        => 'required|in:air,sea,land',
            'transport_provider' => 'nullable|string|max:255',
            'origin'             => 'required|string|max:255',
            'destination'        => 'required|string|max:255',
            'purpose'            => 'required|string|max:255',
            'amount'             => 'required|numeric|min:0',
            'passengers'         => 'required|integer|min:1',
            'travel_date'        => 'required|date',
            'return_date'        => 'nullable|date|after_or_equal:travel_date',
            'notes'              => 'nullable|string|max:1000',
            'itinerary'          => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:5120',
        ]);

        $validated['itinerary_path'] = null;
        if ($request->hasFile('itinerary')) {
            $validated['itinerary_path'] = $this->uploadToSupabase($request->file('itinerary'));
        }

        Travel::create($validated);

        return redirect()->route('travels.index')
            ->with('success', 'Travel record created successfully!');
    }

    // ── SHOW ──────────────────────────────────────────────────────────────────────
    public function show(Travel $travel)
    {
        return view('travels.show', compact('travel'));
    }

    // ── EDIT FORM ─────────────────────────────────────────────────────────────────
    public function edit(Travel $travel)
    {
        return view('travels.edit', compact('travel'));
    }

    // ── UPDATE ────────────────────────────────────────────────────────────────────
    public function update(Request $request, Travel $travel)
    {
        $validated = $request->validate([
            'travel_mode'        => 'required|in:air,sea,land',
            'transport_provider' => 'nullable|string|max:255',
            'origin'             => 'required|string|max:255',
            'destination'        => 'required|string|max:255',
            'purpose'            => 'required|string|max:255',
            'amount'             => 'required|numeric|min:0',
            'passengers'         => 'required|integer|min:1',
            'travel_date'        => 'required|date',
            'return_date'        => 'nullable|date|after_or_equal:travel_date',
            'notes'              => 'nullable|string|max:1000',
            'itinerary'          => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('itinerary')) {
            // Delete old file from Supabase if exists
            if ($travel->itinerary_path) {
                $this->deleteFromSupabase($travel->itinerary_path);
            }
            $validated['itinerary_path'] = $this->uploadToSupabase($request->file('itinerary'));
        }

        $travel->update($validated);

        return redirect()->route('travels.show', $travel)
            ->with('success', 'Travel record updated!');
    }

    // ── DELETE ────────────────────────────────────────────────────────────────────
    public function destroy(Travel $travel)
    {
        if ($travel->itinerary_path) {
            $this->deleteFromSupabase($travel->itinerary_path);
        }
        $travel->delete();

        return redirect()->route('travels.index')
            ->with('success', 'Travel record deleted.');
    }

    // ── SUPABASE STORAGE HELPERS ──────────────────────────────────────────────────

    private function uploadToSupabase($file): string
    {
        $bucket   = env('SUPABASE_STORAGE_BUCKET', 'itineraries');
        $filename = Str::uuid() . '_' . $file->getClientOriginalName();
        $contents = file_get_contents($file->getRealPath());
        $mimeType = $file->getMimeType();

        $url = rtrim(env('SUPABASE_URL'), '/') 
             . '/storage/v1/object/' . $bucket . '/' . $filename;

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => $contents,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . env('SUPABASE_SERVICE_KEY'),
                'Content-Type: ' . $mimeType,
                'x-upsert: true',
            ],
        ]);
        curl_exec($ch);
        curl_close($ch);

        // Return the public URL
        return rtrim(env('SUPABASE_URL'), '/')
             . '/storage/v1/object/public/' . $bucket . '/' . $filename;
    }

    private function deleteFromSupabase(string $publicUrl): void
    {
        $bucket  = env('SUPABASE_STORAGE_BUCKET', 'itineraries');
        $baseUrl = rtrim(env('SUPABASE_URL'), '/') . '/storage/v1/object/public/' . $bucket . '/';
        $filename = str_replace($baseUrl, '', $publicUrl);

        $url = rtrim(env('SUPABASE_URL'), '/')
             . '/storage/v1/object/' . $bucket . '/' . $filename;

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_CUSTOMREQUEST  => 'DELETE',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . env('SUPABASE_SERVICE_KEY'),
            ],
        ]);
        curl_exec($ch);
        curl_close($ch);
    }
}