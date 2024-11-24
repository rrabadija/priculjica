<?php header('Content-Type: application/json; charset=UTF-8');
    require_once 'connect.php';
    require_once 'helpers.php';
    require_once 'anchor.php';

    $links = [];

    if ($_SERVER["REQUEST_METHOD"] == "POST") { //Generates links on search keyword
        if (isset($FETCH['query'])) {
            $query = sanitize($FETCH['query'] ?? '');
            $search = '%' . $query . '%';

            $results = $GLOBALS['queries'] -> search($search);

            if ($results) {
                $regex = '/\b' . preg_quote($query, '/') . '/i';

                foreach ($results as $result) {
                    $keyword = sanitize($result['title'] ?? '');

                    if (preg_match($regex, $keyword)) {
                        $links[] = $anchor -> generateAnchor($result['title'], $result['text'], $result['image_src'], $result['alt_text']);
                    }
                }
            }

            echo json_encode($links);
        }
    }