// Extend js objects!

Object.size = function(obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};

/*
.___       .__  __   
|   | ____ |__|/  |_ 
|   |/    \|  \   __\
|   |   |  \  ||  |  
|___|___|  /__||__|  
         \/          
*/

// VARIABLES //

var t; // setInterval variable
var interval; // interval in seconds
var quizOptions = {}; // what kinds of quiz questions will appear

var questionTypes = []; // types of questions in this quiz
var quizAnswerSpans; // all the spans in the quiz answer table
//var answerPositions = [0,1,2,3]; // answers can go in quizAnswerSpans[0], [1], [2], [3]

var uqna = {}; // all the user quiz info (questions and answers)
var uqYetToAskQs = {};
var thisQuestion;

var vids = []; // video playlist
var vidCount; // number of videos in playlist

var layer1Width; // width of div#layer1 (surrounding the iframe)

var loc = window.location;

var quizImgs = ['balloon', 'froggy', 'redcar', 'sailboat','strawberry'];
var quizImgsLength = quizImgs.length;
var correctAnswer; // correct answer to the quiz question

var clickCount = 0; // number of times clicked on the answers
var totalQsAsked = 0; // how many times has a question popped up?
var totalCorrectAns = 0; // how many did the user get right first time?
var qNumber = 1;

var imgFiles = ['images/balloon.png', 'images/froggy.png', 'images/redcar.png', 'images/sailboat.png', 'images/strawberry.png'];

preload(imgFiles); // preloads imgFiles for faster loading

function toInt(n){ return Math.round(Number(n)); }; // returns a rounded number

function preload(sources) // preloads imgFiles for faster loading
{
  var images = [];
  for (i = 0, length = sources.length; i < length; ++i) {
    images[i] = new Image();
    images[i].src = sources[i];
  }
}

// Gets Url parameters
function getParameterByName(name)
{
  name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
  var regexS = "[\\?&]" + name + "=([^&#]*)";
  var regex = new RegExp(regexS);
  var results = regex.exec(window.location.search);
  if(results == null)
    return "";
  else
    return decodeURIComponent(results[1].replace(/\+/g, " "));
}

// adds vid codes in playlist to playlist div on quiz page
function getVidsFromURL() {
	vidCount = parseInt(getParameterByName('vidCount'));
	
	for (var i = 0; i < vidCount; i++) {
		vids[i] = getParameterByName('vid' + i);
		$('#quizPlaylist ul').append("<li>" + vids[i] + "</li>");
	}
}

function is_int(value){ 
  if((parseFloat(value) == parseInt(value)) && !isNaN(value)){
      return true;
  } else { 
      return false;
  } 
}

// OLD Flash loadPlayer function

//function loadPlayer() {
//	var params = { allowScriptAccess: "always",
//					modestbranding: "1",
//					allowFullScreen: "true",
//					wmode: "opaque"
//				};
//	var atts = { id: "ytPlayer" };

	//swfobject.embedSWF("http://www.youtube.com/apiplayer?version=3&enablejsapi=1&playerapiid=ytPlayer&controls=0&fs=1&autohide=1&border=1&color1=0xb1b1b1&color2=0xcfcfcf", "ytAPI", "1280", "720", "8", null, null, params, atts);

	// swfobject.embedSWF("http://www.youtube.com/v/Wu0z7hDCbYY?enablejsapi=1&playerapiid=ytPlayer&version=3&controls=0&fs=1&autohide=1", "ytAPI", "1280", "720" ,"8", null, null, params, atts);

// get related vids

	//$.getScript("https://gdata.youtube.com/feeds/api/videos/Wu0z7hDCbYY/related?v=2&alt=json-in-script&callback=showVideosBar&max-results=5&format=5");

//}

// loads and plays a video input into a textinput (deprecated)

// function loadVideo(playerUrl, autoplay) {
// 	console.log("Loading from vids bar: " + playerUrl);
// 	clearInterval(t);

// 	ytplayer.cueVideoByUrl(playerUrl);
// 	playWithStops();

