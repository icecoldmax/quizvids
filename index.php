<?php 
session_start();
?>
<!DOCTYPE html>
<html>

<head>
	<title>QuizVids</title>

<link href="css/jqModal.css" rel="stylesheet" type="text/css">
<link href="css/site.css" rel="stylesheet" type="text/css">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>

<!-- <script type="text/javascript" src="js/swfobject.js"></script> -->
<!-- <script type="text/javascript" src="js/jqModal.js"></script> -->
<script type="text/javascript" src="js/scripts.js"></script>
<!-- <script type="text/javascript" src="js/docready.js"></script> -->

<script type="text/javascript">

$(document).ready(function() {

	$.post('qvdb/getquizzes.php', function(data) {
		$(data).appendTo('#userQuizzes');
	});

	$('#searchSubmit').click(function() {
		var searchString = $('#searchInput').attr('value');

		$.getScript("http://gdata.youtube.com/feeds/api/videos?v=2&alt=json-in-script&callback=searchVideos&q=" + searchString + "&max-results=5&format=5&safeSearch=strict");

		return false;
	});

	$('#quizFormSubmit').click(function() {
		var userQuizOn = false;
		var userQuizIds = [];

		$(this).before('<input type="hidden" id="vidCount" name="vidCount" value="' + $('.playlistEntry').length + '"/ >');
		
		var paramString = "vidCount=" + $('#vidCount').val() + '&';
		//var paramString = '';		
		$('.playlistEntry').each(function() {
			var inputTag = $(this).find('input');
			paramString += $(inputTag).attr('name') + '=' + $(inputTag).attr('data-vidcode') + '&';
		});

		$('.quizTypeCheckbox').each(function() {
			if ($(this).is(':checked')) {
				var trParent = $(this).parents('tr');

				if ($(trParent).attr('id') == "division2") {
					paramString += $('#division1').find('input').serialize();
					paramString += "&";
				} else if ($(trParent).attr('class') == 'userQuizRow') {
					if (!userQuizOn) userQuizOn = true;
					userQuizIds.push($(this).parents('tr').attr('id'));

				}

				if ($(trParent).attr('class') != 'userQuizRow') paramString += $(trParent).find('input').serialize() + "&";
				if (userQuizOn) paramString += "uq=on&";			
			}
		});

		if (userQuizIds.length > 0) paramString += "uqIds=" + userQuizIds.join() + "&";				
		if ($('#interval').val() > "0") {
			paramString += 'interval=' + $('#interval').val();
		} else {
			paramString += 'interval=30';
		}
		
		//console.log(paramString);
		
		window.location.assign('http://localhost/quizvids/quiz.php?' + paramString, '_blank');
		//window.location.assign('http://www.stopdontpanic.com/quizvids/quiz.php?' + paramString);
		return false;
	});

});

</script>

</head>

<body>

<div id="header">
	<h1>QuizVids!</h1>
	<?php
	if (!isset($_SESSION['logged_in'])) {
		$_SESSION['logged_in'] = false;
	}
	
	if ($_SESSION['logged_in'] == true) {
		echo '<p>Logged in as <strong>' . $_SESSION['username'] . '</strong>.<br /><a href="qvdb/logout.php">Logout</a> <a href="qvdb/account.php">My account</a></p>';
	} else if ($_SESSION['logged_in'] == false) {
		echo '<p><a href="qvdb/login.php">Login</a></p>';
	}
	?>
</div>

<div id="quizOptions">
	<h3>Quiz Options</h3>


	<form id="quizForm" action="quiz.php" method="get">
		<table>
			<tr id="addition">
				<th>Addition from</th>
				<td><input type="text" id="quizAdditionStart" name="quizAdditionStart" value="0" size="10"></td>
				<td>to</td>
				<td><input type="text" id="quizAdditionEnd" name="quizAdditionEnd" value="20" size="10"></td>
				<td><input class="quizTypeCheckbox" type="checkbox" id="quizAdditionCheckbox" name="quizAdditionCheckbox" checked></td>
			</tr>

			<tr id="subtraction">
				<th>Subtraction from</th>
				<td><input type="text" id="quizSubtractionStart" name="quizSubtractionStart" value="0" size="10"></td>
				<td>to</td>
				<td><input type="text" id="quizSubtractionEnd" name="quizSubtractionEnd" value="20" size="10"></td>
				<td><input class="quizTypeCheckbox" type="checkbox" id="quizSubtractionCheckbox" name="quizSubtractionCheckbox"></td>
			</tr>

			<tr id="multiplication">
				<th>Multiplication from</th>
				<td><input type="text" id="quizMultiplicationStart" name="quizMultiplicationStart" value="1" size="10"></td>
				<td>to</td>
				<td><input type="text" id="quizMultiplicationEnd" name="quizMultiplicationEnd" value="12" size="10"></td>
				<td><input class="quizTypeCheckbox" type="checkbox" id="quizMultiplicationCheckbox" name="quizMultiplicationCheckbox"></td>
			</tr>

			<tr id="division1">
				<th>Division of numbers from</th>
				<td><input type="text" id="quizDivisionStart" name="quizDivisionStart" value="1" size="10"></td>
				<td>to</td>
				<td><input type="text" id="quizDivisionEnd" name="quizDivisionEnd" value="100" size="10"></td>
			</tr>
			<tr id="division2">
				<th>... by numbers from</th>
				<td><input type="text" id="quizDivByStart" name="quizDivByStart" value="1" size="10"></td>
				<td>to</td>
				<td><input type="text" id="quizDivByEnd" name="quizDivByEnd" value="12" size="10"></td>
				<td><input class="quizTypeCheckbox" type="checkbox" id="quizDivisionCheckbox" name="quizDivisionCheckbox"></td>
			</tr>
			<tr id="counting">
				<th>Counting from</th>
				<td><input type="text" id="quizCountingStart" name="quizCountingStart" value="1" size="10"></td>
				<td>to</td>
				<td><input type="text" id="quizCountingEnd" name="quizCountingEnd" value="10" size="10"></td>
				<td><input class="quizTypeCheckbox" type="checkbox" id="quizCountingCheckbox" name="quizCountingCheckbox"></td>
			</tr>
		</table>
		
		<h3>User Quizzes</h3>
		<p><a href="qvdb/newquiz.php">Make a new quiz</a></p>
		<table id="userQuizzes">
			<tr>
				<th>Title</th><th>Questions</th><th>Created by</th><th>Date</th><th>&#10003;</th>
			</tr>
		</table>

	<p>Interval: <input type="text" id="interval" name="interval" value="30"> seconds</p>

	<div id="playlist">
		<h3>Playlist</h3>

		<ul></ul>
		
			<!-- <input type="submit" id="playlistSubmit" name="playlistSubmit" value="Play All" /> -->
			<input type="submit" id="quizFormSubmit" name="quizFormSubmit" value="Start!" />
		
		</form>

	</div>
	
	<div id="searchBar">
		<h3>Search</h3>
		<form>
			<input type="text" id="searchInput" placeholder="Search..." /><input id="searchSubmit" type="submit" value="Search" />
		</form>
	</div>

	<div id="searchResults"></div>

</div>



</body>

</html>