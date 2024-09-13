<?php header('Content-Type: text/html; charset=UTF-8');
    function generateFooter($default) {
        $footer = '<div class="footer_links">

                <a href="">

                    <i class="fa fa-instagram"></i>
                    
                </a>

                <a href="">
                    
                    <i class="fa fa-facebook"></i>
                    
                </a>

            </div>

            <p>

                ' . ($default ? 'Copyright &#169; ' . date("Y") . ' Pričuljica. Sva prava pridržana' : 'Copyright &#169; ' . date("Y") . ' Pričuljica. All rights reserved') . '

            </p>';

        return $footer;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['language'])) { //If the language is changed
        $language = htmlspecialchars($_POST['language'] ?? '');

        if ($language === 'en') {
            echo generateFooter(false);
        }
        else {
            echo generateFooter(true);
        }
    }