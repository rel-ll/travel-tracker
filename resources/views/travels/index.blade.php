@extends('layouts.app')
@section('title', 'Travel Records')

@section('content')

{{-- STATS --}}
<div class="stat-grid" style="display:grid !important; grid-template-columns:repeat(auto-fit, minmax(130px,1fr)) !important; gap:12px !important; margin-bottom:20px !important;">
    <div class="stat-card">
        <div class="stat-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 9h18"/><path d="M9 5V3M15 5V3"/>
            </svg>
        </div>
        <div>
            <div class="stat-value">{{ number_format($stats['total']) }}</div>
            <div class="stat-label">Total Trips</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12.5L12 4l7 8.5"/><path d="M3 17h18"/><path d="M7 17l1.5-4.5h7L17 17"/><circle cx="5" cy="19" r="1"/><circle cx="19" cy="19" r="1"/>
            </svg>
        </div>
        <div>
            <div class="stat-value">{{ $stats['air'] }}</div>
            <div class="stat-label">Air</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 14s0-5 9-5 9 5 9 5"/><path d="M3 14v3a2 2 0 002 2h14a2 2 0 002-2v-3"/>
                <path d="M7 19v2M17 19v2"/><path d="M6 9V7a2 2 0 012-2h8a2 2 0 012 2v2"/>
            </svg>
        </div>
        <div>
            <div class="stat-value">{{ $stats['sea'] }}</div>
            <div class="stat-label">Sea</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="7" width="20" height="11" rx="2"/><path d="M7 18v2M17 18v2"/><path d="M2 11h20"/>
                <circle cx="7" cy="14" r="1" fill="currentColor" stroke="none"/>
                <circle cx="12" cy="14" r="1" fill="currentColor" stroke="none"/>
                <circle cx="17" cy="14" r="1" fill="currentColor" stroke="none"/>
                <path d="M6 7V5a2 2 0 012-2h8a2 2 0 012 2v2"/>
            </svg>
        </div>
        <div>
            <div class="stat-value">{{ $stats['land'] }}</div>
            <div class="stat-label">Land</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="9"/><path d="M12 6v6l4 2"/>
                <path d="M14.5 3.5A9 9 0 0120.5 9"/>
            </svg>
        </div>
        <div>
            <div class="stat-value" style="font-size:1.05rem">₱{{ number_format($stats['total_cost'], 0) }}</div>
            <div class="stat-label">Total Cost</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="9" cy="7" r="3"/><circle cx="15" cy="7" r="3"/>
                <path d="M3 20a6 6 0 0112 0"/><path d="M17 14a4 4 0 014 4"/>
            </svg>
        </div>
        <div>
            <div class="stat-value">{{ number_format($stats['total_pax']) }}</div>
            <div class="stat-label">Total Pax</div>
        </div>
    </div>
</div>

{{-- PAGE HEADER --}}
<div class="page-header">
    <h1>Travel Records</h1>
    <a href="{{ route('travels.create') }}" class="btn btn-primary">+ New Travel</a>
</div>

{{-- FILTERS --}}
<div class="card" style="margin-bottom:1.2rem">
    <div class="card-body" style="padding:1rem 1.5rem">
        <form method="GET" class="filter-bar">
            <div>
                <label>Mode</label>
                <select name="mode">
                    <option value="">All Modes</option>
                    <option value="air"  {{ request('mode')=='air'  ? 'selected':'' }}>Air</option>
                    <option value="sea"  {{ request('mode')=='sea'  ? 'selected':'' }}>Sea</option>
                    <option value="land" {{ request('mode')=='land' ? 'selected':'' }}>Land</option>
                </select>
            </div>
            <div>
                <label>Purpose / Project</label>
                <input type="text" name="purpose" value="{{ request('purpose') }}" placeholder="Search purpose…">
            </div>
            <div>
                <label>From Date</label>
                <input type="date" name="from" value="{{ request('from') }}">
            </div>
            <div>
                <label>To Date</label>
                <input type="date" name="to" value="{{ request('to') }}">
            </div>
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('travels.index') }}" class="btn btn-outline">Reset</a>
        </form>
    </div>
</div>

{{-- TABLE --}}
<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Mode</th>
                    <th>Route</th>
                    <th>Transport Provider</th>
                    <th>Purpose / Project</th>
                    <th>Date</th>
                    <th>Pax</th>
                    <th>Amount</th>
                    <th>Itinerary</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($travels as $travel)
                <tr>
                    <td style="color:#a0aec0;font-size:.85rem">{{ $travel->id }}</td>
                    <td>
                        <span class="badge badge-{{ $travel->travel_mode }}">
                            {{ $travel->mode_icon }} {{ $travel->mode_label }}
                        </span>
                    </td>
                    <td>
                        <strong>{{ $travel->origin }}</strong>
                        <span style="color:#a0aec0;padding:0 .4rem">→</span>
                        <strong>{{ $travel->destination }}</strong>
                    </td>
                    <td>
                        <strong>{{ $travel->transport_provider }}</strong>
                        <span style="color:#a0aec0;padding:0 .4rem"></span>
                    </td>
                    <td>{{ $travel->purpose }}</td>
                    <td style="white-space:nowrap">
                        {{ $travel->travel_date->format('M d, Y') }}
                        @if($travel->return_date)
                            <br><span style="font-size:.8rem;color:#718096">↩ {{ $travel->return_date->format('M d, Y') }}</span>
                        @endif
                    </td>
                    <td style="text-align:center">{{ $travel->passengers }}</td>
                    <td style="font-weight:600;color:#2b6cb0">{{ $travel->formatted_amount }}</td>
                    <td style="text-align:center">
                        @if($travel->itinerary_path)
                            <a href="{{ Storage::url($travel->itinerary_path) }}" target="_blank"
                               class="btn btn-outline btn-sm" title="Download itinerary">📎</a>
                        @else
                            <span style="color:#cbd5e0;font-size:.85rem">—</span>
                        @endif
                    </td>
                    <td>
<div style="display:flex;gap:.4rem;align-items:center">
    <a href="{{ route('travels.show', $travel) }}" class="btn btn-outline btn-sm">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
        </svg>
        View
    </a>
    @if(auth()->user()->isAdmin())
        <a href="{{ route('travels.edit', $travel) }}" class="btn btn-warning btn-sm">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
            </svg>
            Edit
        </a>
        <form method="POST" action="{{ route('travels.destroy', $travel) }}"
              onsubmit="return confirm('Delete this record?')">
            @csrf @method('DELETE')
            <button class="btn btn-danger btn-sm">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/>
                </svg>
                Del
            </button>
        </form>
    @endif
</div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align:center;padding:3rem;color:#a0aec0">
                        No travel records yet. <a href="{{ route('travels.create') }}">Add one now →</a>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($travels->hasPages())
        <div class="card-body" style="padding:.75rem 1.5rem;border-top:1px solid #edf2f7">
            {{ $travels->links() }}
        </div>
    @endif
</div>

@endsection