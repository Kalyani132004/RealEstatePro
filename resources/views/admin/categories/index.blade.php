{{--
    Admin — Manage Categories
    Route: GET /admin/categories -> Admin\CategoryController@index
    Store: POST /admin/categories -> Admin\CategoryController@store (CategoryRequest)
    Update: PUT /admin/categories/{category} -> Admin\CategoryController@update
    Delete: DELETE /admin/categories/{category} -> Admin\CategoryController@destroy
    Expected data: $categories -> Collection<Category> withCount('properties')
--}}
@extends('layouts.dashboard')

@section('page-title', 'Manage Categories')

@section('sidebar')
    <x-sidebar-admin />
@endsection

@section('content')

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h2 class="rep-h3 mb-1">Manage Categories</h2>
            <p class="rep-small mb-0">Property categories used across search filters and listings.</p>
        </div>
        <button type="button" class="rep-btn rep-btn-primary" data-bs-toggle="modal" data-bs-target="#categoryModal" data-mode="create">
            <i class="bi bi-plus-circle"></i> Add Category
        </button>
    </div>

    <div class="row g-4">
        @forelse($categories ?? [] as $category)
            <div class="col-md-6 col-xl-4">
                <div class="rep-card p-4 d-flex align-items-center gap-3">
                    <div class="rep-dash-stat-icon" style="background: rgba(var(--rep-secondary-rgb),0.12); color: var(--rep-secondary);">
                        <i class="bi {{ $category->icon ?? 'bi-house-door' }}"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h3 class="rep-h4 mb-0">{{ $category->name }}</h3>
                        <p class="rep-small mb-0">{{ $category->properties_count ?? 0 }} properties</p>
                    </div>
                    <div class="d-flex flex-column gap-2">
                        <button type="button" class="rep-btn rep-btn-outline rep-btn-sm"
                            data-bs-toggle="modal" data-bs-target="#categoryModal" data-mode="edit"
                            data-id="{{ $category->id }}" data-name="{{ $category->name }}"
                            data-icon="{{ $category->icon }}" data-description="{{ $category->description }}"
                            data-action="{{ route('admin.categories.update', $category->id) }}">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <form method="POST" action="{{ route('admin.categories.destroy', $category->id) }}" data-confirm="Delete this category? Properties in it will be unassigned.">
                            @csrf @method('DELETE')
                            <button type="submit" class="rep-btn rep-btn-outline rep-btn-sm w-100" style="color: var(--rep-danger); border-color: var(--rep-danger);">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="rep-card text-center p-5 rep-text-muted">No categories yet. Add your first one.</div>
            </div>
        @endforelse
    </div>

    {{-- Add / Edit Modal --}}
    <div class="modal fade" id="categoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rep-card border-0">
                <form method="POST" action="{{ route('admin.categories.store') }}" id="categoryForm">
                    @csrf
                    <input type="hidden" name="_method" id="categoryMethod" value="POST">

                    <div class="modal-header border-0">
                        <h5 class="modal-title rep-h4" id="categoryModalTitle">Add Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label rep-small">Category Name</label>
                            <input type="text" name="name" id="categoryName" class="form-control rep-input" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label rep-small">Bootstrap Icon Class <span class="rep-text-muted">(e.g. bi-house-door)</span></label>
                            <input type="text" name="icon" id="categoryIcon" class="form-control rep-input" placeholder="bi-house-door">
                        </div>
                        <div class="mb-0">
                            <label class="form-label rep-small">Description</label>
                            <textarea name="description" id="categoryDescription" rows="3" class="form-control rep-input"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="rep-btn rep-btn-outline" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="rep-btn rep-btn-primary">Save Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/admin.js') }}"></script>
@endpush
