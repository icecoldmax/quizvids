<html>
<?php

date_default_timezone_set('Asia/Tokyo');

$db = new mysqli('localhost', 'qv', 'qv', 'qvtest');

if (mysqli_connect_errno()) {
	die(mysqli_connect_error());
}

?>

<head>
	<title>quiz db</title>
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

<h2>Quizzes</h2>

<?php

function getAllQuizzes($db) {
	$query = "select * from quizzes";
	$query = $db->real_escape_string($query);
	if ($result = $db->query($query)) {
		while ($row = $result->fetch_object()) {
		
			$quiz_id = $row->id;
			$title = $row->title;
			$creator = $row->creator;
			$created_on = $row->created_on;
			$question_count = $row->question_count;
				
			echo "<span class=\"big\">$title</span>";
			echo "<p class=\"small\">Questions: $question_count <br />";
			echo "Created by <span class=\"smallItalic\">$creator</span> on $created_on</p>";
			echo '<form action="doquiz.php" method="GET">';
			echo "<input type=\"hidden\" name=\"quiz_id\" value=\"$quiz_id\">";
			echo '<input type="submit" value="Start quiz!">';
			echo '</form>';
		}
	}
	$result->close();
}

// function getQuiz($quiz_id, $db) {
// 	$query = "select count(*) from questions where quiz_id = $quiz_id";
// 	$query = $db->real_escape_string($query);
// 	if ($result = $db->query($query)) {
// 		$row = $result->fetch_row();
	//	<script src="http://code.jquery.com/jquery-latest.min.js"></script>
// 		$question_count = $row[0];
// 		echo "<p>$question_count questions.</p>";
// 	}
// 	$result->close();
// }

getAllQuizzes($db);
// getQs($question_count, $db); 

?>

<h2><a href="newquiz.php">Make a quiz!</a></h2>
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