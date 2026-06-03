@extends('layouts.app')
@section('title', 'New Travel Record')

@section('content')

{{-- Provider data for JS --}}
@php
$providers = [
    'air'  => ['Philippine Airlines', 'Cebu Pacific', 'AirAsia', 'Royal Air', 'SkyJet Airlines', 'SEAIR'],
    'land' => ['PITX', 'Victory Liner', 'Five Star', 'Genesis', 'Farinas', 'Partas', 'Solid North', 'JAC Liner', 'Philippine Rabbit', 'Dimple Star'],
    'sea'  => ['OceanJet', '2GO Travel', 'SuperCat', 'Starlite Ferries', 'Roble Shipping', 'Montenegro Lines', 'Trans-Asia Shipping', 'Cokaliong Shipping'],
];
@endphp

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

            {{-- TRANSPORT PROVIDER --}}
            <div class="form-group" style="margin-bottom:1.5rem" id="provider-group">
                <label for="transport_provider">Transport Provider <span style="color:#a0aec0;font-weight:400">(optional)</span></label>
                <div style="display:flex;gap:.5rem;flex-wrap:wrap" id="provider-chips"></div>
                <div style="display:flex;gap:.5rem;margin-top:.5rem">
                    <input type="text" id="transport_provider" name="transport_provider"
                           value="{{ old('transport_provider') }}"
                           placeholder="Select above or type a custom provider…"
                           style="flex:1">
                    <button type="button" id="clear-provider" onclick="clearProvider()"
                            class="btn btn-outline btn-sm" style="white-space:nowrap">✕ Clear</button>
                </div>
                @error('transport_provider')<span class="error">{{ $message }}</span>@enderror
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

<style>
.provider-chip {
    display: inline-flex; align-items: center; gap: .35rem;
    padding: .3rem .75rem; border-radius: 99px;
    border: 1.5px solid #e2e8f0; background: white;
    font-size: .82rem; cursor: pointer; transition: all .15s;
    color: #4a5568;
}
.provider-chip:hover { border-color: #2b6cb0; color: #2b6cb0; background: #ebf8ff; }
.provider-chip.selected { border-color: #2b6cb0; background: #2b6cb0; color: white; }
</style>

<script>
const providers = @json($providers);

function renderChips(mode) {
    const container = document.getElementById('provider-chips');
    const input     = document.getElementById('transport_provider');
    const list      = providers[mode] || [];
    const current   = input.value.trim();

    container.innerHTML = '';
    list.forEach(name => {
        const chip = document.createElement('button');
        chip.type = 'button';
        chip.className = 'provider-chip' + (current === name ? ' selected' : '');
        chip.textContent = name;
        chip.onclick = () => selectProvider(name);
        container.appendChild(chip);
    });
}

function selectProvider(name) {
    const input = document.getElementById('transport_provider');
    if (input.value === name) {
        input.value = '';
    } else {
        input.value = name;
    }
    const mode = document.querySelector('.mode-option:checked')?.value;
    if (mode) renderChips(mode);
}

function clearProvider() {
    document.getElementById('transport_provider').value = '';
    const mode = document.querySelector('.mode-option:checked')?.value;
    if (mode) renderChips(mode);
}

// When typing in the input, deselect chips
document.getElementById('transport_provider').addEventListener('input', function() {
    const mode = document.querySelector('.mode-option:checked')?.value;
    if (mode) renderChips(mode);
});

// When mode changes, re-render chips
document.querySelectorAll('.mode-option').forEach(radio => {
    radio.addEventListener('change', () => renderChips(radio.value));
});

// Init on load
window.addEventListener('DOMContentLoaded', () => {
    const checked = document.querySelector('.mode-option:checked');
    if (checked) renderChips(checked.value);
});
</script>

@endsection
