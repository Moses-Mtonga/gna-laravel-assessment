@extends('layouts.dashboard')

@section('content')
<div class="container mt-4">
    <h4>Activate/Deactivate Modules</h4>

    <form action="{{ route('modules.update') }}" method="POST">
        @csrf
        <div class="form-check">
            @foreach($moduleStatuses as $module => $status)
                <div class="mb-3">
                    <input class="form-check-input" type="checkbox" name="modules[]" value="{{ $module }}" id="{{ $module }}" {{ $status['active'] ? 'checked' : '' }}>
                    <label class="form-check-label" for="{{ $module }}">
                        {{ $module }}
                    </label>
                    @if(!$status['installed'])
                        <a href="{{ route('modules.install', $module) }}" class="btn btn-sm btn-success">Install</a>
                    @elseif(!$status['active'])
                        <a href="{{ route('modules.delete', $module) }}" class="btn btn-sm btn-danger">Delete</a>
                    @endif
                </div>
            @endforeach
        </div>
        <button type="submit" class="btn btn-primary">Update Modules</button>
    </form>
</div>
@endsection
