<?php 
//session_start();

date_default_timezone_get('Asia/Tokyo');
include('../localdb.inc');

$newUsername = $_POST['newUsername'];
$newPass = $_POST['newPass'];
$newEmail = $_POST['newEmail'];
//$hashedPass = hash('sha256', $newPass);

echo "Username: " . $newUsername . " - Password: " . $newPass . " - Email: " . $newEmail . "<br />";

// echo "Hashed password: " . $hashedPass . "<br />";

$q = "insert into users (username, password, email) values ('$newUsername', '$newPass', '$newEmail')";
// $q = $db->real_escape_string($q);

if ($result = $db->query($q)) {

	echo $db->error;
	if ($db->affected_rows == 1) {
		echo "Successfully added " . $newUsername . " to database!";
	} else {
		echo "Failure, mother fucker!";
	}
}
$result->close();
$db->close();
?>
