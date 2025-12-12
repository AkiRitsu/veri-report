@extends('layouts.app')

@section('title', 'Create Technician Account')

@section('styles')
<style>
    .form-action-btn {
        width: 140px;
        height: 40px;
        padding: 0;
        font-size: 0.875rem;
        text-align: center;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
    }
</style>
@endsection

@section('content')
<h1 style="margin-bottom: 1.5rem;">Create Technician Account</h1>

<div class="card">
    <form method="POST" action="{{ route('admin.technicians.store') }}">
        @csrf

        <div class="form-group">
            <label for="name" class="form-label">Name *</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
            @error('name')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="email" class="form-label">Email *</label>
            <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
            @error('email')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Password *</label>
            <input type="password" id="password" name="password" class="form-control" required>
            @error('password')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation" class="form-label">Confirm Password *</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
        </div>

        <div class="form-group" style="display: flex; gap: 0.5rem; align-items: center;">
            <button type="submit" class="btn btn-primary form-action-btn">Create</button>
            <a href="{{ route('admin.users.monitoring') }}" class="btn btn-secondary form-action-btn">Cancel</a>
        </div>
    </form>
</div>
@endsection

