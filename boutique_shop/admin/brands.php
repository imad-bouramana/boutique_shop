<?php 
  require_once '../core/init.php';
  if(!login_in()){
    login_error();
 }
   include 'includes/bheader.php';
  include 'includes/bnavbar.php';
  // select
  $product = "SELECT * FROM brand ORDER BY brand";
  $sql = $db->query($product);
  // error array
  $errors = array();
   // edit brand
    if(isset($_GET["edit"]) && !empty($_GET["edit"])){
        $edit = (int)$_GET["edit"];
        $edit = sanitize($edit);
        $brand1 = "SELECT * FROM brand WHERE ID = '$edit'";
        $sql3 = $db->query($brand1);
        $editbrand = mysqli_fetch_assoc($sql3);
        
    }
  // chek isset submit
  if(isset($_POST['add-brand'])){
  	 $brandpost = sanitize($_POST['brand']);
  	if($_POST['brand'] == ''){
  		$errors[] = 'You Must Enter A Brand!';
  	}
    
  	//chek if brand exist  insert brand
  	$product1 = "SELECT * FROM brand WHERE brand = '$brandpost'";
    if(isset($_GET['edit'])){
       $product1= "SELECT * FROM brand where brand = '$brandpost' AND ID != '$edit'";
    }
  	$sql2 = $db->query($product1);
  	$count = mysqli_num_rows($sql2);
  	if($count > 0){
  		$errors[] =  $brandpost ." Is Exist Choose Another Brand Name ...";
  	}
  	//if empty errors
  	if(!empty($errors)){
      echo error_message($errors);
  	}else{
          $product2 = "INSERT INTO brand (brand) VALUES ('$brandpost')";
          if(isset($_GET['edit'])){
               $product2 = "UPDATE brand SET brand = '$brandpost' WHERE ID = '$edit'";
          } 
          $db->query($product2);
          header('LOCATION: brands.php');
  	}
  }


  // delite brand
    if(isset($_GET["delite"]) && !empty($_GET["delite"])){
        $delite = (int)$_GET["delite"];
        $delite = sanitize($delite);
        $brand = "DELETE FROM brand WHERE ID = '$delite'";
        $db->query($brand);
        header('LOCATION: brands.php');
    }
    
   
   ?>


<h2 class="text-center">Brands</h2>
<div class="text-center">
  <form class="form-inline" action="brands.php<?= ((isset($_GET['edit']))? '?edit='. $edit : ''); ?>" method="post">
  	<div class="form-group">
  	  <label for="brand"><?= ((isset($_GET['edit']))? 'Edit' : 'New'); ?> Brand :</label>
      <?php  
       $brand_value = '';
         if(isset($_GET['edit'])){
            $brand_value = $editbrand['brand'];
         }else{
          if(isset($_POST['brand'])){
          $brand_value = sanitize($_POST['brand']);
         }
       }
      ?>
  	  <input type="text" name="brand" id="brand" class="form-control" value="<?= $brand_value; ?>" />
       <?php if(isset($_GET['edit'])) : ?>
       <a href="brands.php" class="btn btn-default">Cancel</a>
     <?php endif;?>
  	  <input type="submit" name="add-brand" value="<?= ((isset($_GET['edit']))? 'Edit' : 'Add')?> Brand" class="btn btn-success" />
    </div>
  </form>
</div>
<hr>
<table class="table table-bordered table-striped table-auto">
	<thead>
		<td></td>
		<td>Brands</td>
		<td></td>
	</thead>
	<?php while($brand = mysqli_fetch_assoc($sql)) : ?>
	<tbody>
		<th><a href="brands.php?edit=<?= $brand['ID'];?>" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-pencil"></span></a></th>
		<th><?= $brand['brand'];?></th>
		<th><a href="brands.php?delite=<?= $brand['ID'];?>" onclick="return confirm('are you sure?')" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-remove"></span></a></th>
	</tbody>
	<?php endwhile;?>
</table>
       
<?php 
   include 'includes/bfooter.php';



