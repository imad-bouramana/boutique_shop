<?php 

  require_once 'core/init.php';
  include 'includes/header.php';
  include 'includes/navbar.php';
  include 'includes/logoMarket.php';
  include 'includes/leftside.php';

  $sql = "SELECT * FROM product WHERE featured = 1";
  $stmt = $db->query($sql);
    ?>
  		<!-- middle side -->
          <div class="col-md-8">
               <div class="row">
                    <h1 class="text-center">Feature Product</h1>
                    <?php while($product = mysqli_fetch_assoc($stmt)) : ?>
                    <div class="col-md-3 text-center">
                         <h4><?php echo $product['Title']; ?></h4>
                         <?php $photo = explode(',', $product['Image']); ?>
                         <img src="<?php echo $photo[0]; ?>" alt="<?php echo $product['Image']; ?>" class="img-thumb" />
                         <p class="text-danger last-price">Last Price :<s>$<?= $product['List_Price']; ?></s></p>
                         <p class="price">New Price : $<?= $product['Price']; ?></p>
                         <button type="button" class="btn btn-success btn-sm" onclick="detailmodal(<?= $product['ID']; ?>)">Details</button>
                    </div>
                <?php endwhile; ?>
               </div>
          </div>
       
<?php 
   include 'includes/rightside.php';
   include 'includes/footer.php';

