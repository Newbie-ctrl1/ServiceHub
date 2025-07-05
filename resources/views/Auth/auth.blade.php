<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Signup Form</title>
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="{{ secure_asset('css/auth.css') }}">
</head>

<body>
    <div class="container">
        @if($errors->any())
        <div class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; padding: 10px; margin: 10px; border-radius: 5px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        
        @if(session('success'))
        <div class="alert alert-success" style="background-color: #d4edda; color: #155724; padding: 10px; margin: 10px; border-radius: 5px;">
            {{ session('success') }}
        </div>
        @endif
        
        <div class="form-box login">
            <form action="/login" method="POST">
                @csrf
                <h1>Login</h1>
                <div class="input-box">
                    <input type="text" name="username" placeholder="Username" required>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input type="password" name="password" id="login-password" placeholder="Password" required>
                    <i class='bx bxs-lock-alt'></i>
                    <i class='bx bx-hide toggle-password' onclick="togglePasswordVisibility('login-password')"></i>
                </div>
                <div class="forgot-link">
                    <a href="#">Forgot Password?</a>
                </div>
                <button type="submit" class="btn">Login</button>
                <p>or login with social platforms</p>
                <div class="social-icons">
                    <a href="#"><i class='bx bxl-google' ></i></a>
                    <a href="#"><i class='bx bxl-facebook' ></i></a>
                    <a href="#"><i class='bx bxl-github' ></i></a>
                    <a href="#"><i class='bx bxl-linkedin' ></i></a>
                </div>
            </form>
        </div>

        <div class="form-box register">
            <form action="/register" method="POST">
                @csrf
                <h1>Registration</h1>
                <div class="input-box">
                    <input type="text" name="username" placeholder="Username" required>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input type="email" name="email" placeholder="Email" required>
                    <i class='bx bxs-envelope' ></i>
                </div>
                <div class="input-box">
                    <input type="password" name="password" id="password" placeholder="Password" required>
                    <i class='bx bxs-lock-alt'></i>
                    <i class='bx bx-hide toggle-password' onclick="togglePasswordVisibility('password')"></i>
                </div>
                <div class="input-box">
                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password" required>
                    <i class='bx bxs-lock-alt'></i>
                    <i class='bx bx-hide toggle-password' onclick="togglePasswordVisibility('password_confirmation')"></i>
                </div>
                <button type="submit" class="btn">Register</button>
                <p>or register with social platforms</p>
                <div class="social-icons">
                    <a href="#"><i class='bx bxl-google' ></i></a>
                    <a href="#"><i class='bx bxl-facebook' ></i></a>
                    <a href="#"><i class='bx bxl-github' ></i></a>
                    <a href="#"><i class='bx bxl-linkedin' ></i></a>
                </div>
            </form>
        </div>

        <div class="toggle-box">
            <div class="toggle-panel toggle-left">
                <h1>Hello, Welcome!</h1>
                <p>Don't have an account?</p>
                <button type="button" class="btn register-btn">Register</button>
            </div>

            <div class="toggle-panel toggle-right">
                <h1>Welcome Back!</h1>
                <p>Already have an account?</p>
                <button type="button" class="btn login-btn">Login</button>
            </div>
        </div>
    </div>

    <script>
        const container = document.querySelector('.container');
        const registerBtn = document.querySelector('.register-btn');
        const loginBtn = document.querySelector('.login-btn');
        const registerForm = document.querySelector('.form-box.register form');
        const loginForm = document.querySelector('.form-box.login form');

        registerBtn.addEventListener('click', (e) => {
            e.preventDefault();
            // Jika sudah di halaman login, arahkan ke halaman register
            if (window.location.pathname === '/login') {
                window.location.href = '/register';
            } else {
                // Jika masih di landing page, hanya ubah tampilan
                container.classList.add('active');
            }
        });

        loginBtn.addEventListener('click', (e) => {
            e.preventDefault();
            // Jika sudah di halaman register, arahkan ke halaman login
            if (window.location.pathname === '/register') {
                window.location.href = '/login';
            } else {
                // Jika masih di landing page, hanya ubah tampilan
                container.classList.remove('active');
            }
        });

        // Tambahkan event listener untuk form submit
        registerForm.addEventListener('submit', (e) => {
            console.log('Register form submitted');
            
            // Validasi password dan konfirmasi password
            const password = document.getElementById('password');
            const passwordConfirmation = document.getElementById('password_confirmation');
            
            if (password.value !== passwordConfirmation.value) {
                e.preventDefault(); // Mencegah form disubmit
                alert('Password dan konfirmasi password tidak cocok!');
                return false;
            }
            // Form akan di-submit secara normal dengan method POST jika password cocok
        });

        loginForm.addEventListener('submit', (e) => {
            console.log('Login form submitted');
            // Form akan di-submit secara normal dengan method POST
        });

        // Fungsi untuk toggle password visibility
        function togglePasswordVisibility(inputId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = event.currentTarget;
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('bx-hide');
                toggleIcon.classList.add('bx-show');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('bx-show');
                toggleIcon.classList.add('bx-hide');
            }
        }
    </script>
</body>
</html>