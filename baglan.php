<?php 
try{
    $baglanti = new PDO("mysql:host=localhost;dbname=anket-uygulamasi;charset=utf8","root","");
    //echo "baglantı var";
}
catch(Exception $e)
{
    echo $e -> getMessage();
}
function Filtrele($deger){
    $a =trim($deger);
    $b =strip_tags($a);
    $c =htmlspecialchars($b, ENT_QUOTES);
    $sonuc =$c;
    return $sonuc;
}
?>