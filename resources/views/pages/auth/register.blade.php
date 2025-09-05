<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Register</title>

    <!-- Custom fonts for this template-->
    <link rel="stylesheet" href="{{ asset('template/css/loginregister.css') }}">
    <link href="{{ asset("template/vendor/fontawesome-free/css/all.min.css") }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Agbalumo&display=swap" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="{{ asset("template/css/sb-admin-2.min.css") }}" rel="stylesheet">

</head>

<body>
    <div class="wrapper">
<div class="loginregister-container">
        <div class="logo-container text-center mb-3">
    <center><img src="{{ asset('template/img/logo polimdo2.png') }}" alt="Logo Polimdo" class="logo-polimdo">
    </div></center>
        <h2 class="form-title">REGISTRASI!</h2>
        <form action="/register" method="POST">
            @csrf
            @method('POST')
            <div class="form-group">
                <input type="text" class="form-control" name="name" placeholder="Full Name" required>
            </div>
            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="Email Address" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password" required>
            </div>
                <button id="submitBtn" type="submit" class="btn-loginregister">Simpan</button>
        </form>
        <div class="text-center mt-3">
            <a href="/">Login</a>
        </div>
    </div>
</body>
</html>