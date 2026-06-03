@extends('layouts.app')
@section('title', 'Edit User')

@section('content')

<div class="page-header">
    <h1>✏️ Edit User — {{ $user->name }}</h1>
    <a href="{{ route('users.index') }}" class="btn btn-outline">← Back</a>
</div>

<div class="card">
    <div class="card-header"><h2>Update Details</h2></div>
    <div class="card-body">
        <form method="POST" action="{{ route('users.update', $user) }}">
            @csrf @method('PUT')
            <div class="form-grid">
                <div class="form-group">
                    <label>Full Name <span class="req">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name')<span class="error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label>Email Address <span class="req">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email')<span class="error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label>Role <span class="req">*</span></label>
                    <select name="role" required>
                        <option value="staff" {{ old('role', $user->role)=='staff' ? 'selected':'' }}>Staff</option>
                        <option value="admin" {{ old('role', $user->role)=='admin' ? 'selected':'' }}>Admin</option>
                    </select>
                    @error('role')<span class="error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label>New Password <span style="color:#a0aec0;font-weight:400">(leave blank to keep current)</span></label>
                    <input type="password" name="password">
                    @error('password')<span class="error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label>Confirm New Password</label>
                    <input type="password" name="password_confirmation">
                </div>
            </div>
            <div style="display:flex;gap:1rem;margin-top:1.5rem;justify-content:flex-end">
                <a href="{{ route('users.index') }}" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-success">💾 Update User</button>
            </div>
        </form>
    </div>
</div>

@endsection
