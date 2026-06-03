<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Record — {{ $travel->origin }} → {{ $travel->destination }}</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: #f1f5f9;
            color: #1e293b;
            min-height: 100vh;
        }

        /* ── Top banner ── */
        .banner {
            background: linear-gradient(135deg, #1e40af 0%, #0369a1 100%);
            color: #fff;
            padding: 1.2rem 2rem;
            display: flex;
            align-items: center;
            gap: .8rem;
        }
        .banner .logo { font-size: 1.5rem; }
        .banner h1 { font-size: 1rem; font-weight: 600; letter-spacing: .01em; }
        .banner p  { font-size: .78rem; opacity: .8; margin-top: .1rem; }
        .banner .expires-badge {
            margin-left: auto;
            background: rgba(255,255,255,.18);
            border: 1px solid rgba(255,255,255,.3);
            padding: .3rem .8rem;
            border-radius: 20px;
            font-size: .78rem;
            white-space: nowrap;
        }

        /* ── Main container ── */
        .container {
            max-width: 860px;
            margin: 2rem auto;
            padding: 0 1rem 4rem;
        }

        /* ── Hero route block ── */
        .hero {
            background: #fff;
            border-radius: 14px;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,.07), 0 4px 20px rgba(0,0,0,.05);
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .mode-pill {
            display: inline-block;
            padding: .3rem 1rem;
            border-radius: 20px;
            font-size: .82rem;
            font-weight: 700;
            letter-spacing: .05em;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }
        .mode-air  { background: #dbeafe; color: #1d4ed8; }
        .mode-sea  { background: #d1fae5; color: #065f46; }
        .mode-land { background: #fef3c7; color: #92400e; }

        .route {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            margin: .8rem 0;
        }
        .route .city { font-size: 1.7rem; font-weight: 800; }
        .route .arrow { font-size: 1.5rem; color: #94a3b8; }
        .hero .purpose { color: #64748b; font-size: 1rem; margin-top: .4rem; }

        /* ── Stats row ── */
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .stat-card {
            background: #fff;
            border-radius: 10px;
            padding: 1.1rem 1.2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,.06);
            text-align: center;
        }
        .stat-icon { font-size: 1.4rem; margin-bottom: .3rem; }
        .stat-label { font-size: .72rem; text-transform: uppercase; color: #94a3b8; letter-spacing: .06em; }
        .stat-value { font-size: 1.2rem; font-weight: 700; margin-top: .15rem; }

        /* ── Info card ── */
        .card {
            background: #fff;
            border-radius: 10px;
            padding: 1.4rem;
            box-shadow: 0 1px 3px rgba(0,0,0,.06);
            margin-bottom: 1.5rem;
        }
        .card h3 {
            font-size: .8rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #94a3b8;
            margin-bottom: 1rem;
            padding-bottom: .6rem;
            border-bottom: 1px solid #f1f5f9;
        }
        .notes-text { line-height: 1.7; color: #334155; }

        /* ── Itinerary ── */
        .itinerary-frame { width: 100%; height: 680px; border: none; border-radius: 8px; display: block; }
        .itinerary-img   { max-width: 100%; border-radius: 8px; display: block; }

        /* ── Footer ── */
        .guest-footer {
            text-align: center;
            color: #94a3b8;
            font-size: .78rem;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e2e8f0;
        }
        .guest-footer strong { color: #64748b; }

        /* ── Print ── */
        @media print {
            .banner .expires-badge { display: none; }
            .itinerary-frame { height: 400px; }
            body { background: #fff; }
        }

        @media (max-width: 600px) {
            .route .city { font-size: 1.2rem; }
            .hero { padding: 1.4rem; }
        }
    </style>
</head>
<body>

<div class="banner">
    <div class="logo">✈️</div>
    <div>
        <h1>Travel Tracker</h1>
        <p>Shared travel record — read only</p>
    </div>
    <div class="expires-badge">
        ⏱ Expires {{ $travel->share_expires_at->diffForHumans() }}
    </div>
</div>

<div class="container">

    {{-- ── Hero ── --}}
    <div class="hero">
        <span class="mode-pill mode-{{ $travel->travel_mode }}">
            @if($travel->travel_mode === 'air') ✈️ Air
            @elseif($travel->travel_mode === 'sea') 🚢 Sea
            @else 🚌 Land @endif
        </span>
        <div class="route">
            <span class="city">{{ $travel->origin }}</span>
            <span class="arrow">→</span>
            <span class="city">{{ $travel->destination }}</span>
        </div>
        <div class="purpose">{{ $travel->purpose }}</div>
    </div>

    {{-- ── Stats ── --}}
    <div class="stats">
        <div class="stat-card">
            <div class="stat-icon">📅</div>
            <div class="stat-label">Travel Date</div>
            <div class="stat-value">{{ $travel->travel_date->format('M d, Y') }}</div>
        </div>
        @if($travel->return_date)
        <div class="stat-card">
            <div class="stat-icon">🔁</div>
            <div class="stat-label">Return Date</div>
            <div class="stat-value">{{ $travel->return_date->format('M d, Y') }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🌙</div>
            <div class="stat-label">Duration</div>
            <div class="stat-value">{{ $travel->travel_date->diffInDays($travel->return_date) + 1 }} days</div>
        </div>
        @endif
        <div class="stat-card">
            <div class="stat-icon">👥</div>
            <div class="stat-label">Passengers</div>
            <div class="stat-value">{{ $travel->passengers }} pax</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">💰</div>
            <div class="stat-label">Total Amount</div>
            <div class="stat-value">₱{{ number_format($travel->amount, 2) }}</div>
        </div>
        @if($travel->passengers > 0)
        <div class="stat-card">
            <div class="stat-icon">🧾</div>
            <div class="stat-label">Per Pax</div>
            <div class="stat-value">₱{{ number_format($travel->amount / $travel->passengers, 2) }}</div>
        </div>
        @endif
    </div>

    {{-- ── Notes ── --}}
    @if($travel->notes)
    <div class="card">
        <h3>Notes</h3>
        <p class="notes-text">{{ $travel->notes }}</p>
    </div>
    @endif

    {{-- ── Itinerary preview ── --}}
    @if($travel->itinerary_path)
    @php
        $fileUrl = Storage::url($travel->itinerary_path);
        $ext     = strtolower(pathinfo($travel->itinerary_path, PATHINFO_EXTENSION));
    @endphp
    <div class="card">
        <h3>Itinerary</h3>
        @if($ext === 'pdf')
            <iframe class="itinerary-frame" src="{{ $fileUrl }}" title="Itinerary"></iframe>
        @elseif(in_array($ext, ['jpg','jpeg','png','gif','webp']))
            <img class="itinerary-img" src="{{ $fileUrl }}" alt="Itinerary">
        @else
            <a href="{{ $fileUrl }}" download
               style="display:inline-block; padding:.6rem 1.2rem; background:#2563eb; color:#fff; border-radius:6px; text-decoration:none; font-size:.9rem">
                ⬇ Download Itinerary
            </a>
        @endif
    </div>
    @endif

    <div class="guest-footer">
        <p>This link was shared from <strong>Travel Tracker</strong> and expires {{ $travel->share_expires_at->format('F d, Y \a\t h:i A') }}.</p>
        <p style="margin-top:.4rem">Content is read-only. Login required to make changes.</p>
    </div>

</div>
</body>
</html>
