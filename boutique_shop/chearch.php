<?php 
  require_once 'core/init.php';
  include 'includes/header.php';
  include 'includes/navbar.php';
  include 'includes/child_logoMarket.php';
  include 'includes/leftside.php';

  $sql = "SELECT * FROM product";
  $cat_id = (($_POST['cat'] != '')?sanitize($_POST['cat']):'');
  if($cat_id == ''){
    $sql .= " WHERE Delited = 0";
  }else{
    $sql .= " WHERE Categorie = '{$cat_id} AND Delited = 0'";
  }
  $sort_price = ((isset($_POST['sort_price']) && $_POST['sort_price'] != '')? sanitize($_POST['sort_price']):'');
  $min_price = ((isset($_POST['min_price']) && $_POST['min_price'] != '')? sanitize($_POST['min_price']):'');
  $max_price = ((isset($_POST['max_price']) && $_POST['max_price'] != '')? sanitize($_POST['max_price']):'');
  $brand = ((isset($_POST['brand']) && $_POST['brand'] != '')? sanitize($_POST['brand']):'');

  if($min_price != ''){
    $sql .= " AND Price >= '{$min_price}'";
  }
  if($max_price != ''){
    $sql .= " AND Price <= '{$max_price}'";
  }
  if($brand != ''){
    $sql .= " AND Brand = '{$brand}'";
  }
  if($sort_price == 'low'){
    $sql .= " ORDER BY Price";
  }
  if($sort_price == 'hight'){
    $sql .= " ORDER BY Price DESC";
  }
  
  $productcat = $db->query($sql);
  $product = getcategory($cat_id);
    ?>
  		<!-- middle side -->
          <div class="col-md-8">
               <div class="row">
                  <?php if($cart_id != ''): ?>
                    <h2 class="text-center"><?=$product['parent']. ' '. $product['child']; ?></h2>
                  <?php else: ?>
                    <h2 class="text-center"> Imad Bautique</h2>
                  <?php endif; ?>
                    <?php while($product = mysqli_fetch_assoc($productcat)) : ?>
                    <div class="col-md-3 text-center">
                         <h4><?php echo $product['Title']; ?></h4>
                         <?php $photo = explode(',', $product['Image']); ?>
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

