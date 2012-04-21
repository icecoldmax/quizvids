<html>

<?php

date_default_timezone_set('Asia/Tokyo');

$db = new mysqli('localhost', 'qv', 'qv', 'qvtest');

if (mysqli_connect_errno()) {
	die(mysqli_connect_error());
}



 ?>

<head>
	<title>Do the quiz!</title>
	<link rel="stylesheet" type="text/css" href="qvdb.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>

	
	<script type="text/javascript">

	$(document).ready(function() {
		$('.wrong, .right').click(function() {
			var thisDiv = $(this).parents('div')[0];
			$(thisDiv).find('.wrong').css({'color': 'red'});
			$(thisDiv).find('.right').css({'color': 'green'});
		});
	});


	</script>

</head>
<body>

<h2>Quizzes</h2
<h3><a href="/experiments/qvdb/">Back to start</a></h3>

<?php

$quiz_id = $_GET['quiz_id'];

function getQCount($quiz_id, $db) {

	$query = "select count(*) from questions where quiz_id = $quiz_id";
	$query = $db->real_escape_string($query);
	if ($result = $db->query($query)) {
		$row = $result->fetch_row();
		
		$question_count = $row[0];
	}
	$result->close();
	return $question_count;

}

function getDetails($quiz_id, $db) {
	$query = "select title, creator, created_on from quizzes where id = $quiz_id limit 1";
	$query = $db->real_escape_string($query);
	if ($result = $db->query($query)) {
		$row = $result->fetch_object();

		$title = $row->title;
		$creator = $row->creator;
		$created_on = $row->created_on;

		echo "<h2>$title</h2>";
		echo "<p class=\"small\">Created by <span class=\"smallItalic\">$creator</span> on <span class=\"smallItalic\">$created_on</span></p>";

	}

}

function getQs($quiz_id, $db) {

		$query = "select id, question_text from questions where quiz_id = $quiz_id";
		$query = $db->real_escape_string($query);

		if ($result = $db->query($query)) {
			while ($row = $result->fetch_object()) {
				$question_id = $row->id;
				$question_text = $row->question_text;
				echo "<div>";	
				echo "<p>$question_text</p>";

				$query2 = "select is_correct, answer_text from answers where question_id = $question_id";
				$query2 = $db->real_escape_string($query2);
				echo "<p>";
				if ($result2 = $db->query($query2)) {
					while ($row2 = $result2->fetch_object()) {
						$is_correct = $row2->is_correct;
						$answer_text = $row2->answer_text;
				
						if ($is_correct == 0) {
							$output = "<span class=\"wrong\">$answer_text</span><br />";
						} else if ($is_correct == 1) {
							$output = "<span class=\"right\">$answer_text</span><br />";
						}
						echo $output;
					}
			
				$result2->close();
				}
				echo "</p>";
				echo "</div>";

			}

			$result->close();
		}

		
	
	
	

}

//$question_count = getQCount($quiz_id, $db);
//getAllQuizzes($db);
getDetails($quiz_id, $db);
getQs($quiz_id, $db); 

?>


<!-- <div class="questions" id="q1"> -->

<?php

// $query = "select question_text from questions where id = 1";
// $query = $db->real_escape_string($query);

// if ($result = $db->query($query)) {
// 	while ($row = $result->fetch_object()) {
// 		$question_text = $row->question_text;
		
// 		echo "<p>$question_text</p>";

// 	}

// 	$result->close();
// }

// $query = "select is_correct, answer_text from answers where question_id = 1";
// $query = $db->real_escape_string($query);
// echo "<p>";
// if ($result = $db->query($query)) {
// 	while ($row = $result->fetch_object()) {
// 		$is_correct = $row->is_correct;
// 		$answer_text = $row->answer_text;

		
// 		if ($is_correct == 0) {
// 			$output = "<span class=\"wrong\">$answer_text</span><br />";
// 		} else if ($is_correct == 1) {
// 			$output = "<span class=\"right\">$answer_text</span><br />";
// 		}
// 		echo $output;
// 	}
	
// 	$result->close();
// }
// echo "</p>";

?>

<!-- </div> -->




<!-- <h2>Answers</h2> -->

<?php 

// $query = "select * from answers";
// $query = $db->real_escape_string($query);

// if ($result = $db->query($query)) {
// 	while ($row = $result->fetch_object()) {
// 		$answer_id = $row->id;
// 		$question_id = $row->question_id;
// 		$is_correct = $row->is_correct;
// 		$answer_text = $row->answer_text;

// 		echo "<p>Answer id: $answer_id <br /> Question id: $question_id <br /> Is correct?: $is_correct <br /> Answer text: $answer_text</p>";

// 	}
// 	$result->close();
// }

$db->close();

?>

<!-- Going fine so far! -->
</body>
</html>