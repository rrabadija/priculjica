<?php header('Content-Type: text/html; charset=UTF-8');
    function limitText($text, $limit) { //Limits the text from column tekst to a set number of words
        if ($text === null) {
            return '';
        }

        $textWithoutTags = preg_replace('/<[^>]*>/', ' ', $text);
        $textWithoutTags = trim(preg_replace('/\s+/', ' ', $textWithoutTags));

        $words = explode(' ', $textWithoutTags);

        if (count($words) > $limit) {
            $words = array_slice($words, 0, $limit);
            return implode(' ', $words) . '...';
        }

        return $textWithoutTags;
    }

    function setChar($text) { //Removes the diacritics from the URLs
        $toReplace = ['č', 'ć', 'š', 'đ', 'ž', 'Č', 'Ć', 'Š', 'Đ', 'Ž'];
        $replacement = ['c', 'c', 's', 'd', 'z', 'C', 'C', 'S', 'D', 'Z'];

        $text = str_replace($toReplace, $replacement, $text);
        $text = str_replace(' ', '-', $text);

        return strtolower($text);
    }