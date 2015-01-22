<?php include "templates/include/header.php" ?>
 
<?php
	$tagler = $veriler['tagler'];
	$sonuc_sayisi = $veriler['sonuc_sayisi'];
	$ayet_sayilari = $veriler['ayet_sayilari'];
?>
 
    <div id="content">
		<div id="title">Tag Listele</div>
		  
		<table class="table">
			<tr style="visibility:hidden">
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			
			<tr style="border-top:0; border-bottom:3px solid #ddd">
				<th colspan="3">
				Tag Adı
				</th>
				<th>
				Ayet Sayısı
				</th>
			</tr>
			
			<?php 
			foreach($tagler as $index => $tag)
			{
			?>
			
				<tr <?php echo ($index % 2 != 0) ? "id='tek'" : "" ?>>
					<td colspan="3">
					<?php echo "<a href='tagler.php?action=tagGoster&tagId={$tag->tag_id}' class='button' id='tag' style='float:none;'>{$tag->tag_adi}</a>"; ?>
					</td>
					<td>
					<?php echo $ayet_sayilari[$index] . ""; ?>
					</td>
				</tr>

			<?php
			}
			?>

		</table>
	</div>
	  
	</div>
	  
 
<?php include "templates/include/footer.php" ?>

