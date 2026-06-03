@extends('layouts.app')
@section('title', 'Trash')

@section('content')

<div class="page-header">
    <div>
        <h1>Trash</h1>
        <p style="font-size:13px;color:var(--text-muted);margin-top:3px">Deleted records are kept here. Restore or permanently remove them.</p>
    </div>
    @if($travels->total() > 0)
        <form method="POST" action="{{ route('travels.empty-trash') }}"
              onsubmit="return confirm('Permanently delete ALL trashed records? This cannot be undone.')">
            @csrf @method('DELETE')
            <button class="btn btn-danger" style="gap:6px">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/>
                </svg>
                Empty Trash
            </button>
        </form>
    @endif
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
            <a href="{{ route('travels.trash') }}" class="btn btn-outline">Reset</a>
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
                    <th>Purpose / Project</th>
                    <th>Date</th>
                    <th>Pax</th>
                    <th>Amount</th>
                    <th>Deleted</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($travels as $travel)
                <tr>
                    <td style="color:#a0aec0;font-size:.85rem">{{ $travel->id }}</td>
                    <td>
                        <span class="badge badge-{{ $travel->travel_mode }}">
                            {{ $travel->mode_label ?? ucfirst($travel->travel_mode) }}
                        </span>
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
                            <br><span style="font-size:.8rem;color:var(--text-muted)">↩ {{ $travel->return_date->format('M d, Y') }}</span>
                        @endif
                    </td>
                    <td style="text-align:center">{{ $travel->passengers }}</td>
                    <td style="font-weight:600">₱{{ number_format($travel->amount, 0) }}</td>
                    <td style="white-space:nowrap;color:var(--text-muted);font-size:12px">
                        {{ $travel->deleted_at->format('M d, Y') }}<br>
                        <span style="font-size:11px">{{ $travel->deleted_at->diffForHumans() }}</span>
                    </td>
                    <td>
                        <div style="display:flex;gap:.4rem;align-items:center">
                            {{-- Restore --}}
                            <form method="POST" action="{{ route('travels.restore', $travel->id) }}">
                                @csrf
                                <button class="btn btn-outline btn-sm" style="gap:5px">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 12a9 9 0 109-9 9.75 9.75 0 00-6.74 2.74L3 8"/><path d="M3 3v5h5"/>
                                    </svg>
                                    Restore
                                </button>
                            </form>
                            {{-- Permanent delete --}}
                            <form method="POST" action="{{ route('travels.force-delete', $travel->id) }}"
                                  onsubmit="return confirm('Permanently delete this record? It cannot be recovered.')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm" style="gap:5px">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/>
                                    </svg>
                                    Remove
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align:center;padding:3rem;color:var(--text-muted)">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin:0 auto 8px;display:block;opacity:.3">
                            <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/>
                        </svg>
                        Trash is empty.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($travels->hasPages())
        <div class="card-body" style="padding:.75rem 1.5rem;border-top:1px solid var(--card-border)">
            {{ $travels->links() }}
        </div>
    @endif
</div>

@endsection