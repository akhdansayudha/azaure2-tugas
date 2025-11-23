<?php
session_start();

// cek session ada atau tidak
// kalau tidak ada tidak boleh login
if( !isset($_SESSION["login"]) ) {
     header("Location:login.php");
     exit;

}

require 'functions.php';

// ambil data di url
$id = $_GET["id"];

// query data mahasiswa berdasarkan id, [0] masuk kedalam index luarnya
$mhs = query("SELECT * FROM mahasiswa WHERE id = $id")[0];


// cek apakah tombol submit sudah di tekan atau belum
if( isset($_POST["submit"])){
  
	// cek apakah data berhasil di ubah atau tidak
	if( ubah($_POST) > 0){

		echo "
		    <script>
                   alert('data berhasil diubah!');
                   document.location.href = 'index.php';
		    </script>
		";
	} else {
		echo "
		    <script>
                   alert('data gagal diubah!');
                   document.location.href = 'index.php';
		    </script>
		";
	}    
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Ubah Data Mahasiswa</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
	 <link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav class="navbar bg-primary" data-bs-theme="dark">
	<div class="container">
	  	<div class="container-fluid">
	    <h1 class="navbar-brand">
	      <img src="icon.png" alt="Logo" width="45" height="45" class="d-inline-block align-text-top">
	      Ubah Data Mahasiswa
	    </h1>
	    </div>
	</div>
</nav>

<br><br>

<div class="container">
	<!-- enctype="multipart/form-data ini untuk mengelola data file -->
    <form action="" method="post" enctype="multipart/form-data">
    	<input type="hidden" name="id" value="<?= $mhs["id"];?>">
    	<!--  gambar lama di kirimkan jika user tidak mau mengupdate gambar -->
    	<input type="hidden" name="gambarLama" value="<?= $mhs["gambar"];?>">
      <div class="mb-3">
      	   <label for="nama" class="form-label">Nama</label>
	      	 <input type="text" class="form-control" name="nama" id="id" required value="<?= $mhs["nama"];?>">
      </div>
      <div class="mb-3">
      	   <label for="nrp" class="form-label">NRP</label>
	      	 <input type="text" class="form-control" name="nrp" id="nrp" required value="<?= $mhs["nrp"];?>">
      </div>
      <div class="mb-3">
      	   <label for="email" class="form-label">Email</label>
	      	 <input type="text" class="form-control" name="email" id="email" required value="<?= $mhs["email"];?>">
      </div>
       <div class="mb-3">
      	   <label for="jurusan" class="form-label">Jurusan</label>
	      	 <input type="text" class="form-control" name="jurusan" id="jurusan" required value="<?= $mhs["jurusan"];?>">
      </div>	   
       <div class="mb-3">
      	   <label for="gambar" class="form-label">Gambar</label> <br>
      	   <img src="img/<?= $mhs['gambar']; ?>"> <br>
	      	 <input type="file" class="form-control" name="gambar" id="gambar">
      </div>	 
       <button type="submit" class="btn btn-primary" name="submit" >Tambah Data</button>
    </form>
</div>
     
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>