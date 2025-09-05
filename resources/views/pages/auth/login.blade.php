<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Agbalumo&display=swap" rel="stylesheet">
    <link href="{{ asset('template/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('template/css/loginregister.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    @if ($errors->any())
        <script>
            Swal.fire({
                title: "Terjadi Kesalahan",
                text: "@foreach ($errors->all() as $error) {{ $error }}{{ $loop->last ? '.' : ',' }} @endforeach",
                icon: "error"
            });
        </script>
    @endif
        
    <div class="loginregister-container">
        <div class="logo-container text-center mb-3">
            <center><img src="{{ asset('template/img/logo polimdo2.png') }}" alt="Logo Polimdo" class="logo-polimdo"></center>
        </div>

        <h2 class="form-title">SELAMAT DATANG</h2>

        <form action="/login" method="POST" 
              onsubmit="const b=document.getElementById('submitBtn'); b.disabled=true; b.textContent='Loading...';">
            @csrf

            <div class="form-group">
                <input name="username" class="form-control" placeholder="Username" required value="{{ old('username') }}">
            </div>

            <div class="form-group">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>

            <button id="submitBtn" type="submit" class="btn-loginregister">Login</button>
        </form>

    </div>
</body>
</html>
