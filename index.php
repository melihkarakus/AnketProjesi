<?php require_once("baglan.php");?>
<!doctype html>
<html lang="tr-TR">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!--fontawesome-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" 
    integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Anket Uygulaması</title>
  </head>
  <body>
    <?php require_once("header.php");
    $Sorucek = $baglanti->prepare("select*from sorular");
    $Sorucek -> execute();
    $SoruSayisi = $Sorucek->rowCount();
    $Sorular=$Sorucek->fetchAll(PDO::FETCH_ASSOC)
    ?>
    <div class="row">

      <div class="col-md-4"></div>
      <form method="POST">
	<input type="text" name="TC" placeholder="Kontrol Edilecek TC Kimlik Numarası" required maxlength="11">
	<button name="kontrolEt">Kontrol Et</button>
  <?php 
  if (isset($_POST["kontrolEt"])) {
    $TC = $_POST["TC"];
    if (strlen($TC) == 11) {
      if (is_numeric($TC)) {
        $TC_10 = ((($TC[0] + $TC[2] + $TC[4] + $TC[6] + $TC[8])*7) - ($TC[1] + $TC[3] + $TC[5] + $TC[7])) % 10;
        if ($TC_10 == $TC[9]) {
          $TC_11 = ($TC[0] + $TC[1] + $TC[2] + $TC[3] + $TC[4] + $TC[5] + $TC[6] + $TC[7] + $TC[8] + $TC[9]) % 10;
          if ($TC_11 == $TC[10]) {
            echo "TC Kimlik Numarası GEÇERLİ!";
          } else {
            echo "Geçersiz TC Kimlik Numarası!";
          }
        } else {
          echo "Geçersiz TC Kimlik Numarası!";
        }
      } else {
        echo "TC Kimlik Numarası yalnızca rakamlardan oluşmaktadır.";
      }
    } else {
      echo "TC Kimlik Numarası 11 hane olmak zorundadır.";
    }
  }
  ?>
      <div class="col-md-4 mt-4" >
      
        <center><h3>Sorular</h3></center>
        <form method="post">
      <?php 
      if($SoruSayisi > 0){
        $Numaralandırma = 0;
        foreach($Sorular as $soru){
          $Numaralandırma++;
      ?>
        <div class="card mt-2">
          <div class="card-header">
           <?php echo $Numaralandırma.".".$soru['soru']; ?>
          </div>
          <div class="card-body">
            <blockquote class="blockquote mb-0">
              <input type="hidden" value="<?php echo $soru['soru_id'];?>" name="soru_id[]">
              <input type="radio" value="Kesinlikle Katılmıyorum" name="<?php echo "soru[]".$Numaralandırma;?>"><i class="fas fa-sad-cry" style="color:red"></i>
              <input type="radio" value="Katılmıyorum" name="<?php echo "soru[]".$Numaralandırma;?>"><i class="fas fa-sad-cry"></i>
              <input type="radio" value="Kararsızım" name="<?php echo "soru[]".$Numaralandırma;?>"><i class="fas fa-meh" style="color:orange"></i>
              <input type="radio" value="Katılıyorum" name="<?php echo "soru[]".$Numaralandırma;?>"><i class="fas fa-smile-beam"style="color:green"></i>
              <input type="radio" value="kesinlikle Katılıyorum" name="<?php echo "soru[]".$Numaralandırma;?>"><i class="fas fa-smile-beam"></i>
          </div>
        </div>
        <?php
        }
      }else {
        echo "<div class='alert alert-danger'>Görüntülenecek Soru Bulunamadı<\div>";
      }   
        ?>
        <div class="d-grid gap-2 mt-2">
  <input class="btn btn-succes" type="submit" name="gönder">
</div>
    </form>
    <?php 
    if(isset($_POST['gönder'])){
      if(isset($_POST['soru'])){
        $GelenCevaplar = $_POST['soru'];
        $Soru_id = $_POST["soru_id"];
        $Cevaplar = array_combine($Soru_id , $GelenCevaplar);
        $TCkimlik = $_SERVER['REMOTE_ADDR'];
        $Zaman = time();
        /*$oykullanici = $baglanti -> prapare("SELECT * FROM oykullananlar WHERE tc_id = ?"); 
        $oykullanici-> execute([
          $tc_id
          $BelirlenecekZaman = $tc_id;
        ]);
        */
        $BelirlenecekZaman = 86400;//Eğer olmazsa 120 saniye yaz.
        $TC_ID = $BelirlenecekZaman;
        
        $OykullaniciCek = $baglanti -> prepare("SELECT * FROM oykullananlar where tc_id = ? and TC = ?");
        $OykullaniciCek->execute([
          $TC_ID,
          $TCkimlik
        ]);

        $OykullaniciSayisi = $OykullaniciCek->rowCount();
        if($OykullaniciSayisi > 0){
          echo "<div class='alert alert-warning mt-2'>Daha Önce TC ile giriş yaptınız.</div>";
        }

        foreach($Cevaplar as $Soru_ID => $Cevap){
          $CevapKaydet = $baglanti -> prepare("INSERT INTO cevaplar SET soru_id = ? , cevap = ?");
          $CevapKaydet -> execute([
            $Soru_ID,
            $Cevap
          ]);

          if($CevapKaydet){
            $OykullaniciKaydet=$baglanti -> prepare("INSERT INTO oykullananlar set tc_id = ? , TC = ?");
            $OykullaniciKaydet->execute([
              $TCkimlik,
              $Zaman
            ]);
          }
        }//forech kapama süslüsü
        
         if($OykullaniciKaydet){
          echo "<div class='alert alert-success mt-2'>Anket Cevaplarınız Gönderildi</div>";
         }
        }

    }
    ?>
      </div>
      <div class="col-md-4"></div>
    </div>






    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
  </body>
</html>
<?php $baglanti = " "; ?>