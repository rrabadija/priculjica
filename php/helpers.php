<?php header('Content-Type: text/html; charset=UTF-8');
    $FETCH = json_decode(file_get_contents('php://input'), true);

    function sanitize($input) {
        return htmlspecialchars(strip_tags(trim($input)));
    }

    function sanitizeHTML($input) {
        return strip_tags(trim($input), STRIP_TAGS_CONDITION);
    }

    function limitText($text, $limit) { //Limits the text from column tekst to a set number of words
        if ($text === null) { //If the text is not set, return an empty string
            return '';
        }

        $text = strip_tags($text); //Strip tags from text
        $text = trim(preg_replace('/\s+/', ' ', $text)); //Replace multiple spaces with a single space

        $words = explode(' ', $text); //Split the string into words using the previously set single space

        if (count($words) > $limit) { //If the number of words exceeds the set limit of words, remove the excess and return the new string with an added ellipsis
            $words = array_slice($words, 0, $limit);
            
            return implode(' ', $words) . '...';
        }

        return $text;
    }

    function setChar($text) { //Removes the diacritics from the URLs
        $toReplace = ['č', 'ć', 'š', 'đ', 'ž', 'Č', 'Ć', 'Š', 'Đ', 'Ž'];
        $replacement = ['c', 'c', 's', 'd', 'z', 'C', 'C', 'S', 'D', 'Z'];

        $text = str_replace($toReplace, $replacement, $text);
        $text = str_replace(' ', '-', $text);

        return strtolower($text);
    }