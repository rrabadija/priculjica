<?php header('Content-Type: text/html; charset=UTF-8');
    require_once 'language.php';

    function generateFooter() { //Footer template
        $footer = '<div class="footer_links">

                        <a href="">

                            <i class="fa fa-instagram"></i>
                    
                        </a>

                        <a href="">
                    
                            <i class="fa fa-facebook"></i>
                    
                        </a>

                    </div>

                    <p>

                        Copyright &#169; ' . date("Y") . ' Pričuljica. <span>' . setLanguage("footer.p") . '</span>

                    </p>';

        return $footer;
    }