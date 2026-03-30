@extends('frontend.layouts.main')

@section('title', 'Responsive Preview - Lux Littles')

@section('content')
<style>
.preview-toolbar {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    background: #2c3e50;
    color: white;
    padding: 12px 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 20px;
    z-index: 10000;
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
}

.preview-toolbar h3 {
    margin: 0;
    font-size: 1rem;
    color: #ecf0f1;
}

.device-buttons {
    display: flex;
    gap: 8px;
}

.device-btn {
    padding: 8px 16px;
    border: 2px solid #34495e;
    background: #34495e;
    color: #ecf0f1;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.85rem;
    transition: all 0.2s;
}

.device-btn:hover {
    background: #4a5f7a;
}

.device-btn.active {
    background: #3498db;
    border-color: #3498db;
}

.preview-container {
    padding-top: 70px;
    min-height: 100vh;
    background: #1a1a2e;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    padding-bottom: 40px;
}

.device-frame {
    background: #222;
    border-radius: 20px;
    padding: 15px;
    margin-top: 20px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.4);
}

.device-screen {
    background: white;
    overflow-y: auto;
    transition: all 0.3s ease;
}

/* iPhone SE / Small Phone */
.device-phone-small .device-screen {
    width: 375px;
    height: 667px;
    border-radius: 20px;
}

/* iPhone 12/13/14 */
.device-phone-medium .device-screen {
    width: 390px;
    height: 844px;
    border-radius: 25px;
}

/* iPhone Pro Max */
.device-phone-large .device-screen {
    width: 428px;
    height: 926px;
    border-radius: 30px;
}

/* iPad Mini */
.device-tablet-small .device-screen {
    width: 744px;
    height: 1133px;
    border-radius: 20px;
}

/* iPad Pro 11" */
.device-tablet-medium .device-screen {
    width: 834px;
    height: 1194px;
    border-radius: 20px;
}

/* iPad Pro 12.9" */
.device-tablet-large .device-screen {
    width: 1024px;
    height: 1366px;
    border-radius: 24px;
}

.device-notch {
    width: 80px;
    height: 20px;
    background: #111;
    margin: 0 auto 10px;
    border-radius: 0 0 12px 12px;
}

.device-home-bar {
    width: 100px;
    height: 4px;
    background: #555;
    margin: 10px auto 0;
    border-radius: 2px;
}

.device-label {
    text-align: center;
    margin-top: 15px;
    color: #888;
    font-size: 0.85rem;
}

.device-label span {
    display: block;
    font-weight: 600;
    color: #aaa;
    margin-bottom: 3px;
}

.dimension-info {
    color: #666;
    font-size: 0.75rem;
}

.back-link {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: #3498db;
    color: white;
    padding: 12px 20px;
    border-radius: 25px;
    text-decoration: none;
    font-size: 0.9rem;
    box-shadow: 0 4px 15px rgba(52, 152, 219, 0.4);
    transition: all 0.2s;
    z-index: 10001;
}

.back-link:hover {
    background: #2980b9;
    transform: translateY(-2px);
}

@media (max-width: 1200px) {
    .device-tablet-large,
    .device-tablet-medium {
        display: none;
    }
}

@media (max-width: 900px) {
    .device-tablet-small {
        display: none;
    }
}

@media (max-width: 500px) {
    .preview-toolbar {
        flex-direction: column;
        gap: 10px;
        padding: 10px;
    }
    
    .device-phone-large {
        display: none;
    }
    
    .device-tablet-small,
    .device-tablet-medium,
    .device-tablet-large {
        display: none;
    }
}
</style>

<div class="preview-toolbar">
    <h3>Responsive Preview Tool</h3>
    <div class="device-buttons">
        <button class="device-btn" onclick="showDevice('phone-small')">Phone S</button>
        <button class="device-btn active" onclick="showDevice('phone-medium')">Phone M</button>
        <button class="device-btn" onclick="showDevice('phone-large')">Phone L</button>
        <button class="device-btn" onclick="showDevice('tablet-small')">Tablet S</button>
        <button class="device-btn" onclick="showDevice('tablet-medium')">Tablet M</button>
        <button class="device-btn" onclick="showDevice('tablet-large')">Tablet L</button>
    </div>
</div>

<div class="preview-container">
    {{-- iPhone SE --}}
    <div class="device-frame device-phone-small" id="frame-phone-small" style="display: none;">
        <div class="device-notch"></div>
        <div class="device-screen">
            <iframe src="{{ route('home') }}" frameborder="0" width="100%" height="100%"></iframe>
        </div>
        <div class="device-home-bar"></div>
        <div class="device-label">
            <span>iPhone SE</span>
            <span class="dimension-info">375 × 667 px</span>
        </div>
    </div>

    {{-- iPhone 12/13/14 --}}
    <div class="device-frame device-phone-medium" id="frame-phone-medium">
        <div class="device-notch"></div>
        <div class="device-screen">
            <iframe src="{{ route('home') }}" frameborder="0" width="100%" height="100%"></iframe>
        </div>
        <div class="device-home-bar"></div>
        <div class="device-label">
            <span>iPhone 12/13/14</span>
            <span class="dimension-info">390 × 844 px</span>
        </div>
    </div>

    {{-- iPhone Pro Max --}}
    <div class="device-frame device-phone-large" id="frame-phone-large" style="display: none;">
        <div class="device-notch"></div>
        <div class="device-screen">
            <iframe src="{{ route('home') }}" frameborder="0" width="100%" height="100%"></iframe>
        </div>
        <div class="device-home-bar"></div>
        <div class="device-label">
            <span>iPhone Pro Max</span>
            <span class="dimension-info">428 × 926 px</span>
        </div>
    </div>

    {{-- iPad Mini --}}
    <div class="device-frame device-tablet-small" id="frame-tablet-small" style="display: none;">
        <div class="device-screen">
            <iframe src="{{ route('home') }}" frameborder="0" width="100%" height="100%"></iframe>
        </div>
        <div class="device-label">
            <span>iPad Mini</span>
            <span class="dimension-info">744 × 1133 px</span>
        </div>
    </div>

    {{-- iPad Pro 11" --}}
    <div class="device-frame device-tablet-medium" id="frame-tablet-medium" style="display: none;">
        <div class="device-screen">
            <iframe src="{{ route('home') }}" frameborder="0" width="100%" height="100%"></iframe>
        </div>
        <div class="device-label">
            <span>iPad Pro 11"</span>
            <span class="dimension-info">834 × 1194 px</span>
        </div>
    </div>

    {{-- iPad Pro 12.9" --}}
    <div class="device-frame device-tablet-large" id="frame-tablet-large" style="display: none;">
        <div class="device-screen">
            <iframe src="{{ route('home') }}" frameborder="0" width="100%" height="100%"></iframe>
        </div>
        <div class="device-label">
            <span>iPad Pro 12.9"</span>
            <span class="dimension-info">1024 × 1366 px</span>
        </div>
    </div>
</div>

<a href="{{ route('home') }}" class="back-link">← Back to Site</a>

<script>
function showDevice(device) {
    // Hide all frames
    const frames = document.querySelectorAll('.device-frame');
    frames.forEach(frame => frame.style.display = 'none');
    
    // Remove active class from all buttons
    const buttons = document.querySelectorAll('.device-btn');
    buttons.forEach(btn => btn.classList.remove('active'));
    
    // Show selected frame
    const selectedFrame = document.getElementById('frame-' + device);
    if (selectedFrame) {
        selectedFrame.style.display = 'block';
    }
    
    // Add active class to clicked button
    event.target.classList.add('active');
}
</script>
@endsection
