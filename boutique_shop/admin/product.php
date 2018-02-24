<?php 
   ob_start();
 require_once $_SERVER['DOCUMENT_ROOT'] . '/boutique_shop/core/init.php';
 if(!login_in()){
  login_error();
 }
 include 'includes/bheader.php';


 include 'includes/bnavbar.php';
 //delete produvt
 if(isset($_GET['delite'])){
  $delite_id = sanitize($_GET['delite']);
  $db->query("UPDATE product SET Delited = 1 whERE ID = '$delite_id'");
  header('LOCATION: product.php');

 }
 // add product
 $dbpath      = '';

 if(isset($_GET['add']) || isset($_GET['edit'])){  
  $postbrand  = $db->query("SELECT * FROM brand ORDER BY brand");
  $parentbrand = $db->query("SELECT * FROM categories WHERE parent = 0  ORDER BY cat_name");
  $title = ((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']):'');
  $brand = ((isset($_POST['brand']) && !empty($_POST['brand']))?sanitize($_POST['brand']):'');
  $parent = ((isset($_POST['parent']) && !empty($_POST['parent']))?sanitize($_POST['parent']):'');
  $categories = ((isset($_POST['child']) && !empty($_POST['child']))?sanitize($_POST['child']):'');
  $price = ((isset($_POST['price']) && !empty($_POST['price']))?sanitize($_POST['price']):'');
  $list_price = ((isset($_POST['list_price']) && !empty($_POST['list_price']))?sanitize($_POST['list_price']):'');
  $description = ((isset($_POST['description']) && !empty($_POST['description']))?sanitize($_POST['description']):'');
  $Sizes = ((isset($_POST['Sizes']) && !empty($_POST['Sizes']))?sanitize($_POST['Sizes']):'');
  $Sizes       = rtrim($Sizes, ',');
  $saved_image = '';
     
   if(isset($_GET['edit'])){
    $edit_id = (int)$_GET['edit'];
    $editsql = $db->query("SELECT * FROM product WHERE ID  = '$edit_id'");
    $editproduct = mysqli_fetch_assoc($editsql);
    if(isset($_GET['delite_image'])){
      $img = (int)$_GET['img'];
      $images = explode(',',$editproduct['Image']);
      $image_url = $_SERVER['DOCUMENT_ROOT'].$images[$img];
      unlink($image_url);
      unset($images[$img]);
      $imagecout = implode(',',$images);
      $db->query("UPDATE product SET Image = '{$imagecout}' WHERE ID = '$edit_id'");
      header('LOCATION: product.php?edit='.$edit_id);
    }
    $categories = ((isset($_POST['child']) && $_POST['child'] != '')?sanitize($_POST['child']):$editproduct['Categorie']);
    $title = ((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']):$editproduct['Title']);
    $brand = ((isset($_POST['brand']) && $_POST['brand'] != '')?sanitize($_POST['brand']):$editproduct['Brand']);
    $parentquery = $db->query("SELECT * FROM categories WHERE ID = '$categories'");
    $parentResult = mysqli_fetch_assoc($parentquery);
    $parent = ((isset($_POST['parent']) && $_POST['parent'] != '')?sanitize($_POST['parent']):$parentResult['parent']);
    $price = ((isset($_POST['price']) && $_POST['price'] != '')?sanitize($_POST['price']):$editproduct['Price']);
    $list_price = ((isset($_POST['list_price']) && $_POST['list_price'] != '')?sanitize($_POST['list_price']):$editproduct['List_Price']);
    $description = ((isset($_POST['description']) && $_POST['description'] != '')?sanitize($_POST['description']):$editproduct['Description']);
    $Sizes = ((isset($_POST['Sizes']) && $_POST['Sizes'] != '')?sanitize($_POST['Sizes']):$editproduct['size']);
    $Sizes       = rtrim($Sizes, ',');
    $saved_image = (($editproduct['Image'] != '')?$editproduct['Image']:'');
    $dbpath      = $saved_image;
   }
  // errors array 
  $errors =  array();
  if(!empty($Sizes)){
       $sizestring = sanitize($Sizes);
       $sizestring = rtrim($sizestring, ',');
       $sizestring = explode(',', $sizestring);
       $sarray = array();
       $qarray = array();
       $tarray = array();
       // file product
       foreach($sizestring as $ss) {
         $s = explode(':', $ss); 
         $sarray[] = $s[0];
         $qarray[] = $s[1];
         $tarray[] = $s[2];
       }
     }else{$sizestring = array();}
     
  // sizestring
  if($_POST){
      
       // modal trick
     $modalvalue = array('title', 'brand', 'parent','child', 'price', 'Sizes'); 
     
     $allowed  = array('png','jpeg','jpg','gif');
     $uploadepath = array();
     $tmp_name = array();
     foreach ($modalvalue as $value) {
      if(!isset($_POST[$value]['child'])){
          $_POST[$value]['child'] = '';
     }
      if($_POST[$value] == ''){
       $errors[] = 'Thie Fields  Whidth * C\'ant Be Empty';
       break;
       }
     }
     // photp trick
     var_dump($_FILES['photo']);
     $photocount = count($_FILES['photo']['name']);
     if($photocount > 0){
       for($i =0; $i<$photocount;$i++){ 
        $name = $_FILES['photo']['name'][$i];
        $namearray = explode('.', $name);
        $filname = $namearray[0];
        $filetype = $namearray[1];
        $mime = explode('/', $_FILES['photo']['type'][$i]);
        $mimetype = $mime[0];
        $mimeext = $mime[1];
        $tmp_name[] = $_FILES['photo']['tmp_name'][$i];
        $size = $_FILES['photo']['size'][$i];
          // trick image
        $uploadename = md5(microtime().$i).'.'.$filetype;
        $uploadepath[] = BASEURL.'images/products/'.$uploadename;
        if($i != 0){
           $dbpath .= ',';
        }
        $dbpath  .= '/boutique_shop/images/products/'.$uploadename;

          if($mimetype != 'image'){
              $errors[] = 'You Must Enter A valide Photo';   
          }
          if(!in_array($mimeext, $allowed)){
            $errors[] = 'This File Cant Be  Valid';
          }
          if($size > 10000000){
            $errors[] = 'The File C\'ant Be More Than 10MG';
          }
       }
     }
     
     // error function
     if(!empty($errors)){
      echo error_message($errors);
     }else{
        // uploade product to database
      if($photocount > 0){
         for($i = 0; $i<$photocount;$i++){
            move_uploaded_file($tmp_name[$i], $uploadepath[$i]);
         }
     }
      $produitsql = "INSERT INTO product (`Title`,`Price`,`List_Price`,`Brand`,`Categorie`,`Image`,`Description`,`size`)
                     VALUES ('$title','$price','$list_price','$brand','$categories','$dbpath','$description','$Sizes')";
                     if(isset($_GET['edit'])){
                      $produitsql = "UPDATE product SET Title = '$title', Price = '$price', List_Price = '$list_price', Brand = '$brand', Categorie = '$categories', Image = '$dbpath',
                       Description = '$description', size = '$Sizes' WHERE ID = '$edit_id'";
                     }
                     $db->query($produitsql);
                     header('LOCATION: product.php');
     }
  }

  ?>
  
  <h2 class="text-center"><?= ((isset($_GET['edit']))?'Edit':'Add'); ?> Product</h2>
  <form action="product.php?<?= ((isset($_GET['edit']))?'edit='.$edit_id:'add=1'); ?>" method="POST" enctype="multipart/form-data">
    <div class="form-group col-md-3">
      <label  for="title">Title *:</label>
      <input type="text" class="form-control" id="title" name="title" value="<?= $title;?>">
    </div>
    <div class="form-group col-md-3">
      <label  for="brand">Brand *:</label>
      <select class="form-control" id="brand" name="brand">
        <option value="" <?= (($brand == '')?' selected':''); ?>></option>
        <?php while($br = mysqli_fetch_assoc($postbrand)):?>
        <option value="<?=$br['ID'];?>" <?=(($brand == $br['ID'])?' selected':''); ?>><?= $br['brand'];?></option>
        <?php endwhile;?>
      </select>
    </div>
    <div class="form-group col-md-3">
      <label  for="parent">Parent *:</label>
      <select class="form-control" id="parent" name="parent">
        <option value="" <?=(($parent == '')?' selected':''); ?>></option>
       <?php while($par = mysqli_fetch_assoc($parentbrand)):?>
           <option value="<?=$par['ID'];?>" <?=(($parent == $par['ID'])?' selected':'');?>><?=$par['cat_name'];?></option>
        <?php endwhile;?> 
      </select>
    </div>
     
    <div class="col-md-3 form-group">
      <label for="child">Child * :</label>
      <select class="form-control" id="child" name="child">
        
        </select>
    </div> 
    <div class="col-md-3 form-group">
      <label for="price">Price * :</label>
      <input type="text" id="price" name="price" class="form-control" value="<?= $price; ?>">
    </div> 
    <div class="col-md-3 form-group">
      <label for="list_price">List Price * :</label>
       <input type="text" id="list_price" name="list_price" class="form-control" value="<?= $list_price; ?>">
    </div> 
    <div class="col-md-3 form-group">
      <label>Quantity & Sizes * :</label>
      <button  class="form-control btn btn-default" onclick="$('#sizemodal').modal('toggle'); return false;">Quantity & Sizes</button>
    </div> 
    <div class="col-md-3 form-group">
      <label for="Sizes">Sizes & Preview* :</label>
      <input type="text" id="Sizes" name="Sizes" class="form-control" value="<?= $Sizes; ?>" readonly>
    </div> 
    <div class="col-md-6 form-group">
      <?php if($saved_image != ''): ?>
       <?php
        $img = 1;
        $images = explode(',',$saved_image);

       ?>
          <?php foreach($images as $image): ?>
           <div class="saved_image col-md-4">
              <img src="<?= $image;?>" alt="saved image" />
               <a href="product.php?delite_image=1&edit=<?= $edit_id;?>&img=<?=$image;?>" class="text-danger">Delite Image</a>
           </div>
           <?php
             $img++;
           endforeach; ?>
       <?php else: ?>
         <label for="photo">Photo* :</label>
         <input type="file" id="photo" name="photo[]" class="form-control" multiple>
      <?php endif;?>
    </div>
    <div class="col-md-6 form-group">
      <label for="description">Description* :</label>
      <textarea id="description" name="description" class="form-control"><?= $description; ?></textarea>
    </div>
    <div class="col-md-6 pull-right">
        <a href="product.php" class="btn btn-default">Cansel</a>
        <input type="submit" class="btn btn-success" value="<?= ((isset($_GET['edit']))?'Edit':'Add'); ?> Produit" >
    </div>
  </form> 





<!-- Modal -->
<div class="modal fade" id="sizemodal" tabindex="-1" role="dialog" aria-labelledby="sizelabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="sizelabel">Quantity & Sizes</h4>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <?php for($i=1; $i <= 12; $i ++):?>
             <div class="form-group col-md-2">
               <label for="size<?=$i;?>">Sizes :</label>
               <input type="text" name="size<?=$i;?>" id="size<?=$i;?>" value="<?=((!empty($sarray[$i-1]))?$sarray[$i-1]:''); ?>" class="form-control">
             </div>
             <div class="form-group col-md-2">
               <label for="qnt<?=$i;?>">Quantity :</label>
               <input type="number" name="qnt<?=$i;?>" id="qnt<?=$i;?>" value="<?=((!empty($qarray[$i-1]))?$qarray[$i-1]:''); ?>" class="form-control" min=0>
             </div>
             <div class="form-group col-md-2">
               <label for="threshold<?=$i;?>">Threshold :</label>
               <input type="number" name="threshold<?=$i;?>" id="threshold<?=$i;?>" value="<?=((!empty($tarray[$i-1]))?$tarray[$i-1]:''); ?>" class="form-control" min=0>
             </div>
          <?php endfor;?>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" >Close</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="updatesize();$('#sizesModal').modal('toggle');return false;">Save changes</button>
      </div>
    </div>
  </div>
</div>
 <?php
}else{

    //  select all product
 $sql = "SELECT * FROM product WHERE Delited = 0";
 $stmt = $db->query($sql);

 // update featured product
 if(isset($_GET['featured'])){
    $id = (int)$_GET['id'];
    $feature = (int)$_GET['featured'];
    $upsql = "UPDATE product SET  featured = '$feature' WHERE ID = '$id'";
    $db->query($upsql);
    header('LOCATION: product.php');
 }

?>
<h2 class="text-center">Product</h2>
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
      <?php while($product = mysqli_fetch_assoc($stmt)): 
          $catpaerent = $product['Categorie'];
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
      		<a href="product.php?edit=<?=$product['ID']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
      		<a href="product.php?delite=<?=$product['ID']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove"></span></a>
            
        </td>
      	<td><?= $product['Title'];?></td>
      	<td><?= mony($product['List_Price']);?></td>
      	<td><?= $catygory;?></td>
      	<td>
      		<a href="product.php?featured=<?=(($product['featured'] == 0)?'1':'0');?>&id=<?=$product['ID'];?>" 
            class="btn btn-default btn-xs">
            <span class="glyphicon glyphicon-<?=(($product['featured'] == 1)?'minus':'plus');?>"></span></a>
            &nbsp <?=(($product['featured'] == 1)?'featured product':'')?>
      	</td>
      	<td></td>

      </tr>

      <?php endwhile;?>

	</tbody>
</table>


    
<?php  }  

 
 include 'includes/bfooter.php'; 

 echo ob_get_clean();
?>
<script>
 $(document).ready(function(){
  child_category('<?=$categories;?>');
 });
</script>