// 	var positionOfV = playerUrl.indexOf('\/v\/');
// 	//console.log(positionOfV);
// 	var codeStart = positionOfV + 3;
// 	var codeEnd = codeStart + 11;
// 	var code = playerUrl.substring(codeStart, codeEnd);
// 	//console.log(code);


// // get related vids
// 	$.getScript("https://gdata.youtube.com/feeds/api/videos/" + code + "/related?v=2&alt=json-in-script&callback=showVideosBar&max-results=5&format=5");

//   	// swfobject.embedSWF(
//    //  		playerUrl + '&rel=1&border=0&fs=1&autoplay=' + 
//    //  		(autoplay?1:0), 'ytAPI', '290', '250', '9.0.0', false, 
//    //  		false, {allowfullscreen: 'true'});
// }

// Update a particular HTML element with a new value
function updateHTML(elmId, value) {
 	document.getElementById(elmId).innerHTML = value;
}

// This function is called when an error is thrown by the player
function onPlayerError(errorCode) {
	alert("An error occured of type:" + errorCode);
}

// Display information about the current state of the player
function updatePlayerInfo() {
// Also check that at least one function exists since when IE unloads the page, it will destroy the SWF before clearing the interval.
  	if(ytplayer && ytplayer.getDuration) {
	   	updateHTML("videoDuration", ytplayer.getDuration());
    	updateHTML("videoCurrentTime", ytplayer.getCurrentTime());
    	updateHTML("bytesTotal", ytplayer.getVideoBytesTotal());
    	//updateHTML("startBytes", ytplayer.getVideoStartBytes());
    	updateHTML("bytesLoaded", ytplayer.getVideoBytesLoaded());
    	updateHTML("volume", ytplayer.getVolume());
  	}
}

function currentTimeInt() {
	return toInt(ytplayer.getCurrentTime());
}

/*
__________.__                              _________                __                .__          
\______   \  | _____  ___.__. ___________  \_   ___ \  ____   _____/  |________  ____ |  |   ______
 |     ___/  | \__  \<   |  |/ __ \_  __ \ /    \  \/ /  _ \ /    \   __\_  __ \/  _ \|  |  /  ___/
 |    |   |  |__/ __ \\___  \  ___/|  | \/ \     \___(  <_> )   |  \  |  |  | \(  <_> )  |__\___ \ 
 |____|   |____(____  / ____|\___  >__|     \______  /\____/|___|  /__|  |__|   \____/|____/____  >
                    \/\/         \/                \/            \/                             \/ 
*/

function setVideoVolume() {
	var volume = parseInt(document.getElementById("volumeSetting").value);
	if(isNaN(volume) || volume < 0 || volume > 100) {
   		alert("Please enter a valid volume between 0 and 100.");
	} else if (ytplayer) {
   		ytplayer.setVolume(volume);
	}
}

function playVideo() {
	if (ytplayer) {
 		ytplayer.playVideo();
	}
}

function playWithStops() {
	if (ytplayer) {
		console.log("PlayWithStops function ran");			
		updateHTML("interval", interval);
		ytplayer.setPlaybackQuality("default");
		ytplayer.playVideo();
	}
}

function pauseVideo() {
	if (ytplayer) {
   		ytplayer.pauseVideo();
   		console.log("PauseVideo function ran");
	}
}

function stopVideo() {
	if (ytplayer) {
  		ytplayer.stopVideo();
   		console.log("Stopvideo function ran");
	}
}

function muteVideo() {
	if(ytplayer) {
   		ytplayer.mute();
	}
}

function unMuteVideo() {
	if(ytplayer) {
   		ytplayer.unMute();
	}
}

