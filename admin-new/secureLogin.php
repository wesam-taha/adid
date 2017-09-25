<?
 include_once('connect.php');

    // [adid_status_UserLevel] => -1
    // [adid_status_UserProfile_UserName] => admin
    // [adid_status_UserID] => -1


    //  [adid_status_UserProfile_UserName] => secure
    // [adid_UserLevel] => 1
    // [adid_UserID] => 2
	$errorCase = 0;

	if(isset($_POST['btnsubmit'])){

		$username =  @$_POST['username'];
		$password =  @$_POST['password'];
		$query = $db->query("select * from `secure_management` where `username`='$username' and `password`='$password'");
		$res = $db->fetch_assoc($query);
		$rows = $db->returned_rows;

		if($rows > 0){
			$_SESSION["adid_status"] = 'login';
			$_SESSION["adid_status_UserID"] = '-2';
			$_SESSION["adid_status_UserProfile_UserName"] = $res['username'];
			$_SESSION["adid_UserLevel"] = $res['type'];
			$_SESSION["adid_UserID"] = $res['id'];
			$_SESSION["EW_SESSION_USER_NAME"] = $res['username'];
			

			?>
			<script type="text/javascript"> location = 'userslist.php'; </script>
			<?
			$errorCase = 0;
		}else{
			$errorCase = 1;
		}


	}

?>


<form  class="ewForm ewLoginForm" action="userslist.php" method="post">
<div class="login-box ewLoginBox">
<div class="login-box-body">
<p class="login-box-msg">Secure Login</p>
	<div class="form-group">
		<div><input type="text" name="username" id="username" class="form-control ewControl" value="" placeholder="User Name"></div>
	</div>
	<div class="form-group">
		<div><input type="password" name="password" id="password" class="form-control ewControl" placeholder="Password"></div>
	</div>
	<button class="btn btn-primary ewButton" name="btnsubmit" type="submit">Login</button>
<? if($errorCase == 1){ ?><p style="margin-top: 20px; color: red"> خطأ في اسم المستخدم أو كلمة المرور </p><? } ?>
</div>
</div>
</form>