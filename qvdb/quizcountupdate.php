<?php
session_start();

date_default_timezone_set('Asia/Tokyo');

//include('../../../qvdbconn.inc');

if ($_SESSION['logged_in'] == true) {
	include('../localdb.inc');

	if (isset($_POST['quiz_taken'])) {
		$query = $db->prepare('update users set quizzes_taken = quizzes_taken+1 where id = ?');
	} else if (isset($_POST['quiz_created'])) {
		$query = $db->prepare('update users set quizzes_created = quizzes_created+1 where id = ?');
	} else {
		echo "Not logged in.";
		exit;
	}
	$query->bind_param('i', $_SESSION['id']);
	$query->execute();

	echo $db->error;

	if ($db->affected_rows == 1) {
		echo "Added 1 to quizzes_taken or quizzes_created";
	} else {
		echo "Failed to add to quizzes_taken/created";
	}

	$query->close();
	$db->close();
} else {
	echo "Not logged in.";
}
?>