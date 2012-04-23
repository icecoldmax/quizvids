<!DOCTYPE html>

<!-- <?php 
	// $vidcount = $_GET["vidCount"];
	// echo $vidcount;
	// $vids = [];
	// for ($i = 0; $i < $vidcount; $i++) { 
	//		$vids[] = $_GET["vid$i"];
	//	}
	// print_r($vids);
	?> -->

<html>
<head>
	<title>QuizVidz</title>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>

<link href="css/jqModal.css" rel="stylesheet" type="text/css">
<link href="css/site.css" rel="stylesheet" type="text/css">

<!-- <script type="text/javascript" src="js/swfobject.js"></script> -->
<script type="text/javascript" src="js/jquery.color.js"></script>
<script type="text/javascript" src="js/jqModal.js"></script>
<script type="text/javascript" src="js/scripts.js"></script>
<script type="text/javascript" src="js/quizdocready.js"></script>

 <script>
    //Load player api asynchronously.
    var tag = document.createElement('script');
    tag.src = "http://www.youtube.com/player_api";
    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
        
    var ytplayer;

    function onYouTubePlayerAPIReady() {
        ytplayer = new YT.Player('ytplayer', {
          
          width: '100%',
          height: '100%',
          //videoId: getParameterByName('vid0'),
          playerVars: { 'autoplay': 0, 'controls': 0, 'wmode': 'opaque',
          				//'playlist': vids.join(),
          				'origin': 'http://www.stopdontpanic.com/', 'modestbranding': 1, 'showinfo': 0 
          			},
          events: {
            'onReady': onPlayerReady,
            'onStateChange': onPlayerStateChange,
            'onError': onPlayerError
          }
        });
    }
    function onPlayerReady(evt) {
     	setInterval(updatePlayerInfo, 1000);
     	updatePlayerInfo();
     	interval = getParameterByName('interval');
     	ytplayer.loadPlaylist(vids);
	 	//initIFrameClick();
	//	evt.target.playVideo();
    }
 
 function onPlayerStateChange(evt) {
 		var newState = evt.data;
    	updateHTML("playerState", newState);
		console.log("newState: " + newState);
	if (newState == 0 || newState == 2 || newState == 3 || newState == 5) {
		clearInterval(t);
		console.log("clearInterval ran (state change)");
	} else if (newState == 1) {
		t = setInterval(stopAtTime, 1000, interval);
	}

 }

 
 </script>
</head>

<body>

<h1>QuizVidz!</h1>

<div id="layer1">
	<div id="ytplayer"></div>
</div>

<!-- <iframe id="ytAPI" type="text/html" width="1280" height="720" src="http://www.youtube.com/embed/<?php //echo $vids[0]; ?>?enablejsapi=1&origin=http://localhost/quizvids/" frameborder="0">
</iframe> -->

<div id="buttons">
	<p>
		<button id="pauseButton">Pause</button>
	<!--<button id="playButton">Play</button> -->
		<button id="playWithStopsButton">Play w/ stops</button>
		<button id="stopButton">Stop</button>
	</p>
	<p>Next question in <span id="timeTillNextQuestion">X</span> seconds!</p>

	<p><button id="showHideInfo">Show / Hide Info</button></p>

	<div id="vidInfo">
		Player State: <span id="playerState"></span><br />
		Duration: <span id="videoDuration"></span><br />
		Current Time: <span id="videoCurrentTime"></span><br />
		Total Bytes: <span id="bytesTotal"></span><br />
		Bytes Loaded: <span id="bytesLoaded"></span><br />
		Volume: <span id="volume"></span><br />
		Interval: <span id="interval"></span><br />
	</div>
</div>

<!-- <form>		
		<p><input id="vidCodeInput" type="text" size="50" placeholder="Paste a youtube link here!" /><input id="vidCodeSubmit" type="submit" value="Load" /></p>
	 </form> -->
	
<div id="quizPlaylist">
	<h3>Playlist</h3>
	<ul>
	<!-- <?php 
			//foreach ($vids as $vidcode) {
			//	echo "<li class=\"playlistEntry\">$vidcode</li>";
			//}
		?> -->
	</ul>
	
	<div id="prevNextButtons">
		<button id="prevVid">Prev Video</button>
		<button id="nextVid">Next Video</button>
	</div>
</div>
	
<div id="videosBar"></div>

<div class="jqmWindow" id="popup">
	<h1>Quiz time!</h1>
	<p id="qNumber">Question <span></span></p>

	<h2 id="quizH2"></h2>
	<p id="score">Score: <span id="totalCorrectAns">0</span> / <span id="totalQsAsked">0</span> <span id="scorePercentage">(0%)</span></p>
	<p id="lastTimeCorrect" style="display: none;">You answered this question <span id="lastTimeCorrectStatus">correctly</span> last time!</p>

	<div id="quizContent">

		<div id="countingImgs"></div>

		
		<table id="quizAnswerTable">
			<tr>
				<td><span class="quizAnswerSpan"></span></td>
				<td><span class="quizAnswerSpan"></span></td>
			</tr>
			<tr>
				<td><span class="quizAnswerSpan"></span></td>
				<td><span class="quizAnswerSpan"></span></td>
			</tr>
		</table>
		<p class="quizResult"></p>
		<p id="qzTitle"></p>
	</div>

	<!-- <form id="quizAnswerForm">
		<p><input type="text" id="quizAnswer" placeholder="Type here..."></p>
		<p class="quizResult"></p>
		<p><input type="submit" id="quizAnswerSubmit" value="Go!" /></p>
	</form> -->

</div>
</body>

</html>