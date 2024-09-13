const audio = document.querySelector('audio');
const playPauseButton = document.querySelector('.section_audio button');
const input = document.querySelector('.section_audio input');
const currentAudioTime = document.querySelector('.section_audio_timing:first-of-type')
const audioTime = document.querySelector('.section_audio_timing:last-of-type');

function audioPlayer() { //Audio player functionality
	function calculateTime(duration) { //Converts audio duration into 0:00 format
		const min = Math.floor(duration / 60);
		const sec = Math.floor(duration % 60);
		const seconds = sec < 10 ? `0${sec}` : `${sec}`;
		return `${min}:${seconds}`;
	}

	function displayTime() { //Displays the audio duration
		audioTime.textContent = calculateTime(audio.duration);
	}

	function inputMax() { //Max value of the range slider of the audio player
		input.max = Math.floor(audio.duration);
	}

	if (audio.readyState > 0) { //Loads the data
		displayTime();
		inputMax();
	}
	else {
		audio.addEventListener('loadedmetadata', () => {
			displayTime();
			inputMax();
		});
	}

	input.addEventListener('input', () => { //Changes the time value on range slider change
		currentAudioTime.textContent = calculateTime(input.value);
	});

	let playState = 'play';

	playPauseButton.addEventListener('click', () => { //Play/pause button functionality
		if (playState === 'play') {
			audio.play();
			playState = 'pause';
		}
		else {
			audio.pause();
			playState = 'play';
		}

		playPauseButton.classList.toggle('active');
	});

	let userInteraction = false; //Makes sliding and clicking the range slider possible when the audio is playing

	input.addEventListener('input', () => {
		userInteraction = true;
		currentAudioTime.textContent = calculateTime(input.value);
	});

	input.addEventListener('change', () => {
		userInteraction = false;
		audio.currentTime = input.value;
	});

	function audioPause() {
		audio.pause();
	}

	function audioPlay() {
		audio.play();
	}

	audio.addEventListener('timeupdate', () => { //Allows user interaction on the slider while the audio is playing
		if (!userInteraction) {
			input.value = Math.floor(audio.currentTime);
			currentAudioTime.textContent = calculateTime(input.value);
			handleSlider();
		}

		if (playState === 'pause') { //When audio plays, pauses audio when the slider is interacted with
			input.addEventListener('mousedown', audioPause);
			input.addEventListener('mouseup', audioPlay);
		}
		else {
			input.removeEventListener('mousedown', audioPause);
			input.removeEventListener('mouseup', audioPlay);
		}

		AJAXAudioCount();
	});

	function AJAXAudioCount() { //AJAX for sending the audio time infomation to the server
		var audioTime = audio.currentTime;

		var xhrAudioCount = new XMLHttpRequest(); //AJAX for audio.php
        xhrAudioCount.open('POST', '/priculjica/php/audio.php', true);
        xhrAudioCount.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

		xhrAudioCount.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                console.log(this.responseText);
            }
        };

		var data = 'audioTime=' + encodeURIComponent(audioTime) + '&audioLength=' + encodeURIComponent(audio.duration); //Encode and send data

		xhrAudioCount.send(data);
	}

	window.onload = () => {AJAXAudioSet()};

	function AJAXAudioSet() {
		var startFlag = false;

		var xhrAudioSet = new XMLHttpRequest(); //AJAX for audio.php
        xhrAudioSet.open('POST', '/priculjica/php/audio.php', true);
        xhrAudioSet.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

		xhrAudioSet.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                console.log(this.responseText);
            }
        };

		var data = 'startFlag=' + encodeURIComponent(startFlag);

		xhrAudioSet.send(data);
	}

	audio.addEventListener('ended', (event) => { //Event listener listening for when the audio ends, resets the player
		if (event) {
			playPauseButton.classList.remove('active');
			input.removeEventListener('mousedown', audioPause);
			input.removeEventListener('mouseup', audioPlay);
			playState = 'play';
		}
	});

	function handleSlider() { //Makes the left side of the slider a different color than the right side
		function setBackgroundSize(input) { //Custom CSS variable
			input.style.setProperty("--background-size", `${getBackgroundSize(input)}%`);
		}
	
		setBackgroundSize(input);
	
		input.addEventListener("input", () => setBackgroundSize(input));
	
		function getBackgroundSize(input) {
			const min = +input.min || 0;
			const max = +input.max || 100;
			const value = +input.value;
			let size = (value - min) / (max - min);

			if (input.value <= 5) { //Makes it so that the right side color doesnt overflow the thumb to the left side
				size = size * 150;
			}
			else if (input.value <= 15) { //Yeah...
				size = size * 120;
			}
			else {
				size = size * 99;
			}
	
			return size;
		}
	}

	handleSlider();
}

document.addEventListener('DOMContentLoaded', function() {

	if (audio) {
		audioPlayer();
	}
	
});