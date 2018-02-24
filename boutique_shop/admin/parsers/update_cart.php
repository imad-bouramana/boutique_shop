<?php
ob_start();
  require_once $_SERVER['DOCUMENT_ROOT']. '/boutique_shop/core/init.php';
  $mode      = ((isset($_POST['mode']))?sanitize($_POST['mode']):'');
  $edit_id   = ((isset($_POST['edit_id']))?sanitize($_POST['edit_id']):'');
 
  $edit_size = ((isset($_POST['edit_size']))?sanitize($_POST['edit_size']):'');
 
  $cart_update = $db->query("SELECT * FROM cart WHERE ID = '{$cart_id}'");
  $result = mysqli_fetch_assoc($cart_update);
  $items = json_decode($result['items'],true);
  $update_items = array();
  
  //$domain = (($_SERVER['HTTP_HOST'] != 'localhost')?'.'.$_SERVER['HTTP_HOST']:false);
  $domain = false;
  if($mode == 'removone'){
    foreach($items as $item){
  	 	if($item['ID'] == $edit_id && $item['size'] == $edit_size){
  	 		$item['quantity'] = $item['quantity'] - 1;
  	 	}
  	 	if($item['quantity'] > 0){
  	 	$update_items[] = $item;
  	 	}
  	 }
  }
  if($mode == 'addone'){
    foreach($items as $item){
  	 	if($item['ID'] == $edit_id && $item['size'] == $edit_size){
  	 		$item['quantity'] +=  1;
  	 	}
  	 	$update_items[] = $item;
  	}
  }
  if(!empty($update_items)){
  	$json_update = json_encode($update_items);
  	$db->query("UPDATE cart SET items = '{$json_update}' WHERE ID = '{$cart_id}'");
  	$_SESSION['success_admin'] = 'Your Cart Shop Was Updated';
  }
  if(empty($update_items)){
     $db->query("DELETE FROM cart WHERE ID = '{$cart_id}'");
     setcookie(CART_COOKIE,'',1,"/",$domain,false);    
  }
  ob_get_clean();
?>