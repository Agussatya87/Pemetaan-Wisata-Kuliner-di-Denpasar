<?php
$id = $_POST['id'];
    $nama = $_POST['nama'];
    $info = $_POST['info'];
    $alamat = $_POST['alamat'];
    $kecamatan = $_POST['kecamatan'];
    $kelurahan = $_POST['kelurahan'];
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];
    $kategori = $_POST['kategori'];

    echo $id, $nama, $info, $alamat, $kategori, $kecamatan, $kelurahan, $lat, $lng;

?>