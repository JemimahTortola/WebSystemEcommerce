@extends('frontend.layouts.main')

@section('title', 'Privacy Policy - Lux Littles')

@section('content')
<section class="legal-page">
    <div class="container">
        <h1>Privacy Policy</h1>
        <p class="last-updated">Last Updated: {{ date('F d, Y') }}</p>
        
        <div class="legal-content">
            <h2>1. Information We Collect</h2>
            <p>We collect information you provide directly to us, including:</p>
            <ul>
                <li><strong>Account Information:</strong> Name, email address, password, and profile picture</li>
                <li><strong>Contact Information:</strong> Shipping address, phone number</li>
                <li><strong>Order Information:</strong> Products purchased, order history, payment information</li>
            </ul>

            <h2>2. How We Use Your Information</h2>
            <p>We use the information we collect to:</p>
            <ul>
                <li>Process and fulfill your orders</li>
                <li>Send order confirmations and updates</li>
                <li>Respond to your questions and provide customer support</li>
                <li>Send promotional communications (with your consent)</li>
                <li>Improve our website and services</li>
            </ul>

            <h2>3. Information Sharing</h2>
            <p>We do not sell your personal information. We may share information with:</p>
            <ul>
                <li>Service providers who assist in our operations</li>
                <li>Legal authorities when required by law</li>
                <li>Business partners with your consent</li>
            </ul>

            <h2>4. Data Security</h2>
            <p>We implement appropriate security measures to protect your personal information, including:</p>
            <ul>
                <li>Encrypted data transmission (HTTPS)</li>
                <li>Secure password hashing</li>
                <li>Regular security assessments</li>
            </ul>

            <h2>5. Cookies</h2>
            <p>We use cookies to enhance your browsing experience. You can control cookie preferences through your browser settings.</p>

            <h2>6. Your Rights</h2>
            <p>You have the right to:</p>
            <ul>
                <li>Access your personal data</li>
                <li>Correct inaccurate data</li>
                <li>Request deletion of your data</li>
                <li>Opt out of marketing communications</li>
            </ul>

            <h2>7. Data Retention</h2>
            <p>We retain your information for as long as your account is active or as needed to provide services. Account data is deleted within 30 days of account deletion request.</p>

            <h2>8. Children's Privacy</h2>
            <p>Our website is not directed to children under 13. We do not knowingly collect personal information from children under 13.</p>

            <h2>9. Changes to This Policy</h2>
            <p>We may update this privacy policy periodically. We will notify you of any material changes via email or website notice.</p>

            <h2>10. Contact Us</h2>
            <p>For privacy-related questions, contact us at:</p>
            <p><strong>Email:</strong> privacy@littlestar.com<br>
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
