<?php header('Content-Type: text/html; charset=UTF-8');
    require 'connect.php';

    session_start();

    function sanitize($input) {
        return htmlspecialchars(strip_tags(trim($input)));
    }

    $ini = parse_ini_file(__DIR__ . "/../settings/audio-settings.ini", true); //Get the contents of the .ini file

    $skippedAmountThreshold = $ini['audio']['skippedAmountThreshold'] ?? 0; //How much is the user allowed to skip in total
    $skipThreshold = $ini['audio']['skipThreshold'] ?? 0; //How much is the user allowed to skip at once
    $listenPercentage = $ini['audio']['listenPercentage'] ?? 0; //What percentage of audio being listened to is counted as a listen

    function setVariables() { //Set the variables to 0
        if (!isset($_SESSION['timeFlag'])) {
            echo "Spremno!\n";
            $_SESSION['timeFlag'] = false;
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
        if (isset($_POST['startFlag'])) { //Set and reset variables on page load
            resetVariables();
            setVariables();
        }

        if (isset($_POST['audioTime'], $_POST['audioLength'])) {
            $audioTime = intval(sanitize($_POST['audioTime'])); //Current time of the audio
            $audioLength = intval(sanitize(floor($_POST['audioLength']))); //Length of the audio

            if ($audioTime === 0 && $_SESSION['timeFlag'] === true) { //If it was counted as a skip and reversed back to 0, reset the counting process
                resetVariables();
                setVariables();
            }

            $audioCondition = $audioTime - $_SESSION['timeCheck']; //Previous time and current time comparison for skipping detection, counts reverse skipping as listen

            if ($audioCondition < 0 && $audioTime >= 0 && !$_SESSION['timeFlag']) { //Save the time of the reverse skip
                $previousCondition = $_SESSION['timeCheck']; //Compare the last time of the reverse skip and the new time, don't count as skipped time if it happens before the last time

                if ($_SESSION['previousTime'] < $previousCondition) {
                    $_SESSION['previousTime'] = $_SESSION['timeCheck'];
                    echo "Preskočeno unatrag od vremena " . $_SESSION['previousTime'] . "!\n";
                }
            }

            if ($audioCondition > 1 && !$_SESSION['timeFlag']) { //Skipped amount condition
                if ($audioTime > $_SESSION['previousTime']) {
                    $_SESSION['skippedAmount'] += $audioCondition;
                }

                if ($_SESSION['skippedAmount'] > $skippedAmountThreshold) { //Permit n amount of skipped time, if more, don't count as listen
                    echo "Previše vremena preskočeno!\n";
                    $_SESSION['timeFlag'] = true;

                    exit;
                }
                else {
                    echo "Preskočeno " . $_SESSION['skippedAmount'] . "s vremena!\n";
                }
            }

            if (($audioCondition > $skipThreshold && !$_SESSION['timeFlag']) || ($audioLength !== $_SESSION['audioDuration'])) { //If the skip is greater than n, don't count as listen, if the audio duration from the database doesn't match the audio duration sent with POST, don't count as listen
                echo "Previše vremena preskočeno odjednom!\n";
                $_SESSION['timeCheck'] = $audioTime;
                $_SESSION['timeFlag'] = true;

                exit;
            }

            $_SESSION['timeCheck'] = $audioTime; //Update previous time for comparison

            if ($audioTime / $audioLength >= $listenPercentage && !$_SESSION['timeFlag']) { //If time exceeds n percentage, count as listen
                echo "Audio zapis poslušan!\n";
                $_SESSION['timeFlag'] = true;

                $stmt = $pdo -> prepare("UPDATE audio SET poslusano_puta = poslusano_puta + 1 WHERE naslov LIKE ?");

                $stmt->bindValue(1, $_SESSION['naslovAudio'], PDO::PARAM_STR);

                $stmt->execute();

                exit;
            }
        }
    }