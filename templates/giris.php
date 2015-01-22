<?php 
include "templates/include/header_bos.php" ?>
 
      <div id="content">
	  <div id="title">Yönetici Girişi</div>
		
	<?php
	if(isset($veriler['mesaj']))
		echo '<div class="alert" id="hata">' . $veriler['mesaj'] . '</div><br />';
	?>
	  
	  
	  <form action="index.php?action=giris" method="post">
			<table style="font-size:1.2em;">
				<tr>
					<td colspan="2">Üye adınız:<br />
					<input type="text" name="uye_adi" maxlength="50">
					<br /><br />
					</td>
				</tr>
				
				<tr>
					<td colspan="2">Şifreniz:<br />
					<input type="password" name="sifre" maxlength="50">
					<br /><br />
					</td>
				</tr>
				
				<tr>
					<td>
					<label for="check"><input type="checkbox" name="hatirla" value="1" id="check" style="width:1.2em; height:1.2em;"> Beni hatırla</label>
					<br /><br />
					</td>
				</tr>
				
				<tr>
					<td>
					<input type="submit" name="giris" class="button" value="Giriş yap!">
					</td>
				</tr>
				
			</table>	
		</form>
	</div>
	  
 
<?php include "templates/include/footer.php" ?>