/* 
________        .__           _____                    __  .__                      
\_____  \  __ __|__|_______ _/ ____\_ __  ____   _____/  |_|__| ____   ____   ______
 /  / \  \|  |  \  \___   / \   __\  |  \/    \_/ ___\   __\  |/  _ \ /    \ /  ___/
/   \_/.  \  |  /  |/    /   |  | |  |  /   |  \  \___|  | |  (  <_> )   |  \\___ \ 
\_____\ \_/____/|__/_____ \  |__| |____/|___|  /\___  >__| |__|\____/|___|  /____  >
       \__>              \/                  \/     \/                    \/     \/ 
*/


// Pauses the video when current time % interval = 0
function stopAtTime(interval) {
	if (ytplayer) {
		var currentTime = currentTimeInt();
		var mod = currentTime % interval;
		console.log("Current Time: " + currentTime + " - Interval: " + interval + " (Modulo = " + mod + ")");
		//console.log("Next question in: " + (interval - mod));

		var timeTillNextQ = (interval - mod);
		
		$('#timeTillNextQuestion').text(timeTillNextQ);
		if (currentTime > 0 && currentTime % interval == 0) {
			console.log("StopAtTime ran");
			pauseVideo();
			showQuiz();
		}
	}
}

/*
|-----------------------------------|
|				showQuiz			|
|									|
|	All the quiz making functions!	| 
|-----------------------------------|
*/

function prepQuiz() {
	
	quizAnswerSpans = $('.quizAnswerSpan'); // all the spans in the quiz answer table

	// throw all the quiz options from the quizOptions object into the questionTypes array
	if (quizOptions.add == "on") questionTypes.push("add");
	if (quizOptions.sub == "on") questionTypes.push("sub");
	if (quizOptions.multi == "on") questionTypes.push("multi");
	if (quizOptions.div == "on") questionTypes.push("div");
	if (quizOptions.counting == "on") questionTypes.push("counting");
	
	// if user quizzes are on, get all the questions and answers
	if (quizOptions.uq == "on") { 
		questionTypes.push("uq");


		for ( var i = 0; i < quizOptions.uqIds.length; i++ ) {
			var quiz_id = quizOptions.uqIds[i];

			uqYetToAskQs[quiz_id] = []; // setup object to make sure questions all come out
			
			console.log(quiz_id);
			uqna[quiz_id] = [];
			
			var ajaxReq = $.ajax({

				type: "POST",
				url: "qvdb/getqna.php",
				dataType: "json",
				data: { quiz_id: quiz_id },
				async: false

			});

			ajaxReq.done(function(data) {
				process(data);
			});
		}
	}

	function process(data) {
		console.log(data);
		
		for (var j = 0; j < data.length; j++) {
			var qText = data[j].qText;
			var ansY = data[j].ansY;
			var ansN = data[j].ansN;
			var qzTitle = data[j].qzTitle;
			var creator = data[j].creator;
			var qzid = data[j].qzid;
			uqna[qzid][j] = {'q': qText, 'ansY': ansY, 'ansN' : ansN, 'qzTitle': qzTitle, 'creator': creator, 'qzid': qzid, 'lastTimeCorrect': true };
			uqYetToAskQs[qzid].push(j);
		}

		// randomise the order of the questions for the first time (it is done again in showQuiz() )
		for (quiz in uqYetToAskQs) {
			uqYetToAskQs[quiz].sort(function() {return 0.5 - Math.random()} ); 
		}
		
	}

}

