<?php header('Content-Type: text/html; charset=UTF-8');
    require_once 'connect.php';
    require_once 'helpers.php';
    require_once 'queries.php';
    
    class Anchor {
        private $rows;
        private $anchor = '';

        public function __construct() {
            $this -> rows = $GLOBALS['queries'] -> initAnchor();
        }

        public function generateAnchor() {
            if (isset($_SESSION['title'], $_SESSION['text']) && !empty($_SESSION['title'] || $_SESSION['text']) && $_SESSION['user_role'] === 'admin') {
                $this -> anchor = $this -> anchor(
                    $_SESSION['title'] ?? '',
                    $_SESSION['text'] ?? '',

                    '<a href="/priculjica/nova-prica" class="session_anchor"',

                    $_SESSION['image_src'] ?? '',
                    $_SESSION['alt_text'] ?? ''
                );
            }

            if ($this -> rows) {
                $this -> anchor .= $this -> anchor(
                    $this -> rows['title'],
                    $this -> rows['text'],

                    '<a href="nova-prica/' . setChar(sanitize($this -> rows['title'])),

                    $this -> rows['image_src'],
                    $this -> rows['alt_text']
                );
            }

            return $this -> anchor;
        }

        public function anchor($title, $text, $link, $imageSrc, $altText) {
            $title = sanitize($title ?? '');
			$text = sanitize($text ?? '');
			$imageSrc = sanitize($imageSrc ?? '');
			$altText = sanitize($altText ?? '');

            $this -> anchor = $link . '>

                            <div class="section_content_text_wrapper">

                                <h2>'. $title . '</h2>

                                <p>'. limitText($text, 20) .'</p>

                            </div>

                            <div class="section_content_image_wrapper">

                                ' . ($imageSrc ? '<img src="' . $imageSrc . '" alt="' . $altText . '">' : '') . '

                            </div>

                        </a>';

            return $this -> anchor;
        }
    }

    $anchor = new Anchor;