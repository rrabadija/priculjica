<?php header('Content-Type: application/json; charset=UTF-8');
    require_once 'connect.php';
    require_once 'helpers.php';
    require_once 'anchor.php';

    $links = [];

    if ($_SERVER["REQUEST_METHOD"] == "POST") { //Generates links on search keyword
        if (isset($FETCH['query'])) {
            if (!empty($FETCH['query'])) {
                $query = sanitize($FETCH['query'] ?? '');
                $search = '%' . $query . '%';

                $results = Queries::search($search);

                if ($results) {
                    $regex = '/\b' . preg_quote($query, '/') . '/i';

                    foreach ($results as $result) {
                        $keyword = sanitize($result['title'] ?? '');

                        if (preg_match($regex, $keyword)) {
                            $links[] = $anchor -> anchor(
                                $result['title'],
                                $result['text'],

                                '<a href="nova-prica/' . setChar(sanitize($result['title'])),

                                $result['image_src'],
                                $result['alt_text']
                            );
                        }
                    }
                }
            }
            else {
                $links[] = $anchor -> generateAnchor();
            }

            echo json_encode($links);
        }
    }