function showQuiz() {
	var answerPositions = [0,1,2,3];
	var correctAnswerPosition = Math.floor((Math.random() * 4)); // where the correct answer will go
	//console.log("correct answer pos is: " + correctAnswerPosition);
	var randomAnswers = []; // where random (incorrect) answers will go (so we can filter out duplicates)

	// since there are three incorrect answers, we want them to come up in a random order each time, so...
	var uqRandomAnswers = [0, 1, 2];
	uqRandomAnswers.sort(function() {return 0.5 - Math.random()});
	//console.log(uqRandomAnswers);

// how many types of questions do we have? let's pick a random one...
	var qTLength = questionTypes.length;
	var nextProblem = questionTypes[(Math.floor(Math.random() * qTLength))];
	
// make sure the result text is blank each time we throw up a question
	$('.quizResult').text("");

// if next prob is NOT a user quiz, this will be all numbers between the start and end i.e. if addition is from 0 to 5 then this will be [0,1,2,3,4,5]
	if (nextProblem != "uq") {
		var allNumbers = []; 
	}

	// set the clickedCount back to 0 for the next question
	clickCount = 0;

/* 
------------------------------------------------------
	Let's make the quizzes and fill in the answers!
------------------------------------------------------
*/

// if nextProblem is Addition
	if (nextProblem == "add") {
		
		var addStart = parseInt(quizOptions.addStart);
		var addEnd = parseInt(quizOptions.addEnd);

	// add all possible numbers to allNumbers...
		for (var i = addStart; i <= addEnd; i++) {
			allNumbers.push(i);
		}

	// pick two random numbers within range
		var rand1 = Math.floor((Math.random()*(addEnd-addStart+1) + addStart));
		var rand2 = Math.floor((Math.random()*(addEnd-addStart+1) + addStart));

	// ask the question...
		$('#quizH2').html("What's <span id=\"rand1\">" + rand1 + "</span> <span id=\"operator\">+</span> <span id=\"rand2\">" + rand2 + "</span>?");

	// here's the answer
		correctAnswer = (rand1 + rand2);

	// hide the 'counting images' div
		$('#countingImgs').hide();


// else if nextProblem is Subtraction
	} else if (nextProblem == "sub") {

		var subStart = parseInt(quizOptions.subStart);
		var subEnd = parseInt(quizOptions.subEnd);
	
	// add all possible numbers to allNumbers...
		for (var i = subStart; i <= subEnd; i++) {
			allNumbers.push(i);
		}

	// pick two random numbers within range
		var rand1 = Math.floor((Math.random()*(subEnd-subStart+1) + subStart));
		var rand2 = Math.floor((Math.random()*(subEnd-subStart+1) + subStart));
	
	// make sure the number isn't < 0 after subtraction by flipping them around if necessary.
	// Also, ask the question and get the answer
		if (rand1 > rand2) {
			correctAnswer = (rand1 - rand2);
			$('#quizH2').html("What's <span id=\"rand1\">" + rand1 + "</span> <span id=\"operator\">-</span> <span id=\"rand2\">" + rand2 + "</span>?");	
		} else {
			correctAnswer = (rand2 - rand1);
			$('#quizH2').html("What's <span id=\"rand1\">" + rand2 + "</span> <span id=\"operator\">-</span> <span id=\"rand2\">" + rand1 + "</span>?");
		}
		
	// hide the 'counting images' div
		$('#countingImgs').hide();


// else if nextProblem is Multiplication		
	} else if (nextProblem == "multi") {

		var multiStart = parseInt(quizOptions.multiStart);
		var multiEnd = parseInt(quizOptions.multiEnd);

	// add all possible numbers to allNumbers...
		for (var i = multiStart; i <= multiEnd; i++) {
			allNumbers.push(i);
		}

	// pick two random numbers within range
		var rand1 = Math.floor((Math.random()*(multiEnd-multiStart) + multiStart));
		var rand2 = Math.floor((Math.random()*(multiEnd-multiStart) + multiStart));
	
	// ask the question
		$('#quizH2').html("What's <span id=\"rand1\">" + rand1 + "</span> <span id=\"operator\">x</span> <span id=\"rand2\">" + rand2 + "</span>?");
		
	// here's the answer	
		correctAnswer = (rand1 * rand2);

	// hide the 'counting images' div
		$('#countingImgs').hide();


// else if nextProblem is Division
	} else if (nextProblem == "div") {

		var divStart = parseInt(quizOptions.divStart);
		var divEnd = parseInt(quizOptions.divEnd);
		var divByStart = parseInt(quizOptions.divByStart);
		var divByEnd = parseInt(quizOptions.divByEnd);

	// add all possible numbers to allNumbers...
		for (var i = divStart; i <= divEnd; i++) {
			allNumbers.push(i);
		}

		var allDivByNumbers = [];
		for (var j = divByStart; j <= divByEnd; j++) {
			allDivByNumbers.push(j);
		}

	// pick two random numbers from correct places
		var rand1;
		var rand2;
		
		function divideOK() {
			rand1 = Math.floor((Math.random()*(divEnd-divStart) + divStart));
			rand2 = Math.floor((Math.random()*(divByEnd-divByStart) + divByStart));

			if (rand2 > rand1) {
				return false;
			}

			//console.log(rand1 + " / " + rand2);
			var divideCheck = (rand1 / rand2);

			if (is_int(divideCheck)) {
				return divideCheck;
			} else {
				return false;
			}
		}

		correctAnswer = divideOK();
		
		while (!correctAnswer) {
			correctAnswer = divideOK();
		}
		
	// ask the question
		$('#quizH2').html("What's <span id=\"rand1\">" + rand1 + "</span> <span id=\"operator\">/</span> <span id=\"rand2\">" + rand2 + "</span>?");

	// hide the 'counting images' div
		$('#countingImgs').hide();



// else if nextProblem is Counting	
	} else if (nextProblem == "counting") {
		
		var countingStart = quizOptions.countingStart;
		var countingEnd = quizOptions.countingEnd;

	// add all possible numbers to allNumbers...
		for (var i = countingStart; i <= countingEnd; i++) {
			allNumbers.push(i);
		}
	// choose an image at random from the array of images
		var rand = Math.floor((Math.random() * (quizImgsLength)));
		var quizNextImg = quizImgs[rand];
	
	// img tag for the images
		var html = '<img src="images/' + quizNextImg + '.png" />\n';

	// randomly choose how many times the images needs to be displayed	
		var imgCount = Math.floor((Math.random() * (countingEnd-countingStart) + countingStart));

	// throw it in that many times
		for (var i = 1; i <= imgCount ; i++) {
			$('#countingImgs').append(html);
		}

	// ask the question
		$('#quizH2').html("How many?");

	// make sure the div is visible				
		$('#countingImgs').show();

	// set correctAnswer to imgCount (correctAnswer is used in a second)
		correctAnswer = imgCount;


// else if next problem is a uq problem
	} else if (nextProblem == "uq") {

	// filter down to the question requested

		var quizCount = quizOptions.uqIds.length; // how many quizzes?
		
		var whichQuizRand = Math.floor((Math.random() * (quizCount))); // choose one at random
		var thisQuiz = quizOptions.uqIds[whichQuizRand];
		
		var questionCount = uqna[thisQuiz].length; // how many questions in this quiz?
		
	/* if all the questions have been asked already, uqYetToAskQs[thisQuiz] will be empty.
		So, we have to repopulate it and re-randomise it

		Also, we are going to add all the questions the user didn't click correctly on the first go in AGAIN so they appear more often
	*/
		if (uqYetToAskQs[thisQuiz].length == 0) {
			console.log("Repopulating uqYetToAskQs for quiz id " + thisQuiz + "...");
			for (var i = 0; i < questionCount; i++) {
				uqYetToAskQs[thisQuiz].push(i);
				
				if (uqna[thisQuiz][i].lastTimeCorrect == false) {
					uqYetToAskQs[thisQuiz].push(i);
					console.log("Adding question " + i + " again (lastTimeCorrect = false)");
				}

			}

			uqYetToAskQs[thisQuiz].sort(function() {return 0.5 - Math.random()} ); 

		// enable "You answered this question ..." line on quiz popup
			$('#lastTimeCorrect').show();
		}

		var unaskedQs = uqYetToAskQs[thisQuiz];

		console.log("Quiz ID: " + thisQuiz + ". Unasked Qs: " + unaskedQs);

		var whichQ = unaskedQs[0]; // gives the randomised index to choose the question
		thisQuestion = uqna[thisQuiz][whichQ]; // gives the question!
		unaskedQs.shift(); // chops off that one, and when there are none left it repopulates them!

	// then get the info we want
		var qzTitle = thisQuestion['qzTitle'];
		var creator = thisQuestion['creator'];

		var questionText = thisQuestion['q'];
		correctAnswer = thisQuestion['ansY'];
		var incorrectAnswers = thisQuestion['ansN'];

	// set "You answered this question ..." to 'correctly' or 'incorrectly'	
		if (thisQuestion.lastTimeCorrect == true) {
			$('#lastTimeCorrectStatus').html('correctly').css({'color': 'lightgreen'});
		} else {
			$('#lastTimeCorrectStatus').html('incorrectly').css({'color': 'red'});
		}


		//console.log("incorrect answers: " + incorrectAnswers);

		// ask the question
		$('#quizH2').html(questionText);

		// put in the qz title and creator
		var titleHtml = "<p>Quiz: <span style=\"font-size: 1.2em; font-weight: bold;\">" + qzTitle + "</span> by <span style=\"font-style: italic;\">" + creator + "</span></p>";
		$('#qzTitle').html(titleHtml);

		// hide the 'counting images' div
		$('#countingImgs').hide();
		
// end ifs
	}

// put correct answer in previously assigned position	
	$(quizAnswerSpans[correctAnswerPosition]).text(correctAnswer);

// remove the correct answer from allNumbers so it doesn't get used again
	if (nextProblem != "uq") {
		var posOfAnswerInAllNumbers = allNumbers.indexOf(correctAnswer);
		if (posOfAnswerInAllNumbers != -1) {
			allNumbers.splice(posOfAnswerInAllNumbers, 1);
		}
	}

// remove position of correct answer from array of possible answer positions
	var posOfCorrectAnswer = answerPositions.indexOf(correctAnswerPosition);
	if (posOfCorrectAnswer != -1) {
		answerPositions.splice(posOfCorrectAnswer, 1);
	}

// for each of the remaining answer positions (i.e. with nothing in them yet)...
	//console.log('remaining answer pozzes: ' + answerPositions);
	$(answerPositions).each(function() {
		
	// if the question is addition, multiplication or subtraction...
		if (nextProblem == "add" || nextProblem == "multi" || nextProblem == "sub") {
	
		// pick two new random numbers
			var randAnswer1 = allNumbers[Math.floor(Math.random() * ((allNumbers.length)-1))];
			var randAnswer2 = allNumbers[Math.floor(Math.random() * ((allNumbers.length)-1))];
		
		// add, multiply or subtract them (making sure the number is > 0 after subtraction)	to get a randomAnswer
			if (nextProblem == "add") randomAnswer = (randAnswer1 + randAnswer2);
			else if (nextProblem == "multi") randomAnswer = (randAnswer1 * randAnswer2);
			else if (nextProblem == "sub") { 

				if (randAnswer1 > randAnswer2) {
					randomAnswer = (randAnswer1 - randAnswer2);
				} else {
					randomAnswer = (randAnswer2 - randAnswer1);
				}
			}

		// if that randomAnswer already appears in an answer position, add 1 to it.
			while (randomAnswers.indexOf(randomAnswer) != -1 || randomAnswer == correctAnswer) {
				randomAnswer = (randomAnswer+1);
			}

		// throw that in the randomAnswers array so it doesn't get used again and...
			randomAnswers.push(randomAnswer);

		// put it in the answerSpan
			$(quizAnswerSpans[this]).text(randomAnswer);
	
		} else if (nextProblem == "counting") {
	// if it's a counting question...
		
		// do almost the same thing but...
			var randomAnswer = allNumbers[Math.floor(Math.random() * ((allNumbers.length)-1))];
			$(quizAnswerSpans[this]).text(randomAnswer);

		// since it's so simple (no adding/subtracting etc.), just remove that number from allNumbers and we're all good
			var posOfRandomAnswerInAllNumbers = allNumbers.indexOf(randomAnswer);
			if (posOfRandomAnswerInAllNumbers != -1) {
				allNumbers.splice(posOfRandomAnswerInAllNumbers, 1);
			}
		
	// else if it's a uq
		} else if (nextProblem == "uq") {
			//console.log("uqRandomAnswers at the start: " + uqRandomAnswers);
			
		// get the first of the 3 randomised numbers			
			var randomAnswerIdx = uqRandomAnswers[0];
			//console.log("randomAnswerIdx: " + randomAnswerIdx);
		
		// use that as the index to get one of the incorrect answers	
			var randomAnswer = incorrectAnswers[randomAnswerIdx];
			//console.log("randomAnswer: " + randomAnswer);

		// remove that number from the randomised array 
			uqRandomAnswers.shift();
			//console.log("Splicing.. New uqRandomAnswers: " + uqRandomAnswers);
		
		// put that answer into the current answerSpan						
			$(quizAnswerSpans[this]).text(randomAnswer);
		}

// end each
	});

	// make sure the background is black (i.e. not green or pink)
	$('#popup').css({"background": "black"});

	// fix score
	$('#qNumber span').html(qNumber);
	$('#totalQsAsked').html(totalQsAsked);
	$('#totalCorrectAns').html(totalCorrectAns);
	var scorePercentage = ((totalCorrectAns / totalQsAsked) * 100);
	if (isNaN(scorePercentage)) {
		scorePercentage = 0;
	}
	$('#scorePercentage').html("(" + scorePercentage + "%)");

	// show that motherfuckin' quiz!
	$('#popup').jqmShow();

}

