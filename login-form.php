<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form with Custom Validation Messages</title>
    <link rel="stylesheet" href="css/form.css">
    <link rel="stylesheet" href="fontawesome-free-6.6.0-web/css/all.min.css">
</head>
<body>
<form id="loginForm" action="login.php" method="POST">
    <h5>Login</h5>
    <div class="form-group">
        <label for="email">Email:</label>
        <!-- Email validation with custom message -->
        <input 
            type="text" 
            name="email" 
            id="email" 
            class="form-control" 
            required 
            pattern="[a-z0-9._%+-]+@gmail\.com" 
            title="Email must be a valid Gmail address (e.g., example@gmail.com)"
            oninvalid="this.setCustomValidity('Please enter a valid Gmail address ending with @gmail.com')" 
            oninput="this.setCustomValidity('')"
        >
    </div>
    <div class="form-group">
        <label for="password">Password:</label>
        <div class="input-group">
            <!-- Password validation with custom message -->
            <input 
                type="password" 
                name="password" 
                id="password" 
                class="form-control" 
                required 
                pattern="(?=.*[A-Z])(?=.*\d)(?=.*[@]).{6,}" 
                title="Password must be at least 6 characters long, contain a capital letter, a number, and @"
                oninvalid="this.setCustomValidity('Password must have at least 6 characters, 1 uppercase letter, 1 number, and the @ symbol')" 
                oninput="this.setCustomValidity('')"
            >
            <div class="input-group-append">
                <span class="input-group-text" id="toggleLoginPassword">
                    <i class="fas fa-eye"></i>
                </span>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Login</button>
</form>
</body>
</html>
<script>
    document.getElementById('toggleLoginPassword').addEventListener('click', function () {
        const passwordInput = document.getElementById('password');
        const passwordIcon = this.querySelector('i');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            passwordIcon.classList.remove('fa-eye');
            passwordIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            passwordIcon.classList.remove('fa-eye-slash');
            passwordIcon.classList.add('fa-eye');
        }
    });
</script>
