@extends('layouts.app')

@section('title', 'Sign In')

@section('content')
<div style="width:100%; max-width:380px; padding:0 16px">
    <div style="text-align:center; margin-bottom:28px">
        <div style="width:40px;height:40px;background:#2563eb;border-radius:10px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px">
            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/>
            </svg>
        </div>
        <div style="font-size:20px;font-weight:600;color:#0f1117;letter-spacing:-.02em">Travel Tracker</div>
        <div style="font-size:13px;color:#6b7280;margin-top:4px">Sign in to your account</div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-error" style="margin-bottom:16px">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="/login" style="display:flex;flex-direction:column;gap:14px">
                @csrf
                <div class="form-group">
                    <label>Email address</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required autofocus>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>
                <button type="submit" class="topbar-btn btn-primary" style="justify-content:center;margin-top:4px;padding:9px 16px;font-size:13.5px">
                    Sign in
                </button>
            </form>
        </div>
    </div>

    <p style="text-align:center;font-size:11.5px;color:#9ca3af;margin-top:16px">
        Travel Management System &mdash; Internal Use Only
    </p>
</div>
@endsection
