<?php 
ob_start();
  require_once '../core/init.php';
  if(!login_in()){
 	login_error();
 }
 if(!has_permission('admin')){
 	login_error_permission();
 }

  include 'includes/bheader.php';
  include 'includes/bnavbar.php';
   $userdata = $db->query("SELECT * FROM users ORDER BY ID");
   // delite 
  if(isset($_GET['delite'])){
  	$deliteuser = sanitize($_GET['delite']);
  	$db->query("DELETE FROM users WHERE  ID = '$deliteuser'");
  	$_SESSION['success_admin'] = 'User Was Delite By Success';
    header('LOCATION: users.php');
  }
   if(isset($_GET['add'])){   
      $name = (isset($_POST['name'])?sanitize($_POST['name']):'');
      $email = (isset($_POST['email'])?sanitize($_POST['email']):'');
  	  $password = (isset($_POST['password'])?sanitize($_POST['password']):'');
  	  $confirm = (isset($_POST['confirm'])?sanitize($_POST['confirm']):'');
  	  $permission = (isset($_POST['permission'])?sanitize($_POST['permission']):'');
      $errors  = array();

      

      if($_POST){
        $emailquery = $db->query("SELECT * FROM users WHERE email = '$email'");
        $emailcount = mysqli_num_rows($emailquery);
        $require = array('name','email','password','confirm','permission');
        foreach ($require as $reqe) {
          if(empty($_POST[$reqe])){
            $errors[] = 'You Must Be Fill All Fields';
            break;
          }
        }
        //chek password
        if(strlen($password) < 6){
          $errors[] = 'The Paswword Must Be More Thane 6 Character';
        }
        // chek password match
        if($password != $confirm){
          $errors[] = 'The Password Not Match Of Confirm';
        }
        //chek email
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
          $errors[] = 'You Must Enter A Valide Email';
        }
        //chek if email exist
        if($emailcount != 0){
          $errors[] = 'This Email Already Exist In Database';
        }
        if(!empty($errors)){
          echo error_message($errors);
        }else{
          $hashed = password_hash($password, PASSWORD_DEFAULT);
           $db->query("INSERT INTO users (full_name, email, password, permission) 
            VALUES ('$name', '$email', '$hashed', '$permission')");
           $_SESSION['success_admin'] = 'User Has Aded By Success';
           header('LOCATION: users.php');
        }
      }
  	
  	?>
     <h2 class="text-center">Users</h2><hr>
      <form action="users.php?add=1" method="post">

      	<div class="form-group col-md-6">
      		<label for="name">Fulle Name :</label>
      		<input type="text" id="name" name="name" class="form-control" value="<?= $name;?>">
      	</div>
      	<div class="form-group col-md-6">
      		<label for="email">Email :</label>
      		<input type="email" id="email" name="email" class="form-control" value="<?= $email;?>">
      	</div>
      	<div class="form-group col-md-6">
      		<label for="password">Password :</label>
      		<input type="password" id="password" name="password" class="form-control" value="<?= $password;?>">
      	</div>
      	<div class="form-group col-md-6">
      		<label for="confirm">Confirm :</label>
      		<input type="password" id="confirm" name="confirm" class="form-control" value="<?= $confirm;?>">
      	</div>
      	<div class="form-group col-md-6">
      		<label for="permission">Permission :</label>
      		<select class="form-control" name="permission">
      			<option value=""<?=(($permission == '')?' selected':'' );?>></option>
      			<option value="author"<?=(($permission == 'author')?' selected':''); ?>>Author</option>
      		  <option value="admin,author"<?=(($permission == 'admin,author')?' selected':''); ?>>Admin</option>
      		</select>
      	</div>
      	<div class="form-group col-md-6 text-right left-button">
      	    <a href="users.php" class="btn btn-default"> Cansel</a>
      	    <input type="submit" class="btn btn-success"  value="Add User">
        </div>
      </form>
  <?php  
 }else{
   ?>
 <h2 class="text-center">Users</h2><hr>
 <a href="users.php?add=1" method="post" class="btn btn-success pull-right" id="add-product1">Add User</a>
 <table class="table table-bordered table-striped table-condensed">
 	<thead>
 		<th></th><th>Fulle Name</th><th>Email</th><th>Join Date</th><th>Last Login</th><th>Permission</th>
 	</thead>
 	<tbody>
 	 <?php while($users = mysqli_fetch_assoc($userdata)): ?>
 	    <tr>
 	    	<td>
 	    		<?php if($users['ID'] != $logindata['ID']):?>
 	    		<a href="users.php?delite=<?= $users['ID']?>" class="btn btn-default btn-sm confirm"><span class="glyphicon glyphicon-remove"></span></a>
 	    	  
            <?php endif;?>
 	    	</td>
 	    	<td><?= $users['full_name']?></td>
 			<td><?= $users['email']?></td>
 			<td><?= pretty($users['join_date']);?></td>
 			<td><?= ((($users['latest_join']) == '0000-00-00 00:00:00')?' never':pretty($users['latest_join']));?></td>
 			<td><?= $users['permission']?></td>
 	    </tr>
 	 <?php endwhile;?>
 	</tbody>

 </table>
       
 

<?php }

   include 'includes/bfooter.php';
ob_end_flush();
