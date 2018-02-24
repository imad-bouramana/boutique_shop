<?php
$cat_id = ((isset($_REQUEST['cat']))? sanitize($_REQUEST['cat']):'');
$sort_price = ((isset($_REQUEST['sort_price']))? sanitize($_REQUEST['sort_price']):'');
$min_price = ((isset($_REQUEST['min_price']))? sanitize($_REQUEST['min_price']):'');
$max_price = ((isset($_REQUEST['max_price']))? sanitize($_REQUEST['max_price']):'');
$b = ((isset($_REQUEST['brand']))? sanitize($_REQUEST['brand']):'');

$query = $db->query("SELECT * FROM brand ORDER BY brand");

?>
<h3 class="text-center">Filter By :</h3>
<h4 class="text-center">Price</h4>

<form action="chearch.php" method="post">
	<input type="hidden" name="cat" value="<?=$cat_id;?>">
	<input type="hidden" name="sort_price" value="0">
	<input type="radio" name="sort_price" value="hight" <?=(($sort_price == 'hight')?' cheked':''); ?>> Hight To Low<br>
	<input type="radio" name="sort_price" value="low" <?=(($sort_price == 'low')?' cheked':''); ?>> Low To Height<br><br>
	<input type="text" name="min_price"  placeholder="min $" class="sort_price" value="<?=$min_price;?>"> To
	<input type="text" name="max_price"  placeholder="max $" class="sort_price" value="<?=$max_price;?>"><br><br>
	<h3 class="text-center">Brand</h3>
	<input type="radio" name="brand" value="" <?=(($b == '')?' cheked':'');?>> All<br>
	<?php while($result = mysqli_fetch_assoc($query)): ?>
	   <input type="radio" name="brand" value="<?=$result['ID'];?>" <?=(($result['ID'] == $b)?' cheked':'') ?>> <?=$result['brand'];?><br>
	
    <?php endwhile; ?>
    <input type="submit" class="b tn btn-xs btn-primary" value="search" >
    
</form>