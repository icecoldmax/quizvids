$(document).ready(function() {

	$('#pauseButton').click(function() {
		pauseVideo();
	});

	$('#playButton').click(function() {
		playVideo();
	});

	$('#stopButton').click(function() {
		stopVideo();
	});

	$('#playWithStopsButton').click(function() {
		playWithStops();
	});

	$('#nextVid').click(function() {
		clearInterval(t);
		ytplayer.nextVideo();
	});

	$('#prevVid').click(function() {
		clearInterval(t);
		ytplayer.previousVideo();
	});

	$('#popup').jqm({ onHide: popupClose, modal: true });

	//$('#quizAnswer').focus(function() { $(this).select(); }).mouseup(function(e) { e.preventDefault(); });

	// $('#quizAnswerForm').submit(function() {
	// 	var rand1 = parseInt($('#rand1').text());
	// 	var rand2 = parseInt($('#rand2').text());
	// 	var operator = $('#operator').text();

	// 	if (operator == "+") {
	// 		var sum = rand1 + rand2;
	// 	} else if (operator == "-") {
	// 		var sum = rand1 - rand2;
	// 	}
		
	// 	var userInput = $('#quizAnswer').attr('value');

	// 	if (parseInt(userInput) == sum) {
	// 		$('.quizResult').text("Correct!!");
	// 		$('#popup').css({"background-color": "lightgreen"});
	// 		$('#popup').animate({"background-color": "green"}, 600);
			
	// 		setTimeout(function() {
	// 			$('#popup').jqmHide();
	// 		}, 500);
			
	// 	} else {
	// 		$('#popup').css({"background-color": "pink"});
	// 		$('#popup').animate({"background-color": "rgb(220,20,60)"}, 600);
	// 		$('.quizResult').text("Try again :(");
	// 		$('#quizAnswer').focus();
	// 	//	$('#popup').css({"background": "crimson"});
	// 	}
	// 	return false;
		
	// });

	$('#quizContent table td').click(function() {
		if (clickCount == 0) {
			clickCount = 1;
		} else if (clickCount > 0) {
			clickCount++;
		}
		console.log("Click " + clickCount);
		
		var clickedAnswer = $(this).text();
	
		if (clickedAnswer == correctAnswer || parseInt(clickedAnswer) == correctAnswer) {
			if (clickCount == 1) {
				thisQuestion.lastTimeCorrect = true;
				totalCorrectAns++; // add 1 to score
				
			} else if (clickCount > 1) {
				thisQuestion.lastTimeCorrect = false;
			}

			console.log("lastTimeCorrect: " + thisQuestion.lastTimeCorrect);

			$('.quizResult').text("Correct!!");
			$('#popup').css({"background-color": "lightgreen"});
			$('#popup').animate({"background-color": "green"}, 600);
			

			setTimeout(function() {
				$('#popup').jqmHide();
			}, 500);
				
		} else {
			$('#popup').css({"background-color": "pink"});
			$('#popup').animate({"background-color": "rgb(220,20,60)"}, 600);
			$('.quizResult').text("Try again :(");
			$('#quizAnswer').focus();
		//	$('#popup').css({"background": "crimson"});
		}
			
	});

	$('#vidCodeSubmit').click(function() {
		//clearInterval(t);

		var url = $('#vidCodeInput').attr('value');

		console.log("Pasted url: " + url);

		
		
		var vLocation = url.indexOf("v=");
		var codeStart = parseInt(vLocation) + 2;
		var code = url.substring(codeStart, codeStart + 11);

		// https: www.youtube.com/v/6fRhXtwQN6c?version=3&f=related&app=youtube_gdata&rel=0
		//ytplayer.cueVideoById(code);

		url = "https://www.youtube.com/v/" + code + '?enablejsapi=1&version=3&rel=0playerapiid=ytPlayer&version=3&controls=0&fs=1&autohide=1';
		console.log("Loading this URL: " + url);
		loadVideo(url, "true");



		$.getScript("https://gdata.youtube.com/feeds/api/videos/" + code + "/related?v=2&alt=json-in-script&callback=showVideosBar&max-results=5&format=5");

		playWithStops();
		return false;

		//http://www.youtube.com/watch?v=MJ5ghlTdF9k&feature=g-all-c&context=G2788915FAAAAAAAAAAA
	});

	$('#showHideInfo').click(function() {
		if ($('#vidInfo').css('display') == 'none') {
			$('#vidInfo').show();
		} else {
			$('#vidInfo').hide();
		}
	});

	$('#timeTillNextQuestion').text(interval);

	//loadPlayer();
 	
 	layer1Width = $('#layer1').width();	
	$(window).resize(function() {
		adjustVidQuality();
	});

	getVidsFromURL();
	getOptions();	
	prepQuiz();	
	quizTaken();



});