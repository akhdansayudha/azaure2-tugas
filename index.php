<?php
session_start();

// cek session ada atau tidak
// kalau tidak ada tidak boleh login
if( !isset($_SESSION["login"]) ) {
	header("Location:login.php");
	exit;

}

// ngambil data functions connect simpan ke sini
require 'functions.php';

// agar mereplace query mahasiswa sesuai input pencarian
// jika tombol cari ditekan
if ( isset($_POST["cari"]) ){
	$keyword = $_POST["keyword"];
	$mahasiswa = cari($keyword);
} else {

// pagination pakai SELECT * FROM mahasiswa LIMIT
// konfigurasi
$jumlahDataPerhalaman = 5; // jumlah halaman
// ada berapa data di arrayassosiatif menggunakan count
$jumlahData = count(query("SELECT * FROM mahasiswa")); // jumlah seluruh data
$jumlahHalaman = ceil($jumlahData / $jumlahDataPerhalaman); // di bulatkan ke atas
// kondisikan user ke halaman pertama
// operator ternari
$halamanAktif = ( isset($_GET["halaman"]) ) ? $_GET["halaman"] : 1; // jika true halman aktif jika false berikan 1
// halaman aktif = ketia user masuk ke indek ini contoh 
// 4 * 4 - 4 = 12
$awalData = ( $jumlahDataPerhalaman * $halamanAktif ) - $jumlahDataPerhalaman;


// melakukan query unduk mendapatkan semua data mahasiswa
// agar secara default ketika di buka semua mhs tampil
// Mengurutkan Besar ke kecil : $mahasiswa = query("SELECT * FROM mahasiswa ORDER BY id DESC");

$mahasiswa = query("SELECT * FROM mahasiswa LIMIT $awalData, $jumlahDataPerhalaman");

$keyword = '';

}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Halaman Admin</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
	<link rel="stylesheet" href="css/style.css">
</head>
<body>

	<nav class="navbar bg-primary" data-bs-theme="dark">
		<div class="container">
			<div class="container-fluid d-flex justify-content-between align-items-center">
				<h1 class="navbar-brand mb-0 d-flex align-items-center">
					<img src="icon.png" alt="Logo" width="45" height="45" class="d-inline-block align-text-top me-2">
					Daftar Mahasiswa
				</h1>
				<div action="logout.php" method="post" class="d-flex align-items-center ms-auto">
					<?php if (isset($_POST["cari"]) ) :?>
					<a href="index.php" class="btn btn-light me-2">
			        <i class="bi bi-house-door"></i> Home
			      </a>
			      <?php else : ?>
					<a class="nav-link text-white d-flex align-items-center" href="logout.php">
						<i class="bi bi-exclamation-circle-fill me-2" style="font-size: 1.2rem;"></i> Logout
					</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</nav>

	<br><br>

	<form action="" method="post"> 
		<div class="container">
			<div class="col-md-6">
				<div class="input-group mb-3">
					<input type="text" name="keyword" class="form-control" placeholder="Masukan keyword" aria-label="Recipient’s username" aria-describedby="button-addon2"  autocomplete="off"
					required 
					oninvalid="this.setCustomValidity('Masukkan nama mahasiswa!')" 
               oninput="this.setCustomValidity('')">
					<button type="submit" name="cari" class="btn btn-warning"id="button-addon2">Cari</button>
					<button type="button" class="btn btn-success" role="button"><a class="tambah-mhs"href="tambah.php">Tambah Mahasiswa</a></button>
				</div>  
			</div>
		</div>
	</form>

	<!-- navigasi -->
  
  <?php if (!$keyword): ?>
  <div class="container">
  <nav aria-label="Page navigation example">
	  <ul class="pagination">
	  	<!-- panah nomer -->
	  	<?php if( $halamanAktif > 1) : ?>
	    <li class="page-item"><a class="page-link" href="?halaman=<?= $halamanAktif - 1; ?>">Previous</a></li>
      <?php endif; ?>	
       <!-- panah nomer -->

	     <?php for($i =1; $i <= $jumlahHalaman; $i++) : ?>
	     	<?php if( $i == $halamanAktif) :?>
	      <li class="page-item active">
	      	<a class="page-link" href="?halaman=<?= $i; ?>" aria-current="page"><?= $i; ?></a>
	      </li>
	      <?php else : ?>
	     	<li class="page-item"><a class="page-link" href="?halaman=<?= $i; ?>"><?= $i; ?></a></li>
	      <?php endif; ?>
	    <?php endfor; ?>

	    <!-- panah nomer -->
	  	<?php if( $halamanAktif < $jumlahHalaman) : ?>
	    <li class="page-item"><a class="page-link" href="?halaman=<?= $halamanAktif + 1; ?>">Next</a></li>
	   <?php endif; ?>
	   <!-- panah nomer -->
	  </ul>
  </nav>
  </div>
  <?php endif; ?>

	<br>
	<div class=container>
		<table class="table" border="1" cellpadding="10" cellspacing="0">

			<tr>
				<th>No.</th>
				<th>Aksi</th>
				<th>Gambar</th>
				<th>NRP</th>
				<th>Nama</th>
				<th>Email</th>
				<th>Jurusan</th>
			</tr>

			<?php $i = 1; ?>
			<?php foreach ( $mahasiswa as $row) : ?>
				<tr>
					<td><?= $i; ?></td>
					<td>
						<button type="button" class="btn btn-success" role="button">
							<a class="ubah" href="ubah.php?id=<?= $row["id"]; ?> ">ubah</a></button>
							<button type="button" class="btn btn-danger" role="button">
								<a class="hapus" href="hapus.php?id=<?= $row["id"]; ?> " 
									onclick="return confirm('yakin?');">hapus</a></button>	
								</td>
								<td><img src="img/<?= $row["gambar"]; ?>" width="50"></td>
								<td><?= $row["nrp"]; ?></td>
								<td><?= $row["nama"]; ?></td>
								<td><?= $row["email"]; ?></td>
								<td><?= $row["jurusan"]; ?></td>
							</tr>
							<?php $i++; ?>
						<?php endforeach; ?>

					</table>
				</div>	

				<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
			</body>
			</html> 