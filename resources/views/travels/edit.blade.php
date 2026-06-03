@extends('layouts.app')
@section('title', 'Edit Travel Record')

@section('content')

<div class="page-header">
    <h1>✏️ Edit Travel Record #{{ $travel->id }}</h1>
    <div style="display:flex;gap:.75rem">
        <a href="{{ route('travels.show', $travel) }}" class="btn btn-outline">← View</a>
        <a href="{{ route('travels.index') }}" class="btn btn-outline">← List</a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2>Update Details</h2>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('travels.update', $travel) }}" enctype="multipart/form-data">
            @csrf @method('PUT')

            {{-- TRAVEL MODE --}}
            <div class="form-group" style="margin-bottom:1.5rem">
                <label>Travel Mode <span class="req">*</span></label>
                <div class="mode-selector">
                    @foreach(['air' => '✈️', 'sea' => '🚢', 'land' => '🚌'] as $val => $icon)
                        <input type="radio" name="travel_mode" id="mode_{{ $val }}" value="{{ $val }}" class="mode-option"
                            {{ old('travel_mode', $travel->travel_mode) == $val ? 'checked' : '' }}>
                        <label for="mode_{{ $val }}" class="mode-label">
                            <span class="mode-icon">{{ $icon }}</span>
                            <span class="mode-text">{{ ucfirst($val) }}</span>
                        </label>
                    @endforeach
                </div>
                @error('travel_mode')<span class="error">{{ $message }}</span>@enderror
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label>From (Origin) <span class="req">*</span></label>
                    <input type="text" name="origin" value="{{ old('origin', $travel->origin) }}" required>
                    @error('origin')<span class="error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label>To (Destination) <span class="req">*</span></label>
                    <input type="text" name="destination" value="{{ old('destination', $travel->destination) }}" required>
                    @error('destination')<span class="error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group full">
                    <label>Purpose / Project Name <span class="req">*</span></label>
                    <input type="text" name="purpose" value="{{ old('purpose', $travel->purpose) }}" required>
                    @error('purpose')<span class="error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label>Travel Date <span class="req">*</span></label>
                    <input type="date" name="travel_date" value="{{ old('travel_date', $travel->travel_date->format('Y-m-d')) }}" required>
                    @error('travel_date')<span class="error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label>Return Date</label>
                    <input type="date" name="return_date" value="{{ old('return_date', optional($travel->return_date)->format('Y-m-d')) }}">
                    @error('return_date')<span class="error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label>Number of Passengers <span class="req">*</span></label>
                    <input type="number" name="passengers" value="{{ old('passengers', $travel->passengers) }}" min="1" required>
                    @error('passengers')<span class="error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label>Total Amount (₱) <span class="req">*</span></label>
                    <input type="number" name="amount" value="{{ old('amount', $travel->amount) }}" min="0" step="0.01" required>
                    @error('amount')<span class="error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group full">
                    <label>Upload New Itinerary <span style="color:#a0aec0;font-weight:400">(leave blank to keep existing)</span></label>
                    @if($travel->itinerary_path)
                        <div style="margin-bottom:.5rem;font-size:.88rem;color:#4a5568">
                            Current file: 
                            <a href="{{ Storage::url($travel->itinerary_path) }}" target="_blank" style="color:#2b6cb0">
                                📎 {{ basename($travel->itinerary_path) }}
                            </a>
                        </div>
                    @endif
                    <input type="file" name="itinerary" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
                    @error('itinerary')<span class="error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group full">
                    <label>Notes</label>
                    <textarea name="notes" rows="3">{{ old('notes', $travel->notes) }}</textarea>
                    @error('notes')<span class="error">{{ $message }}</span>@enderror
                </div>
            </div>

            <div style="display:flex;gap:1rem;margin-top:1.5rem;justify-content:flex-end">
                <a href="{{ route('travels.show', $travel) }}" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-success">💾 Update Record</button>
            </div>
        </form>
    </div>
</div>

@endsection
