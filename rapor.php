<?php 
session_start();

$header_url = $_SESSION['header_url'];
header("refresh:5; url=$header_url");
$veriler['baslik'] = "Ayetlerim - Bildirim";

include "templates/include/header_bos.php" ?>
 
      <div id="content">
	  <div id="title">Bildirim</div>
	  
	  <?php
	  echo "<div class='alert' id='";
	  echo $_SESSION['basari'] ? "basari'" : "hata'";
	  echo ">" . $_SESSION['rapor'];
	  echo "<p>5 saniye içinde ";
	  echo ($header_url == "index.php?action=anaSayfa") ? "ana sayfaya " : "";
	  echo "yönlendirileceksiniz.</p><p><img src='images/loader.gif' /></p></div>";
	  ?>
	  
	  </div>
	  
 
<?php include "templates/include/footer.php" ?>