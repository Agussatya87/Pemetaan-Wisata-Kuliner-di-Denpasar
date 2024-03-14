<?php
include "validasi_session.php";
include "koneksi.php";
/** JUMLAH KECAMATAN, KELURAHAN, Halal, Non Halal */
$sql_count_kecamatan = "SELECT COUNT(id_kecamatan) AS jumlah_kecamatan FROM kecamatan";
$countKecamatan = mysqli_query($conn, $sql_count_kecamatan);
while ($row = mysqli_fetch_assoc($countKecamatan)) {
    $jumlahKecamatan = $row['jumlah_kecamatan'];
}

$sql_count_kelurahan = "SELECT COUNT(id_kelurahan) AS jumlah_kelurahan FROM kelurahan";
$countKelurahan = mysqli_query($conn, $sql_count_kelurahan);
while ($row = mysqli_fetch_assoc($countKelurahan)) {
    $jumlahKelurahan = $row['jumlah_kelurahan'];
}

$sql_count_halal = "SELECT COUNT(id_lokasi) AS jumlah_halal FROM lokasi WHERE kategori = 'halal'";
$countHalal = mysqli_query($conn, $sql_count_halal);
while ($row = mysqli_fetch_assoc($countHalal)) {
    $jumlahHalal = $row['jumlah_halal'];
}

$sql_count_nonhalal = "SELECT COUNT(id_lokasi) AS jumlah_nonhalal FROM lokasi WHERE kategori = 'nonhalal'";
$countNonhalal = mysqli_query($conn, $sql_count_nonhalal);
while ($row = mysqli_fetch_assoc($countNonhalal)) {
    $jumlahNonhalal = $row['jumlah_nonhalal'];
}

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
if (isset($_POST['filter'])) {
    $result = [];
    $id_kecamatan = $_POST['id_kecamatan'];
    $id_kelurahan = $_POST['id_kelurahan'];
    $kategori = $_POST['kategori'];

    $sql_lokasi = "SELECT lokasi.*, kecamatan.nama_kecamatan, kelurahan.nama_kelurahan FROM lokasi 
            INNER JOIN kecamatan ON lokasi.id_kecamatan = kecamatan.id_kecamatan
            INNER JOIN kelurahan ON lokasi.id_kelurahan = kelurahan.id_kelurahan";

    if ($id_kecamatan != 0 || $id_kelurahan != 0 || $kategori != "") {
        $and_kecamatan = false;
        $and_lokasi = false;

        $sql_lokasi .= " WHERE ";
        if ($id_kecamatan != 0) {
            $sql_lokasi .= " kecamatan.id_kecamatan = ".$id_kecamatan;
            $and_kecamatan = true;
        }
        if ($id_kelurahan != 0) {
            $sql_lokasi .= $and_kecamatan === true ? " AND " : "";
            $sql_lokasi .= " kelurahan.id_kelurahan = ".$id_kelurahan;
            $and_lokasi = true;
        }
        if ($kategori != "") {
            $sql_lokasi .= $and_lokasi === true ? " AND " : "";
            $sql_lokasi .= " lokasi.kategori = '$kategori'";
        }
    }

    $listLokasi = mysqli_query($conn, $sql_lokasi);
    $rowLokasi = [];
    $id_lokasi = [];
    while ($row = mysqli_fetch_assoc($listLokasi)) {
        $rowLokasi[] = $row;
        $id_lokasi[] = $row['id_lokasi'];
    }

    $sql_foto = "SELECT * FROM foto WHERE id_lokasi IN (".implode(",", $id_lokasi).")";
    $listFoto = mysqli_query($conn, $sql_foto);
    $rowFoto = [];

    while ($row = mysqli_fetch_assoc($listFoto)) {
        $temp = [];
        $temp['id_lokasi'] = $row['id_lokasi'];
        $temp['nama'] = $row['nama'];
        $rowFoto[] = $temp;
    }
    foreach ($rowLokasi as $indexLokasi => $lokasi) {
        foreach ($rowFoto as $foto) {
            if ($lokasi['id_lokasi'] == $foto['id_lokasi']) {
                $rowLokasi[$indexLokasi]['foto'][] = $foto['nama'];
            }
        }
    }

    $sql_poligon = "SELECT * FROM poligon ";
    $sql_poligon .= $id_kecamatan != 0 ? "WHERE id_kecamatan=".$id_kecamatan : "WHERE id_kecamatan = 4";

    $listPoligon = mysqli_query($conn, $sql_poligon);
    $rowPoligon = [];
    
    while ($row = mysqli_fetch_assoc($listPoligon)) {
        $rowPoligon[] = [$row['lat'], $row['lng']];
    }

    $result['poligon'] = $rowPoligon;
    $result['lokasi'] = $rowLokasi;
    $data = $result;
    echo json_encode($data);
    exit;
}