var popupClose = function(hash) {
	hash.w.fadeOut('200', function() {
		hash.o.remove();
		//$('#quizAnswer').attr("value", "");
		$('#popup').css({"background": "black"});
		$('#countingImgs').html("");
		$(quizAnswerSpans).text('');

		// add one to the number of questions shown
		totalQsAsked++;
		qNumber++;

		playWithStops();
	});
};

function showVideosBar(data) {
	var feed = data.feed;
	var entries = feed.entry || [];
	var html = ['<ul class="videos">'];
  		
	for (var i = 0; i < entries.length; i++) {
		var entry = entries[i];
		var title = entry.title.$t.substr(0, 20);
		var thumbnailUrl = entries[i].media$group.media$thumbnail[0].url;
		var playerUrl = entries[i].media$group.media$content[0].url + "&rel=0";
	
		html.push('<li onclick="loadVideo(\'', playerUrl, '\', true)">', '<span class="titlec">', title, '...</span><br /><img src="',thumbnailUrl, '" width="130" height="97"/>', '</span></li>');
  	}
  		
  	html.push('</ul><br style="clear: left;"/>');
  	document.getElementById('videosBar').innerHTML = html.join('');

}

/*
  _________                           .__                         .___ __________.__                .__  .__          __   
 /   _____/ ____ _____ _______   ____ |  |__   _____    ____    __| _/ \______   \  | _____  ___.__.|  | |__| _______/  |_ 
 \_____  \_/ __ \\__  \\_  __ \_/ ___\|  |  \  \__  \  /    \  / __ |   |     ___/  | \__  \<   |  ||  | |  |/  ___/\   __\
 /        \  ___/ / __ \|  | \/\  \___|   Y  \  / __ \|   |  \/ /_/ |   |    |   |  |__/ __ \\___  ||  |_|  |\___ \  |  |  
/_______  /\___  >____  /__|    \___  >___|  / (____  /___|  /\____ |   |____|   |____(____  / ____||____/__/____  > |__|  
        \/     \/     \/            \/     \/       \/     \/      \/                      \/\/                  \/       
*/

