<?php
include "validasi_session.php";
include "koneksi.php";

/** KECAMATAN UNTUK SELECT OPTION */
$sqlKecamatan = "SELECT * FROM kecamatan ORDER BY nama_kecamatan ASC";
$listKecamatan = mysqli_query($conn, $sqlKecamatan);


/** KELURAHAN DARI JAVASCRIPT AJAX */
if (isset($_GET['id_kecamatan'])) {
    if ($_GET['id_kecamatan'] != 0) {
        $result = [];

        $sqlKelurahan = "SELECT * FROM kelurahan WHERE id_kecamatan=".$_GET['id_kecamatan']." ORDER BY nama_kelurahan ASC";
        $listKelurahan = mysqli_query($conn, $sqlKelurahan);
        $rowKelurahan = [];
        while ($row = mysqli_fetch_assoc($listKelurahan)) {
            $temp = [];
            $temp['id_kelurahan'] = $row['id_kelurahan'];
            $temp['id_kecamatan'] = $row['id_kecamatan'];
            $temp['nama_kelurahan'] = $row['nama_kelurahan'];
            $rowKelurahan[] = $temp;
        }
        echo json_encode($rowKelurahan);
        exit;
    }
}

/** DATA LOKASI DAN POLIGON */
if(isset($_POST['filter'])){
    $result = [];
    $id_kecamatan = $_POST['id_kecamatan'];
    $id_kelurahan = $_POST['id_kelurahan'];
    $kategori = $_POST['kategori'];

    if($id_kecamatan == 0){
        $sqlPoligon = "SELECT * FROM poligon";
    }else{
        $sqlPoligon = "SELECT * FROM poligon WHERE id_kecamatan=".$id_kecamatan;
    }

    $listPoligon = mysqli_query($conn, $sqlPoligon);
    $rowPoligon = [];
    while ($row = mysqli_fetch_assoc($listPoligon)) {
        $rowPoligon[] = [$row['lat'], $row['lng']];
    }
    $result['poligon'] = $rowPoligon;
    echo json_encode($result);exit;
}
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link href="https://api.tiles.mapbox.com/mapbox-gl-js/v1.6.1/mapbox-gl.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css" rel="stylesheet">
    <title>Admin</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">ADMIN</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="halaman_admin.php">Beranda <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="halaman_halal.php">Halal</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="halaman_nonhalal.php">Non Halal</a>
                </li>
            </ul>
            <span class="form-inline my-2 my-lg-0">
                <a class="btn btn-warning my-2 my-sm-0">Logout</a>
            </span>
        </div>
    </nav>

    <div class="wrapper container my-2">
        <div class="row">
            <div class="col-sm-12">
                            <a href="tambah_data.php?kategori=nonhalal" class="btn btn-primary float-right">Tambah Data Kuliner Non Halal</a>
                            <br><br>
                            <div class="table-responsive">
                            <table id="example" class="display nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Info</th>
                            <th>Alamat</th>
                            <th>Kecamatan</th>
                            <th>Kelurahan</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            <th>Option</th>
                        </tr>
                    </thead>
                    <tbody>
                            <?php
                                $query=mysqli_query($conn,"SELECT * FROM lokasi WHERE kategori = 'nonhalal'");
                                while($row=mysqli_fetch_assoc($query)){
                            $id = $row['id_lokasi'];
                            $nama = $row['nama'];
                            $info = $row['info'];
                            $alamat = $row['alamat'];
                            $kategori = $row['kategori'];
                            $kecamatan = $row['id_kecamatan'];
                            $kelurahan = $row['id_kelurahan'];
                            $lat = $row['lat'];
                            $lng = $row['lng'];
                            echo "
                            <tr>
                            <td>$nama</td>
                            <td>$info</td>
                            <td>$alamat</td>
                            <td>$kecamatan</td>
                            <td>$kelurahan</td>
                            <td>$lat</td>
                            <td>$lng</td>
                            <td><a class='btn btn-warning' href='edit_data.php?id=$id'>Edit</a> <a class='btn btn-danger' href='proses.php?p=hapus_lokasi&&id=$id'>Delete</a></td>
                            </tr>";
                                }
                            ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Nama</th>
                            <th>Info</th>
                            <th>Alamat</th>
                            <th>Kecamatan</th>
                            <th>Kelurahan</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            <th>Option</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            </div>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous">
    </script>
    <script src='https://api.mapbox.com/mapbox-gl-js/v1.2.0/mapbox-gl.js'></script>
    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#example').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                'excel', 'pdf', 'print'
            ]
        } );
    } );    
</script>
</body>

</html>