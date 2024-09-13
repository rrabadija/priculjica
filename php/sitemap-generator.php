<?php header('Content-Type: application/xml; charset=UTF-8');
    require 'connect.php';
    require 'string.php';

    $xml = new DomDocument();

    $xml -> preserveWhiteSpace = false;
    $xml -> formatOutput = true;

    $xml -> load('../sitemap.xml');

    $urlset = $xml -> documentElement;

    class Loc {
        private $locs;
        private $edit;
        private $titleEdit;
        private $delete;

        public function __construct($locs, $edit, $titleEdit, $delete)
        {
            $this -> locs = $locs;
            $this -> edit = $edit;
            $this -> titleEdit = $titleEdit;
            $this -> delete = $delete;
        }

        public function forEachLoc(callable $callBack) {
            foreach ($this -> locs as $loc) {
                $value = $loc -> nodeValue;

                if ($callBack($value, $loc)) {
                    $lastmod = $loc -> nextSibling;
                    $lastmod -> nodeValue = date('Y-m-d');

                    if ($value !== 'https://priculjica.com' && $value !== 'https://priculjica.com/ostale-price') {
                        if ($this -> delete !== '' && $value === $loc -> nodeValue) {
                            $url = $loc -> parentNode;
                            $url -> parentNode -> removeChild($url);
                        }

                        $loc -> nodeValue = 'https://priculjica.com/nova-prica/' . $this -> titleEdit;
                    }
                }
            }
        }

        private function editIndex($value) {
            return $value === 'https://priculjica.com';
        }

        private function editOtherStories($value) {
            return $value === 'https://priculjica.com/ostale-price';
        }

        private function editStory($value) {
            return $value === 'https://priculjica.com/nova-prica/' . $this -> edit;
        }

        private function deleteStory($value) {
            return $value === 'https://priculjica.com/nova-prica/' . $this -> delete;
        }

        public function editIndexCall() {
            return function($value, $loc) {
                return $this -> editIndex($value, $loc);
            };
        }

        public function editOtherStoriesCall() {
            return function($value, $loc) {
                return $this -> editOtherStories($value, $loc);
            };
        }

        public function editStoryCall() {
            return function($value, $loc) {
                return $this -> editStory($value, $loc);
            };
        }

        public function deleteStoryCall() {
            return function($value, $loc) {
                return $this -> deleteStory($value, $loc);
            };
        }
    }

    $locs = $urlset -> getElementsByTagName('loc');
    $edit = '';
    $titleEdit = '';
    $delete = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['write']) && !empty($_POST['write'])) {
            $write = setChar(htmlspecialchars($_POST['write'] ?? ''));

            $stmt = $pdo -> prepare("SELECT naslov FROM price WHERE naslov LIKE ?");

            $stmt -> bindValue(1, $write, PDO::PARAM_STR);
            $stmt -> execute();

            $naslovCheck = $stmt -> fetch(PDO::FETCH_ASSOC);

            if (!$naslovCheck) {
                $url = $xml -> createElement('url');

                $loc = $xml -> createElement('loc', 'https://priculjica.com/nova-prica/' . $write);
                $lastmod = $xml -> createElement('lastmod', date('Y-m-d'));
                $priority = $xml -> createElement('priority', '0.7');

                $url -> appendChild($loc);
                $url -> appendChild($lastmod);
                $url -> appendChild($priority);

                $urlset -> appendChild($url);

                $loc = new Loc($locs, $edit, $titleEdit, $delete);

                $loc -> forEachLoc($loc -> editIndexCall());
                $loc -> forEachLoc($loc -> editOtherStoriesCall());
            }
        }

        if (isset($_POST['edit'], $_POST['titleEdit']) && !empty($_POST['edit']) && !empty($_POST['titleEdit'])) {
            $edit = setChar(htmlspecialchars($_POST['titleEdit'] ?? ''));
            $titleEdit = setChar(htmlspecialchars($_POST['edit'] ?? ''));

            $loc = new Loc($locs, $edit, $titleEdit, $delete);

            $loc -> forEachLoc($loc -> editStoryCall());
            $loc -> forEachLoc($loc -> editOtherStoriesCall());

            checkNaslov($pdo, $loc, $edit);
        }

        if (isset($_POST['delete']) && !empty($_POST['delete'])) {
            $delete = setChar(htmlspecialchars($_POST['delete'] ?? ''));

            $loc = new Loc($locs, $edit, $titleEdit, $delete);

            $loc -> forEachLoc($loc -> deleteStoryCall());
            $loc -> forEachLoc($loc -> editOtherStoriesCall());

            checkNaslov($pdo, $loc, $edit);
        }

        $xml -> save('../sitemap.xml');
    }

    function checkNaslov($pdo, $loc, $edit) {
        $stmt = $pdo -> prepare("SELECT naslov FROM price ORDER BY id DESC LIMIT 3");

        $stmt -> execute();

        $naslovCheck = $stmt -> fetchAll(PDO::FETCH_ASSOC);

        foreach ($naslovCheck as $naslov) {
            if (setChar($naslov['naslov']) === $edit) {
                $loc -> forEachLoc($loc -> editIndexCall());
            }
        }
    }