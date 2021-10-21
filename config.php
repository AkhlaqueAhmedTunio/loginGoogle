<?php

session_start();
##### DB Configuration #####
$servername = "localhost";
$username = "root";
$password = "";
$db = "db_oauth";

##### Google App Configuration #####
$googleappid = "202129665112-r57j7p22sdddv7le3ovakqqg2rg9tth9.apps.googleusercontent.com"; 
$googleappsecret = "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"; 
// $redirectURL = "http://localhost:81/LoginwithGoogle/authenticate.php"; 
// $redirectURL = "http://localhost/phpCore/loginGoogle/view.php"; 
$redirectURL = "http://localhost/phpCore/loginGoogle/authenticate.php"; 
// $redirectURL = "http://localhost/phpCore/loginGoogle/group.php"; 


##### Create connection #####
$conn = new mysqli($servername, $username, $password, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
##### Required Library #####
include_once 'src/Google/Google_Client.php';
include_once 'src/Google/contrib/Google_Oauth2Service.php';

$googleClient = new Google_Client();
$googleClient->setApplicationName('Login to CodeCastra.com');
$googleClient->setClientId($googleappid);
$googleClient->setClientSecret($googleappsecret);
$googleClient->setRedirectUri($redirectURL);

// custom code
// $googleClient->addScope('email');
// $googleClient->addScope('profile');



$google_oauthV2 = new Google_Oauth2Service($googleClient);

?>

<!-- custom -->