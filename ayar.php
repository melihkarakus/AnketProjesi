<?php
try {
     $db = new PDO("mysql:host=localhost;dbname=anket-uygulamasi;charset=utf8","root","");
} catch ( PDOException $e ){
     print $e->getMessage();
}

$username = $_POST['user'];
$password = $_POST['pass'];

$login = $db->prepare("SELECT * FROM yöneticiler WHERE username=? AND password=?");
$login->execute(array($username, $password));
if ($login->rowCount())
{
    echo "Giriş başarılı.\nHoşgeldin $username <script>window.location.href='cevaplar.php';</script>";
}
else
{
    echo "Giriş başarısız.\nLütfen giriş bilgilerinizi kontrol edip tekrar deneyiniz.";
}?>