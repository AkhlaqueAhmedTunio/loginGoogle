

<?php 
require_once 'config.php';




if(isset($_GET['code'])){
	$googleClient->authenticate($_GET['code']);
	$_SESSION['token'] = $googleClient->getAccessToken();
	header('Location: ' . filter_var($redirectURL, FILTER_SANITIZE_URL));
}
############ Set Google access token ############
if (isset($_SESSION['token'])) {
	$googleClient->setAccessToken($_SESSION['token']);
}


if ($googleClient->getAccessToken()) {
	############ Fetch data from graph api  ############
	try {
		$gpUserProfile = $google_oauthV2->userinfo->get();
		// echo '<pre>';
		// print_r($gpUserProfile);
		// exit();

	}
	catch (\Exception $e) {
		echo 'Graph returned an error: ' . $e->getMessage();
		session_destroy();
		header("Location: ./");
		exit;
	}


	############ Store data in database  ############
	$oauthpro = "google";
	$oauthid = $gpUserProfile['id'] ?? '';
	$f_name = $gpUserProfile['given_name'] ?? '';
	$l_name = $gpUserProfile['family_name'];
	$gender = $gpUserProfile['gender'] ??  '';
	$email_id = $gpUserProfile['email'] ?? '';
	$locale = $gpUserProfile['locale'] ?? '';
	$cover = '';
	$picture = $gpUserProfile['picture'] ?? '';
	
	$url = $gpUserProfile['link'] ?? '';

	$sql = "SELECT * FROM usersdata WHERE oauthid='".$gpUserProfile['id']."'";
	$result = $conn->query($sql);
	if ($result->num_rows == 1) {
		$res = $conn->query($sql);
		$userData = $res->fetch_assoc();
		$_SESSION['userData'] = $userData;
		header("Location: view.php");	
	} else {

		if(isset($_POST['submited'])){
		if($_POST['usergroup_id'] == 1 || $_POST['usergroup_id'] == 2){

			$usergroup_id  = $_POST['usergroup_id'];


		$conn->query("INSERT INTO usersdata (usergroup_id, oauth_pro, oauthid, f_name, l_name, email_id, gender, locale, cover, picture, url) VALUES ('".$usergroup_id."','".$oauthpro."', '".$oauthid."', '".$f_name."', '".$l_name."', '".$email_id."', '".$gender."', '".$locale."', '".$cover."', '".$picture."', '".$url."')");  
		$res = $conn->query($sql);
		$userData = $res->fetch_assoc();
		$_SESSION['userData'] = $userData;
		header("Location: view.php");

	}else{
	
		$_SESSION['error'] = 'Kindly put 1 for admin and 2 for user';
		?>
		<form action="" method="post">
		<input type="text" name="usergroup_id" id="usergroup_id" >
		<input type="submit" value="submit" name="submited">
		<div style="color:red;">  <?php   echo $_SESSION['error'];  unset($_SESSION['error']);  ?> </div>
		</form>
		
	<?php
	}
	}else{
		?>
			<form action="" method="post">
			<input type="text" name="usergroup_id" id="usergroup_id" >
			<input type="submit" value="submit" name="submited">
			</form>
			
		<?php
		
			
		}

	}
	


}

		

// end



	








?>

