<?php
session_start();

// kalau sudah login kembali ke index
if( isset($_SESSION["login"]) ) {
   header("Location: login.php");
   exit;
}

require 'functions.php'; 

if ( isset($_POST["register"]) ){

  $username = trim($_POST['username']);
  $password = trim($_POST['password']);
  $password2 = trim($_POST['password2']);

  if (empty($username) || empty($password) || empty($password2)) {
    echo "<script>alert('Anda belum mengisi username dan password!');</script>";
  } elseif ($password !== $password2) {
    echo "<script>alert('Konfirmasi password tidak sama!');</script>";
  } else if( registrasi($_POST) > 0 ) {
    // lanjut proses simpan data ke database
    echo "<script>
               alert('user baru berhasil ditambahkan!');
               document.location.href = 'login.php';
            </script>";
    } else {
      echo mysql_error($connection);
    }
  }


?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Halaman Registrasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
	 <link rel="stylesheet" href="css/style.css">

</head>
<body class="bg-primary">
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card shadow p-4" style="width: 350px;">
    <div class="text-center">
      <img src="icon.png" alt="Logo" width="50" height="50" class="d-inline-block align-text-top">
    </div>
    <br>
      <h3 class="text-center mb-3">Register</h3>
      <form action="" method="post" onsubmit="return validateForm()">
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input type="text" class="form-control" name="username" id="username" placeholder="Masukkan email">
          <div id="usernameError" class="text-danger small mt-1"></div>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" name="password" id="password" placeholder="Masukkan password">
          <div id="passwordError" class="text-danger small mt-1"></div">
        </div>
        <div class="mb-3">
          <label for="password2" class="form-label">Konfirmasi password</label>
          <input type="password" class="form-control" name="password2" id="password2" placeholder="Masukkan ulang password">
          <div id="password2Error" class="text-danger small mt-1"></div>
        </div>
        <button type="submit" class="btn btn-success w-100" name="register">Register</button>
      </form>
      <p class="text-center mt-3 mb-0" style="font-size: 14px;">
        Kembali ke <a href="login.php" class="text-success">Login</a>
      </p>
    </div>
  </div>

<script>
  function validateForm() {
    //.trim() ngapus spasi di awal & akhir teks (biar input kosong tapi keisi spasi tetep dianggap kosong).
    let username = document.getElementById("username").value.trim();
    let password = document.getElementById("password").value.trim();
    let password2 = document.getElementById("password2").value.trim();

   // membuat tempat text pesan mucul
    let usernameError = document.getElementById("usernameError");
    let passwordError = document.getElementById("passwordError");
    let password2Error = document.getElementById("password2Error");

    // reset pesan error
    // textContext yang akan muncul di <div id="usernameError" dll..
    usernameError.textContent = "";
    passwordError.textContent = "";
    password2Error.textContent = "";

    let valid = true;

    if (username === "") {
      usernameError.textContent = "Isi username!";
      valid = false;
    }

    if (password === "") {
      passwordError.textContent = "Isi password!";
      valid = false;
    }

    if (password2 === "") {
      password2Error.textContent = "Konfirmasi password belum diisi!";
      valid = false;
    } else if (password !== password2) {
      password2Error.textContent = "Konfirmasi password tidak sama!";
      valid = false;
    }

    return valid; // false = form tidak dikirim
  }
</script>

 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>