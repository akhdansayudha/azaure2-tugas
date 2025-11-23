<?php
session_start();
require 'functions.php';

// cookie tidak akan hilang walau browsernya di close, tergantung durasi cookie
// begitu aplikasinya di buka di cek dulu cookienya ada tidak
// kalau ada cookie set sessionnya / user masih login
// if( isset($_COOKIE['login']) ) {
  // 'true' sini dari remember me
  // isinya true atau tidak  
  // if( $_COOKIE['login'] == 'true') {
    // kalau true set sessionnya / masih login 
    // true tanpa kutip boolean
    // $_SESSION['login'] = true;
  // }
// }

// cek cookie
if( isset($_COOKIE['id']) && isset($_COOKIE['key']) ) {
  $id = $_COOKIE['id'];
  $key = $_COOKIE['key'];

  // ambil username berdasarkan id
  $result = mysqli_query($connection, "SELECT username FROM user WHERE
    id = $id");
  $row = mysqli_fetch_assoc($result);

  // cek cookie dan username (dari data base) sama tidak, di acak hash sha256
  // $key disini adalah username yang sudah di acak
  if( $key === hash('sha256', $row['usernama']) ) {
    $_SESSION['login'] = true;
    // session ada karna true, lanjut ke login
  }
}

// kalau sudah login kembali ke index
if( isset($_SESSION["login"]) ) {
   header("Location: index.php");
   exit;
}

// cek apakah tombol submit sudah di cek atau belum
if( isset($_POST["login"]) ) {

  // menangkap data username dan password dari method POST
  $username = $_POST["username"];
  $password = $_POST["password"];

  // cek apakah ada username di dalam database yang sama di inputkan saat login
  // kalau ada lalu cek password, kalau tidak ada berikan info error
  $result = mysqli_query($connection, "SELECT * FROM user WHERE username ='$username' ");

  // cek username
  // ada berpa baris yang di kembalikan dari table / fungsi select ini
  // kalau 1 berarti ada username di table user, kalau 0 berarti tidak ada
  if( mysqli_num_rows($result) === 1 ) {

  	// kalau ada, cek password berdasarkan username tadi, kalau gak ada langsung keluar dari IF
  	// ambil dulu pakai mysqli_fetch_assoc
  	// di dalam $row : ada id, usernam dan password yang sudah di acak
  	$row = mysqli_fetch_assoc($result);
    // password_verify : ngecek string sebuah password sama tidak dengan hash nya
    // jika sama passwordnya benar
    // ($password, $row["password"]); : ini password yang di inputkan oleh user dan password di database
  	if (password_verify($password, $row["password"]) ) {

      // set session
      $_SESSION["login"] = true;

      // cek rember me
      if( isset($_POST['remember']) ) {
        // cookie
        // setcookie('', 'true', time() + 60); ini 60 = 1 menit
        setcookie('id', $row['id'], time()+60);
        // sha256 : algoritma acak php
        setcookie('key', hash('sha256', $row['username']), time()+60 );
      }

  		echo "<script>
  		      alert('Login berhasil!');
              document.location.href = 'index.php';
  		      </script>";
  		// header("Location:index.php");
  		exit;
  	}
  }

  // kalau salah input username atau password
  $loginError = true;

}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Halaman Login</title>
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
      <h3 class="text-center mb-3">Login</h3>
      <form action="" method="post">
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input type="text" class="form-control <?php echo $loginError ? 'is-invalid' : ''; ?>" name="username" id="username" placeholder="Masukkan username">
          <?php if ( isset($loginError)): ?>
             <div class="text-danger small mt-1"><?php echo $notifError = "Username salah"; ?></div>
          <?php endif; ?>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control <?php echo $loginError ? 'is-invalid' : ''; ?>" name="password" id="password" 
          placeholder="Masukkan password">
          <?php if ( isset($loginError)): ?>
             <div class="text-danger small mt-1"><?php echo $notifError = "assword salah"; ?></div>
          <?php endif; ?>
        </div>
        <div class="mb-3 form-check">
         <input class="form-check-input" type="checkbox" name="remember" id="remember"> 
         <label class="form-check-label" for="remember">Remember me</label>
        </div>
        <button type="submit" class="btn btn-success w-100" name="login">Login</button>
      </form>
      <p class="text-center mt-3 mb-0" style="font-size: 14px;">
        Belum punya akun? <a href="registrasi.php" class="text-success">Daftar</a>
      </p>
    </div>
  </div>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

</body>
</html>