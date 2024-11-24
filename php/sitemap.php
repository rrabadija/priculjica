<?php header('Content-Type: application/xml; charset=UTF-8');
    require_once 'connect.php';
    require_once 'queries.php';
    require_once 'helpers.php';

    $xml = new DomDocument();

    $xml -> preserveWhiteSpace = false;
    $xml -> formatOutput = true;

    $xml -> load('../sitemap.xml');

    $urlset = $xml -> documentElement;

    class Loc { //Class for reading, writing, editing and/or deleting the XML nodes
        private $locs;
        private $edit;
        private $titleEdit;
        private $delete;

        public function __construct($locs, $edit, $titleEdit, $delete) { //Class constructor
            $this -> locs = $locs;
            $this -> edit = $edit;
            $this -> titleEdit = $titleEdit;
            $this -> delete = $delete;
        }

        public function forEachLoc(callable $callBack) {
            foreach ($this -> locs as $loc) {
                $value = $loc -> nodeValue; //The value of the 'loc' node

                if ($callBack($value, $loc)) { //Execute the callback method, setting the condition of the if block
                    $lastmod = $loc -> nextSibling; //The next sibling of the loc node, which is the 'lastmod' node
                    $lastmod -> nodeValue = date('Y-m-d'); //Set the new date of the 'lastmod' node

                    if ($value !== 'https://priculjica.com' && $value !== 'https://priculjica.com/ostale-price') {
                        if (!empty($this -> delete)) { //If the delete keyword is not empty, delete the associated 'url' node
                            $url = $loc -> parentNode;
                            $url -> parentNode -> removeChild($url);
                        }

                        $loc -> nodeValue = 'https://priculjica.com/nova-prica/' . $this -> titleEdit; //Edit the existing 'loc' node's value
                    }
                }
            }
        }

        //Callback methods

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

        //Callback methods calls

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
        if (isset($_POST['write']) && !empty($_POST['write'])) { //Write a new 'url' nod
            $write = setChar(sanitize($_POST['write']));
            
            $naslovCheck = $GLOBALS['queries'] -> sitemap($write);

            if (!$naslovCheck) { //If the record does not already exist in the database, write a new 'url' node
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

        if (isset($_POST['edit'], $_POST['titleEdit']) && !empty($_POST['edit']) && !empty($_POST['titleEdit'])) { //Edit the 'url' node's 'loc' and 'lastmod'
            $edit = setChar(sanitize($_POST['titleEdit']));
            $titleEdit = setChar(sanitize($_POST['edit']));

            $loc = new Loc($locs, $edit, $titleEdit, $delete);

            $loc -> forEachLoc($loc -> editStoryCall());
            $loc -> forEachLoc($loc -> editOtherStoriesCall());

            titleCheck($loc, $edit);
        }

        if (isset($_POST['delete']) && !empty($_POST['delete'])) { //Delete the 'url' node
            $delete = setChar(sanitize($_POST['delete']));

            $loc = new Loc($locs, $edit, $titleEdit, $delete);

            $loc -> forEachLoc($loc -> deleteStoryCall());
            $loc -> forEachLoc($loc -> editOtherStoriesCall());

            titleCheck($loc, $delete);
        }

        $xml -> save('../sitemap.xml');
    }

    function titleCheck($loc, $edit) { //If the edited or deleted story is in the last three database rows (present on the homepage), edit the 'lastmod' node of the homepage 'url' node
        $naslovCheck = $GLOBALS['queries'] -> titleCheck();

        foreach ($naslovCheck as $naslov) {
            if (setChar($naslov['title']) === $edit) {
                $loc -> forEachLoc($loc -> editIndexCall());
            }
        }
    }