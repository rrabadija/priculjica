<?php header('Content-Type: text/html; charset=UTF-8');
    require_once 'helpers.php';

    class Login {
        private $session;
        private $redirectURL;

        public function __construct() {
            $this -> session = &$_SESSION;

            if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !empty($_SERVER['HTTP_REFERER'])) {
                $this -> redirectURL = $_SERVER['HTTP_REFERER'] ?? $_SERVER['REQUEST_URI'];
            }

            $this -> getUserRole();
            $this -> adminRedirect();
            $this -> userRedirect();
        }

        private function adminRedirect() {
            $redirectURL = str_replace('?admin=true', '', $this -> redirectURL);

            if (($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['admin']) && $_GET['admin'] === 'true')) {
                $this -> setUserRole('login');

                header ("Location: $redirectURL");

                exit;
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                header('Content-Type: application/json; charset=UTF-8');

                if (isset($GLOBALS['FETCH']['username'], $GLOBALS['FETCH']['password'])) {
                    $username = sanitize($GLOBALS['FETCH']['username']);
                    $password = sanitize($GLOBALS['FETCH']['password']);

                    if ($username === 'test' && $password === 'test') {
                        $this -> setUserRole('admin');
                    }
                    else {
                        $this -> setUserRole('login');
                    }

                    echo json_encode($this -> redirectURL);

                    exit;
                }
            }
        }

        private function userRedirect() {
            $userRedirectURL = str_replace('?admin=true', '', $this -> redirectURL);

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (isset($_POST['userRedirect']) && $this -> session['user_role'] === 'admin') {
                    $this -> setUserRole('user');

                    header("Location: $userRedirectURL");

                    exit;
                }
            }
        }

        public function generateLogin() {
            if ($this -> session['user_role'] === 'login') {
                $this -> setUserRole('user');

                return '<div class="login_form_wrapper">

                            <label for="username">Korisničko ime:</label>
                            <br>
                            <input type="text" required>
                            <br>
                            <label for="password">Lozinka:</label>
                            <br>
                            <input type="password" required>
                            <br>
                            <button type="submit"></button>

                        </div>
                        
                        <script>

                            const login = document.querySelector(".login_form_wrapper");

                            if (login) {
                                document.documentElement.style.overflowY = "hidden";
                                document.body.style.overflowY = "hidden";
                
                                Array.from(document.body.children).forEach(child => {
                                    if (child !== login) {
                                        child.style.filter = "blur(20px)";
                                        child.style.pointerEvents = "none";
                                        child.style.transition = "none";
                                    }
                                })
                            }

                            document.querySelector(".login_form_wrapper button").addEventListener("click", () => {
                                const username = document.querySelector(".login_form_wrapper input:first-of-type").value;
                                const password = document.querySelector(".login_form_wrapper input:last-of-type").value;

                                fetch("/priculjica/php/login.php", {
		                            method: "POST",
		                            body: JSON.stringify({
                                        username: username,
                                        password: password
                                    })
	                            })

                                .then(response => response.json())
	                            .then(data => window.location.href = data);
                            });

                        </script>';
            }
            
            return '';
        }

        private function setUserRole($role) {
            $this -> session['user_role'] = $role;
        }

        private function getUserRole() {
            return $this -> session['user_role'] ?? $this -> session['user_role'] = 'user';
        }
    }

    $login = new Login;