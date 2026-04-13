@extends('admin.layout.main')

@section('title', 'My Profile - Admin')

@section('content')
<div class="content-header">
    <div>
        <h1>My Profile</h1>
        <p>Manage your admin profile</p>
    </div>
</div>

<div class="content-card">
    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="{{ Auth::guard('admin')->user()->name }}">
        </div>
        
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ Auth::guard('admin')->user()->email }}">
        </div>
        
        <div class="form-group">
            <label>New Password (leave blank to keep current)</label>
            <input type="password" name="password" class="form-control">
        </div>
        
        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>
        
        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
</div>
@endsection
