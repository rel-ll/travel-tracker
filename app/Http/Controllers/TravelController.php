<?php

namespace App\Http\Controllers;

use App\Models\Travel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TravelController extends Controller
{


    public function index(Request $request)
    {
        $query = Travel::query();
        if ($request->filled('mode'))    $query->where('travel_mode', $request->mode);
        if ($request->filled('purpose')) $query->where('purpose', 'like', '%'.$request->purpose.'%');
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

    public function all(Request $request)
    {
        $query = Travel::query();
        if ($request->filled('mode'))    $query->where('travel_mode', $request->mode);
        if ($request->filled('purpose')) $query->where('purpose', 'like', '%'.$request->purpose.'%');
        if ($request->filled('from'))    $query->whereDate('travel_date', '>=', $request->from);
        if ($request->filled('to'))      $query->whereDate('travel_date', '<=', $request->to);

        $travels = $query->latest('travel_date')->paginate(15)->withQueryString();
        return view('travels.all', compact('travels'));
    }

    public function create()
    {
        return view('travels.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'travel_mode' => 'required|in:air,sea,land',
            'origin'      => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'purpose'     => 'required|string|max:255',
            'amount'      => 'required|numeric|min:0',
            'passengers'  => 'required|integer|min:1',
            'travel_date' => 'required|date',
            'return_date' => 'nullable|date|after_or_equal:travel_date',
            'notes'       => 'nullable|string|max:1000',
            'itinerary'   => $this->itineraryRules(),
        ]);

        $validated['itinerary_path'] = null;
        if ($request->hasFile('itinerary')) {
            $validated['itinerary_path'] = $request->file('itinerary')
                ->store('itineraries', 'public');
        }

        Travel::create($validated);
        return redirect()->route('travels.index')
            ->with('success', 'Travel record created successfully!');
    }

    public function show(Travel $travel)
    {
        return view('travels.show', compact('travel'));
    }

    public function edit(Travel $travel)
    {
        return view('travels.edit', compact('travel'));
    }

    public function update(Request $request, Travel $travel)
    {
        $validated = $request->validate([
            'travel_mode' => 'required|in:air,sea,land',
            'origin'      => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'purpose'     => 'required|string|max:255',
            'amount'      => 'required|numeric|min:0',
            'passengers'  => 'required|integer|min:1',
            'travel_date' => 'required|date',
            'return_date' => 'nullable|date|after_or_equal:travel_date',
            'notes'       => 'nullable|string|max:1000',
            'itinerary'   => $this->itineraryRules(),
        ]);

        if ($request->hasFile('itinerary')) {
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

    public function destroy(Travel $travel)
    {
        $travel->delete(); // soft delete — file kept until force deleted
        return redirect()->route('travels.index')
            ->with('success', 'Travel record moved to trash.');
    }

    public function trash(Request $request)
    {
        $query = Travel::onlyTrashed();
        if ($request->filled('mode'))    $query->where('travel_mode', $request->mode);
        if ($request->filled('purpose')) $query->where('purpose', 'like', '%'.$request->purpose.'%');
        if ($request->filled('from'))    $query->whereDate('travel_date', '>=', $request->from);
        if ($request->filled('to'))      $query->whereDate('travel_date', '<=', $request->to);

        $travels = $query->latest('deleted_at')->paginate(15)->withQueryString();
        return view('travels.trash', compact('travels'));
    }

    public function restore($id)
    {
        Travel::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->route('travels.trash')->with('success', 'Record restored.');
    }

    public function forceDelete($id)
    {
        $travel = Travel::onlyTrashed()->findOrFail($id);
        if ($travel->itinerary_path) {
            Storage::disk('public')->delete($travel->itinerary_path);
        }
        $travel->forceDelete();
        return redirect()->route('travels.trash')->with('success', 'Record permanently deleted.');
    }

public function emptyTrash()
{
    Travel::onlyTrashed()->get()->each(function ($travel) {
        if ($travel->itinerary_path) {
            Storage::disk('public')->delete($travel->itinerary_path);
        }
        $travel->forceDelete();
    });
    return redirect()->route('travels.trash')->with('success', 'Trash emptied.');
}

    // ── Shared validation rules ──────────────────────────────────────────────
    private function itineraryRules(): array
    {
        return [
            'nullable',
            'file',
            'max:5120',
            'mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png',
            function ($attribute, $value, $fail) {
                $realMime = mime_content_type($value->getRealPath());
                $allowed  = [
                    'application/pdf',
                    'image/jpeg',
                    'image/png',
                    'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'application/vnd.ms-excel',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                ];
                if (!in_array($realMime, $allowed)) {
                    $fail('Invalid file type.');
                }
            },
        ];
    }
}