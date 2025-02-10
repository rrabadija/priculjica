<?php header('Content-Type: text/html; charset=UTF-8');
    require_once 'connect.php';
    require_once 'helpers.php';
    require_once 'queries.php';
    require_once 'language.php';
    require_once 'template.php';

    class Header {
        private static $headerURL;

        public static function init() {
            self::$headerURL = setChar(sanitize(Queries::header()));
        }

        private static function storyURL() {
            return ($_SESSION['user_role'] === 'user' && self::$headerURL) ? 'nova-prica/' . self::$headerURL : 'nova-prica';
        }

        private static function template($URLs, $anchors) {
            return Template::render('header.html',
                [
                    'href' => $URLs,
                    'anchor' => $anchors,
                    'logout' => ($_SESSION['user_role'] === 'admin') ? Template::render('logout.html') : ''
                ]
            );
        }

        public static function generateHeader($file) {
            switch($file) {
                case 'index':

                    $URLs = [self::storyURL(), 'ostale-price', 'o-meni', 'pisi-mi'];
                    $anchors = [setLanguage('header.a-2'), setLanguage('header.a-3'), setLanguage('header.a-4'), setLanguage('header.a-5')];

                    return self::template($URLs, $anchors);

                break;
                
                case 'nova-prica':
                    
                    $URLs = ['/', 'ostale-price', 'o-meni', 'pisi-mi'];
                    $anchors = [setLanguage('header.a-1'), setLanguage('header.a-3'), setLanguage('header.a-4'), setLanguage('header.a-5')];

                    return self::template($URLs, $anchors);

                break;

                case 'ostale-price':
                    
                    $URLs = ['/', self::storyURL(), 'o-meni', 'pisi-mi'];
                    $anchors = [setLanguage('header.a-1'), setLanguage('header.a-2'), setLanguage('header.a-4'), setLanguage('header.a-5')];

                    return self::template($URLs, $anchors);

                break;

                case 'o-meni':
                    
                    $URLs = ['/', self::storyURL(), 'ostale-price', 'pisi-mi'];
                    $anchors = [setLanguage('header.a-1'), setLanguage('header.a-2'), setLanguage('header.a-3'), setLanguage('header.a-5')];

                    return self::template($URLs, $anchors);

                break;

                case 'pisi-mi':
                    
                    $URLs = ['/', self::storyURL(), 'ostale-price', 'o-meni'];
                    $anchors = [setLanguage('header.a-1'), setLanguage('header.a-2'), setLanguage('header.a-3'), setLanguage('header.a-4')];

                    return self::template($URLs, $anchors);
                    
                break;
            }
        }
    }

    Header::init();