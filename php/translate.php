<?php header('Content-Type: application/json; charset=UTF-8');
    require 'connect.php';

    session_start();

    function getTranslation($pdo, $key, $default) { //Get the translation from the database
        if ($default === false) { //If the function is not called from the index.php with the default language
            $language = htmlspecialchars($_POST['language']) ?? '';
            $keys = explode(',', (htmlspecialchars($_POST['keys'] ?? ''))); //Create an array with keys by splitting the string where the commas are

            $translations = []; //Initialize an empty array for storing the translations and their keys

            foreach ($keys as $key) { //For each key inside the array, run the SQL
                $translation = STMT($pdo, $key, $language);

                $translations[$key] = $translation['prijevod'] ?? ''; //Save the translation inside the array under its key
            }

            return $translations;
        }
        else { //Function is called from index.php with the default language
            $language = 'hr';
        }

        $translation = STMT($pdo, $key, $language);

        return $translation['prijevod'] ?? '';
    }

    function STMT($pdo, $key, $language) { //Function for reusing stmt variables and the SQL query
        $stmt = $pdo->prepare("SELECT prijevod FROM prijevod WHERE kljuc = ? AND jezik = ?");

        $stmt->bindValue(1, $key, PDO::PARAM_STR);
        $stmt->bindValue(2, $language, PDO::PARAM_STR);
        $stmt->execute();

        $translation = $stmt->fetch(PDO::FETCH_ASSOC);

        return $translation;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['language'], $_POST['keys'])) {
        $translations = getTranslation($pdo, $_POST['keys'], false);

        echo json_encode($translations); //JSON encode and send to header.js
    }