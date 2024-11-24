<?php header('Content-Type: text/html; charset=UTF-8');
    require_once 'connect.php';

    class Queries {
        private $PDO;
        private $stmt = null;

        public function __construct($PDO) {
            $this -> PDO = $PDO;
        }

        private function execute($fetchAll) {
            $this -> stmt -> execute();

            return $fetchAll ? $this -> stmt -> fetchAll(PDO::FETCH_ASSOC) : $this -> stmt -> fetch(PDO::FETCH_ASSOC);
        }

        public function header() {
            $this -> stmt = $this -> PDO -> prepare("SELECT title FROM stories ORDER BY id DESC LIMIT 1");

            return $this -> execute(false);
        }

        public function circle() {
            $this -> stmt = $this -> PDO -> prepare("SELECT stories.title, stories.text, images.image_src, images.alt_text 
            FROM stories 
            LEFT JOIN images ON stories.title = images.title
            ORDER BY stories.id DESC LIMIT 3");

            return $this -> execute(true);
        }

        public function story($URL) {
            $this -> stmt = $this -> PDO -> prepare("SELECT stories.title, stories.text, stories.audio_bool, audio.audio_src, audio.audio_duration
            FROM stories
            LEFT JOIN audio ON stories.title = audio.title
            WHERE stories.title LIKE ?");

            $this -> stmt -> bindParam(1, $URL, PDO::PARAM_STR);

            return $this -> execute(false);
        }

        public function storyCount($URL) {
            $this -> stmt = $this -> PDO -> prepare("UPDATE stories SET watch_count = watch_count + 1 WHERE title LIKE ?");

            $this -> stmt -> bindParam(1, $URL, PDO::PARAM_STR);
            $this -> stmt -> execute();
        }

        public function audioCount($title) {
            $this -> stmt = $this -> PDO -> prepare("UPDATE audio SET listen_count = listen_count + 1 WHERE title LIKE ?");

            $this -> stmt -> bindParam(1, $title, PDO::PARAM_STR);
            $this -> stmt -> execute();
        }

        public function initAnchor() {
            $this -> stmt = $this -> PDO -> prepare("SELECT stories.title, stories.text, images.image_src, images.alt_text 
            FROM stories 
            LEFT JOIN images ON stories.title = images.title
            ORDER BY stories.id DESC");

            return $this -> execute(true);
        }

        public function search($search) {
            $this -> stmt = $this -> PDO -> prepare("SELECT stories.title, stories.text, images.image_src, images.alt_text 
            FROM stories 
            LEFT JOIN images ON stories.title = images.title 
            WHERE stories.title LIKE ? ORDER BY stories.id DESC");

            $this -> stmt -> bindParam(1, $search, PDO::PARAM_STR);

            return $this -> execute(true);
        }

        public function translate($keys, $language) {
            $this -> stmt = $this -> PDO -> prepare("SELECT translation FROM translations WHERE `key` = ? AND `language` = ?");

            $this -> stmt -> bindParam(1, $keys, PDO::PARAM_STR);
            $this -> stmt -> bindParam(2, $language, PDO::PARAM_STR);

            return $this -> execute(false);
        }

        public function sitemap($write) {
            $this -> stmt = $this -> PDO -> prepare("SELECT title FROM stories WHERE title LIKE ?");

            $this -> stmt -> bindParam(1, $write, PDO::PARAM_STR);

            return $this -> execute(false);
        }

        public function titleCheck() {
            $this -> stmt = $this -> PDO -> prepare("SELECT title FROM stories ORDER BY id DESC LIMIT 3");

            return $this -> execute(true);
        }
    }

    $queries = new Queries($PDO);