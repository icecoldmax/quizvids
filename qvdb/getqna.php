<?php
date_default_timezone_set('Asia/Tokyo');

//include('../../../qvdbconn.inc');

include('../localdb.inc');

$quiz_id = $_POST['quiz_id'];
//$quiz_id = '1';

function getQs($quiz_id, $db) {

	// $query = "select id, question_text from questions where quiz_id = $quiz_id";
	$query = "select qz.id, qz.title, qz.creator, qu.id as question_id, qu.question_text from quizzes qz, questions qu where qz.id = $quiz_id and qu.quiz_id = $quiz_id";
	$query = $db->real_escape_string($query);

	if ($result = $db->query($query)) {
		while ($row = $result->fetch_object()) {
			$quiz_title = $row->title;
			$creator = $row->creator;
			$question_id = $row->question_id;
			$question_text = $row->question_text;
						
			//echo "<p class=\"qText\">$question_text</p>";

			$query2 = "select is_correct, answer_text from answers where question_id = $question_id";
			$query2 = $db->real_escape_string($query2);
			$ansN = array();
			if ($result2 = $db->query($query2)) {
				while ($row2 = $result2->fetch_object()) {
					$is_correct = $row2->is_correct;
					$answer_text = $row2->answer_text;
					
					if ($is_correct == 0) {
						$ansN[] = $answer_text;
						//$str['ansN'][] = $answer_text;
						//$output = "<p class=\"ansWrong\">$answer_text</p>";
					} else if ($is_correct == 1) {
						$ansY = $answer_text;
						//$str['ansY'][] = $answer_text;
						//$output = "<p class=\"ansRight\">$answer_text</p>";
					}
					//echo $output;

				}
		
			$result2->close();
			
			}
		$str[] = array('qText' => $question_text, 'ansY' => $ansY, 'ansN' => $ansN, 'qzTitle' => $quiz_title, 'creator' => $creator, 'qzid' => $quiz_id );	
		}

		$result->close();
		echo json_encode($str);
	}
		

}
getQs($quiz_id, $db);
$db->close();



?>