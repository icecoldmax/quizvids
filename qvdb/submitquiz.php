<?php

date_default_timezone_set('Asia/Tokyo');

$db = new mysqli('localhost', 'qv', 'qv', 'qvtest');

if (mysqli_connect_errno()) {
	die(mysqli_connect_error());
}

$title = $_POST['title'];
$username = $_POST['username'];
$totalQuestions = $_POST['totalQuestions'];
$allQAndA = json_decode($_POST['allQAndA']);

$date = date("Y-m-d");

$query = $db->prepare('insert into quizzes (title, creator, created_on, question_count) values (?, ?, ?, ?)');
$query->bind_param('sssi', $title, $username, $date, $totalQuestions);
$query->execute();
$insert_id = $db->insert_id;
echo $db->error;


echo "<br />";


foreach ($allQAndA as $q) {
	$question = $q->question;
	$correctAns = $q->correctAns;
	$incorrectAns = $q->incorrectAns;
	
	$query = $db->prepare('insert into questions(question_text, quiz_id) values (?, ?)');
	$query->bind_param('si', $question, $insert_id);

	$query->execute();
	echo $db->error;
	echo "questions insert_id: ". $db->insert_id;
	$questions_insert_id = $db->insert_id;

	if ($db->affected_rows == 1) {
		echo "Insert into questions successful";
	} else {
		echo "Insert into questions FAILED.";
	}

	$query = $db->prepare('insert into answers (question_id, is_correct, answer_text) values (?, ?, ?)');
	$query->bind_param('sis', $questions_insert_id, $is_correct, $insertAns);

	for ($i = 0; $i < 4; $i++) {
		if ($i == 3) {
			$insertAns = $correctAns;
			$is_correct = 1;
		} else {
			$insertAns = $incorrectAns[$i];
			$is_correct = 0;
		}
		
		$query->execute();
		echo $db->error;
		printf("%d Row inserted.\n", $query->affected_rows);
		
	}
	$query->close();
}

$db->close();

