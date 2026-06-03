@extends('layouts.app')
@section('title', 'New User')

@section('content')

<div class="page-header">
    <h1>👤 New User</h1>
    <a href="{{ route('users.index') }}" class="btn btn-outline">← Back</a>
</div>

<div class="card">
    <div class="card-header"><h2>User Details</h2></div>
    <div class="card-body">
        <form method="POST" action="{{ route('users.store') }}">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label>Full Name <span class="req">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required>
                    @error('name')<span class="error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label>Email Address <span class="req">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required>
                    @error('email')<span class="error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label>Role <span class="req">*</span></label>
                    <select name="role" required>
                        <option value="staff"  {{ old('role')=='staff'  ? 'selected':'' }}>Staff</option>
                        <option value="admin"  {{ old('role')=='admin'  ? 'selected':'' }}>Admin</option>
                    </select>
                    @error('role')<span class="error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label>Password <span class="req">*</span></label>
                    <input type="password" name="password" required>
                    @error('password')<span class="error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label>Confirm Password <span class="req">*</span></label>
                    <input type="password" name="password_confirmation" required>
                </div>
            </div>
            <div style="display:flex;gap:1rem;margin-top:1.5rem;justify-content:flex-end">
                <a href="{{ route('users.index') }}" class="btn btn-outline">Cancel</a>
                <button type="submit" class="btn btn-success">💾 Create User</button>
            </div>
        </form>
    </div>
</div>

@endsection
