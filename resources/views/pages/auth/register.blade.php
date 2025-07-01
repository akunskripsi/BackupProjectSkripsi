<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Register</title>

    <!-- Custom fonts for this template-->
    <link href={{ asset('template/vendor/fontawesome-free/css/all.min.css') }} rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href={{ asset('template/css/sb-admin-2.min.css') }} rel="stylesheet">
    <link rel="icon" href="{{ asset('template/img/ICON-TAB.png') }}" type="image/jpeg">

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <!-- Gambar di kiri -->
                    <div class="col-lg-6 d-none d-lg-flex align-items-center justify-content-center">
                        <img src="{{ asset('template/img/logo-login.jpg') }}" alt="Login Image" class="img-fluid"
                            style="max-width: 80%;">
                    </div>

                    <!-- Form register -->
                    <div class="col-lg-6">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                            </div>
                            @if ($errors->any())
                                <div class="alert alert-danger small">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form method="POST" action="{{ route('register.post') }}" class="user">
                                @csrf

                                <div class="form-group">
                                    <input type="text" name="name" class="form-control form-control-user"
                                        placeholder="Full Name" required>
                                </div>

                                <div class="form-group">
                                    <input type="email" name="email" class="form-control form-control-user"
                                        placeholder="Email Address" required>
                                </div>

                                <div class="form-group position-relative">
                                    <input type="password" name="password" id="password"
                                        class="form-control form-control-user" placeholder="Password" required>
                                    {{-- Icon toggle --}}
                                    <span class="position-absolute" onclick="togglePassword()"
                                        style="top: 50%; right: 15px; transform: translateY(-50%); cursor: pointer;">
                                        <i id="toggleIcon" class="fas fa-eye"></i>
                                    </span>
                                </div>

                                <div class="form-group position-relative">
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        class="form-control form-control-user" placeholder="Konfirmasi Password"
                                        required>

                                    {{-- Icon toggle --}}
                                    <span class="position-absolute" onclick="togglePasswordConfirmation()"
                                        style="top: 50%; right: 15px; transform: translateY(-50%); cursor: pointer;">
                                        <i id="toggleIconConfirmation" class="fas fa-eye"></i>
                                    </span>
                                </div>

                                <button type="submit" class="btn btn-primary btn-user btn-block">
                                    Register Account
                                </button>

                                <div class="text-center mt-3">
                                    <a class="small" href="{{ route('login') }}">Sudah punya akun? Login!</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <!-- Bootstrap core JavaScript-->
    <script src={{ asset('template/vendor/jquery/jquery.min.js') }}></script>
    <script src={{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}></script>

    <!-- Core plugin JavaScript-->
    <script src={{ asset('template/vendor/jquery-easing/jquery.easing.min.js') }}></script>

    <!-- Custom scripts for all pages-->
    <script src={{ asset('template/js/sb-admin-2.min.js') }}></script>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById("password");
            const toggleIcon = document.getElementById("toggleIcon");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggleIcon.classList.remove("fa-eye");
                toggleIcon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                toggleIcon.classList.remove("fa-eye-slash");
                toggleIcon.classList.add("fa-eye");
            }
        }

        function togglePasswordConfirmation() {
            const passwordInput = document.getElementById("password_confirmation");
            const toggleIcon = document.getElementById("toggleIconConfirmation");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggleIcon.classList.remove("fa-eye");
                toggleIcon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                toggleIcon.classList.remove("fa-eye-slash");
                toggleIcon.classList.add("fa-eye");
            }
        }
    </script>

</body>

</html>
