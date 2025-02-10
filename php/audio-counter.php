<?php header('Content-Type: text/html; charset=UTF-8');
    require_once 'connect.php';
    require_once 'helpers.php';

    $ini = parse_ini_file(__DIR__ . "/settings/audio-settings.ini", true); //Get the contents of the .ini file

    $skippedAmountThreshold = $ini['audio']['skippedAmountThreshold'] ?? 0; //How much is the user allowed to skip in total
    $skipThreshold = $ini['audio']['skipThreshold'] ?? 0; //How much is the user allowed to skip at once
    $listenPercentage = $ini['audio']['listenPercentage'] ?? 0; //What percentage of audio being listened to is counted as a listen

    $response = '';

    function setVariables() { //Set the variables to 0
        global $response;

        if (!isset($_SESSION['timeFlag'])) {
            $_SESSION['timeFlag'] = false;

            $response = 'Spremno!';
        }

        if (!isset($_SESSION['timeCheck'])) {
            $_SESSION['timeCheck'] = 0;
        }

        if (!isset($_SESSION['skippedAmount'])) {
            $_SESSION['skippedAmount'] = 0;
        }
        
        if (!isset($_SESSION['previousTime'])) {
            $_SESSION['previousTime'] = 0;
        }
    }

    function resetVariables() { //Unset the variables
        unset($_SESSION['timeFlag']);
        unset($_SESSION['timeCheck']);
        unset($_SESSION['skippedAmount']);
        unset($_SESSION['previousTime']);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        header('Content-Type: application/json; charset=UTF-8');

        if (isset($FETCH['startFlag'], $_SESSION['audio_counter_bool']) && $_SESSION['audio_counter_bool'] === 1) { //Set and reset variables on page load
            resetVariables();
            setVariables();
        }

        if (isset($FETCH['currentTime'], $FETCH['audioDuration'])) {
            $currentTime = intval(sanitize($FETCH['currentTime'] ?? 0)); //Current time of the audio
            $audioDuration = intval(sanitize(floor($FETCH['audioDuration'] ?? 0))); //Duration of the audio

            if ($currentTime === 0 && $_SESSION['timeFlag'] === true) { //If it was counted as a skip and reversed back to 0, reset the counting process
                resetVariables();
                setVariables();
            }

            $audioCondition = $currentTime - $_SESSION['timeCheck']; //Previous time and current time comparison for skipping detection, counts reverse skipping as listen

            if ($audioCondition < 0 && $currentTime >= 0 && !$_SESSION['timeFlag']) { //Save the time of the reverse skip
                $previousCondition = $_SESSION['timeCheck']; //Compare the last time of the reverse skip and the new time, don't count as skipped time if it happens before the last time

                if ($_SESSION['previousTime'] < $previousCondition) {
                    $_SESSION['previousTime'] = $_SESSION['timeCheck'];

                    $response = "Preskočeno unatrag od vremena " . $_SESSION['previousTime'] . "!\n";
                }
            }

            if ($audioCondition > 1 && !$_SESSION['timeFlag']) { //Skipped amount condition
                if ($currentTime > $_SESSION['previousTime']) {
                    $_SESSION['skippedAmount'] += $audioCondition;
                }

                if ($_SESSION['skippedAmount'] > $skippedAmountThreshold) { //Permit n amount of skipped time, if more, don't count as listen
                    $_SESSION['timeFlag'] = true;

                    $response = "Previše vremena preskočeno!\n";
                }
                else {
                    $response = "Preskočeno " . $_SESSION['skippedAmount'] . "s vremena!\n";
                }
            }

            if (($audioCondition > $skipThreshold && !$_SESSION['timeFlag']) || ($audioDuration !== $_SESSION['audio_counter_duration'])) { //If the skip is greater than n, don't count as listen, if the audio duration from the database doesn't match the audio duration sent with POST, don't count as listen
                $_SESSION['timeCheck'] = $currentTime;
                $_SESSION['timeFlag'] = true;

                $response = "Previše vremena preskočeno odjednom!\n";
            }

            $_SESSION['timeCheck'] = $currentTime; //Update previous time for comparison

            if ($currentTime / $audioDuration >= $listenPercentage && !$_SESSION['timeFlag']) { //If time exceeds n percentage, count as listen
                $_SESSION['timeFlag'] = true;
                
                Queries::audioCount($_SESSION['audio_counter_filename']);
            
                $response = "Audio zapis poslušan!\n";
            }
        }

        echo json_encode($response);
    }