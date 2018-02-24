<?php 
   ob_start();
 require_once $_SERVER['DOCUMENT_ROOT'] . '/boutique_shop/core/init.php';
 if(!login_in()){
    login_error();
 }
  
 include 'includes/bheader.php';
 $hashed = $logindata['password'];
 $old_password = ((isset($_POST['old_password']))?sanitize($_POST['old_password']):'');
 $old_password = trim($old_password);
 
 $password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
 $password = trim($password);

 $confirm = ((isset($_POST['confirm']))?sanitize($_POST['confirm']):'');
 $confirm = trim($confirm);
 $newhash = password_hash($password, PASSWORD_DEFAULT);
 $user_id = $logindata['ID'];
 $errors  = array();
?>
<div id="login_form">
	<div>
     <?php  
     if($_POST){
     // validate form
     if(empty($_POST['old_password']) || empty($_POST['password']) || empty($_POST['confirm'])){
     	$errors[] = 'You Must Enter Old_password And Password And Confirm..!';
     } 

     
     if(strlen($password < 6)){
     	$errors[] = 'The Password Must Be More Thane 6 Characters';
     }
     // chek if password match of user password
     if($password != $confirm){
        $errors[] = 'The Password And Confirm Not Match';
     }
     // chek if password is matsh of admin password
     if(!password_verify($old_password, $hashed)){
 
    	$errors[] = 'Sory This Password Not Matsh Of Users Password!';
     }
     // function erors
     if(!empty($errors)){
     	 echo error_message($errors);
        }else{
            $db->query("UPDATE users SET password = '$newhash' WHERE ID = '$user_id'");
            $_SESSION['success_admin'] = 'Your Password Has Change Whith Success';
            header('LOCATION: bindex.php');
            }
    }    
       ?>
	</div>
	<h2 class="text-center">Change Password</h2>
	<form action="change_password.php" method="post">
       <div class="form-group">
			<label for="old_password">Old Password :</label>
			<input type="password" id="old_password" name="old_password" class="form-control" value="<?=$old_password;?>">
		</div>
		<div class="form-group">
			<label for="password">New Password :</label>
			<input type="password" id="password" name="password" class="form-control" value="<?=$password;?>">
		</div>
        <div class="form-group">
            <label for="confirm">Confirm New Password :</label>
            <input type="password" id="confirm" name="confirm" class="form-control" value="<?=$confirm;?>">
        </div>
        
		<div class="form-group">
            <a href="bindex.php" class="btn btn-default">Cancel</a>
			<input type="submit" value="login" class="btn btn-info">
		</div>
	</form>
	<p class="text-right"><a href="bindex.php">Boutique</a></p>
</div>

<?php
 include 'includes/bfooter.php';
 ob_end_flush();