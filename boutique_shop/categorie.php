<?php 
  require_once 'core/init.php';
  include 'includes/header.php';
  include 'includes/navbar.php';
  include 'includes/child_logoMarket.php';
  include 'includes/leftside.php';
  if(isset($_GET['cat'])){
    $cat_id = sanitize($_GET['cat']);
  }else{
    $cat_id = '';
  }
  $sql = "SELECT * FROM product WHERE Categorie = '$cat_id'";
  $productcat = $db->query($sql);
  $product = getcategory($cat_id);
    ?>
  		<!-- middle side -->
          <div class="col-md-8">
               <div class="row">
                    <h1 class="text-center"><?=$product['parent']. ' '. $product['child']; ?></h1>
                    <?php while($product = mysqli_fetch_assoc($productcat)) : ?>
                    <div class="col-md-3 text-center">
                         <h4><?php echo $product['Title']; ?></h4>
                         <?php  $photo = explode(',', $product['Image']); ?>
                         <img src="<?php echo $photo[0]; ?>" alt="<?php echo $product['Image']; ?>" class="img-thumb" />
                         <p class="text-danger last-price">Last Price :<s>$<?= $product['List_Price']; ?></s></p>
                         <p class="price">New Price : $<?= $product['Price']; ?></p>
                         <button type="button" class="btn btn-success btn-sm" onclick="detailmodal(<?= $product['ID']; ?>)" >Details</button>
                    </div>
                <?php endwhile; ?>
               </div>
          </div>
       
<?php 
   include 'includes/rightside.php';
   include 'includes/footer.php';