$sql_count_halal = "SELECT COUNT(id_lokasi) AS jumlah_halal FROM lokasi WHERE kategori = 'halal'";
$countHalal = mysqli_query($conn, $sql_count_halal);
$halal = mysqli_fetch_assoc($countHalal);

$sql_count_nonhalal = "SELECT COUNT(id_lokasi) AS jumlah_nonhalal FROM lokasi WHERE kategori = 'nonhalal'";
$countNonhalal = mysqli_query($conn, $sql_count_nonhalal);
$nonhalal = mysqli_fetch_assoc($countNonhalal);

    $dataPoints = array( 
        array("y" => $halal['jumlah_halal'],"label" => "Halal" ),
        array("y" => $nonhalal['jumlah_nonhalal'],"label" => "Non Halal" )
    );
    
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
    <style>
    .marker-nonhalal {
        background-image: url('halal.png');
        background-size: cover;
        width: 30px;
        height: 30px;
        cursor: pointer;
    }

    .marker-halal {
        background-image: url('non-halal.png');
        background-size: cover;
        width: 30px;
        height: 30px;
        cursor: pointer;
    }
</style>
    <title>Panel Admin</title>
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
                <a class="btn btn-warning my-2 my-sm-0" href="session_logout.php">Logout</a>
					
            </span>
        </div>
    </nav>

    <div class="wrapper container my-2">
        <div class="row">
            <span class="col">
                <div class="card text-white bg-info mb-3 mx-1">
                    <div class="card-body">
                        <h5 class="card-title text-center">Kecamatan</h5>
                        <h2 class="card-text text-center"><?php echo $jumlahKecamatan ?></h2>
                    </div>
                </div>
            </span>
            <span class="col">
                <div class="card text-white bg-warning mb-3 mx-1">
                    <div class="card-body">
                        <h5 class="card-title text-center">Kelurahan</h5>
                        <h2 class="card-text text-center"><?php echo $jumlahKelurahan ?></h2>
                    </div>
                </div>
            </span>
            <span class="col">
                <div class="card text-white bg-success mb-3 mx-1">
                    <div class="card-body">
                        <h5 class="card-title text-center">Halal</h5>
                        <h2 class="card-text text-center"><?php echo $jumlahHalal ?></h2>
                    </div>
                </div>
            </span>
            <span class="col">
                <div class="card text-white bg-primary mb-3 mx-1">
                    <div class="card-body">
                        <h5 class="card-title text-center">Non Halal</h5>
                        <h2 class="card-text text-center"><?php echo $jumlahNonhalal ?></h2>
                    </div>
                </div>
            </span>
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <select class="form-control" id="kecamatan" name="kecamatan">
                                        <option value="0">Semua Kecamatan</option>
                                        <?php
                                            while ($row = mysqli_fetch_assoc($listKecamatan)) {
                                                ?>
                                        <option value="<?php echo $row['id_kecamatan']; ?>">
                                            <?php echo $row['nama_kecamatan']; ?></option>
                                        <?php
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <select class="form-control" id="kelurahan" name="kelurahan">
                                        <option value='0'>Semua Kelurahan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <select class="form-control" id="kategori" name="kategori">
                                        <option value="">Semua Kategori</option>
                                        <option value="umkm">Halal</option>
                                        <option value="wisata">Non Halal</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <button class="btn btn-primary btn-block" id="filter">FILTER</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div id='map' class="mt-4" style='width: 100%; height: 600px;'></div>
                    </div>
                    <div class="col-sm-12">
                        <br><br>
                        <div id="chartContainer" style="height: 370px; width: 100%;"></div>
                    </div>
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
    <script src='https://api.mapbox.com/mapbox-gl-js/v2.14.1/mapbox-gl.js'></script>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <script>
        $(function() {
            $('#kecamatan').trigger('change');
            $('#filter').trigger('click');
        });

        /**
         * kelurahan berdasarkan kecamatan
         */
        $('#kecamatan').on('change', function () {
            if($(this).val() == 0){
                $('#kelurahan').empty();
                var kelurahan = '';
                kelurahan += "<option value='0'>Semua Kelurahan</option>";
                $('#kelurahan').append(kelurahan);
            } else {
                $.ajax({
                    type: 'GET',
                    data: {
                        id_kecamatan: $(this).val(),
                    },
                    dataType: 'json',
                    success: function (data) {
                        $('#kelurahan').empty();
                        var kelurahan = '';
                        kelurahan += "<option value='0'>Semua Kelurahan</option>";
                        for (var i = 0; i < data.length; i++) {
                            kelurahan += "<option value='" + data[i].id_kelurahan +
                                "' nama_kelurahan='" + data[i].nama_kelurahan +
                                "'>" + data[i].nama_kelurahan +
                                "</option>";
                        }
                        $('#kelurahan').append(kelurahan);
                    }
                });
            }
        });


        /** 
         * FILTER ON CLICK
         */
        $('#filter').on('click', function() {
            var id_kecamatan = $('#kecamatan option:selected').val();
            var id_kelurahan = $('#kelurahan option:selected').val();
            var kategori = $('#kategori option:selected').val();
            $.ajax({
                type: 'POST',
                data: {
                    filter: true,
                    id_kecamatan: id_kecamatan,
                    id_kelurahan: id_kelurahan,
                    kategori: kategori,
                },
                dataType: 'json',
                success: function (data) {
                    showPolygon(data.poligon, data.lokasi);
                }
            });
        });

        /** 
         * mapbox
         */
        
        mapboxgl.accessToken = 
            'pk.eyJ1IjoiZ2FzdGE3NyIsImEiOiJjbG9maWdseTQwbm81MnJxY2hxYjV6ZmxkIn0.Q4VhTnOV2eCBMYuQ0F6ZeQ';
        function showPolygon(coordinates, lokasi){
            var map = new mapboxgl.Map({
                        container: 'map',
                        style: 'mapbox://styles/mapbox/streets-v12',
                        center: [115.216481, -8.656251],
                        zoom: 12,
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
            });

            lokasi.forEach(data => {

                var el = document.createElement('div');
                el.className = data.kategori == 'nonhalal' ? 'marker-nonhalal' : 'marker-halal';

                var html = '';
                html += "<b>" + data['nama'] + " (" + data['kategori'] + ")</b>";
                html += "<p>"+data['alamat']+"</p>";
                html += data['info'];

                new mapboxgl
                    .Marker(el)
                    .setLngLat([data['lng'], data['lat']])
                    .setPopup(new mapboxgl.Popup({
                        offset: 50
                    }).setHTML(html))
                    .addTo(map);
            });
        }

        window.onload = function() {
 
        var chart = new CanvasJS.Chart("chartContainer", {
            animationEnabled: true,
            title:{
                text: "Jumlah Per Kategori"
            },
            data: [{
                type: "bar",
                yValueFormatString: "#,##0 Lokasi",
                indexLabel: "{y}",
                indexLabelPlacement: "inside",
                indexLabelFontWeight: "bolder",
                indexLabelFontColor: "white",
                dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
            }]
        });
        chart.render();
         
        }
    </script>
</body>

</html>