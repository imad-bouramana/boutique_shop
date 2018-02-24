<?php

$db = 	mysqli_connect('127.0.0.1','root','','tutorial');

if(mysqli_connect_errno()){
   echo 'error connect' . mysqli_connect_error();
   die();
}
session_start();
require_once $_SERVER['DOCUMENT_ROOT'].'/boutique_shop/config.php';
require_once  BASEURL.'helpers/helpers.php';
require BASEURL.'vendor/autoload.php';

$cart_id = '';
if(isset($_COOKIE[CART_COOKIE])){
   $cart_id = sanitize($_COOKIE[CART_COOKIE]);
}

if(isset($_SESSION['dbadmin'])){
   $userid = $_SESSION['dbadmin'];
   $loginadmin = $db->query("SELECT * FROM users WHERE ID = '$userid'");
   $logindata = mysqli_fetch_assoc($loginadmin);
   $fn = explode(' ', $logindata['full_name']);
   $logindata['first'] = $fn[0];
   //$logindata['last']  = $fn[1];
   if(isset($logindata['last'])){
      $logindata['last'] = $fn[1];
   }else{
      $logindata['last'] = '';
   }
}
if(isset($_SESSION['success_admin'])){
	echo '<div class="bg-success"><p class="text-success text-center">'. $_SESSION['success_admin'] .'</p></div>';
	
	unset($_SESSION['success_admin']);
}
if(isset($_SESSION['error_admin'])){
	echo '<div class="bg-danger"><p class="text-danger text-center">'. $_SESSION['error_admin'] .'</p></div>';

	unset($_SESSION['error_admin']);
}
