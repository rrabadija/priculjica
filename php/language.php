<?php header('Content-Type: text/html; charset=UTF-8');
    require_once 'connect.php';
    require_once 'helpers.php';
    require_once 'queries.php';

    if (!isset($_SESSION['language'])) {
        $_SESSION['language'] = 'hr';
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        header('Content-Type: application/json; charset=UTF-8');

        if (isset($FETCH['language'])) {
            $language = sanitize($GLOBALS['FETCH']['language']);

            $_SESSION['language'] = $language;

            $translations = [];

            if (isset($FETCH['key'])) {
                $keys = explode(',', ($GLOBALS['FETCH']['key']));

                foreach ($keys as $key) {
                    $translation = $GLOBALS['queries'] -> translate($key, $language);

                    $translations[$key] = sanitizeHTML($translation['translation']);
                }

                echo json_encode($translations);
            }
        }
    }

    function setLanguage($key) {   
        $translations = $GLOBALS['queries'] -> translate($key, $_SESSION['language']);

        return sanitizeHTML($translations['translation'] ?? '');
    }
