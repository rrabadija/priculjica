<?php header('Content-Type: text/html; charset=UTF-8');
	require_once 'connect.php';
	require_once 'queries.php';
	require_once 'helpers.php';
    require_once 'audio-player.php';
	require_once 'audio-counter.php';

    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['naslov'])) { //Get the URL from the naslov GET variable
		$URL = urldecode(htmlspecialchars($_GET['naslov'] ?? ''));
		$URL = str_replace('-', ' ', $URL); //Replace the spaces with a dash

		$rows = $GLOBALS['queries'] -> story($URL);

		if ($rows) { //Redirect to ostale-price.php if there is no content in the database for the URL keyword
			$title = htmlspecialchars($rows['title'] ?? '');
			$text = sanitizeHTML($rows['text'] ?? '');
			$audioBool = intval($rows['audio_bool'] ?? null);
			$audioSrc = sanitize($rows['audio_src'] ?? '');
			$audioDuration = intval($rows['audio_duration'] ?? null);

			if ($audio === 1) { //If there is an audio duration record, save it into a session variable for audio-counter.php
				$_SESSION['audio_counter_filename'] = $title;
				$_SESSION['audio_counter_bool'] = $audio;
				$_SESSION['audio_counter_duration'] = $audioDuration;
			}

			$GLOBALS['queries'] -> storyCount($URL);
		}
		else {
			header('Location: /priculjica/ostale-price');

			exit;
		}
	}