// Callback for search on index page

function searchVideos(data) {
	var feed = data.feed;
	var entries = feed.entry || false;

	var html;

	if (entries) {

		html = ['<ul class="videos">'];
	  		
		for (var i = 0; i < entries.length; i++) {
			var entry = entries[i];
			var title = entry.title.$t.substr(0, 50);
			var thumbnailUrl = entries[i].media$group.media$thumbnail[0].url;

			var videoID = entries[i].media$group.yt$videoid.$t;

			var playerUrl = entries[i].media$group.media$content[0].url + "&rel=0";
			//console.log(entries[i].media$group.media$content[0].duration);

			// html.push('<li onclick="loadVideo(\'', playerUrl, '\', true)">', '<span class="titlec">', title, '...</span><br /><img src="',thumbnailUrl, '" width="130" height="97"/>', '</span></li>');
			
			html.push('<li onclick="addToPlaylist(\'' + videoID + '\',\'' + title + '\')">', '<span class="titlec">', title, '...</span><br /><img src="', thumbnailUrl, '" width="130" height="97"/>', '</span></li>');
		}

		html.push('</ul><br style="clear: left;"/>');
		html = html.join('');

	} else { 
		html = '<p>No results!</p>';
	}
  	  	
  	document.getElementById('searchResults').innerHTML = html;
}

