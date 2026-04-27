@extends('layouts.app')

@section('title', 'Privacy Policy - Flourista')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/user/page.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="page-content">
        <h1>Privacy Policy</h1>
        
        <div class="content-block">
            <p>At Flourista, we value your privacy and are committed to protecting your personal information.</p>
            
            <h2>Information We Collect</h2>
            <ul>
                <li>Personal information (name, email, phone)</li>
                <li>Delivery addresses</li>
                <li>Order history</li>
                <li>Payment information</li>
            </ul>
            
            <h2>How We Use Your Information</h2>
            <ul>
                <li>To process your orders</li>
                <li>To deliver your purchases</li>
                <li>To communicate with you about orders</li>
                <li>To improve our services</li>
            </ul>
            
            <h2>Data Protection</h2>
            <p>We implement security measures to protect your personal information.</p>
        </div>
    </div>
</div>
@endsection