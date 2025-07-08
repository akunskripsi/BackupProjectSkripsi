<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Toko Surya Elektrik - Login</title>

    <!-- Font Awesome -->
    <link href="{{ asset('template/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,700,800,900" rel="stylesheet">
    <link href="{{ asset('template/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link rel="icon" href="{{ asset('template/img/ICON-TAB.png') }}" type="image/jpeg">

    <style>
        .toggle-password {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #aaa;
        }
    </style>
</head>

<body class="bg-warning">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-flex align-items-center justify-content-center">
                                <img src="{{ asset('template/img/logo-login.jpg') }}" alt="Login Image"
                                    class="img-fluid" style="max-width: 80%;">
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                    </div>
                                    <form class="user" action="/login" method="POST">
                                        @csrf
                                        @method('POST')
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user" id="InputEmail"
                                                name="email" placeholder="Enter Email Address..." required>
                                        </div>
                                        <div class="form-group position-relative">
                                            <input type="password" class="form-control form-control-user"
                                                id="InputPassword" name="password" placeholder="Password" required>
                                            <span toggle="#InputPassword" class="fas fa-eye toggle-password"></span>
                                        </div>
                                        <button type="submit" class="btn btn-danger btn-user btn-block">
                                            Login
                                        </button>
                                        <div class="text-center mt-3">
                                            <a class="small" href="{{ route('register') }}">Create an Account!</a>
                                        </div>
                                    </form>

                                    {{-- Pesan error biasa (jika selain error "belum terdaftar") --}}
                                    @if ($errors->any() && !str_contains($errors->first('email'), 'belum terdaftar'))
                                        <div class="alert alert-danger mt-3" role="alert">
                                            {{ $errors->first('email') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Akun Tidak Terdaftar -->
    <div class="modal fade" id="modalAkunTidakTerdaftar" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="modalLabel">Login Gagal</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Akun dengan email tersebut belum terdaftar. Silakan periksa kembali atau daftar terlebih dahulu.
                </div>
                <div class="modal-footer">
                    <a href="{{ route('register') }}" class="btn btn-primary">Daftar Sekarang</a>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Script -->
    <script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('template/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('template/js/sb-admin-2.min.js') }}"></script>

    <!-- Toggle password -->
    <script>
        $(document).ready(function () {
            $(".toggle-password").click(function () {
                let input = $($(this).attr("toggle"));
                let icon = $(this);
                if (input.attr("type") === "password") {
                    input.attr("type", "text");
                    icon.removeClass("fa-eye").addClass("fa-eye-slash");
                } else {
                    input.attr("type", "password");
                    icon.removeClass("fa-eye-slash").addClass("fa-eye");
                }
            });

            // Cek jika error karena akun belum terdaftar
            @if (session()->has('errors') && strpos($errors->first('email'), 'belum terdaftar') !== false)
                $('#modalAkunTidakTerdaftar').modal('show');
            @endif
        });
    </script>

</body>

</html>
