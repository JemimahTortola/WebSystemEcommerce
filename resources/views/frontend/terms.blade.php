@extends('frontend.layouts.main')

@section('title', 'Terms of Service - TinyThreads')

@section('content')
<section class="legal-page">
    <div class="container">
        <h1>Terms of Service</h1>
        <p class="last-updated">Last Updated: {{ date('F d, Y') }}</p>
        
        <div class="legal-content">
            <h2>1. Acceptance of Terms</h2>
            <p>By accessing and using the TinyThreads website, you accept and agree to be bound by these Terms of Service. If you do not agree, please do not use our services.</p>

            <h2>2. Products and Services</h2>
            <p>TinyThreads offers newborn to toddler clothing and accessories for sale. All products are subject to availability. We reserve the right to limit quantities or discontinue products.</p>

            <h2>3. Pricing</h2>
            <p>All prices are listed in USD and are subject to change without notice. We make every effort to ensure accurate pricing, but errors may occur. In case of pricing errors, we reserve the right to cancel orders.</p>

            <h2>4. Orders and Payment</h2>
            <p>By placing an order, you agree to:</p>
            <ul>
                <li>Provide accurate billing and shipping information</li>
                <li>Pay all charges as specified</li>
                <li>Be responsible for any applicable taxes</li>
            </ul>

            <h2>5. Shipping and Delivery</h2>
            <p>Shipping times are estimates only. We are not responsible for delays caused by carriers, customs, or events beyond our control. Risk of loss transfers to you upon delivery.</p>

            <h2>6. Returns and Refunds</h2>
            <p>Our return policy allows for returns within 14 days of delivery for unused items in original condition. Refunds will be processed within 5-7 business days of receiving returned items.</p>

            <h2>7. Account Responsibilities</h2>
            <p>You are responsible for:</p>
            <ul>
                <li>Maintaining confidentiality of your account</li>
                <li>All activities under your account</li>
                <li>Notifying us of unauthorized access</li>
            </ul>

            <h2>8. Intellectual Property</h2>
            <p>All content on this website, including logos, images, and text, is the property of TinyThreads and protected by copyright laws.</p>

            <h2>9. Limitation of Liability</h2>
            <p>TinyThreads shall not be liable for any indirect, incidental, or consequential damages arising from the use of our website or products.</p>

            <h2>10. Governing Law</h2>
            <p>These terms shall be governed by the laws of the jurisdiction in which our business is registered.</p>

            <h2>11. Changes to Terms</h2>
            <p>We reserve the right to modify these terms at any time. Continued use of the website constitutes acceptance of modified terms.</p>

            <h2>12. Contact Information</h2>
            <p>For questions regarding these terms, contact us:</p>
            <p><strong>Email:</strong> legal@littlestar.com<br>
            <strong>Phone:</strong> (123) 456-7890</p>
        </div>
    </div>
</section>

<style>
.legal-page {
    padding: 50px 0;
    max-width: 800px;
    margin: 0 auto;
}
.legal-page h1 {
    font-size: 2.5rem;
    color: #4a4a4a;
    margin-bottom: 0.5rem;
}
.last-updated {
    color: #7a7a7a;
    font-size: 0.9rem;
    margin-bottom: 2rem;
}
.legal-content h2 {
    font-size: 1.3rem;
    color: #4a4a4a;
    margin-top: 2rem;
    margin-bottom: 1rem;
}
.legal-content p {
    color: #5a5a5a;
    line-height: 1.8;
}
.legal-content ul {
    margin-left: 1.5rem;
    color: #5a5a5a;
}
.legal-content li {
    margin-bottom: 0.5rem;
    line-height: 1.6;
}
</style>
@endsection
