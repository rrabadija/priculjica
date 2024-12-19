<?php header('Content-Type: text/html; charset=UTF-8');
    require_once 'connect.php';
    require_once 'helpers.php';
    require_once 'queries.php';

    class Header {
        private $headerURL;
        private $URLs = ['nova-prica', 'ostale-price', 'o-meni', 'pisi-mi'];

        public function __construct() {
            $this -> headerURL = setChar(sanitize($GLOBALS['queries'] -> header()));
        }

        public function generateHeader($hrefs) {
            $header = '<div class="header_logo_wrapper">
			
                            <button>
                            
                                <img src="/priculjica/assets/images/priculjica-logo.png" alt="">
                                
                            </button>
                            
                        </div>
                        
                        <div class="header_links_wrapper">';

            foreach($hrefs as $href) {
                switch ($href) {
                    case 'POČETNA':
                    case 'HOME':
                        $header .= '<a href="/priculjica/">';

                        break;
                    
                    case 'NOVA PRIČA':
                    case 'NEW STORY':
                        if ($_SESSION['user_role'] === 'user') {
                            $header .= $this -> headerURL
                                ? '<a href="/priculjica/' . $this -> URLs[0] . '/' . $this -> headerURL . '">'
                                : '<a href="/priculjica/' . $this -> URLs[0] . '">';
                        }
                        else {
                            $header .= '<a href="/priculjica/' . $this -> URLs[0] . '">';
                        }

                        break;

                    case 'OSTALE PRIČE':
                    case 'OTHER STORIES':
                        $header .= '<a href="/priculjica/' . $this -> URLs[1] . '">';

                        break;

                    case 'O MENI':
                    case 'ABOUT ME':
                        $header .= '<a href="/priculjica/' . $this -> URLs[2] . '">';
        
                        break;

                    default:
                        $header .= '<a href="/priculjica/' . $this -> URLs[3] . '">';
                
                        break;
                }

                $header .= '<div class="header_links_dot"></div>

                            ' . $href . '

                        </a>';
            }
                            
                $header .= '<button class="header_aside_button">

			                    <i class="fa fa-cog"></i>

			                    <i class="fa fa-close"></i>

		                    </button>

                        </div>
                                    
                        <aside>

                            <select class="header_aside_language_select">

                                <option value="hr">Hrvatski</option>
                                <option value="en">Engleski</option>

                            </select>

                            <div class="header_aside_darkmode_select_wrapper">

                                <input type="checkbox">
                                <label>Darkmode</label>
                                                
                            </div>'

                            . ($_SESSION['user_role'] === 'admin'
                                ? '<form method="POST" action="/priculjica/php/login.php">

                                        <input type="hidden" name="userRedirect" value="true">
                                        <button type="submit" style="cursor:pointer;color:white;" class="header_aside_user_redirect">' . $_SESSION['user_role'] . '</button>

                                    </form>'
                                : '') .
                            
                        '</aside>';
            
            return $header;
        }
    }

    $header = new Header;