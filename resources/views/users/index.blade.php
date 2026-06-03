@extends('layouts.app')
@section('title', 'Manage Users')

@section('content')

<div class="page-header">
    <h1>👥 User Management</h1>
    <a href="{{ route('users.create') }}" class="btn btn-primary">+ New User</a>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($users as $user)
                <tr>
                    <td style="color:#a0aec0;font-size:.85rem">{{ $user->id }}</td>
                    <td>
                        <strong>{{ $user->name }}</strong>
                        @if($user->id === auth()->id())
                            <span style="font-size:.75rem;color:#a0aec0;margin-left:.4rem">(you)</span>
                        @endif
                    </td>
                    <td style="color:#718096">{{ $user->email }}</td>
                    <td>
                        @if($user->isAdmin())
                            <span class="badge" style="background:#fefcbf;color:#744210">⭐ Admin</span>
                        @else
                            <span class="badge" style="background:#e2e8f0;color:#4a5568">Staff</span>
                        @endif
                    </td>
                    <td style="color:#718096;font-size:.85rem">{{ $user->created_at->format('M d, Y') }}</td>
                    <td>
                        <div style="display:flex;gap:.4rem">
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-sm">Edit</a>
                            @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('users.destroy', $user) }}"
                                      onsubmit="return confirm('Delete {{ $user->name }}?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:3rem;color:#a0aec0">
                        No users yet. <a href="{{ route('users.create') }}">Add one →</a>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
