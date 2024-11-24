<?php header('Content-Type: text/html; charset=UTF-8');
    require_once 'connect.php';
    require_once 'queries.php';
    require_once 'helpers.php';

    class Header {
        private $headerURL;
        private $URLs = ['nova-prica', 'ostale-price', 'o-meni', 'pisi-mi'];
        private $admin = null;
        private $adminConditions = ['/priculjica/admin/', '/priculjica/admin/nova-prica', '/priculjica/admin/ostale-price'];

        public function __construct() {
            $this -> headerURL = setChar(sanitize($GLOBALS['queries'] -> header()));

            if (in_array($_SERVER['REQUEST_URI'], $this -> adminConditions)) {
                $this -> admin = true;
            }
            else {
                $this -> admin = false;
            }
        }

        public function generateHeader($hrefs) {
            $header = '<div class="header_logo_wrapper">
			
                            <button>
                            
                                <img src="/priculjica/assets/images/priculjica-logo.png" alt="">
                                
                            </button>
                            
                        </div>
                        
                        <div class="header_links_wrapper">';

            foreach($hrefs as $key => $href) {
                switch ($href) {
                    case 'POČETNA':
                    case 'HOME':
                        $header .= !$this -> admin ? '<a href="/priculjica/">' : '<a href="/priculjica/admin/">';

                        break;
                    
                    case 'NOVA PRIČA':
                    case 'NEW STORY':
                        if (!$this -> admin) {
                            $header .= $this -> headerURL
                                ? '<a href="/priculjica/' . $this -> URLs[0] . '/' . $this -> headerURL . '">'
                                : '<a href="/priculjica/' . $this -> URLs[0] . '">';
                        }
                        else {
                            $header .= '<a href="/priculjica/admin/' . $this -> URLs[0] . '">';
                        }

                        break;

                    case 'OSTALE PRIČE':
                    case 'OTHER STORIES':
                        $header .= !$this -> admin
                            ? '<a href="/priculjica/' . $this -> URLs[1] . '">'
                            : '<a href="/priculjica/admin/' . $this -> URLs[1] . '">';

                        break;

                    default:
                        $header .= '<a href="/priculjica/' . $this -> URLs[$key] . '">';

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
                                                
                            </div>

                        </aside>';
            
            return $header;
        }
    }

    $header = new Header;