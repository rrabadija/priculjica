<?php header('Content-Type: text/html; charset=UTF-8');
    $link = '';
    $links = '';

    function linkTemplate($title, $text, $image, $imageAlt) { //Template for the link
        $link = '<a href="nova-prica/' . setChar($title) . '">

                            <div class="section_content_text_wrapper">

                                <h2>'. $title . '</h2>

                                <p>'. limitText($text, 20) .'</p>

                            </div>

                            <div class="section_content_image_wrapper">

                                ' . ($image ? '<img src="' . $image . '" alt="' . $imageAlt . '">' : '') . '

                            </div>

                        </a>'; //Link template

        return $link;
    }

    function createLinks($rows, $links, $title, $text, $image, $imageAlt) { //Creates the links on page load and when the search keyword is empty
        if (isset($rows)) {
            foreach ($rows as $row) {
                if ($row) {
                    $links .= linkTemplate($title, $text, $image, $imageAlt);
                }
            }
        }

        return $links;
    }