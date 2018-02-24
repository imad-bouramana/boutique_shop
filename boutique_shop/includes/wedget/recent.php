<h3 class="text-center">Popular Items</h3>
<?php
  $cartq = $db->query("SELECT * FROM cart WHERE paid = 1 ORDER BY ID DESC");
  $results = array();
  while($row = mysqli_fetch_assoc($cartq)){
  	$results[] = $row;
  }
  $cart_count = $cartq->num_rows;
  $used_id = array();
  for($i=1;$i<$cart_count;$i++){
  	$itemsr = $results[$i]['items'];
  	$items = json_decode($itemsr, true);
  	foreach($items as $item){
       if(!in_array($item['ID'], $used_id)){
       	$used_id[] = $item['ID'];
       }
  	}
  }
?>
<div id="recent">
	<table class="table table-condensed">
		<?php   
           foreach($used_id as $id):
           	$productq = $db->query("SELECT * FROM product WHERE ID = '{$id}'");
           	$prod = mysqli_fetch_assoc($productq);
           
		?>
		
			<tr>
				<td><?=substr($prod['Title'],0, 16);?></td>
				<td><a class="text-primary" onclick="detailmodal('<?=$id;?>')">View</a></td>
			</tr>
		<?php endforeach; ?>
	</table>
</div>