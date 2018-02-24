<?php

 require_once $_SERVER['DOCUMENT_ROOT'] .'/boutique_shop/core/init.php';
 if(!login_in()){
 	login_error();
 }
  include 'includes/bheader.php';
  include 'includes/bnavbar.php';
 $sql = "SELECT * FROM categories WHERE parent = 0";
 $cats = $db->query($sql);
 $errors = array();

 
 //edite categories
 if(isset($_GET['edit']) && !empty($_GET['edit'])){
 	$edit_id = (int)$_GET['edit'];
 	$edit_id = sanitize($edit_id );
 	$sqledit = "SELECT * FROM categories WHERE ID = '$edit_id'";
 	$stmt = $db->query($sqledit);
 	$result_edit = mysqli_fetch_assoc($stmt);
 }
 //delite categories
 if(isset($_GET['delite']) && !empty($_GET['delite'])){
 	$delite = (int)$_GET['delite'];
 	$delite = sanitize($delite);
 	       //delit parent
 		$pdelite = "SELECT * FROM categories WHERE ID = '$delite'";
 		$pseme = $db->query($pdelite);
 		$resulte = mysqli_fetch_assoc($pseme);
 	if($resulte['parent'] == 0){
 		$delitparent = "DELETE FROM categories WHERE parent = '$delite'";
 	   $db->query($delitparent);
 	   header('LOCATION: categories.php');

 	}
         // delite all
 	$delitesql = "DELETE FROM categories WHERE ID = '$delite'";
 	$db->query($delitesql);
 	header('LOCATION: categories.php');
 }

  $display = '';
   $parent = '';
  $catygory = '';
  $editcaty = '';
  $editparent = 0;
 
// check if isset post
 if(isset($_POST) && !empty($_POST)){
 	$parent = sanitize($_POST['parent']);
 	$catygory = sanitize($_POST['categories']);
 	$sqlform = "SELECT * FROM categories WHERE  parent = '$parent' AND cat_name = '$catygory'";
 	if(isset($_GET['edit'])){
 		$id = $result_edit['ID'];
 		$sqlform= "SELECT * FROM categories WHERE cat_name = '$catygory' AND parent = '$parent' AND ID != '$id'";
 	}
 	$sql4 = $db->query($sqlform);
 	$count = mysqli_num_rows($sql4);

 	if($catygory == ''){
 		$errors[] = 'category cant be empty'; 
 	}
    if($count > 0 ){
    	$errors[] = $catygory .' already exist choose another';
    }
    if(!empty($errors)){
        $display = error_message($errors); 
        
    }else{
    	$insert = "INSERT INTO categories (cat_name, parent) VALUES ('$catygory', '$parent')";
    	if(isset($_GET['edit'])){
            $insert = "UPDATE categories SET cat_name = '$catygory',   parent = '$parent' WHERE ID = '$edit_id'";
    	}
    	$db->query($insert);
    	header('LOCATION: categories.php');
    }
  }
   if(isset($_GET['edit'])){
   	$editcaty = $result_edit['cat_name'];
    $editparent = $result_edit['parent'];
   }else{
   	if(isset($_POST)){
   		$editcaty = $catygory;
   		$editparent = $parent;
   	}
   }
?>
<h2 class="text-center h1">Categories</h2>
<div class="row">
	<!-- form control -->
	<div class="col-md-6">
		<form class="form" action="categories.php<?= ((isset($_GET['edit'])))?'?edit='.$edit_id:''?>" method="post">
			<legend><?= ((isset($_GET['edit'])))?'Edit ':'Add A '?> Categories</legend>
			<div id="errors"><?php echo $display;?></div>
			<div class="form-group">
				<label for="parent">Parent</label>
				<select class="form-control" id="parent" name="parent">
					<option value="0" <?= (($editparent == 0))?'selected':''?>>Parent</option>
					<?php  while($parent = mysqli_fetch_assoc($cats)):?>
					   <option value="<?= $parent['ID'];?>"<?= (($editparent == $parent['ID'])?'selected':'')?>><?= $parent['cat_name'];?></option>
					<?php  endwhile;?>
				</select>

			</div>
			<div class="form-group">
				<label for="categories">Categories</label>
				<input type="text" id="categories" name="categories" class="form-control" value="<?=$editcaty; ?>">
			</div>
			<div class="form-group">
				<input type="submit" value="<?= ((isset($_GET['edit'])))?'Edit':'Add'?> Category" class="btn btn-success">
			</div>
		</form>
	</div>
	<!-- categories table -->
	<div class="col-md-6">
		<table class="table table-bordered">
			<thead>
				<th>Categories</th>
				<th>Parent</th>
				<th></th>
			</thead>
			<tbody>
				<?php 
				     $sql = "SELECT * FROM categories WHERE parent = 0";
                     $cats = $db->query($sql);
               while($product = mysqli_fetch_assoc($cats)):
				$parent_id = (int)$product['ID'];
	              $sql3 = "SELECT * FROM categories WHERE parent = '$parent_id'"; 
	              $child = $db->query($sql3);

				?>
				<tr class="bg-primary">
					<td><?= $product['cat_name'];?></td>
					<td>parent</td>
					<td>
						<a href="categories.php?edit=<?= $product['ID']?>" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-edit"></span></a>
	                	<a href="categories.php?delite=<?= $product['ID']?>"  class="btn btn-default btn-sm confirm"><span class="glyphicon glyphicon-remove-sign"></span></a>
					</td>
				</tr>
				<?php while($children = mysqli_fetch_assoc($child)):?>
				   <tr class="bg-info">
					<td><?= $children['cat_name'];?></td>
					<td><?= $product['cat_name'];?></td>
					<td>
						<a href="categories.php?edit=<?= $children['ID']?>" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-edit"></span></a>
	                	<a href="categories.php?delite=<?= $children['ID']?>"  class="btn btn-default btn-sm confirm"><span class="glyphicon glyphicon-remove-sign"></span></a>
					</td>
				</tr>
			     <?php endwhile; ?>
				<?php endwhile; ?>
			</tbody>
		</table>
	</div>
</div>
<?php
include 'includes/bfooter.php';
    
?>