<?php 
session_start();

date_default_timezone_get('Asia/Tokyo');
include('../localdb.inc');

$username = $_POST['username'];
$password = $_POST['password'];

$query = $db->prepare("select count(*), id from users where username = ? and password = ?");
$query->bind_param('ss', $username, $password);
$query->execute();
$query->bind_result($count, $id);
$query->fetch();
//echo $db->error;


$query->close();

if ($count == 1) {
	$_SESSION['logged_in'] = true;
	$_SESSION['username'] = $username;
	$_SESSION['id'] = $id;
	session_regenerate_id();

	echo 'Logged in successfully.<br/><a href="../">Back to main page</a>';

} else if ($count == 0) {
	echo "Username/Password Mismatch";
} else {
	echo "Database error";
}
$db->close();