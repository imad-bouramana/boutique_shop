<?php
function error_message($errors){
   $display = '<ul class="bg-danger">';
   foreach ($errors as $error) {
   	$display .= '<li class="text-danger">' . $error. '</li>';
   }
   $display .= '</ul>';
   return $display;
}
function sanitize($dirty){
	return htmlentities($dirty, ENT_QUOTES, "UTF-8");
}
// dollar sign function
function mony($number){
	return '$ '. number_format($number, 2);
}
function login($userid){
	$_SESSION['dbadmin'] = $userid;
	global $db;
	$date = date('Y-m-d H:i:s');
    $db->query("UPDATE users SET latest_join = '$date' WHERE ID = '$userid'");
	$_SESSION['success_admin'] = 'Welcome You Are Login';
	header('location: bindex.php');
}
function login_in(){
	if(isset($_SESSION['dbadmin']) && $_SESSION['dbadmin'] > 0){
		return true;
	}else{
  return false;
	}
}
function login_error(){
	$_SESSION['error_admin'] = 'You Cant Enter Please Login In';
	header('LOCATION: login.php');
}
function login_error_permission(){
	$_SESSION['error_admin'] = 'You Havent Permission The Enter';
	header('LOCATION: login.php');
	
}
function has_permission($permission = 'admin'){
	global $logindata;
 $permissons = explode(',', $logindata['permission']);
 if(in_array($permission, $permissons, true)){
 	return true;
 }else{
  return false;
	}
}
function pretty($date){
	return date("M d, Y h:i A", strtotime($date));
}
function getcategory($child_id){
	global $db;
	$cat = sanitize($child_id);
	$sql = "SELECT p.ID as 'parent_id', p.cat_name as 'parent', c.ID as 'child_id', c.cat_name as 'child'
           FROM categories c
           INNER JOIN categories p
          ON c.Parent = p.ID   WHERE c.ID = '$cat'";
    $childs = $db->query($sql);
    $querychild = mysqli_fetch_assoc($childs);
    return $querychild;
}
function sizeToArray($string){
	$stringarray = explode(',', $string);
	$newarray = array();
	foreach($stringarray as $sizes){
		$s = explode(':', $sizes);
		$newarray[] = array('size' => $s[0], 'quantity' => $s[1],  'threshold' => $s[2]);
	}
	 return $newarray;
}
function sizeToString($sizes){
	$sizestring = '';
	foreach($sizes as $size){
		$sizestring .= $size['size'].':'.$size['quantity'].':'.$size['threshold'].',';
		}
	$trim = rtrim($sizestring, ',');	
	return $trim;
}