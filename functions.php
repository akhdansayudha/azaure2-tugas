<?php
// koneksi ke database
$connection = mysqli_connect("Localhost", "root", "", "phpdasar");

function query($query){

	// global biar variable $connection di atas bisa di pakai
	global $connection;
	// $query ini dari file index.php -> query("SELECT * FROM mahasiswa");
	$result = mysqli_query($connection, $query); 
	$rows = [];
    while( $row = mysqli_fetch_assoc($result) ){
    	$rows[] = $row;
    }
    return $rows;
}

function tambah($data){
    global $connection;

    // ambil data dari tiap elemen dalam form
    $nama = htmlspecialchars($data["nama"]);
	$nrp = htmlspecialchars($data["nrp"]);
	$email = htmlspecialchars($data["email"]);
	$jurusan = htmlspecialchars($data["jurusan"]);

    // upload gambar
    $gambar = upload();
    if( !$gambar ){
    	return false;
    }

    // query insert data
	$queryMhs = "INSERT INTO mahasiswa
	         VALUES
	         ('', '$nama', '$nrp', '$email', '$jurusan', '$gambar')";

	 mysqli_query($connection, $queryMhs);

	 return mysqli_affected_rows($connection);

}

function upload() {

    $namaFile = $_FILES['gambar']['name'];
    $ukuranFile = $_FILES['gambar']['size'];
    $error = $_FILES['gambar']['error'];
    $tmpName = $_FILES['gambar']['tmp_name'];

    // cek apakah tidak ada gambar yang di upload
    if( $error == 4) {
    	echo "<script>
        alert('pilih gambar terlebih dahulu');
    	</script>";
    return false;	
    } 

    // cek apakah yang di upload adalah gambar
    $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
    $ekstensiGambar = explode('.', $namaFile); // memecah nama file misal getsu.jpg
    $ekstensiGambar = strtolower(end($ekstensiGambar)); // mengambil array yang paling terakhir dan merubah ke huruf kecil

    // mengecek ekstensinya ada tidak
    if( !in_array($ekstensiGambar, $ekstensiGambarValid) ){
    	echo "<script>
              alert( 'yang anda upload bukan gambar!');
    	      </script>";
    	return false;      
    }
	

	// cek jika ukuran tertalu besar
    if( $ukuranFile > 1000000 ) {
    	echo "<script>
               alert('ukuran gambar terlalu besar');
    	     </script>";
    	return false;
    }

    // lolos pengecekan, gambar siap di upload
    // generate nama gambar baru
    $namaFileBaru = uniqid();
    $namaFileBaru .= '.';
    $namaFileBaru .= $ekstensiGambar;

    move_uploaded_file($tmpName, 'img/' . $namaFileBaru);

    // ini supaya ketika gambar di upload isi $gambar adalah nama filenya
    // sehingga bisa di masukan ke query INSERT INTO mahasiswa VALUES '$gambar'
    return $namaFileBaru;

}

function hapus($id){
	global $connection;
	mysqli_query($connection, "DELETE FROM mahasiswa WHERE id = $id");

	return mysqli_affected_rows($connection);
}

function ubah($data)  {
    global $connection;

    $id = $data["id"];
    $nama = htmlspecialchars($data["nama"]);
	$nrp = htmlspecialchars($data["nrp"]);
	$email = htmlspecialchars($data["email"]);
	$jurusan = htmlspecialchars($data["jurusan"]);
	$gambarLama = htmlspecialchars($data["gambarLama"]);

    // cek apakah user pilih gambar baru atau tidak
    if( $_FILES['gambar']['error'] === 4){
    	$gambar = $gambarLama;
    } else {
    	$gambar = upload();
    }


	

    // query insert data
	$queryMhs = "UPDATE mahasiswa SET 
                 nrp = '$nrp',
                 nama = '$nama',
                 email = '$email',
                 jurusan = '$jurusan',
                 gambar = '$gambar' 
                 WHERE id = $id  
	         ";

	 mysqli_query($connection, $queryMhs);

	 return mysqli_affected_rows($connection);
}

function cari($keyword){
     // untuk menulis querynya
	 // LIKE : agar keyword yang di cari bisa menemukan kata (nama) yang mirip sesuai di inputkan
	 // % depan dan belakang : agar nama keyword depan belakang bisa di temukan
	$queryKeyword = "SELECT * FROM mahasiswa
	          WHERE
	          nama LIKE '%$keyword%' OR 
	          nrp LIKE '%$keyword%' OR
	          email LIKE '%$keyword%' OR
	          jurusan LIKE '%$keyword%'
	          ";
    return query($queryKeyword);
}

function registrasi($data) {
    global $connection;

    // stripcslashes : ketika user input blackslasher akan di hapus
    // strtolower : ketika user input huruf besar makan akan di ubah ke huruf kecil
    $username = stripcslashes($data["username"]);
    // jika ada user yang masukan tanda kutip makan tetap akan masuk ke database
    $password = mysqli_real_escape_string($connection, $data["password"]);
    $password2 = mysqli_real_escape_string($connection, $data["password2"]);

    // cek username sudah ada atau belum
    $result = mysqli_query($connection, "SELECT username FROM user WHERE username ='$username'");
    
    // jika fungsi ini menghasilkan nilai true berarti sudah ada user / sama
    if( mysqli_fetch_assoc($result) ) {
        echo "<script>
            alert('username sudah terdaftar')
            document.location.href = 'registrasi.php';
             </script>";
        return false;     
    }

    // cek konfirmasik password
    // if( $password !== $password2 ){
       // echo "<script>
          //      alert('konfirmasi password tidak sesuai');
          //      document.location.href = 'registrasi.php';
          //    </script>";
        // return false;
    // } 
    
    // enkripsi password
    // password_hash : untuk mengacak password
    $password = password_hash($password, PASSWORD_DEFAULT);

    // tambahkan userbaru ke database
    // tanda VALUES('', '') id dikosongkan karna id auto increment
    mysqli_query($connection, "INSERT INTO user VALUES('', '$username', '$password')");

    // untuk menghasilkan angka 1 jika berhasil, dan kosong jika gagal
    return mysqli_affected_rows($connection);

}

?> 