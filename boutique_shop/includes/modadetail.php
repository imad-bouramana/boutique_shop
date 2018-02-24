<?php ob_start(); 

   require_once "../core/init.php";
   // product table
   $id = $_POST['ID'];
   $id = (int)$id;
   $stmt = "SELECT * FROM product WHERE ID = '$id'";
   $sql = $db->query($stmt);
   $product1 = mysqli_fetch_assoc($sql);
   // brand table
   $brand_id = $product1['Brand'];
   $stmt2 = "SELECT brand FROM brand WHERE ID = '$brand_id'";
   $sql2 = $db->query($stmt2);
   $brand = mysqli_fetch_assoc($sql2);
   // size explode
   $size = $product1['size'];
   $product_size = explode(',', $size);

?>

    <!-- modal -->
     <div class="modal fade details-1" id="detail-modal" tabindex="-1" role="dialog" aria-labelledby="details-1" aria-hidden="true">
     	<div class="modal-dialog modal-lg">
     	  <div class="modal-content"	>
     		<div class="modal-header">
     			<button class="close" type="button" onclick="modalhide()" aria-label="close">
     				<span aria-hidden="true">&times;</span>
     			</button>
     			<h4 class="text-center modal-title"><?= $product1['Title'];?></h4>
     		</div>
     		<div class="modal-body">
     			<div class="container-fluid">
     				<div class="row">
              <span id="span_cart" class="bg-danger"></span>
     					<div class="col-sm-6 fotorama">
                  <?php
                   $photos = explode(',', $product1['Image']);
                   foreach($photos as $photo):
                  ?>
                <div class="center-block">
     							<img src="<?= $photo;?>" alt="<?= $product1['Title'];?>" class="img-responsive" />
                </div>
                  <?php endforeach; ?>
     		        
     					</div>
     					<div class="col-sm-6">
     						<h4>Details</h4>
     						<p><?php echo $product1['Description']; ?></p>
     						<hr>
     				          <p class="price">Price : $<?= $product1['Price'];?></p>
     				          <p>Brand : <?= $brand['brand'];?></p>
                                   <form action="add_cart.php" method="post" id="cart_form">
                                    <input type="hidden" name="product_id"  value="<?=$id;?>">
                                    <input type="hidden" name="avialable" id="avialable" value="">
                                        <div class="form-group col-xs-3">
                                             <div class="colon">
                                                  <label for="quantity" class="quantity">Quantity :</label>
                                                  <input type="number" class="form-control" id="quantity" name="quantity" min=0>
                                             </div>
                                             
                                        </div>
                                        <br><br><br><br>
                                        <div class="form-group">
                                             <label for="size">Size :</label>
                                             <select name="size" class="form-control" id="size">
                                                  <option value=""></option>
                                                  <?php 
                                                  foreach ($product_size as $sizes) {
                                                       $size_array = explode(':', $sizes);
                                                       $size     = $size_array[0];
                                                       $avialable = $size_array[1];
                                                       if($avialable > 0){
                                                     echo '<option value="'. $size .'" data-avialable="'.$avialable.'">'.$size.' <> '.$avialable.' avialable </option>';
                                                        }
                                                  }
                                                
                                                  ?>
                                             </select>
                                        </div>

                                   </form>
     				    </div>
     				</div>
     			</div>
     		</div>
     		<div class="modal-footer">
     			<button class="btn btn-default" onclick="modalhide()">Close</button>
     			<button class="btn btn-success" onclick="add_to_cart(); return false;"><span class="glyphicon glyphicon-shopping-cart"></span> Add The Cart</button>
     		</div>
     	 </div>
     	</div>
     </div>
     <script type="text/javascript">
       $('#size').change(function(){
           var avialable = $('#size option:selected').data("avialable");
           $('#avialable').val(avialable);
        });
       $(function () {
          $('.fotorama').fotorama({
            'loop': true, 'autoplat': true
          });
        });

       function modalhide(){
  $('#detail-modal').modal('hide');
  setTimeout(function(){
    $('#detail-modal').remove();
    $('.modal-backdrop').remove();
  
  },500);
  
}
     </script>
  <?php  echo  ob_get_clean(); ?>