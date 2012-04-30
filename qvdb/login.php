<?php 
session_start();
date_default_timezone_get('Asia/Tokyo');
include('../localdb.inc');

?>
<!DOCTYPE html>
<html>
<head>
	<title>QuizVids Login</title>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script src="../js/jquery.sha256.js" type="text/javascript"></script>

<script type="text/javascript">

$(document).ready(function() {
	
	$('#newUserSubmit').click(function() {
		var newUsername = $('#newusername').val();
		var newPass = $.sha256($('#newpass').val());
		var newEmail = $('#newemail').val();


		$.post('newuser.php',
				{ newUsername: newUsername, newPass: newPass, newEmail: newEmail},
				function(data) {
						$('#container').html(data).fadeIn();

				}
		);
	});

	$('#loginButton').click(function() {

		var username = $('#username').val();
		var password = $.sha256($('#password').val());

		$.post('authenticate.php',
				{ username: username, password: password },
				function(data) {
					$('#container').html(data).fadeIn();
					 
				}
		);
	});

});

</script>

<style type="text/css">

html, body {
	text-align: center;
}

div {
	width: 960px;
	border: solid 1px black;
	margin: 10px auto;
	padding: 10px;
}

#container {
	display: none;
}

</style>

</head>

<body>

<div id="loginForm">
	<h2>Log in</h2>
	<input type="text" name="username" id="username" placeholder="Username" /><br />
	<input type="text" name="password" id="password" placeholder="Password" /><br />
	<input type="button" name="loginButton" id="loginButton" value="Log In" />
</div>

<?php


// $query = "select * from users";
// $query = $db->real_escape_string($query);

// if ($result = $db->query($query)) {
// 	while ($row = $result->fetch_object()) {
// 		$username = $row->username;
// 		$pass = $row->password;

// 		echo "<p>$username's password is $pass<br />";

// 	}
// 	$result->close();
// }


// $db->close();


?>

<div id="newUserForm">
	<h2>New User</h2>

<input type="text" name="newusername" id="newusername" placeholder="Username" /><br />
<input type="text" name="newpass" id="newpass" placeholder="Password" /><br />
<input type="email" name="newemail" id="newemail" placeholder="Email" /><br />
<input type="button" name="newUserSubmit" id="newUserSubmit" value="Sign up!">

</div>

<div id="container"></div>
</body>
</html>