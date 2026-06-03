<?php

namespace App\Http\Controllers;

use App\Models\Travel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TravelController extends Controller
{
    // ── LIST ────────────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Travel::query();

        // Filter by mode
        if ($request->filled('mode')) {
            $query->where('travel_mode', $request->mode);
        }

        // Filter by purpose / project
        if ($request->filled('purpose')) {
            $query->where('purpose', 'like', '%' . $request->purpose . '%');
        }

        // Filter by date range
        if ($request->filled('from')) {
            $query->whereDate('travel_date', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('travel_date', '<=', $request->to);
        }

        $travels = $query->latest('travel_date')->paginate(15)->withQueryString();

        // Summary stats
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

    // ── CREATE FORM ─────────────────────────────────────────────────────────────
    public function create()
    {
        return view('travels.create');
    }

    // ── STORE ────────────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'travel_mode'  => 'required|in:air,sea,land',
            'origin'       => 'required|string|max:255',
            'destination'  => 'required|string|max:255',
            'purpose'      => 'required|string|max:255',
            'amount'       => 'required|numeric|min:0',
            'passengers'   => 'required|integer|min:1',
            'travel_date'  => 'required|date',
            'return_date'  => 'nullable|date|after_or_equal:travel_date',
            'notes'        => 'nullable|string|max:1000',
            'itinerary'    => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:5120',
        ]);

        $itineraryPath = null;
        if ($request->hasFile('itinerary')) {
            $itineraryPath = $request->file('itinerary')
                ->store('itineraries', 'public');
        }

        $validated['itinerary_path'] = $itineraryPath;
        Travel::create($validated);

        return redirect()->route('travels.index')
            ->with('success', 'Travel record created successfully!');
    }

    // ── SHOW ─────────────────────────────────────────────────────────────────────
    public function show(Travel $travel)
    {
        return view('travels.show', compact('travel'));
    }

    // ── EDIT FORM ────────────────────────────────────────────────────────────────
    public function edit(Travel $travel)
    {
        return view('travels.edit', compact('travel'));
    }

    // ── UPDATE ────────────────────────────────────────────────────────────────────
    public function update(Request $request, Travel $travel)
    {
        $validated = $request->validate([
            'travel_mode'  => 'required|in:air,sea,land',
            'origin'       => 'required|string|max:255',
            'destination'  => 'required|string|max:255',
            'purpose'      => 'required|string|max:255',
            'amount'       => 'required|numeric|min:0',
            'passengers'   => 'required|integer|min:1',
            'travel_date'  => 'required|date',
            'return_date'  => 'nullable|date|after_or_equal:travel_date',
            'notes'        => 'nullable|string|max:1000',
            'itinerary'    => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('itinerary')) {
            // Delete old file if exists
            if ($travel->itinerary_path) {
                Storage::disk('public')->delete($travel->itinerary_path);
            }
            $validated['itinerary_path'] = $request->file('itinerary')
                ->store('itineraries', 'public');
        }

        $travel->update($validated);

        return redirect()->route('travels.show', $travel)
            ->with('success', 'Travel record updated!');
    }

    // ── DELETE ────────────────────────────────────────────────────────────────────
    public function destroy(Travel $travel)
    {
        if ($travel->itinerary_path) {
            Storage::disk('public')->delete($travel->itinerary_path);
        }
        $travel->delete();

        return redirect()->route('travels.index')
            ->with('success', 'Travel record deleted.');
    }
}
