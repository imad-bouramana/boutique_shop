<?php 
   ob_start();
 require_once $_SERVER['DOCUMENT_ROOT'] . '/boutique_shop/core/init.php';
 if(!login_in()){
  login_error();
 }
 include 'includes/bheader.php';
 include 'includes/bnavbar.php';

 if(isset($_GET['aprouve'])){
  $upid = sanitize($_GET['aprouve']);
  $db->query("UPDATE product SET Delited = 0 where ID = '$upid'");
   header('LOCATION: arshive.php');
 
 }
 $sql = "SELECT * FROM product WHERE Delited = 1";
 $stmt = $db->query($sql);

 


?>
<h2 class="text-center">Arshive Product</h2>
<a href="product.php?add=1" class="btn btn-success pull-right" id="add-product">Add Product</a>
<div class="clearfix"></div>
<hr>
<table class="table table-bordered table-striped table-condensed">
	<thead>
	   <th></th>
       <th>Title</th>
       <th>List_price</th>
       <th>Categorie</th>
       <th>Featured</th>
       <th>Sold</th>
    </thead>
	<tbody>
      <?php while($produc = mysqli_fetch_assoc($stmt)): 
          $catpaerent = $produc['Categorie'];
          $sql2 = "SELECT * from categories WHERE ID = '$catpaerent'";
          $stmt2 = $db->query($sql2);
          $cats = mysqli_fetch_assoc($stmt2);
          $catchild = $cats['parent'];
          $sql3 = "SELECT * FROM categories WHERE ID = '$catchild'";
          $stmt3 = $db->query($sql3);
          $catparent = mysqli_fetch_assoc($stmt3);
          $catygory = $catparent['cat_name']  . ' - ' .  $cats['cat_name'];
          ?> 
      <tr>
      	<td>
      		<a href="arshive.php?aprouve=<?=$produc['ID']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-refresh"></span></a>
            
        </td>
      	<td><?= $produc['Title'];?></td>
      	<td><?= mony($produc['List_Price']);?></td>
      	<td><?= $catygory;?></td>
      	<td>
      		<a href="product.php?featured=<?=(($produc['featured'] == 0)?'1':'0');?>&id=<?=$produc['ID'];?>" 
            class="btn btn-default btn-xs">
            <span class="glyphicon glyphicon-<?=(($produc['featured'] == 1)?'minus':'plus');?>"></span></a>
            &nbsp <?= (($produc['featured'] == 1)?'featured product':'')?>
      	</td>
      	<td></td>

      </tr>

      <?php endwhile;?>

	</tbody>
</table>


    
<?php  

 
 include 'includes/bfooter.php'; 

 echo ob_get_clean();
?>

