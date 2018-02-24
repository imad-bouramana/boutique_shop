<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/boutique_shop/core/init.php';
  
  $name = sanitize($_POST['full_name']);
  $email = sanitize($_POST['email']);
  $adress = sanitize($_POST['adress']);
  $adress2 = sanitize($_POST['adress2']);
  $city = sanitize($_POST['city']);
  $state = sanitize($_POST['state']);
  $zip_code = sanitize($_POST['zip_code']);
  $country = sanitize($_POST['country']);
  
  $errors = array();
  $required = array(
  	'full_name' => 'Full Name',
  	'email' => 'Email',
  	'adress' => 'Adress',
  	'city' => 'City',
  	'state' => 'State',
  	'zip_code' => 'Zip Code',
  	'country' => 'Country',
  	);
  
  foreach ($required as $k => $v) {
  	   if(empty($_POST[$k]) || $_POST[$k] == ''){
          $errors[] = $v. ' Is Required';
  	   }
  }
  // check if email is valid
  if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    $errors[] = 'Please enter a valid email!';
  } 
  //check if empty error
  if(!empty($errors)){
     echo  error_message($errors);
  }else{
  	echo 'Added';
  }
?>