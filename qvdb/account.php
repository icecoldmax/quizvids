<?php 
session_start();

date_default_timezone_get('Asia/Tokyo');
include('../localdb.inc');

if ($_SESSION['logged_in'] == true && isset($_SESSION['username'])) {

	$query = $db->prepare("select id, username, email, quizzes_taken, quizzes_created from users where username = ?");
	$query->bind_param('s', $_SESSION['username']);
	$query->execute();
	$query->bind_result($id, $username, $email, $quizzes_taken, $quizzes_created);

	$query->fetch();

	$output = <<<EOF
		<table id="userTable">
			<tr>
				<th>Username:</th><td>$username</td>
			</tr>
			<tr>
				<th>Email:</th><td>$email</td>
			</tr>
			<tr>	
				<th>Quizzes taken:</th><td>$quizzes_taken</td>
			</tr>
			<tr>	
				<th>Quizzes created:</th><td>$quizzes_created</td>
			</tr>
		</table>
EOF;
	$query->close();

	$query = $db->prepare("select title, created_on, question_count from quizzes where creator = ?");
	$query->bind_param('s', $_SESSION['username']);
	$query->execute();
	$query->store_result();
	$query->bind_result($title, $created_on, $question_count);

	if ($query->num_rows > 0) {

		$output2 = <<<EOF
			<h3>Your Quizzes</h3>
			<table id="userQuizzes">
				<tr>
					<th>Title</th>
					<th>Date</th>
					<th>Questions</th>
				</tr>
EOF;

		while ($query->fetch()) {
			$output2 .= <<<EOF
				<tr>
					<td>$title</td>
					<td>$created_on</td>
					<td>$question_count</td>
				</tr>
EOF;
		}

		$output2 .= '</table>';
	}

} else {
	$output = "You are not logged in.";
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>My Account</title>

	<style type="text/css">
	
	html, body {
		text-align: center;
	}

	.userTableDiv {
		text-align: center;
		border: solid 1px black;
		width: 500px;
		margin: 5px auto;
	}

	table {
		width: 75%;
		margin: 0px auto;
		border-collapse: collapse;
	}

	#userTable th {
		text-align: left;
	}
	#userTable td {
		text-align: right;
	}

	#userQuizzes tr:first-child {
		border-bottom: solid 1px gray;
	}
	</style>

</head>

<body>
<h1>My Account</h1>

<p><a href="../">Back</a></p>
<div class="userTableDiv">
	<?php echo $output;?>
</div>

<div class="userTableDiv">
	<?php echo $output2;?>
</div>


</body>
<?php $db->close(); ?>
</html>