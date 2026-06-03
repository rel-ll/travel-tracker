@extends('layouts.app')

@section('title', 'Travel Record — ' . $travel->origin . ' → ' . $travel->destination)

@section('content')
<style>
    .detail-grid {
        display: grid !important;
        grid-template-columns: repeat(3, 1fr) !important;
        gap: 1rem !important;
    }
    @media (max-width: 640px) {
        .detail-grid { grid-template-columns: repeat(2, 1fr) !important; }
    }
    .detail-card {
        background: var(--content-bg, #f8f9fa);
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 1rem;
    }
    .detail-label { font-size: .75rem; text-transform: uppercase; color: #6c757d; letter-spacing: .05em; }
    .detail-value { font-size: 1.05rem; font-weight: 600; margin-top: .2rem; }
    .badge-mode { display: inline-block; padding: .3rem .8rem; border-radius: 20px; font-size: .85rem; font-weight: 600; }
    .badge-air  { background: #dbeafe; color: #1d4ed8; }
    .badge-sea  { background: #d1fae5; color: #065f46; }
    .badge-land { background: #fef3c7; color: #92400e; }
    .btn { padding: .5rem 1rem; border-radius: 6px; border: none; cursor: pointer; font-size: .9rem; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; gap: .4rem; }
    .btn-primary   { background: #2563eb; color: #fff; }
    .btn-secondary { background: #6b7280; color: #fff; }
    .btn-success   { background: #059669; color: #fff; }
    .btn-danger    { background: #dc2626; color: #fff; }
    .btn-outline   { background: transparent; border: 1px solid #d1d5db; color: #374151; }
    .btn:hover { opacity: .88; }
    .share-box { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 1rem 1.2rem; margin-top: 1rem; }
    .share-box input { width: 100%; padding: .4rem .7rem; border: 1px solid #d1d5db; border-radius: 5px; font-size: .85rem; background: #fff; }
    .share-box .expires { font-size: .78rem; color: #6b7280; margin-top: .3rem; }
    .share-warning { background: #fef9c3; border: 1px solid #fde047; border-radius: 8px; padding: .7rem 1rem; font-size: .85rem; color: #713f12; margin-top: 1rem; }
    .file-preview { margin-top: 1.5rem; }
    .file-preview iframe { width: 100%; height: 700px; border: 1px solid #e5e7eb; border-radius: 8px; }
    .file-preview img { max-width: 100%; border-radius: 8px; background: #1f2937; padding: .5rem; }
    @media print {
        .no-print { display: none !important; }
        .detail-grid { grid-template-columns: repeat(3,1fr); }
        .print-header { display: block !important; margin-bottom: 1.5rem; }
    }
    .print-header { display: none; }
</style>

<div class="print-header">
    <h2>Travel Record: {{ $travel->origin }} → {{ $travel->destination }}</h2>
    <p style="color:#6b7280;font-size:.85rem">Printed on {{ now()->format('F d, Y h:i A') }}</p>
    <hr>
</div>

<div class="no-print" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; flex-wrap:wrap; gap:.5rem">
    <a href="{{ route('travels.index') }}" class="btn btn-outline">← Back</a>
    <div style="display:flex; gap:.5rem; flex-wrap:wrap; align-items:center">
        @if(auth()->user()->role === 'admin')
            {{-- Share button --}}
            <button class="btn btn-success" onclick="document.getElementById('share-section').style.display='block'; this.style.display='none'">
                🔗 Share
            </button>
            <a href="{{ route('travels.edit', $travel) }}" class="btn btn-primary">✏️ Edit</a>
            <form method="POST" action="{{ route('travels.destroy', $travel) }}" onsubmit="return confirm('Delete this record?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger">🗑 Delete</button>
            </form>
        @endif
        <button class="btn btn-secondary" onclick="window.print()">🖨 Print / Save PDF</button>
    </div>
</div>

{{-- ── Share section (admin only) ──────────────────────────────── --}}
@if(auth()->user()->role === 'admin')
<div id="share-section" style="display:{{ $travel->share_active ? 'block' : 'none' }}; margin-bottom:1.5rem" class="no-print">
    @if($travel->share_active)
        <div class="share-box">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:.6rem">
                <strong style="color:#065f46">🔗 Active Share Link</strong>
                <form method="POST" action="{{ route('travel.share.revoke', $travel) }}">
                    @csrf
                    <button type="submit" class="btn btn-danger" style="font-size:.8rem; padding:.3rem .7rem">Revoke</button>
                </form>
            </div>
            <input type="text" id="share-url-input" value="{{ route('travel.share.view', $travel->share_token) }}" readonly onclick="this.select()">
            <div style="display:flex; justify-content:space-between; align-items:center">
                <span class="expires">⏱ Expires: {{ $travel->share_expires_at->format('M d, Y h:i A') }}</span>
                <button class="btn btn-outline" style="font-size:.78rem; padding:.2rem .6rem; margin-top:.3rem" onclick="copyShareUrl()">📋 Copy</button>
            </div>
        </div>
    @else
        <div class="share-warning">
            <strong>No active share link.</strong>
            <p style="margin:.3rem 0 .6rem">Generate a link that expires in <strong>24 hours</strong>. Anyone with the link can view this record without logging in.</p>
            <button class="btn btn-success" onclick="generateShareLink({{ $travel->id }})">🔗 Generate Share Link</button>
        </div>
        <div id="new-share-box" style="display:none" class="share-box">
            <strong style="color:#065f46">✅ Link generated!</strong>
            <input type="text" id="new-share-url" readonly onclick="this.select()" style="margin-top:.5rem">
            <div style="display:flex; justify-content:space-between; align-items:center">
                <span class="expires" id="new-share-expires"></span>
                <button class="btn btn-outline" style="font-size:.78rem; padding:.2rem .6rem; margin-top:.3rem" onclick="copyNewUrl()">📋 Copy</button>
            </div>
        </div>
    @endif
</div>
@endif

@if(session('success'))
    <div style="background:#d1fae5; border:1px solid #6ee7b7; padding:.8rem 1rem; border-radius:8px; margin-bottom:1rem; color:#065f46">
        ✅ {{ session('success') }}
    </div>
@endif

{{-- ── Main detail card ────────────────────────────────────────── --}}
<div style="background:#fff; border:1px solid #e5e7eb; border-radius:10px; padding:1.5rem">

    <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:1.5rem; flex-wrap:wrap; gap:.5rem">
        <div>
            <h2 style="margin:0; font-size:1.4rem">
                {{ $travel->mode_icon }} {{ $travel->origin }} → {{ $travel->destination }}
            </h2>
            <div style="color:#6b7280; font-size:.9rem; margin-top:.3rem">{{ $travel->purpose }}</div>
        </div>
        <span class="badge-mode badge-{{ $travel->travel_mode }}">
            {{ ucfirst($travel->travel_mode) }}
        </span>
    </div>

    <div class="detail-grid">
        <div class="detail-card">
            <div class="detail-label">Travel Date</div>
            <div class="detail-value">{{ $travel->travel_date->format('M d, Y') }}</div>
        </div>
        <div class="detail-card">
            <div class="detail-label">Return Date</div>
            <div class="detail-value">{{ $travel->return_date ? $travel->return_date->format('M d, Y') : '—' }}</div>
        </div>
        <div class="detail-card">
            <div class="detail-label">Duration</div>
            <div class="detail-value">
                @if($travel->return_date)
                    {{ $travel->travel_date->diffInDays($travel->return_date) + 1 }} days
                @else
                    —
                @endif
            </div>
        </div>
        <div class="detail-card">
            <div class="detail-label">Passengers</div>
            <div class="detail-value">{{ $travel->passengers }} pax</div>
        </div>
        <div class="detail-card">
            <div class="detail-label">Total Amount</div>
            <div class="detail-value">₱{{ number_format($travel->amount, 2) }}</div>
        </div>
        <div class="detail-card">
            <div class="detail-label">Cost per Pax</div>
            <div class="detail-value">
                @if($travel->passengers > 0)
                    ₱{{ number_format($travel->amount / $travel->passengers, 2) }}
                @else
                    —
                @endif
            </div>
        </div>
    </div>

    @if($travel->notes)
    <div style="margin-top:1.5rem">
        <div class="detail-label" style="margin-bottom:.4rem">Notes</div>
        <div style="background:#f8f9fa; border-radius:8px; padding:1rem; line-height:1.6">{{ $travel->notes }}</div>
    </div>
    @endif

    {{-- ── Inline file preview ─────────────────────────────── --}}
    @if($travel->itinerary_path)
    @php
        $fileUrl  = Storage::url($travel->itinerary_path);
        $ext      = strtolower(pathinfo($travel->itinerary_path, PATHINFO_EXTENSION));
        $isPdf    = $ext === 'pdf';
        $isImage  = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
    @endphp
    <div class="file-preview">
        <div class="detail-label" style="margin-bottom:.8rem">Itinerary</div>
        @if($isPdf)
            <iframe src="{{ $fileUrl }}" title="Itinerary PDF"></iframe>
        @elseif($isImage)
            <img src="{{ $fileUrl }}" alt="Itinerary">
        @else
            <a href="{{ $fileUrl }}" download class="btn btn-outline">⬇ Download Itinerary</a>
        @endif
    </div>
    @endif

</div>

<script>
function generateShareLink(travelId) {
    // Try meta tag first, then fall back to XSRF-TOKEN cookie
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    let csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : null;

    if (!csrfToken) {
        const match = document.cookie.split(';')
            .map(c => c.trim())
            .find(c => c.startsWith('XSRF-TOKEN='));
        csrfToken = match ? decodeURIComponent(match.split('=')[1]) : '';
    }

    console.log('CSRF token:', csrfToken); // remove after fix confirmed

    fetch('/travels/' + travelId + '/share/generate', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        credentials: 'same-origin',
    })
    .then(r => {
        if (!r.ok) return r.text().then(t => { throw new Error(t); });
        return r.json();
    })
    .then(data => {
        document.getElementById('new-share-url').value = data.url;
        document.getElementById('new-share-expires').textContent = '⏱ Expires: ' + data.expires_at;
        document.getElementById('new-share-box').style.display = 'block';
    })
    .catch(err => {
        console.error('Share error:', err);
        alert('Failed to generate link: ' + err.message);
    });
}

function copyShareUrl() {
    const el = document.getElementById('share-url-input');
    el.select(); document.execCommand('copy');
    alert('Link copied!');
}

function copyNewUrl() {
    const el = document.getElementById('new-share-url');
    el.select(); document.execCommand('copy');
    alert('Link copied!');
}
</script>
@endsection