@extends('layouts.dashboard')

@section('content')
    <div class="container mt-4">

        <ul class="nav nav-tabs justify-content-center" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="supports-tab" data-bs-toggle="tab" data-bs-target="#supports-tab-pane"
                    type="button" role="tab" aria-controls="supports-tab-pane" aria-selected="true">Supported
                    Farmers</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="products-tab" data-bs-toggle="tab" data-bs-target="#products-tab-pane"
                    type="button" role="tab" aria-controls="products-tab-pane" aria-selected="false">Supported Product
                    Inputs</button>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="supports-tab-pane" role="tabpanel" aria-labelledby="supports-tab"
                tabindex="0">
                <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
                    <h6 class="fw-bold">All Farm Supports</h6>
                    <button class="create-button btn btn-sm" data-bs-toggle="modal"
                        data-bs-target="#createFarmSupportModal">
                        <span class="material-icons">add</span> Create Farmer Support
                    </button>

                </div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Farmer Name</th>
                            <th>Description</th>
                            <th>Products</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($supports as $support)
                            <tr>
                                <td>{{ $support->id }}</td>
                                <td>{{ $support->farmer->first_name . ' ' . $support->farmer->last_name ?? '' }}</td>
                                <td>{{ $support->description }}</td>
                                <td>
                                    @foreach ($support->products as $product)
                                        <span class="badge bg-secondary">{{ $product->name }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#editFarmSupportModal{{ $support->id }}">Edit</button>
                                    <form action="{{ route('farmsupport.destroy', $support->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Farm Support Modal -->
                            <div class="modal fade" id="editFarmSupportModal{{ $support->id }}" tabindex="-1"
                                aria-labelledby="editFarmSupportModalLabel{{ $support->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h6 class="modal-title" id="editFarmSupportModalLabel{{ $support->id }}">Edit
                                                {{ $support->name }}</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('farmsupport.update', $support->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="mb-3">
                                                    <label for="farmer_id" class="form-label">Farmer</label>
                                                    <select name="farmer_id" class="form-control" id="farmer_id" required>
                                                        @foreach ($farmers as $farmer)
                                                            <option value="{{ $farmer->id }}"
                                                                {{ $support->farmer_id == $farmer->id ? 'selected' : '' }}>
                                                                {{ $farmer->first_name . ' ' . $farmer->last_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="description" class="form-label">Description</label>
                                                    <textarea class="form-control" id="description" name="description" required>{{ $support->description }}</textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="products" class="form-label">Products</label>
                                                    <select name="products[]" id="products" class="form-control" multiple
                                                        required>
                                                        @foreach ($products as $product)
                                                            <option value="{{ $product->id }}"
                                                                {{ in_array($product->id, $support->products->pluck('id')->toArray()) ? 'selected' : '' }}>
                                                                {{ $product->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <button type="submit" class="create-button btn btn-sm">
                                                    <span class="material-icons">save</span> save changes
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade" id="products-tab-pane" role="tabpanel" aria-labelledby="products-tab" tabindex="0">
                <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
                    <h6 class="fw-bold">All Product Inputs</h6>
                    <button class="create-button btn btn-sm" data-bs-toggle="modal" data-bs-target="#createProductModal">
                        <span class="material-icons">add</span> Add Product Input
                    </button>
                </div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>{{ $product->name }}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#editProductModal{{ $product->id }}">Edit</button>
                                    <form action="{{ route('supported.products.destroy', $product->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Product Modal -->
                            <div class="modal fade" id="editProductModal{{ $product->id }}" tabindex="-1"
                                aria-labelledby="editProductModalLabel{{ $product->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h6 class="modal-title" id="editProductModalLabel{{ $product->id }}">Edit
                                                Product</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('supported.products.update', $product->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Name</label>
                                                    <input type="text" class="form-control" id="name"
                                                        name="name" value="{{ $product->name }}" required>
                                                </div>
                                                <button type="submit" class="create-button btn btn-sm">
                                                    <span class="material-icons">save</span> save changes
                                                </button>

                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Create Farm Support Modal -->
    <div class="modal fade" id="createFarmSupportModal" tabindex="-1" aria-labelledby="createFarmSupportModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createFarmSupportModalLabel">Create Farm Support</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('farmsupport.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="farmer_id" class="form-label">Farmer</label>
                            <select name="farmer_id" class="form-control" id="farmer_id" required>
                                <option value="">--select farmer--</option>
                                @foreach ($farmers as $farmer)
                                    <option value="{{ $farmer->id }}">
                                        {{ $farmer->first_name . ' ' . $farmer->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="products" class="form-label">Products</label>
                            <select name="products[]" id="products" class="form-control" multiple required>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="create-button btn btn-sm">
                            <span class="material-icons">add</span> submit
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Product Modal -->
    <div class="modal fade" id="createProductModal" tabindex="-1" aria-labelledby="createProductModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createProductModalLabel">Add Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('supported.products.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <button type="submit" class="create-button btn btn-sm">
                            <span class="material-icons">add</span> submit
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
