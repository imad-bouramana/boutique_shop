<?php
 require_once 'core/init.php';
// Set your secret key: remember to change this to your live secret key in production
// See your keys here: https://dashboard.stripe.com/account/apikeys
\Stripe\Stripe::setApiKey(STRIPE_PRIVATE);

// Get the credit card details submitted by the form

  $token = $_POST['stripeToken'];
  $name = sanitize($_POST['full_name']);
  $email = sanitize($_POST['email']);
  $adress = sanitize($_POST['adress']);
  $adress2 = sanitize($_POST['adress2']);
  $city = sanitize($_POST['city']);
  $state = sanitize($_POST['state']);
  $zip_code = sanitize($_POST['zip_code']);
  $country = sanitize($_POST['country']);
  $tax = sanitize($_POST['tax']);
  $sub_total = sanitize($_POST['sub_total']);
  $grand_total = sanitize($_POST['grand_total']);
  $cart_id = sanitize($_POST['cart_id']);
  $description =  sanitize($_POST['description']);
  $chang_amount = number_format($grand_total, 2) * 100;
  $metadata = array(
    "cart_id"   => $cart_id,
    "tax"       => $tax,
    "sub_total" => $sub_total,

    );

// Create a charge: this will charge the user's card
try {
  $charge = \Stripe\Charge::create(array(
    "amount" => $chang_amount, // Amount in cents
    "currency" => CURRENCY,
    "source" => $token,
    "description" => $description,
   // "reciept_email" => $email,
    "metadata" => $metadata
    ));
  // edjust quantity
  $cartitems = $db->query("SELECT * FROM cart WHERE ID = '{$cart_id}'");
  $result = mysqli_fetch_assoc($cartitems);
  $items = json_decode($result['items'], true);
  foreach ($items as $item) {
     $newarray = array();
     $itemid = $item['ID'];
     $productitems = $db->query("SELECT size FROM product WHERE ID = '{$itemid}'");
     $presult =mysqli_fetch_assoc($productitems);
     $sizes = sizeToArray($presult['size']);
     foreach($sizes as $size){
         if($size['size'] == $item['size']){
          $q = $size['quantity'] - $item['quantity'];
          $newarray[] = array('size' => $size['size'], 'quantity' => $q, 'threshold' => $size['threshold']);
         }else{
          $newarray[] = array('size' => $size['size'], 'quantity' => $size['quantity'], 'threshold' => $size['threshold']);
         }
     }
     $sizestring = sizeToString($newarray);
     $db->query("UPDATE product SET size = '{$sizestring}' WHERE ID = '{$itemid}'");
  }

  // update cart
  $db->query("UPDATE cart SET paid = 1 WHERE ID = '{$cart_id}'");
  $db->query("INSERT INTO transactions
    (charge_id,cart_id,full_name,email,adress,adress2,city,state,zip_code,country,sub_totale,tax,grand_total,description,txn_type) VALUES 
    ('$charge->id','$cart_id','$name','$email','adress','adress2','$city','$state','$zip_code','$country','$sub_total','$tax','$grand_total','$description','$charge->object')");

  //$domain = ($_SERVER['HTTP_HOST'] != 'localhost')? '.'.$_SERVER['HTTP_HOST']:false;
  $domain = false;
  setcookie(CART_COOKIE, '', 1, "/", $domain,false);
   include 'includes/header.php';
   include 'includes/navbar.php';
   include 'includes/child_logoMarket.php';
?>
   <h2 class="text-center text-success">Thank You!</h2>
   <div class="successfly">
   <p class="lead">Your card has been succesfully charged  <?= mony($grand_total);?>. you have been emailed a receipt. Please
    chech your span folder if it is not in your inbox. Additionally you can print this page as a receipt.</p>
   <p class="lead">Your receipt number Is: <strong><?=$cart_id;?></strong></p>
   <p class="lead">Your order will be shipped ti the address below.</p>
   <address>
     <?=$name;?><br>
     <?=$adress;?><br>
     <?=(($adress2 != '')?$adress2. '<br>':'');?>
     <?=$city. ' .'.$state.' '.$zip_code;?><br>
     <?=$country;?>
   </address>
  </div>

<?php
include 'includes/footer.php';

} catch(\Stripe\Error\Card $e) {
  // The card has been declined
  echo $e;
}

?>