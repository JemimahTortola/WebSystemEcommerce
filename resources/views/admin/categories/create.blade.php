@extends('admin.layout.main')

@section('title', 'Add Category - Admin')

@section('content')
<div class="admin-header">
    <h1>Add New Category</h1>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Category Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Category Image</label>
                <div class="image-upload" onclick="document.getElementById('image').click()">
                    <p>Click to upload image</p>
                    <input type="file" id="image" name="image" style="display: none;" accept="image/*">
                </div>
                @error('image')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Create Category</button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
