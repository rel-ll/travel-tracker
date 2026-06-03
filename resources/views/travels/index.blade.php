@extends('layouts.app')
@section('title', 'Travel Records')

@section('content')

{{-- STATS --}}
<div class="stats-row">
    <div class="stat-card">
        <div class="stat-icon">📋</div>
        <div class="stat-value">{{ number_format($stats['total']) }}</div>
        <div class="stat-label">Total Trips</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">✈️</div>
        <div class="stat-value">{{ $stats['air'] }}</div>
        <div class="stat-label">Air</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">🚢</div>
        <div class="stat-value">{{ $stats['sea'] }}</div>
        <div class="stat-label">Sea</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">🚌</div>
        <div class="stat-value">{{ $stats['land'] }}</div>
        <div class="stat-label">Land</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">💰</div>
        <div class="stat-value" style="font-size:1.1rem">₱{{ number_format($stats['total_cost'], 0) }}</div>
        <div class="stat-label">Total Cost</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">👥</div>
        <div class="stat-value">{{ number_format($stats['total_pax']) }}</div>
        <div class="stat-label">Total Pax</div>
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
                <label style="font-size:.8rem;color:#718096;display:block;margin-bottom:.3rem">Mode</label>
                <select name="mode">
                    <option value="">All Modes</option>
                    <option value="air"  {{ request('mode')=='air'  ? 'selected':'' }}>✈️ Air</option>
                    <option value="sea"  {{ request('mode')=='sea'  ? 'selected':'' }}>🚢 Sea</option>
                    <option value="land" {{ request('mode')=='land' ? 'selected':'' }}>🚌 Land</option>
                </select>
            </div>
            <div>
                <label style="font-size:.8rem;color:#718096;display:block;margin-bottom:.3rem">Purpose / Project</label>
                <input type="text" name="purpose" value="{{ request('purpose') }}" placeholder="Search purpose…">
            </div>
            <div>
                <label style="font-size:.8rem;color:#718096;display:block;margin-bottom:.3rem">From Date</label>
                <input type="date" name="from" value="{{ request('from') }}">
            </div>
            <div>
                <label style="font-size:.8rem;color:#718096;display:block;margin-bottom:.3rem">To Date</label>
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
                    <th>Provider</th>
                    <th>Route</th>
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
                    <td style="font-size:.88rem;color:#4a5568">
                        {{ $travel->transport_provider ?? '—' }}
                    </td>
                    <td>
                        <strong>{{ $travel->origin }}</strong>
                        <span style="color:#a0aec0;padding:0 .4rem">→</span>
                        <strong>{{ $travel->destination }}</strong>
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
                        <div style="display:flex;gap:.4rem">
                            <a href="{{ route('travels.show', $travel) }}" class="btn btn-outline btn-sm">View</a>
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('travels.edit', $travel) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form method="POST" action="{{ route('travels.destroy', $travel) }}"
                                      onsubmit="return confirm('Delete this record?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Del</button>
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
