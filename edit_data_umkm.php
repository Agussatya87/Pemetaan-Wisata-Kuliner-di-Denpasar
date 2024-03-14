<?php
include "validasi_session.php";
include "koneksi.php";

/** KECAMATAN UNTUK SELECT OPTION */
$id = $_GET['id'];
$sqlKecamatan = "SELECT * FROM kecamatan ORDER BY nama_kecamatan ASC";
$listKecamatan = mysqli_query($conn, $sqlKecamatan);

$sqlKelurahan = "SELECT * FROM kelurahan";
$listKelurahan = mysqli_query($conn, $sqlKelurahan);

$sqlFoto = "SELECT * FROM foto WHERE id_lokasi=".$id;
$listFoto = mysqli_query($conn, $sqlFoto);


/** KELURAHAN DARI JAVASCRIPT AJAX */
if (isset($_GET['id_kecamatan'])) {
    if ($_GET['id_kecamatan'] != 0) {
        $getKec = $_GET['id_kecamatan'];
        $result = [];

        $sqlPoligon = "SELECT * FROM poligon WHERE id_kecamatan=".$getKec;
        $listPoligon = mysqli_query($conn, $sqlPoligon);
        $rowPoligon = [];
        while ($row = mysqli_fetch_assoc($listPoligon)) {
            $rowPoligon[] = [$row['lat'], $row['lng']];
        }
        $result['poligon'] = $rowPoligon;

        echo json_encode($result);
        exit;
    }
}

$query = mysqli_query($conn,"SELECT * FROM lokasi WHERE id_lokasi = '$id'");
                while($value = mysqli_fetch_assoc($query))
                {
                $nama = $value['nama'];
                $info = $value['info'];
                $alamat = $value['alamat'];
                $kategori = $value['kategori'];
                $kecamatan = $value['id_kecamatan'];
                $kelurahan = $value['id_kelurahan'];
                $lat = $value['lat'];
                $lng = $value['lng'];

                $items = array("nama" => "$nama", "info" => "$info", "alamat" => "$alamat", "kategori" => "kategori", "kecamatan" => "$kecamatan", "kelurahan" => "$kelurahan", "lat" => "$lat", "lng" => "$lng");

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
                    <a class="nav-link active" href="halaman_admin.php">Beranda <span
                            class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="halaman_umkm.php">UMKM</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="halaman_wisata.php">Wisata</a>
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
                <div id='map' class="mt-4" style='width: 100%; height: 600px;'></div>
            </div>
            <div class="col-sm-12 mt-4">
                <form action="proses.php?p=tambah_<?php echo $items['kategori']; ?>" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-success font-weight-bold text-light" id="label-latitude">Latitude</span>
                                        </div>
                                        <input type="text" class="form-control" id="lat" name="lat" aria-describedby="label-latitude" value="<?php echo $items['lat']; ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-success font-weight-bold text-light" id="label-longitude">Longitude</span>
                                        </div>
                                        <input type="text" class="form-control" id="lng" name="lng" aria-describedby="label-longitude" value="<?php echo $items['lng']; ?>" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="">Nama</label>
                                <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $items['nama']; ?>" placeholder="Nama">
                            </div>
                            <div class="form-group">
                                <label for="">Kecamatan</label>
                                <select class="form-control" id="kecamatan" name="kecamatan">
                                    <option value="0">Semua Kecamatan</option>
                                    <?php
                                            while ($row = mysqli_fetch_assoc($listKecamatan)) {
                                                ?>
                                    <option value="<?php echo $row['id_kecamatan']; ?>" >
                                        <?php echo $row['nama_kecamatan']; ?></option>
                                    <?php
                                            }
                                        ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Kelurahan</label>
                                <select class="form-control" id="kelurahan" name="kelurahan">
                                    <option value='0'>Semua Kelurahan</option>
                                    <?php
                                            while ($row = mysqli_fetch_assoc($listKelurahan)) {
                                                ?>
                                    <option class="<?php echo $row['id_kecamatan']; ?>" value="<?php echo $row['id_kelurahan']; ?>" >
                                        <?php echo $row['nama_kelurahan']; ?></option>
                                    <?php
                                            }
                                        ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="">Alamat</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="5"><?php echo $items['alamat']; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="">Foto</label>
                                <input type="file" class="form-control" name="foto[]" accept="image/*" multiple>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <label for="">Info</label>
                            <textarea class="ckeditor" id="ckedtor" name="info"><?php echo $items['info']; ?></textarea>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-primary float-right">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-sm-12">
                <br><br>
                <?php
                while ($row = mysqli_fetch_assoc($listFoto)) {
                ?>
                    <img src="images/<?php echo $kategori.'/'.$row['nama'] ?>" class="img-thumbnail" alt="Cinque Terre">
                <?php
                }
                ?>
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
    <script type="text/javascript" src="ckeditor/ckeditor.js"></script>
    <script src="jquery.chained.js"></script>
    <script>
        $(function () {
            mapboxgl.accessToken =
            'SILAHKAN ISI DENGAN KODE TOKEN YANG SUDAH ANDA DAPATKAN DARI MAPBOX';

            var map = new mapboxgl.Map({
                container: 'map',
                style: 'mapbox://styles/mapbox/outdoors-v11',
                center: [107.533867, -6.899541],
                zoom: 11,
            });

            marker = new mapboxgl.Marker();
        });

        /**
         * kelurahan berdasarkan kecamatan
         */
        $('#kecamatan').on('change', function () {
            $.ajax({
                type: 'GET',
                data: {
                    id_kecamatan: $(this).val(),
                },
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                    showPolygon(data.poligon);
                    getKecamatan();
                }
            });
        });


        /** 
         * mapbox
         */

        function showPolygon(coordinates) {
            var map = new mapboxgl.Map({
                container: 'map',
                style: 'mapbox://styles/mapbox/outdoors-v11',
                center: [107.533867, -6.899541],
                zoom: 11,
            });

            map.on('load', function () {
                map.addLayer({
                    'id': 'maine',
                    'type': 'fill',
                    'source': {
                        'type': 'geojson',
                        'data': {
                            'type': 'Feature',
                            'geometry': {
                                'type': 'Polygon',
                                'coordinates': [coordinates]
                            }
                        }
                    },
                    'layout': {},
                    'paint': {
                        'fill-color': '#088',
                        'fill-opacity': 0.5
                    }
                });

                /** HANYA BISA TAMBAH MARKER DI DALAM POLIGON */
                map.on('click', 'maine', function(e) {
                    var lat = e.lngLat.lat;
                    var lng = e.lngLat.lng;
                    marker.setLngLat([lng, lat]).addTo(map);
                    $('#lat').val(lat);
                    $('#lng').val(lng);
                }); 
            });
        }


        $("#kecamatan").val("<?php echo $items['kecamatan']; ?>").attr('selected','selected');
        $("#kelurahan").val("<?php echo $items['kelurahan']; ?>").attr('selected','selected');

    $("#kelurahan").chainedTo("#kecamatan");
      $("#kecamatan").on("change", function () {
        $('#kecamatan option').prop('selected', function() {
            return this.defaultSelected;
        });
        $('#kelurahan option').prop('selected', function() {
            return this.defaultSelected;
        });
    });

    </script>
</body>

</html>