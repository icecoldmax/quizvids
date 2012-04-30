<?php
date_default_timezone_set('Asia/Tokyo');

//include('../../../qvdbconn.inc');
include('../localdb.inc');

function getAllQuizzes($db) {
	$query = "select * from quizzes";
	$query = $db->real_escape_string($query);
	if ($result = $db->query($query)) {
		$output = "";
		while ($row = $result->fetch_object()) {
		
			$quiz_id = $row->id;
			$title = $row->title;
			$creator = $row->creator;
			$created_on = $row->created_on;
			$question_count = $row->question_count;
						
			$output .= <<<EOF
			<tr id="$quiz_id" class="userQuizRow">
				<td>$title</td>
				<td>$question_count</td>
				<td>$creator</td>
				<td>$created_on</td>
				<td><input type="checkbox" class="quizTypeCheckbox" name="quizUserQuizCheckbox" /></td>
			</tr>
EOF;

			// echo "<span class=\"big\">$title</span>";
			// echo "<p class=\"small\">Questions: $question_count <br />";
			// echo "Created by <span class=\"smallItalic\">$creator</span> on $created_on</p>";
			// echo '<form action="doquiz.php" method="GET">';
			// echo "<input type=\"hidden\" name=\"quiz_id\" value=\"$quiz_id\">";
			// echo '<input type="submit" value="Start quiz!">';
			// echo '</form>';

		}
	}
	$result->close();
	return $output;
}

echo getAllQuizzes($db);
$db->close();

?>