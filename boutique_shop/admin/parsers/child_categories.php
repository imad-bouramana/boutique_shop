<?php
ob_start();
 require_once $_SERVER['DOCUMENT_ROOT'] . '/boutique_shop/core/init.php';
$parentID = (int)$_POST['parentID'];
$selected = sanitize($_POST['selected']);
$chilquery = $db->query("SELECT  *  FROM categories WHERE parent = '$parentID'  ORDER BY cat_name");


  ?>

<option value=""></option>
<?php while($childajax = mysqli_fetch_assoc($chilquery)): ?>
	<option value="<?= $childajax['ID']; ?>" <?=(($selected == $childajax['ID'])?' selected':'');?> ><?= $childajax['cat_name']; ?></option>
<?php  endwhile; ?>

<?php
echo ob_get_clean();
?>