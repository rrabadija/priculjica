<?php header('Content-Type: text/html; charset=UTF-8');
	require_once 'connect.php';
	require_once 'queries.php';
	require_once 'helpers.php';
    require_once 'audio-player.php';
	require_once 'audio-counter.php';

	$title = '';
	$text = '';
	$audioBool = '';
	$audioSrc = '';
	$audioDuration = '';

    if ($_SERVER["REQUEST_METHOD"] == "GET") { //Get the URL from the naslov GET variable
		if (isset($_GET['title']) && isset($rows) || $_SESSION['user_role'] === 'admin') {
			$URL = urldecode(htmlspecialchars($_GET['title'] ?? ''));
			$URL = str_replace('-', ' ', $URL); //Replace the spaces with a dash

			$rows = $GLOBALS['queries'] -> story($URL);

			$title = sanitize($rows['title'] ?? '');
			$text = sanitizeHTML($rows['text'] ?? '');
			$audioBool = intval($rows['audio_bool'] ?? null);
			$audioSrc = sanitize($rows['audio_src'] ?? '');
			$audioDuration = intval($rows['audio_duration'] ?? null);

			if ($audioBool === 1) { //If there is an audio duration record, save it into a session variable for audio-counter.php
				$_SESSION['audio_counter_filename'] = $title;
				$_SESSION['audio_counter_bool'] = $audioBool;
				$_SESSION['audio_counter_duration'] = $audioDuration;
			}

			$GLOBALS['queries'] -> storyCount($URL);
		}
		else {
			header('Location: ostale-price');

			exit;
		}
	}

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		header('Content-Type: application/json; charset=UTF-8');

		$_SESSION['title'] = sanitize($FETCH['title'] ?? '');
		$_SESSION['text'] = sanitize($FETCH['text'] ?? '');
	}