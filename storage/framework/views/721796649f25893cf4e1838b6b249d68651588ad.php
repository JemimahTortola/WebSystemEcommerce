<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Admin Login - Flourista</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/admin/login.css')); ?>">
</head>
<body>
    <div class="login-wrapper">
        <div class="login-box">
            <div class="login-logo">
                <h1>Flourista<span>Admin</span></h1>
            </div>

            <form id="adminLoginForm">
                <div id="loginError" class="error-message"></div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required placeholder="admin@flourista.com">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required placeholder="Enter your password">
                </div>

                <button type="submit" class="btn-login">Sign In</button>

                <a href="<?php echo e(route('login')); ?>" class="back-link">← Back to customer login</a>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('adminLoginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const errorDiv = document.getElementById('loginError');
            errorDiv.style.display = 'none';

            const res = await fetch('/admin/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    email: document.getElementById('email').value,
                    password: document.getElementById('password').value,
                }),
            });

            const data = await res.json();
            if (data.redirect) {
                window.location.href = data.redirect;
            } else {
                errorDiv.textContent = data.message || 'Login failed';
                errorDiv.style.display = 'block';
            }
        });
    </script>
</body>
</html><?php /**PATH C:\Users\jemim\Ecoms-apps\ecoms-florist\resources\views/admin/login.blade.php ENDPATH**/ ?>