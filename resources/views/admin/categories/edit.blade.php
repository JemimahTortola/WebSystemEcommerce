@extends('admin.layout.main')

@section('title', 'Edit Category - Admin')

@section('content')
<div class="admin-header">
    <h1>Edit Category</h1>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.categories.update', $category->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label class="form-label">Category Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}" required>
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $category->description) }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Category Image</label>
                @if($category->image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/categories/' . $category->image) }}" alt="{{ $category->name }}" style="width: 100px; height: 100px; object-fit: cover;">
                    </div>
                @endif
                <div class="image-upload" onclick="document.getElementById('image').click()">
                    <p>Click to upload new image</p>
                    <input type="file" id="image" name="image" style="display: none;" accept="image/*">
                </div>
                @error('image')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Update Category</button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
