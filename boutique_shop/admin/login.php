<?php 
   ob_start();
 require_once $_SERVER['DOCUMENT_ROOT'] . '/boutique_shop/core/init.php';
 include 'includes/bheader.php';

 $email    = ((isset($_POST['email']))?sanitize($_POST['email']):'');
 $email    = trim($email);
 $password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
 $password = trim($password);

 $errors  = array();
?>
<style type="text/css">
  
body{ 
	background-image: url("/boutique_shop/images/headerlogo/background.png");
    background-size: 100VW 100VH;
    background-attachment: fixed;
}
</style>
<div id="login_form">
	<div>
     <?php  
     if($_POST){
     // validate form
     if(empty($_POST['email']) || empty($_POST['password'])){
     	$errors[] = 'You Must Enter Email And Password..!';

     } 
     // chek if valid email 
     if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
     	$errors[] = 'You Must Enter A Valid Email';
     }
     // chek if email exist in database
     $sql = $db->query("SELECT * FROM users WHERE email = '$email'");
     $users = mysqli_fetch_assoc($sql);
     $emailcount = mysqli_num_rows($sql);

     if($emailcount < 1){
     	$errors[] = 'This Email Not Found In Database';
     }
     // chek if password is matsh of admin password
     if(!password_verify($password, ($users['password']))){
 
    	$errors[] = 'Sory This Password Not Matsh Of Admin Password!';
     }
     // function erors
     if(!empty($errors)){
     	 echo error_message($errors);
        }else{
            $userid = $users['ID'];
            login($userid);
        }
    }    
       ?>
	</div>
	<h2 class="text-center">Login</h2>
	<form action="login.php" method="post">
       <div class="form-group">
			<label for="email">Email :</label>
			<input type="email" id="email" name="email" class="form-control" value="<?=$email; ?>">
		</div>
		<div class="form-group">
			<label for="password">Password :</label>
			<input type="password" id="password" name="password" class="form-control" value="<?=$password; ?>">
		</div>
		<div class="form-group">
			<input type="submit" value="login" name="submit" class="btn btn-info">
		</div>
	</form>
	<p class="text-right"><a href="bindex.php">Boutique</a></p>
</div>

<?php
 include 'includes/bfooter.php';
 ob_end_flush();