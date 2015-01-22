<html>
<head>
<meta http-equiv="Content-Type" content="text/HTML; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="style.css" media="screen" />
<link rel="shortcut icon" href="images/favicon.ico" />
<title><?php echo $veriler['baslik'] ?></title>

<script type="text/javascript" src="jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="scripts.js"></script>

</head>

<body>
<a id="page_top"></a>
<div id="header-wrapper">
<div class="center-wrapper">

<div id="header">
<a href="index.php"><div id="logo"></div></a>

<hr>
<p><i>Oysa o (Kur'an), âlemler için ancak bir öğüttür. (Kalem/52)</i></p>
</div>

<!--<div id="navbar">
<ul>
<li><a href="index.php" class="link">Ana Sayfa</a></li>
<li><a href="#" class="link">Hakkinda</a></li>
<li><a href="#" class="link">Bilgiler</a></li>
<li><a href="#" class="link">Iletisim</a></li>
</ul>
</div>
!-->

</div>
</div>

<div id="main-wrapper">
<div class="center-wrapper">
<div id="main">

<div id="left-bar">

<div id="box">
<h3>Menü</h3>
<ul id="special-list">
<li><a href="index.php?action=anaSayfa" class="link">Ana Sayfa</a></li>
<li><a href="ayetler.php?action=ayetListele" class="link">Ayetleri Görüntüle</a></li>
<li><a href="tagler.php?action=tagListele" class="link">Tag'leri Görüntüle</a></li>

<?php
if(isset($giris_yapmis) && $giris_yapmis)
{
?>
<li><a href="ayetler.php?action=ayetEkle" class="link">Ayet Ekle</a></li>
<li><a href="tagler.php?action=tagEkle" class="link">Tag Ekle</a></li>
<li><a href="index.php?action=cikis" class="link">Çıkış</a></li>
<?php
}
else
{
?>
<li><a href="index.php?action=giris" class="link">Yönetici Girişi</a></li>
<?php
}
?>

</ul>
</div>

<div id="box">
<h3>İstatistikler</h3>
<ul id="special-list">
<li>Ayet Sayısı: <?php echo Ayet::getTotalAyetNumber(); ?></a></li>
<li>Tag Sayısı: <?php echo Tag::getTotalTagNumber(); ?></li>
<li><a href="http://www.kuranmeali.com" class="link">Kuranmeali.com</a></li>
</ul>
</div>

<div id="box">
<h3>Arama</h3>
<ul id="special-list">
<form action="ayetler.php?action=ayetListele" method="GET">
	<table style="width: 100%;">
		<tbody>
		<tr>
			<td>
			Arama: <br><input type="text" name="ara">
			<input type="submit" value="Ara" class="button">
			</td>
		</tr>
		</tbody>
	</table>
</form>

</li></ul>
</div>

</div>