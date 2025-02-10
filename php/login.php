<?php header('Content-Type: text/html; charset=UTF-8');
    require_once 'connect.php';
    require_once 'helpers.php';
    require_once 'template.php';

    class Login {
        private static $session;
        private $redirectURL;

        public function __construct() {
            self::$session = &$_SESSION;

            if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !empty($_SERVER['HTTP_REFERER'])) {
                $this -> redirectURL = $_SERVER['HTTP_REFERER'] ?? $_SERVER['REQUEST_URI'];
            }

            self::$session['user_role'] ?? self::$session['user_role'] = 'user';

            $this -> adminRedirect();
            $this -> userRedirect();
        }

        private function adminRedirect() {
            $redirectURL = str_replace('?admin', '', $this -> redirectURL);

            if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['admin'])) {
                if (self::$session['user_role'] === 'user') {
                    self::$session['user_role'] = 'login';
                }

                header ("Location: $redirectURL");

                exit;
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                header('Content-Type: application/json; charset=UTF-8');

                if (isset($GLOBALS['FETCH']['username'], $GLOBALS['FETCH']['password'])) {
                    $username = sanitize($GLOBALS['FETCH']['username']);
                    $password = sanitize($GLOBALS['FETCH']['password']);

                    if ($username === 'test' && $password === 'test') {
                        self::$session['user_role'] = 'admin';
                    }
                    else {
                        self::$session['user_role'] = 'login';
                    }

                    echo json_encode($this -> redirectURL);

                    exit;
                }
            }
        }

        private function userRedirect() {
            $userRedirectURL = str_replace('?admin=true', '', $this -> redirectURL);

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (isset($_POST['userRedirect']) && self::$session['user_role'] === 'admin') {
                    self::$session['user_role'] = 'user';

                    header("Location: $userRedirectURL");

                    exit;
                }
            }
        }

        public static function generateLogin() {
            if (self::$session['user_role'] === 'login') {
                self::$session['user_role'] = 'user';

                return Template::render('login.html');
            }
            
            return '';
        }
    }

    new Login;