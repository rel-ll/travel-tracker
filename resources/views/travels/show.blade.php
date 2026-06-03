@extends('layouts.app')
@section('title', 'Travel #' . $travel->id)

@section('content')

{{-- PRINT STYLES --}}
<style>
@media print {
    nav, .page-header, .no-print { display: none !important; }
    body { background: white !important; font-family: Arial, sans-serif; }
    .container { padding: 0 !important; max-width: 100% !important; }
    .card { box-shadow: none !important; border: 1px solid #ddd !important; }
    .card-body { padding: 1.5rem !important; }
    .route-display { background: #f0f0f0 !important; border: 1px solid #ddd !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .badge { border: 1px solid #ddd !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .detail-label { color: #666 !important; font-size: 9pt !important; }
    .detail-value { font-size: 11pt !important; }
    .detail-grid { grid-template-columns: repeat(3, 1fr) !important; gap: .75rem !important; }
    .print-header { display: block !important; }
    .itinerary-preview { display: none !important; }
    a { text-decoration: none !important; color: inherit !important; }
}
@media screen {
    .print-header { display: none; }
}
</style>

{{-- PRINT HEADER (only visible when printing) --}}
<div class="print-header" style="margin-bottom:1.5rem;padding-bottom:1rem;border-bottom:2px solid #1a365d;">
    <div style="display:flex;justify-content:space-between;align-items:center">
        <div>
            <h1 style="font-size:16pt;color:#1a365d;margin:0">Travel Record #{{ $travel->id }}</h1>
            <p style="font-size:9pt;color:#666;margin:.25rem 0 0">Printed: {{ now()->format('F d, Y h:i A') }}</p>
        </div>
        <div style="font-size:10pt;color:#666;text-align:right">
            🗺️ Travel Tracker
        </div>
    </div>
</div>

<div class="page-header no-print">
    <h1>Travel Record #{{ $travel->id }}</h1>
    <div style="display:flex;gap:.75rem;flex-wrap:wrap">
        @if(auth()->user()->isAdmin())
            <a href="{{ route('travels.edit', $travel) }}" class="btn btn-warning">Edit</a>
            <form method="POST" action="{{ route('travels.destroy', $travel) }}"
                  onsubmit="return confirm('Delete this record permanently?')">
                @csrf @method('DELETE')
                <button class="btn btn-danger">Delete</button>
            </form>
        @endif
        <button onclick="window.print()" class="btn btn-primary">🖨 Print / Save PDF</button>
        <a href="{{ route('travels.index') }}" class="btn btn-outline">Back to List</a>
    </div>
</div>

<div class="card">
    <div class="card-body">

        {{-- ROUTE BANNER --}}
        <div class="route-display">
            <span>{{ $travel->mode_icon }}</span>
            <span>{{ $travel->origin }}</span>
            <span class="route-arrow">→</span>
            <span>{{ $travel->destination }}</span>
            <span class="badge badge-{{ $travel->travel_mode }}" style="margin-left:auto">
                {{ $travel->mode_label }}
            </span>
        </div>

        <div class="detail-grid">
            <div class="detail-item">
                <div class="detail-label">Purpose / Project</div>
                <div class="detail-value">{{ $travel->purpose }}</div>
            </div>
            @if($travel->transport_provider)
            <div class="detail-item">
                <div class="detail-label">Transport Provider</div>
                <div class="detail-value">{{ $travel->transport_provider }}</div>
            </div>
            @endif
            <div class="detail-item">
                <div class="detail-label">Travel Date</div>
                <div class="detail-value">{{ $travel->travel_date->format('F d, Y') }}</div>
            </div>
            @if($travel->return_date)
            <div class="detail-item">
                <div class="detail-label">Return Date</div>
                <div class="detail-value">{{ $travel->return_date->format('F d, Y') }}</div>
            </div>
            @endif
            <div class="detail-item">
                <div class="detail-label">Passengers</div>
                <div class="detail-value">{{ $travel->passengers }} person(s)</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Total Amount</div>
                <div class="detail-value" style="color:#2b6cb0;font-size:1.25rem">
                    {{ $travel->formatted_amount }}
                </div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Itinerary File</div>
                <div class="detail-value">
                    @if($travel->itinerary_path)
                        @php
                            $fileUrl  = Storage::url($travel->itinerary_path);
                            $fileName = basename($travel->itinerary_path);
                            $ext      = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                        @endphp
                        <span class="no-print">
                            <a href="{{ $fileUrl }}" download="{{ $fileName }}" class="btn btn-outline btn-sm">
                                Download
                            </a>
                        </span>
                        <div style="font-size:.78rem;color:#a0aec0;margin-top:.35rem">{{ $fileName }}</div>
                    @else
                        <span style="color:#a0aec0">No file uploaded</span>
                    @endif
                </div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Created</div>
                <div class="detail-value" style="font-size:.9rem;font-weight:400;color:#718096">
                    {{ $travel->created_at->format('M d, Y H:i') }}
                </div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Created By</div>
                <div class="detail-value" style="font-size:.9rem">
                    {{ auth()->user()->name }}
                </div>
            </div>
        </div>

        {{-- NOTES --}}
        @if($travel->notes)
            <div style="margin-top:1.5rem;padding:1rem;background:#f7fafc;border-radius:8px;border-left:3px solid #cbd5e0">
                <div class="detail-label" style="margin-bottom:.5rem">Notes</div>
                <p style="color:#4a5568;line-height:1.6">{{ $travel->notes }}</p>
            </div>
        @endif

        {{-- INLINE FILE VIEWER (screen only) --}}
        @if($travel->itinerary_path)
            @php
                $fileUrl  = Storage::url($travel->itinerary_path);
                $fileName = basename($travel->itinerary_path);
                $ext      = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $isImage  = in_array($ext, ['jpg','jpeg','png','gif','webp']);
                $isPdf    = $ext === 'pdf';
            @endphp

            @if($isImage || $isPdf)
                <div style="margin-top:1.5rem" class="itinerary-preview no-print">
                    <div class="detail-label" style="margin-bottom:.75rem">Itinerary Preview</div>
                    <div style="border:1.5px solid #e2e8f0;border-radius:10px;overflow:hidden;">
                        @if($isPdf)
                            <iframe src="{{ $fileUrl }}"
                                    style="width:100%;height:700px;border:none;display:block;"
                                    title="Itinerary PDF">
                                <p style="padding:1rem">
                                    <a href="{{ $fileUrl }}" target="_blank">Open PDF in new tab</a>
                                </p>
                            </iframe>
                        @elseif($isImage)
                            <div style="text-align:center;padding:1rem;background:#1a202c;">
                                <img src="{{ $fileUrl }}"
                                     alt="Itinerary"
                                     style="max-width:100%;max-height:700px;object-fit:contain;border-radius:6px;">
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        @endif

    </div>
</div>

@endsection
