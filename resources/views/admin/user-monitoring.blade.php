@extends('layouts.app')

@section('title', 'User Monitoring')

@section('content')
<h1 style="margin-bottom: 1.5rem;">User Monitoring</h1>

<div class="card" style="margin-bottom: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: 1rem;">
        <h2 style="margin: 0;">All Technicians</h2>
        <a href="{{ route('admin.technicians.create') }}" class="btn btn-primary">Create New Technician</a>
    </div>
    
    <!-- Search Form -->
    <form method="GET" action="{{ route('admin.users.monitoring') }}" style="display: flex; gap: 0.5rem; align-items: center; margin-bottom: 1rem;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..." style="padding: 0.5rem; border: 1px solid #ddd; border-radius: 0.25rem; min-width: 250px;">
        <button type="submit" class="btn btn-secondary" style="display: inline-flex; align-items: center; justify-content: center; text-align: center;">Search</button>
        @if(request('search'))
            <a href="{{ route('admin.users.monitoring') }}" class="btn btn-secondary" style="display: inline-flex; align-items: center; justify-content: center; text-align: center;">Clear</a>
        @endif
    </form>
    
    @if($technicians->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th style="text-align: center;">Ongoing Reports</th>
                    <th style="text-align: center;">Completed Reports</th>
                    <th style="text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($technicians as $technician)
                <tr>
                    <td>{{ $technician->name }}</td>
                    <td>{{ $technician->email }}</td>
                    <td style="text-align: center;">
                        <span class="badge badge-warning">{{ $technician->ongoing_reports_count }}</span>
                    </td>
                    <td style="text-align: center;">
                        <span class="badge badge-success">{{ $technician->completed_reports_count }}</span>
                    </td>
                    <td style="text-align: center;">
                        <a href="{{ route('admin.technicians.reports', $technician) }}" class="btn btn-primary" style="padding: 0.5rem 1rem;">View Reports</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>
            @if(request('search'))
                No technicians found matching "{{ request('search') }}". 
                <a href="{{ route('admin.users.monitoring') }}">Clear search</a>
            @else
                No technicians found. <a href="{{ route('admin.technicians.create') }}">Create the first technician account</a>
            @endif
        </p>
    @endif
</div>
@endsection

