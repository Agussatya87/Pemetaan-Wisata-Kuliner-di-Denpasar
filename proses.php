<?php
include "koneksi.php";

if($_GET['p']=='tambah_lokasi'){
    session_start();
    $idadmin = $_SESSION['admin']['id_admin'];

    $namalokasi = $_POST['nama'];
    $info = $_POST['info'];
    $alamat = $_POST['alamat'];
    $kecamatan = $_POST['kecamatan'];
    $kelurahan = $_POST['kelurahan'];
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];
    $kategori = $_POST['kategori'];

    $sql = mysqli_query($conn,"INSERT INTO lokasi VALUES ('','$namalokasi','$info','$alamat','$kategori','$kecamatan','$kelurahan','$lat','$lng','$idadmin')");

    $id = mysqli_query($conn,"SELECT id_lokasi FROM lokasi WHERE kategori = '$kategori' ORDER BY id_lokasi DESC LIMIT 1");
    if($id){
        $row = mysqli_fetch_assoc($id);
        $roww = $row['id_lokasi'];
        if(!empty($_FILES["foto"]["tmp_name"])){
            $uploads_dir = 'images/'.$kategori;
            foreach ($_FILES["foto"]["error"] as $key => $error) {
                if ($error == UPLOAD_ERR_OK) {
                    $tmp_name = $_FILES["foto"]["tmp_name"][$key];
                    // basename() may prevent filesystem traversal attacks;
                    // further validation/sanitation of the filename may be appropriate
                    $nama = basename($_FILES["foto"]["name"][$key]);
                    $ext = pathinfo($nama, PATHINFO_EXTENSION);
                    $keyy = $key + 1;
                    $name = $namalokasi.$keyy.".".$ext;
                    move_uploaded_file($tmp_name, "$uploads_dir/$name");
                    $sql = mysqli_query($conn,"INSERT INTO foto VALUES ('','$roww','$name')");
                }
            }
        }
    }

    if($sql){
        header("Location: halaman_".$kategori.".php");
    } 
}

if($_GET['p']=='ubah_lokasi'){
    session_start();
    $idadmin = $_SESSION['admin']['id_admin'];

    $id = $_POST['id'];
    $namalokasi = $_POST['nama'];
    $info = $_POST['info'];
    $alamat = $_POST['alamat'];
    $kecamatan = $_POST['kecamatan'];
    $kelurahan = $_POST['kelurahan'];
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];
    $kategori = $_POST['kategori'];

    $sql = mysqli_query($conn,"UPDATE lokasi SET nama = '$namalokasi', info = '$info', alamat = '$alamat', kategori = '$kategori', id_kecamatan = '$kecamatan', id_kelurahan = '$kelurahan', lat = '$lat', lng = '$lng', id_admin = '$idadmin' WHERE id_lokasi = '$id'");

    $countFoto = mysqli_query($conn,"SELECT count(nama) as jumlah FROM foto WHERE nama = '$namalokasi'");
    if($countFoto){
        $row = mysqli_fetch_assoc($countFoto);
        $roww = $row['jumlah'];
        if(!empty($_FILES["foto"]["tmp_name"])){
            $uploads_dir = 'images/'.$kategori;
            foreach ($_FILES["foto"]["error"] as $key => $error) {
                if ($error == UPLOAD_ERR_OK) {
                    $tmp_name = $_FILES["foto"]["tmp_name"][$key];
                    // basename() may prevent filesystem traversal attacks;
                    // further validation/sanitation of the filename may be appropriate
                    $nama = basename($_FILES["foto"]["name"][$key]);
                    $ext = pathinfo($nama, PATHINFO_EXTENSION);
                    $keyy = $roww + 1;
                    $name = $namalokasi.$keyy.".".$ext;
                    move_uploaded_file($tmp_name, "$uploads_dir/$name");
                    $sql = mysqli_query($conn,"INSERT INTO foto VALUES ('','$id','$name')");
                }
            }
        }
    }

    if($sql){
        header("Location: halaman_".$kategori.".php");
    } 
}


if($_GET['p']=='hapus_lokasi'){
        $id = $_GET['id'];
        $query = mysqli_query($conn,"SELECT kategori FROM lokasi WHERE id_lokasi = '$id'");
        $getKategori = mysqli_fetch_assoc($query);
        $kategori = $getKategori['kategori'];
        $sql=mysqli_query($conn,"DELETE FROM lokasi WHERE id_lokasi = '$id'");

        $select=mysqli_query($conn,"SELECT * FROM foto WHERE id_lokasi = '$id'");
        while($row = mysqli_fetch_assoc($select))
        {
            $nama = $row['nama'];
            $file_to_delete = 'images/'.$kategori.'/'.$nama;
            unlink($file_to_delete);
        }

        $sql2=("DELETE FROM foto WHERE id_lokasi = '$id'");
        if($sql)
        {
            header("Location: halaman_".$kategori.".php");
        }
}

if($_GET['p']=='hapus_foto'){
        $id = $_GET['id'];
        $query = mysqli_query($conn,"SELECT kategori FROM lokasi WHERE id_lokasi = '$id'");
        $getKategori = mysqli_fetch_assoc($query);
        $kategori = $getKategori['kategori'];
        $select=mysqli_query($conn,"SELECT * FROM foto WHERE id_foto = '$id'");
        while($row = mysqli_fetch_assoc($select))
        {
            $nama = $row['nama'];
            $file_to_delete = 'images/'.$kategori.'/'.$nama;
            unlink($file_to_delete);
        }
        $sql=mysqli_query($conn,"DELETE FROM foto WHERE id_foto = '$id'");
        if($sql)
        {
            header("Location: halaman_".$kategori.".php");
        }
}
?>