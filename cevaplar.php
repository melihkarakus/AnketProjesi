<?php require_once("baglan.php");
 $Sorucek = $baglanti->prepare("select*from sorular");
 $Sorucek -> execute();
 $SoruSayisi = $Sorucek->rowCount();
 $Sorular=$Sorucek->fetchAll(PDO::FETCH_ASSOC)

?>
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

    <title>Cevaplar</title>
  </head>
  <body>
    <?php require_once("header.php");?>
        <div class="row">
            <div class="col-md-4"></div>
            <center><h3>Cevaplar</h3></center>
            <?php 
            if ($SoruSayisi > 0) { 
                $Numaralandirma = 0;
                foreach ($Sorular as $Soru) { 
                    $Numaralandirma++;
                    ?>
            <div class="accordion mt-2" id="accordionPanelsStayOpenExample">
  <div class="accordion-item">
    <h2 class="accordion-header" id="panelsStayOpen-headingThree">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false" aria-controls="panelsStayOpen-collapseThree">
        <?php echo $Numaralandirma.".".$Soru['soru']; ?>
      </button>
    </h2>
    <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingThree">
      <div class="accordion-body">
        <?php 
        $CevaplariCek = $baglanti -> prepare("SELECT * FROM cevaplar where soru_id = ?");
        $CevaplariCek -> execute([
           $Soru['soru_id'] 
        ]);
        $CevapSayisi = $CevaplariCek ->rowCount();
        $Cevaplar = $CevaplariCek-> fetchAll(PDO::FETCH_ASSOC);
        
        $KesinlikleKatılmıyorum =0;
        $Katılmıyorum=0;
        $Kararsızım=0;
        $Katılıyorum=0;
        $KesinlikleKatılıyorum=0;
        foreach ($Cevaplar as  $Cevap) {
            if ($Cevap['cevap']=="KesinlikleKatılmıyorum") {
                $KesinlikleKatılmıyorum++;
            }elseif ($Cevap['cevap']=="Katılmıyorum") {
                $Katılmıyorum++;
            }elseif ($Cevap['cevap']=="Kararsızım") {
                $Kararsızım++;
            }elseif ($Cevap['cevap']=="Katılıyorum") {
                $Katılıyorum++;
            }elseif ($Cevap['cevap']=="KesinlikleKatılıyorum") {
                $KesinlikleKatılıyorum++;
            }
        }
        $KesinlikleKatılmıyorumYüzdeHesapla = ($KesinlikleKatılmıyorum / $CevapSayisi)*100;
        $KatılmıyorumYüzdeHesapla = ($Katılmıyorum / $CevapSayisi)*100;
        $KararsızımYüzdeHesapla = ($Kararsızım / $CevapSayisi)*100;
        $KatılıyorumYüzdeHesapla = ($Katılıyorum / $CevapSayisi)*100;
        $KesinlikleKatılıyorumYüzdeHesapla = ($KesinlikleKatılıyorum / $CevapSayisi)*100;
        
        echo "% ".number_format($KesinlikleKatılmıyorumYüzdeHesapla,2)."<br>";
        echo "% ".number_format($KatılmıyorumYüzdeHesapla,2)."<br>";
        echo "% ".number_format($KararsızımYüzdeHesapla,2)."<br>";
        echo "% ".number_format($KatılıyorumYüzdeHesapla,2)."<br>";
        echo "% ".number_format($KesinlikleKatılıyorumYüzdeHesapla,2)."<br>";
        
        ?>
      </div>
    </div>
  </div>
<?php 
                }//forech kapama süslüsü
}
else {
    echo "<div class='alert alert-danger'>Görüntülenecek Soru Bulunamadı<\div>";
}?>
            <div class="col-md-4 "></div>
            <div class="col-md-4"></div>
            <div class="col-md-4"></div>
            <div class="col-md-4"></div>



















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
<?php $baglanti=" "; ?>