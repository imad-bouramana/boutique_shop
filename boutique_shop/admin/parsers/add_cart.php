<?php
ob_start();
 require_once $_SERVER['DOCUMENT_ROOT'] . '/boutique_shop/core/init.php';

 $product_id = (isset($_POST['product_id'])? sanitize($_POST['product_id']):'');
 $size = (isset($_POST['size'])? sanitize($_POST['size']):'');
 $avialable = (isset($_POST['avialable'])? sanitize($_POST['avialable']):'');
 $quantity = (isset($_POST['quantity'])? sanitize($_POST['quantity']):'');
 $item = array();
 $item[] = array(
 	'ID' => $product_id,
 	'size' => $size,
 	'quantity' => $quantity
 	);
 //$domain = ($_SERVER['HTTP_HOST'] != 'localhost')?'.'.$_SERVER['HTTP_HOST']:false;
 $domain = false;
 $quer = $db->query("SELECT * FROM product WHERE ID = '$product_id'");
 $product = mysqli_fetch_assoc($quer);
 $_SESSION['success_admin'] = $product['Title'].' was added to cart';
 // chek if cart exist in database
if($cart_id != ''){
    $cartq = $db->query("SELECT * FROM cart WHERE  ID = '{$cart_id}'");
    $cart = mysqli_fetch_assoc($cartq);
    $previous_item = json_decode($cart['items'],true);
    $item_match = 0;
    $new_item = array();
    foreach ($previous_item as $pr_item) {
      if($item[0]['ID'] == $pr_item['ID'] && $item[0]['size'] == $pr_item['size']){
      	$pr_item['quantity'] = $pr_item['quantity'] + $item[0]['quantity'];
      	if($pr_item['quantity'] > $avialable){
      		$pr_item['quantity'] = $avialable;
      	}
      	$item_match = 1;
      }
    $new_item[] = $pr_item;
    }
    if($item_match != 1){
    	$new_item = array_merge($item,$previous_item);
    }
    $item_json = json_encode($new_item);
    $cart_expire = date('Y-m-d H:i:s', strtotime("+30 days"));
    $db->query("UPDATE cart SET items = '{$item_json}', expire_time = '{$cart_expire}' WHERE ID = '{$cart_id}'");
    setcookie(CART_COOKIE, '',1, "/",$domain,false);
    setcookie(CART_COOKIE,$cart_id,CART_COOKIE_EXPIRE,'/',$domain,false);
}else{
	$item_json = json_encode($item);
	$cart_expire = date("Y-m-d H:i:s", strtotime("+30 days"));
	$db->query("INSERT INTO cart (items, expire_time) VALUES ('{$item_json}', '{$cart_expire}')");
	$cart_id = $db->insert_id;
	setcookie(CART_COOKIE, $cart_id, CART_COOKIE_EXPIRE, '/',$domain,false);
}
ob_get_clean();