<?php
   require_once 'core/init.php';
   include 'includes/header.php';
   include 'includes/navbar.php';
   include 'includes/child_logoMarket.php';

   if($cart_id != ''){
   	  $result = $db->query("SELECT * FROM cart WHERE ID = '{$cart_id}'");
   	  $resultq = mysqli_fetch_assoc($result);
   	  $itemq = json_decode($resultq['items'], true);
   	  $i = 1;
   	  $items_count = 0;
   	  $sub_total = 0;

   }
?>
   <div class="col-md-12">
   	<div class="row">
   		<h2 class="text-center">My Shoping Cart</h2><hr>
   		<?php if($cart_id == ''): ?>
   		  <div class="bg-danger text-center">
   		  	 <p class="text-danger">Your Shoping Cart Is Empty!</p>
   		  </div>
   		<?php else:?>
   		 <table class="table table-bordered table-striped table-condensed">
   		 	<thead><th>#</th><th>item</th><th>Price</th><th>Size</th><th>Quantity</th><th>Total Shop</th></thead>
   		 <tbody>
           <?php 

               foreach($itemq as $item){
               	 $product_id = $item['ID'];
               	 $productq = $db->query("SELECT * FROM product WHERE ID = '{$product_id}'");
               	 $product = mysqli_fetch_assoc($productq);
               	 $sizes_q = $product['size'];
               	 $sizes = explode(',', $sizes_q);
               	 foreach ($sizes as $sizeq) {
               	 	$s = explode(':', $sizeq);
               	 	if($s[0] == $item['size']){
               	 		$avialable = $s[1];
               	 	}
               	 }
               	 ?>
               	 <tr>
               	 	<td><?=$i; ?></td>
               	 	<td><?=$product['Title']; ?></td>
               	 	<td><?=mony($product['Price']); ?></td>
               	 	<td><?=$item['size']; ?></td>
               	 	<td> 
                      <button class="btn btn-xs btn-default" onclick="update_cart('removone','<?=$product['ID'];?>','<?=$item['size'];?>');">-</button>
                       <?=$item['quantity']; ?>
                       <?php if($item['quantity'] < $avialable): ?>
                      <button class="btn btn-xs btn-default" onclick="update_cart('addone','<?=$product['ID'];?>','<?=$item['size'];?>');">+</button>
                    
                      <?php else: ?>
                       <span class="text-danger">No More Product</span>
                      <?php endif;?>
                  </td>
               	 	<td><?=mony($item['quantity'] * $product['Price']); ?></td>
               	 </tr>
         <?php    $i++;
                  $items_count += $item['quantity'];
                  $sub_total += $item['quantity'] * $product['Price'];
              } 
                $tax = TAXRATE * $sub_total;
                $tax = number_format($tax,2);
                $grand_totale = $tax + $sub_total;
            ?>
   		 	</tbody>
   		 </table>

   		 <table class="table table-bordered table-condensed table-striped table_cart">
   		 	<legend>Totals</legend><hr>
   		 	<thead><th>Items Count</th><th>Sub Totale</th><th>Tax</th><th>Grand Totale</th></thead>
   		 	<tbody>

   		 		<tr class="text-right">
   		 			<td><?=$items_count;?></td>
   		 			<td><?=mony($sub_total);?></td>
   		 			<td><?=mony($tax);?></td>
   		 			<td class="bg-success"><?=mony($grand_totale);?></td>
   		 		</tr>
   		 	</tbody>
   		 </table>
   		 <!-- Button trigger modal -->
<button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#my_cart">
    <span class="glyphicon glyphicon-shopping-cart"></span> Shoping Cart
</button>

