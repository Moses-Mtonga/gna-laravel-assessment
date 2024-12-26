@extends('layouts.dashboard')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3 farmers-header">
            <h6 class="">System Modules</h6>
            <button class="create-button btn btn-sm" data-bs-toggle="modal" data-bs-target="#uploadModuleModal">
                <span class="material-icons">add</span> upload new module
            </button>
        </div>

        <form action="{{ route('modules.update') }}" method="POST" id="moduleForm">
            @csrf
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Module Name</th>
                        <th>Status</th>
                        <th>Status Updates</th>
                        <th>Installation Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($moduleStatuses as $module => $status)
                        <tr>
                            <td class="{{ !$status['active'] ? 'text-muted' : '' }}">{{ $module }}</td>
                            <td class="{{ !$status['active'] ? 'text-muted ' : 'text-success' }}">{{ $status['active'] ? 'Active' : 'Inactive' }}</td>
                            <td>
                                @if ($status['installed'])
                                    <input class="form-check-input" type="checkbox" name="modules[]"
                                        value="{{ $module }}" id="{{ $module }}"
                                        {{ $status['active'] ? 'checked' : '' }}>
                                @endif
                            </td>
                            <td>
                                @if (!$status['installed'])
                                    <a href="{{ route('modules.install', $module) }}" class="btn btn-sm btn-success"
                                        onclick="return confirmInstall(event, '{{ $module }}')">Install</a>
                                @elseif(!$status['active'])
                                    <a href="{{ route('modules.delete', $module) }}" class="btn btn-sm btn-danger"
                                        onclick="return confirmDelete(event, '{{ $module }}')">Delete</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <button type="submit" class="create-button btn btn-sm">
                <span class="material-icons mx-1">update</span> update module statuses
            </button>
        </form>
    </div>

    <!-- Upload Module Modal -->
    <div class="modal fade" id="uploadModuleModal" tabindex="-1" aria-labelledby="uploadModuleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModuleModalLabel">Upload New Module</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('modules.upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="moduleZip" class="form-label">Module Zip File</label>
                            <input type="file" class="form-control" id="moduleZip" name="moduleZip" required>
                        </div>
                        <button type="submit" class="create-button btn btn-sm">
                            <span class="material-icons">add</span> submit
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmInstall(event, module) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: `Do you want to install the ${module} module?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, install it!',
                cancelButtonText: 'No, cancel',
                heightAuto: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = event.target.href;
                }
            });
        }

        function confirmDelete(event, module) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: `Do you want to delete the ${module} module?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel',
                heightAuto: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = event.target.href;
                }
            });
        }
    </script>
@endsection
