@extends('admin.layout.main')

@section('title', 'Categories - Admin')

@section('content')
<div class="admin-header">
    <h1>Categories</h1>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">Add New Category</a>
</div>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Name</th>
            <th>Slug</th>
            <th>Products</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($categories as $category)
            <tr>
                <td>{{ $category->id }}</td>
                <td>
                    <img src="{{ $category->image ? asset('storage/categories/' . $category->image) : 'https://via.placeholder.com/50' }}" 
                         alt="{{ $category->name }}" style="width: 50px; height: 50px; object-fit: cover;">
                </td>
                <td>{{ $category->name }}</td>
                <td>{{ $category->slug }}</td>
                <td>{{ $category->products_count }}</td>
                <td>
                    <div style="display: flex; gap: 8px;">
                        <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form method="POST" action="{{ route('admin.categories.destroy', $category->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure? This will delete the category.')">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="pagination">
    {{ $categories->links() }}
</div>
@endsection
