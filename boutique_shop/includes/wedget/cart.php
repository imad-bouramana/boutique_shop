<h3 class="text-center">Cart Shoping</h3>
<div id="wedget">
	<?php if(empty($cart_id)): ?>
     <p>Your Cart Shoping Is Empty</p>
   <?php else: 
     
      $itemq = $db->query("SELECT * FROM cart WHERE  ID = '{$cart_id}'");
      $result = mysqli_fetch_assoc($itemq);
      $items = json_decode($result['items'], true);
      $sub_totale = 0;
      ?>

      <table class="table table-condensed">
      	<tbody>
      	<?php  foreach ($items as $item):
           $productq = $db->query("SELECT * FROM product WHERE ID = '{$item['ID']}'");
           $product = mysqli_fetch_assoc($productq);
         ?>
         <tr>
      	  <td><?=$item['quantity'];?></td>
      	  <td><?=substr($product['Title'],0,14);?></td>
      	   <td><?=mony($item['quantity'] * $product['Price']);?></td>
         </tr>
         <?php 
          $sub_totale += ($item['quantity'] * $product['Price']);
        
         endforeach; ?>
         <tr>
         	<td></td>
         	<td>Sub Totale</td>
         	<td><?=mony($sub_totale);?></td>
         </tr>
       </tbody>
      </table>
      <a href="my_cart.php" class="btn btn-primary pull-right"> View Cart</a>
      <div class="clearfix"></div>
    <?php endif; ?>
</div>