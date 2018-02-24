<?php 
ob_start();
  require_once '../core/init.php';
 if(!login_in()){
 	header('LOCATION: login.php');
 	}
  include 'includes/bheader.php';
  include 'includes/bnavbar.php';
 if(isset($_GET['complete']) && $_GET['complete'] == 1){
 	 $cart_id = sanitize((int)$_GET['cart_id']);
  	$db->query("UPDATE cart SET shipped = 1 WHERE ID = '{$cart_id}'");
  	$_SESSION['success_admin'] = 'Your Order Shopping Was Complete By success!';
  	header('LOCATION: bindex.php');
  }
?>
<?php
$txn_id = sanitize((int)$_GET['txn_id']);
$query = $db->query("SELECT * FROM transactions WHERE ID = '{$txn_id}'");
$result = mysqli_fetch_assoc($query);
$cart_id = $result['cart_id'];
$queryq = $db->query("SELECT * FROM cart WHERE ID = '{$cart_id}'");
$resultq = mysqli_fetch_assoc($queryq);
$items = json_decode($resultq['items'],true);
$itemid = array();
$products = array();
foreach($items as $item){
	$itemid[] = $item['ID'];
}
$ids = implode(',', $itemid);
$queryr = $db->query("SELECT i.ID as 'ID', i.Title as 'Title', c.ID as 'cID', c.cat_name as 'category', p.cat_name as 'parent'
            FROM product i 
            LEFT JOIN categories c ON i.Categorie = c.ID
            LEFT JOIN categories p ON c.parent  = p.ID
            WHERE i.ID IN ({$ids})");
while($p = mysqli_fetch_assoc($queryr)){
	foreach($items as $item){
		if($item['ID'] == $p['ID']){
			$x = $item;
			continue;
		}
	}
	$products[] = array_merge($x,$p);
}

?>
<h2 class="text-center">Product Order</h2>
<table class="table table-bordered table-condensed table-striped">
	<thead>
		<th>Quantity</th>
		<th>Title</th>
		<th>Category</th>
		<th>Size</th>
	</thead>
	<tbody>
	<?php foreach($products as $product): ?>
		<tr>
			<td><?=$product['quantity']; ?></td>
			<td><?=$product['Title']; ?></td>
			<td><?=$product['parent'].'~'.$product['category']; ?></td>
			<td><?=$product['size']; ?></td>
		</tr>
	 <?php endforeach; ?>
	</tbody>
</table>

<div class="row">
	<div class="col-md-6">
		<h2 class="text-center">Order Details</h2>
		<table class="table table-bordered table-condensed table-striped">
			<tbody>
				<tr>
					<td>Sub Totale</td>
					<td><?=mony($result['sub_totale']);?></td>
				</tr>
				<tr>
					<td>Grand Totale</td>
					<td><?=mony($result['grand_total']);?></td>
				</tr>
				<tr>
					<td>Tax</td>
					<td><?=mony($result['tax']);?></td>
				</tr>
				<tr>
					<td>Txn Date</td>
					<td><?=pretty($result['txn_date']);?></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="col-md-6">
		<h2 class="text-center"> Shiping Details</h2>
		<address>
			<?=$result['full_name'];?><br>
			<?=$result['adress'];?><br>
			<?=(($result['adress2'] != '')?$result['adress2']:'');?><br>
			<?=$result['city'].' '.$result['state'].' '.$result['zip_code'].'<br>';?>
			<?=$result['country'];?><br>

		</address>

	</div>

</div>
<div class="pull-right">
	   <a href="bindex.php" class="btn btn-large btn-default">Cancel</a>
	   <a href="chargers.php?complete=1&cart_id=<?=$cart_id;?>" class="btn btn-large btn-primary">Complete Order</a>
</div>



<?php 
   include 'includes/bfooter.php';
 
ob_end_flush();
