@extends('layouts.app')
@section('title', 'New Travel Record')

@section('content')

<div class="page-header">
    <h1>✈️ New Travel Record</h1>
    <a href="{{ route('travels.index') }}" class="btn btn-outline">← Back to List</a>
</div>

<div class="card">
    <div class="card-header">
        <h2>Travel Details</h2>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('travels.store') }}" enctype="multipart/form-data">
            @csrf

            {{-- TRAVEL MODE --}}
            <div class="form-group" style="margin-bottom:1.5rem">
                <label>Travel Mode <span class="req">*</span></label>
                <div class="mode-selector">
                    <input type="radio" name="travel_mode" id="mode_air" value="air" class="mode-option"
                        {{ old('travel_mode','air') == 'air' ? 'checked' : '' }}>
                    <label for="mode_air" class="mode-label">
                        <span class="mode-icon">✈️</span>
                        <span class="mode-text">Air</span>
                    </label>

                    <input type="radio" name="travel_mode" id="mode_sea" value="sea" class="mode-option"
                        {{ old('travel_mode') == 'sea' ? 'checked' : '' }}>
                    <label for="mode_sea" class="mode-label">
                        <span class="mode-icon">🚢</span>
                        <span class="mode-text">Sea</span>
                    </label>

                    <input type="radio" name="travel_mode" id="mode_land" value="land" class="mode-option"
                        {{ old('travel_mode') == 'land' ? 'checked' : '' }}>
                    <label for="mode_land" class="mode-label">
                        <span class="mode-icon">🚌</span>
                        <span class="mode-text">Land</span>
                    </label>
                </div>
                @error('travel_mode')<span class="error">{{ $message }}</span>@enderror
            </div>

            <div class="form-grid">

                {{-- ORIGIN --}}
                <div class="form-group">
                    <label for="origin">From (Origin) <span class="req">*</span></label>
                    <input type="text" id="origin" name="origin" value="{{ old('origin') }}"
                        placeholder="e.g. Manila, Cebu, Davao" required>
                    @error('origin')<span class="error">{{ $message }}</span>@enderror
                </div>

                {{-- DESTINATION --}}
                <div class="form-group">
                    <label for="destination">To (Destination) <span class="req">*</span></label>
                    <input type="text" id="destination" name="destination" value="{{ old('destination') }}"
                        placeholder="e.g. Makati, Iloilo, Singapore" required>
                    @error('destination')<span class="error">{{ $message }}</span>@enderror
                </div>

                {{-- PURPOSE --}}
                <div class="form-group full">
                    <label for="purpose">Purpose / Project Name <span class="req">*</span></label>
                    <input type="text" id="purpose" name="purpose" value="{{ old('purpose') }}"
                        placeholder="e.g. Q1 Audit, Site Visit - Cebu Plant, Training" required>
                    @error('purpose')<span class="error">{{ $message }}</span>@enderror
                </div>

                {{-- TRAVEL DATE --}}
                <div class="form-group">
                    <label for="travel_date">Travel Date <span class="req">*</span></label>
                    <input type="date" id="travel_date" name="travel_date"
                        value="{{ old('travel_date', date('Y-m-d')) }}" required>
                    @error('travel_date')<span class="error">{{ $message }}</span>@enderror
                </div>

                {{-- RETURN DATE --}}
                <div class="form-group">
                    <label for="return_date">Return Date <span style="color:#a0aec0;font-weight:400">(optional)</span></label>
                    <input type="date" id="return_date" name="return_date" value="{{ old('return_date') }}">
                    @error('return_date')<span class="error">{{ $message }}</span>@enderror
                </div>

                {{-- PASSENGERS --}}
                <div class="form-group">
                    <label for="passengers">Number of Passengers <span class="req">*</span></label>
                    <input type="number" id="passengers" name="passengers"
                        value="{{ old('passengers', 1) }}" min="1" max="999" required>
                    @error('passengers')<span class="error">{{ $message }}</span>@enderror
                </div>

                {{-- AMOUNT --}}
                <div class="form-group">
                    <label for="amount">Total Amount (₱) <span class="req">*</span></label>
                    <input type="number" id="amount" name="amount"
                        value="{{ old('amount') }}" min="0" step="0.01"
                        placeholder="0.00" required>
                    @error('amount')<span class="error">{{ $message }}</span>@enderror
                </div>

                {{-- ITINERARY UPLOAD --}}
                <div class="form-group full">
                    <label for="itinerary">Upload Itinerary <span style="color:#a0aec0;font-weight:400">(PDF, Word, Excel, Image — max 5MB)</span></label>
                    <input type="file" id="itinerary" name="itinerary"
                        accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
                    @error('itinerary')<span class="error">{{ $message }}</span>@enderror
                </div>

                {{-- NOTES --}}
                <div class="form-group full">
                    <label for="notes">Notes <span style="color:#a0aec0;font-weight:400">(optional)</span></label>
                    <textarea id="notes" name="notes" rows="3"
                        placeholder="Additional remarks, hotel, flight details…">{{ old('notes') }}</textarea>
                    @error('notes')<span class="error">{{ $message }}</span>@enderror
                </div>

            </div>

            <div style="display:flex;gap:1rem;margin-top:1.5rem;justify-content:flex-end">
                <a href="{{ route('travels.index') }}" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-success">💾 Save Travel Record</button>
            </div>

        </form>
    </div>
</div>

@endsection
