<?php
include "koneksi.php";
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
        background-image: url('wisata.png');
        background-size: cover;
        width: 30px;
        height: 30px;
        cursor: pointer;
    }

    .marker-halal {
        background-image: url('umkm.png');
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
                <li class="nav-item">
                    <a class="nav-link" href="pencarian.php">Pencarian</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="py-5 text-center">
    <div class="container">
      <div class="row">
        <div class="mx-auto col-lg-6">
          <h1>Pencarian Tempat</h1>
          <p class="mb-4">Pencarian tempat berdasarkan nama tempat yang ingin di cari dan juga bisa berdasarkkan kategori tempat yang di pilih</p>
              <a class="btn btn-primary" href="sample.php?c=wisata" >Tempat Wisata</a> <a class="btn btn-primary" href="sample.php?c=umkm">UMKM</a>         
          <form class="form-inline d-flex justify-content-center" style="padding-top: 20px;">
            <div class="input-group" style="width: 500px;"> <input type="email" class="form-control form-control-lg" id="myInput" onkeyup="cari()" placeholder="Masukkan Nama Tempat...">
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="py-5">
    <ul id="myUL" class="no-bullets">
<?php
if(isset($_GET['c']))
{
  if($_GET['c'] == "wisata")
  {
$sql = mysqli_query($conn,"SELECT id_lokasi, nama, info FROM lokasi WHERE kategori = 'wisata'");
while($row = mysqli_fetch_assoc($sql))
  {
  $id = $row['id_lokasi'];
  $nama = $row['nama'];
  $info = $row['info'];

  $sqlfoto = mysqli_query($conn,"SELECT nama FROM foto WHERE id_lokasi = $id LIMIT 1");
  $rowfoto = mysqli_fetch_assoc($sqlfoto);

  $foto = $rowfoto['nama'];
?>
    <li>
    <div class="container" style="padding-bottom: 20px;" id="konten">
      <div class="row">
        <div class="col-md-12" >
          <div class="row">
            <div class="col-md-3 order-3 order-md-1"> <img class="img-fluid d-block" src="images/wisata/<?php echo $foto; ?>"> </div>
            <div class="col-md-6 col-8 d-flex flex-column justify-content-center p-3 order-1 order-md-2">
              <h3><?php echo $nama ; ?></h3>
              <?php echo $info ; ?>
            </div>
            <div class="col-md-2 col-4 d-flex flex-column align-items-center justify-content-center order-2 order-md-2 p-3"> <a class="btn btn-outline-primary mb-3" href="detail.php?id=<?php echo $id; ?>&p=wisata">Read more</a> </div>
          </div>
        </div>
      </div>
    </div>
    </li>
<?php
    }  
  }

  if($_GET['c'] == "umkm")
  {
  $sql = mysqli_query($conn,"SELECT id_lokasi, nama, info FROM lokasi WHERE kategori = 'umkm'");
while($row = mysqli_fetch_assoc($sql))
  {
  $id = $row['id_lokasi'];
  $nama = $row['nama'];
  $info = $row['info'];

  $sqlfoto = mysqli_query($conn,"SELECT nama FROM foto WHERE id_lokasi = $id LIMIT 1");
  $rowfoto = mysqli_fetch_assoc($sqlfoto);

  $foto = $rowfoto['nama'];
?>
    <li>
    <div class="container" style="padding-bottom: 20px;" id="konten">
      <div class="row">
        <div class="col-md-12" >
          <div class="row">
            <div class="col-md-3 order-3 order-md-1"> <img class="img-fluid d-block" src="images/umkm/<?php echo $foto; ?>"> </div>
            <div class="col-md-6 col-8 d-flex flex-column justify-content-center p-3 order-1 order-md-2">
              <h3><?php echo $nama ; ?></h3>
              <?php echo $info ; ?>
            </div>
            <div class="col-md-2 col-4 d-flex flex-column align-items-center justify-content-center order-2 order-md-2 p-3"> <a class="btn btn-outline-primary mb-3" href="detail.php?id=<?php echo $id; ?>&p=umkm">Read more</a> </div>
          </div>
        </div>
      </div>
    </div>
    </li>
<?php
    }
  }
}
else
{
$sql = mysqli_query($conn,"SELECT id_lokasi, nama, info FROM lokasi WHERE kategori = 'wisata'");
while($row = mysqli_fetch_assoc($sql))
  {
  $id = $row['id_lokasi'];
  $nama = $row['nama'];
  $info = $row['info'];

  $sqlfoto = mysqli_query($conn,"SELECT nama FROM foto WHERE id_lokasi = $id LIMIT 1");
  $rowfoto = mysqli_fetch_assoc($sqlfoto);

  $foto = $rowfoto['nama'];
?>
    <li>
    <div class="container" style="padding-bottom: 20px;" id="konten">
      <div class="row">
        <div class="col-md-12" >
          <div class="row">
            <div class="col-md-3 order-3 order-md-1"> <img class="img-fluid d-block" src="images/wisata/<?php echo $foto; ?>"> </div>
            <div class="col-md-6 col-8 d-flex flex-column justify-content-center p-3 order-1 order-md-2">
              <h3><?php echo $nama ; ?></h3>
              <?php echo $info ; ?>
            </div>
            <div class="col-md-2 col-4 d-flex flex-column align-items-center justify-content-center order-2 order-md-2 p-3"> <a class="btn btn-outline-primary mb-3" href="detail.php?id=<?php echo $id; ?>&p=wisata">Read more</a> </div>
          </div>
        </div>
      </div>
    </div>
    </li>
<?php
}

$sql = mysqli_query($conn,"SELECT id_lokasi, nama, info FROM lokasi WHERE kategori = 'umkm'");
while($row = mysqli_fetch_assoc($sql))
{
  $id = $row['id_lokasi'];
  $nama = $row['nama'];
  $info = $row['info'];

  $sqlfoto = mysqli_query($conn,"SELECT nama FROM foto WHERE id_lokasi = $id LIMIT 1");
  $rowfoto = mysqli_fetch_assoc($sqlfoto);

  $foto = $rowfoto['nama'];
?>
    <li>
    <div class="container" style="padding-bottom: 20px;" id="konten">
      <div class="row">
        <div class="col-md-12" >
          <div class="row">
            <div class="col-md-3 order-3 order-md-1"> <img class="img-fluid d-block" src="images/umkm/<?php echo $foto; ?>"> </div>
            <div class="col-md-6 col-8 d-flex flex-column justify-content-center p-3 order-1 order-md-2">
              <h3><?php echo $nama ; ?></h3>
              <?php echo $info ; ?>
            </div>
            <div class="col-md-2 col-4 d-flex flex-column align-items-center justify-content-center order-2 order-md-2 p-3"> <a class="btn btn-outline-primary mb-3" href="detail.php?id=<?php echo $id; ?>&p=umkm">Read more</a> </div>
          </div>
        </div>
      </div>
    </div>
    </li>
<?php
  }
}
?>
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
    <script type="text/javascript">
    function cari() {
    var input, filter, ul, li, a, i, div,txtValue;
    input = document.getElementById("myInput");
    filter = input.value.toUpperCase();
    ul = document.getElementById("myUL");
    li = ul.getElementsByTagName("li");
    for (i = 0; i < li.length; i++) {
        a = li[i].getElementsByTagName("h3")[0];
        txtValue = a.textContent || a.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            li[i].style.display = "";
        } else {
            li[i].style.display = "none";
        }
    }
}
  </script>
</body>

</html>