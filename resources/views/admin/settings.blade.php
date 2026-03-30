@extends('admin.layout.main')

@section('title', 'Settings - Admin')

@section('content')
<div class="content-header">
    <div>
        <h1>Settings</h1>
        <p>Configure your store settings</p>
    </div>
</div>

<div class="content-card">
    <h2>Store Information</h2>
    <form>
        <div class="form-group">
            <label>Store Name</label>
            <input type="text" class="form-control" value="Lux Littles">
        </div>
        
        <div class="form-group">
            <label>Contact Email</label>
            <input type="email" class="form-control" value="hello@littlestar.com">
        </div>
        
        <div class="form-group">
            <label>Contact Phone</label>
            <input type="text" class="form-control" value="(123) 456-7890">
        </div>
        
        <div class="form-group">
            <label>Address</label>
            <textarea class="form-control" rows="3">123 Baby Lane, City, State 12345</textarea>
        </div>
        
        <h2 style="margin-top: 2rem;">Order Settings</h2>
        
        <div class="form-group">
            <label>Free Shipping Threshold</label>
            <input type="number" class="form-control" value="50">
        </div>
        
        <button type="submit" class="btn btn-primary">Save Settings</button>
    </form>
</div>
@endsection
