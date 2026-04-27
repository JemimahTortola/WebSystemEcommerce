@extends('layouts.app')

@section('title', 'Terms of Service - Flourista')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/user/page.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="page-content">
        <h1>Terms of Service</h1>
        
        <div class="content-block">
            <p>By using Flourista, you agree to these terms.</p>
            
            <h2>Orders</h2>
            <p>Orders are confirmed upon payment. We reserve the right to cancel orders if products are unavailable.</p>
            
            <h2>Delivery</h2>
            <p>Delivery times are estimates. We strive to deliver on time but cannot guarantee exact times.</p>
            
            <h2>Cancellations</h2>
            <p>Cancellations must be requested within 24 hours of order placement.</p>
            
            <h2>Returns</h2>
            <p>We accept returns for damaged or incorrect items. Please contact us within 24 hours of delivery.</p>
            
            <h2>Intellectual Property</h2>
            <p>All content on this website is proprietary to Flourista.</p>
        </div>
    </div>
</div>
@endsection