<?php
session_start();

// cek session ada atau tidak
// kalau tidak ada tidak boleh login
if( !isset($_SESSION["login"]) ) {
     header("Location:login.php");
     exit;

}

require 'functions.php'; 

$id = $_GET["id"];

if( hapus($id) > 0){
	echo "
		    <script>
                   alert('data berhasil dihapus!');
                   document.location.href = 'index.php';
		    </script>
		";
} else {
	echo "
		    <script>
                   alert('data gagal dihapus!');
                   document.location.href = 'index.php';
		    </script>
		";
}


?>