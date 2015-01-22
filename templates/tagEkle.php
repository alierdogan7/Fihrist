<?php include "templates/include/header.php" ?>
 
    <div id="content">
	<div id="title">Tag Ekleme</div>
	  
	<form action="tagler.php?action=tagEkle" method="POST">
		  
	<table>
		<tr>
			<td><label for="sure_adi">Tag adı: </td>
			<td><input type="text" name="tag_adi" id="sure_adi" /></label></td>
		</tr>
		
		<tr>
			<td>
				Ayet ekleyin: <br />
				<a style="font-size:.85em; font-style:italic; color:red;">(Birden fazla eklemek için <br />Ctrl tuşuna basılı tutup tıklayın)</a>
			</td>
			<td>
				<select name="ayet_idleri[]" size="5" multiple>
					<?php
					
					if(count($veriler['ayetler']) > 0)
					{
						foreach($veriler['ayetler'] as $ayet)
						{
							echo "<option value='" . $ayet->ayet_id . "'>" .
								$ayet->sure_adi . " " . $ayet->sure_no . "</option>\n";
						}
					}
					else
					{
						echo "<option disabled='disabled'>Ayet yok!</option>";
					}
					?>
				</select>
			</td>
		</tr>
		
		<tr>
			<td />
			<td><input type="submit" name="tagEkle" value="Ekle!" class="button" style="float:right;"/></td>
		</tr>
	</table>
	</form>
	  
	</div>
 
<?php include "templates/include/footer.php" ?>