function addToPlaylist(videoID, title) {
	var playlistLength = $('#playlist ul li').length;
	$('#playlist ul').append('<li class="playlistEntry"><input type="text" data-vidcode="' + videoID + '" name="vid' + (parseInt(playlistLength)) + '" value="' + title + '" /></li>');	
}
		
function playAllInPlaylist() {
	var playlist = [];
	var playlistEntries = $('.playlistEntry');

	for (var i = 0; i < playlistEntries.length; i++) {
		playlist.push($(playlistEntries[i]).text());
	}

	ytplayer.loadPlaylist(playlist);
}

function getOptions() {
	if (getParameterByName('quizAdditionCheckbox') == "on") {
		quizOptions.add = "on";
		quizOptions.addStart = parseInt(getParameterByName('quizAdditionStart'));
		quizOptions.addEnd = parseInt(getParameterByName('quizAdditionEnd'));
	}

	if (getParameterByName('quizSubtractionCheckbox') == "on") {
		quizOptions.sub = "on";
		quizOptions.subStart = parseInt(getParameterByName('quizSubtractionStart'));
		quizOptions.subEnd = parseInt(getParameterByName('quizSubtractionEnd'));
	}

	if (getParameterByName('quizCountingCheckbox') == "on") {
		quizOptions.counting = "on";
		quizOptions.countingStart = parseInt(getParameterByName('quizCountingStart'));
		quizOptions.countingEnd = parseInt(getParameterByName('quizCountingEnd'));
	}

	if (getParameterByName('quizMultiplicationCheckbox') == "on") {
		quizOptions.multi = "on";
		quizOptions.multiStart = parseInt(getParameterByName('quizMultiplicationStart'));
		quizOptions.multiEnd = parseInt(getParameterByName('quizMultiplicationEnd'));
	}

	if (getParameterByName('quizDivisionCheckbox') == "on") {
		quizOptions.div = "on";
		quizOptions.divStart = parseInt(getParameterByName('quizDivisionStart'));
		quizOptions.divEnd = parseInt(getParameterByName('quizDivisionEnd'));
		quizOptions.divByStart = parseInt(getParameterByName('quizDivByStart'));
		quizOptions.divByEnd = parseInt(getParameterByName('quizDivByEnd'));
	}

	if (getParameterByName('uq') == "on") {
		quizOptions.uq = "on";
		quizOptions.uqIds = getParameterByName('uqIds').split(",");
	}
}

function showOpts() {
	console.log(quizOptions);
}

function adjustVidQuality() {
	if (layer1Width < 480) {
		ytplayer.setPlaybackQuality('small');
	} else if (layer1Width < 640) {
		ytplayer.setPlaybackQuality('medium')
	} else if (layer1Width < 853) {
		ytplayer.setPlaybackQuality('large')
	} else if (layer1Width < 1280) {
		ytplayer.setPlaybackQuality('hd720')
	} else if (layer1Width < 1920) {
		ytplayer.setPlaybackQuality('hd1080')
	} else if (layer1Width >= 1920) {
		ytplayer.setPlaybackQuality('highres')
	} 
}
