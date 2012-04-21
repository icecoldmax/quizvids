<html>
<?php

date_default_timezone_set('Asia/Tokyo');

$db = new mysqli('localhost', 'qv', 'qv', 'qvtest');

if (mysqli_connect_errno()) {
	die(mysqli_connect_error());
}

?>

<head>
	<!-- <meta http-equiv="Content-type" content="text/html; charset=utf-8"> -->
	<title>quiz db</title>
	<link rel="stylesheet" type="text/css" href="qvdb.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>

	<script type="text/javascript">

	$(document).ready(function() {
		var html = $('.newQuizQA')[0];

		$('#addQ').click(function() {
			var questionCount = $('.newQuizQA').length;
			var newQhtml = $(html).clone()[0];
			$(newQhtml).find('.qNumber').text(questionCount + 1);
			$(newQhtml).appendTo('#newQuizForm');

		});

		$('#quizSubmit').click(function() {
			var newQuizTitle = $('#newQuizTitle').val();
			var newQuizUsername = $('#newQuizUsername').val();
			var totalQuestions = $('.newQuizQA').length;

			var allQAndA = {};

			$('.newQuizQA').each(function() {
				var qNumber = $(this).find('.qNumber').text();
				var question = $(this).find('.newQuizQ').val();
				var correctAns = $(this).find('.newQuizCorrAns').val();
				var incorrectAns = $(this).find('.newQuizIncorrAns');
				incorrectAns = $(incorrectAns).map(function() { return $(this).val(); }).get();

				allQAndA[qNumber] = {question: question, correctAns: correctAns, incorrectAns: incorrectAns};
				
			});
			console.log(allQAndA);
			var json_allQAndA = JSON.stringify(allQAndA);
			var submitAjax = $.ajax({
				type: "POST",
				url: "submitquiz.php",
				data: { title: newQuizTitle, username: newQuizUsername, totalQuestions: totalQuestions, allQAndA: json_allQAndA },
				contentType: "application/x-www-form-urlencoded;charset=UTF-8"
			});

			submitAjax.done(function(data) {
				$('#container').html(data);
			});


			// $.post ('submitquiz.php',
			// 		{ title: newQuizTitle, username: newQuizUsername, totalQuestions: totalQuestions, allQAndA: json_allQAndA },
			// 		function(data) {
			// 			$('#container').html(data);
			// 		}
			// );
			

		});

	});

	</script>

</head>
<body>

<h2>Make a quiz!</h2>
<h3><a href="../">Back to front page</a></h3>

<form id="newQuizForm">
	<p><input type="text" id="newQuizTitle" name="newQuizTitle" placeholder="Title" size=50 /><br />
	<input type="text" id="newQuizUsername" name="newQuizUsername" placeholder="Username"/></p>
	
	<div class="newQuizQA">
		<p>Question <span class="qNumber">1</span>:<br />
		<textarea name="newQuizQ1" id="Q1" class="newQuizQ" placeholder="Type your question here..." cols=30 rows=2 /></textarea></p>

		<p>Correct answer:<br />
			<input type="text" class="newQuizCorrAns" placeholder="Correct answer..." size=40 /></p>

		<p>Incorrect answers:<br />
			<input type="text" class="newQuizIncorrAns" size=40 /><br />
			<input type="text" class="newQuizIncorrAns" size=40 /><br />
			<input type="text" class="newQuizIncorrAns" size=40 /></p>
	</div>
	

</form>

<button id="addQ">Add a question</button><br />
<button id="quizSubmit">Submit Quiz</button>
<?php $db->close(); ?>

<div id="container"></div>

</body>
</html>