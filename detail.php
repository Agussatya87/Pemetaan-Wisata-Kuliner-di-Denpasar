<!DOCTYPE html>
<html>
<?php
include"koneksi.php";
$id = $_GET['id'];
$case = $_GET['p'];
if($case == "nonhalal")
{
  $folder = "nonhalal";
  $icon = "nonhalal";
}
if($case == "halal")
{
  $folder = "halal";
  $icon = "halal";
}
$result = mysqli_query($conn,"SELECT * FROM lokasi WHERE id_lokasi = $id");
$row = mysqli_fetch_assoc($result);
$nama = $row['nama'];
$info = $row['info'];
$alamat = $row['alamat'];
$kat = $row['kategori'];
$kec = $row['id_kecamatan'];
$kel = $row['id_kelurahan'];
$lat = $row['lat'];
$lng = $row['lng'];
?>
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
        background-image: url('non-halal.png');
        background-size: cover;
        width: 30px;
        height: 30px;
        cursor: pointer;
    }

    .marker-halal {
        background-image: url('halal.png');
        background-size: cover;
        width: 30px;
        height: 30px;
        cursor: pointer;
    }
</style>
    <title>Admin</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="index.php">Beranda <span class="sr-only">(current)</span></a>
                </li>
            </ul>
        </div>
    </nav>
  <div class="pt-5">
    <div class="container">
      <div class="row">
        <div class="mx-auto col-md-12 px-4">
          <h2><?php echo $nama ;?></h2>
        </div>
      </div>
      <div class="row">
              <div class="col-md-6" >
              <div class="carousel slide" data-ride="carousel" id="carousel">
                <div class="carousel-inner">
                  <?php
$no = 1;
$sqlfoto = mysqli_query($conn,"SELECT * FROM foto WHERE id_lokasi = $id");
while($rowfoto = mysqli_fetch_assoc($sqlfoto))
{
$foto = $rowfoto['nama'];
  if($no==1)
  {
?>
              <div class="carousel-item active"> <img class="d-block img-fluid w-100" src="images/<?php echo $folder."/".$foto ;?>">
                <div class="carousel-caption">
                  <h5 class="m-0">Carousel</h5>
                  <p>with controls</p>
                </div>
              </div>
<?php
  }
  if($no>1)
  {
?>
              <div class="carousel-item"> <img class="d-block img-fluid w-100" src="images/<?php echo $folder."/".$foto ;?>">
                <div class="carousel-caption">
                  <h5 class="m-0">Carousel</h5>
                  <p>with controls</p>
                </div>
              </div>

<?php
  }
$no++;
}
?>
                </div> <a class="carousel-control-prev" href="#carousel" role="button" data-slide="prev"> <span class="carousel-control-prev-icon"></span> <span class="sr-only">Previous</span> </a> <a class="carousel-control-next" href="#carousel" role="button" data-slide="next"> <span class="carousel-control-next-icon"></span> <span class="sr-only">Next</span> </a>
              </div>
            </div>
            <div class="col-md-6"><div style="height: 400px;" id="map"></div></div>
      </div>
      <div class="row justify-content-center">
        <div class="col-4 p-4 col-md-12">
          <h4> <b>Informasi</b> </h4>
          <p><?php echo $info; ?></p>
        </div>
      </div>
    </div>
  </div>
  <div class="bg-dark py-3">
    <div class="container">
      <div class="row d-flex justify-content-between">
        <div class="col-lg-4 col-md-6">
          <p class="text-secondary mb-0">2019 - Created by Danie Anwar</p>
        </div>
      </div>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  <script src='https://api.mapbox.com/mapbox-gl-js/v2.14.1/mapbox-gl.js'></script>
  <script>
  mapboxgl.accessToken = 'pk.eyJ1IjoiZ2FzdGE3NyIsImEiOiJjbG9maWdseTQwbm81MnJxY2hxYjV6ZmxkIn0.Q4VhTnOV2eCBMYuQ0F6ZeQ';
    var map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/streets-v12',
        center: [115.083889, -8.238056],
        zoom: 12
    });
    var marker = new mapboxgl.Marker()

    <?php
    echo "marker.setLngLat([$lng,$lat]).addTo(map);
          map.flyTo({
          center: [$lng,$lat],
          zoom: 15
          });";

    ?>
  </script>
</body>

</html>