<!-- Modal -->
<div class="modal fade" id="my_cart" tabindex="-1" role="dialog" aria-labelledby="my_cart_modal">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="my_cart_modal">Total Shoping</h4>
      </div>
      <div class="modal-body">
        <div class="row">
           <form action="thank_you.php" method="POST" id="form_adress">
            <span class="bg-danger" id="error_form"></span>
            <input type="hidden" name="tax" value="<?=$tax;?>">
            <input type="hidden" name="sub_total" value="<?=$sub_total;?>">
            <input type="hidden" name="grand_total" value="<?=$grand_totale;?>">
            <input type="hidden" name="cart_id" value="<?=$cart_id;?>">
            <input type="hidden" name="description" value="<?=$items_count .' item' . (($items_count > 1)?'s':'').' From Imad bautique';?>">
           
             <div id="form1" style="display:block;">
              <div class="col-md-6 form-group" >
                <label for="full_name">Full Name :</label>
                <input type="text" name="full_name" id="full_name" class="form-control">
              </div>
              <div class="col-md-6 form-group" >
                <label for="email">Email :</label>
                <input type="email" name="email" id="email" class="form-control">
              </div>
              <div class="col-md-6 form-group" >
                <label for="adress">Adress :</label>
                <input type="text" name="adress" id="adress" class="form-control" data-stripe="address_line1">
              </div>
              <div class="col-md-6 form-group" >
                <label for="adress2">Adress 2 :</label>
                <input type="text" name="adress2" id="adress2" class="form-control" data-stripe="address_line2">
              </div>
              <div class="col-md-6 form-group" >
                <label for="city">City :</label>
                <input type="text" name="city" id="city" class="form-control" data-stripe="address_city">
              </div>
              <div class="col-md-6 form-group" >
                <label for="state">State :</label>
                <input type="text" name="state" id="state" class="form-control" data-stripe="address_state">
              </div>
              <div class="col-md-6 form-group" >
                <label for="zip_code">Zip Code :</label>
                <input type="text" name="zip_code" id="zip_code" class="form-control" data-stripe="address_zip">
              </div>
              <div class="col-md-6 form-group" >
                <label for="country">Contry :</label>
                <input type="text" name="country" id="country" class="form-control" data-stripe="address_country">
              </div>
             </div>
            <div id="form2" style="display:none;">
              <div class="col-md-3 form-group">
                <label for="name">Name On Card :</label>
                <input type="text" class="form-control" id="name" data-stripe="name">
              </div>
              <div class="col-md-3 form-group">
                <label for="card">Card Number :</label>
                <input type="text" class="form-control" id="card" data-stripe="number">
              </div>
              <div class="col-md-2 form-group">
                <label for="cvc">CVC :</label>
                <input type="text" class="form-control" id="cvc" data-stripe="cvc">
              </div>
              <div class="col-md-2 form-group">
                <label for="expire_month">Expire Month :</label>
                <select class="form-control" id="exp_month" data-stripe="exp_month">
                  <option value=""></option>
                   <?php for($i=1; $i<13; $i++): ?>
                     <option value="<?=$i;?>"><?=$i;?></option>
                   <?php endfor;?>
                </select>
              </div>
              <div class="col-md-2 form-group">
                <label for="expire_year">Expire Year :</label>
                <select class="form-control" id="exp_year" data-stripe="exp_year">
                  <option value=""></option>
                  <?php $year = date('Y');?>
                  <?php for($i=0; $i<16; $i++): ?>
                     <option value="<?=$year+$i;?>"><?=$year+$i;?></option>
                   <?php endfor;?>
                </select>
              </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="check_adresse();" id="next_button">Next >></button>
        <button type="button" class="btn btn-primary" onclick="check_cart();" style="display:none;" id="back_button"> << Back </button>
        <button class="btn btn-primary" type="submit" style="display:none;" id="confirm_button"> Confirm </button>
      </div>
    </form>
    </div>
  </div>
</div>

     	<?php endif;?>
   	</div>
   </div>

   <script>
      function check_cart(){
              $('#error_form').html('');
              $('#form1').css('display', 'block');
              $('#form2').css('display', 'none');
              $('#next_button').css('display', 'inline-block');
              $('#back_button').css('display', 'none');
              $('#confirm_button').css('display', 'none');
              $('#my_cart_modal').html("Total Shoping");
           
        }
      function check_adresse(){
        'use strict';
         var data = {
          'full_name': $('#full_name').val(),
          'email' : $('#email').val(),
          'adress' : $('#adress').val(),
          'adress2' : $('#adress2').val(),
          'city'  : $('#city').val(), 
          'state' :  $('#state').val(),
          'zip_code' : $('#zip_code').val(),
          'country' : $('#country').val(),
           };
        $.ajax({
          url : '/boutique_shop/admin/parsers/chek_message.php',
          method : 'post',
          data : data,
          success : function(ad){
            if(ad != 'Added'){
              $('#error_form').html(ad);
            }
            if(ad == 'Added'){
              $('#error_form').html('');
              $('#form1').css('display', 'none');
              $('#form2').css('display', 'block');
              $('#next_button').css('display', 'none');
              $('#back_button').css('display', 'inline-block');
              $('#confirm_button').css('display', 'inline-block');
              $('#my_cart_modal').html("Confirm Shoping");
            }
          },
          error  : function(){alert('Something Went Wronge');},
        });
      }
      Stripe.setPublishableKey('<?=STRIPE_PUBLIC;?>');

      function stripeResponseHandler(status, response) {
            // Grab the form:
         var $form = $('#form_adress');

         if (response.error) { // Problem!

            // Show the errors on the form:
           $form.find('#error_form').text(response.error.message);
           $form.find('.submit').prop('disabled', false); // Re-enable submission

         } else { // Token was created!

             // Get the token ID:
          var token = response.id;

           // Insert the token ID into the form so it gets submitted to the server:
          $form.append($('<input type="hidden" name="stripeToken">').val(token));

          // Submit the form:
          $form.get(0).submit();
        }
    };
  
      
      $(function() {
        var $form = $('#form_adress');
              $form.submit(function(event) {
                // Disable the submit button to prevent repeated clicks:
              $form.find('.submit').prop('disabled', true);

               // Request a token from Stripe:
              Stripe.card.createToken($form, stripeResponseHandler);

                // Prevent the form from being submitted:
              return false;
             });
       });
   </script>
<?php 
   include 'includes/footer.php';
?>