<?php header('Content-Type: text/html; charset=UTF-8');
    require_once 'connect.php';

    class Queries {
        private static $PDO;

        public static function init($PDO) {
            self::$PDO = $PDO;
        }

        private static function execute($stmt, $fetchAll) {
            $stmt -> execute();

            return $fetchAll ? $stmt -> fetchAll(PDO::FETCH_ASSOC) : $stmt -> fetch(PDO::FETCH_ASSOC);
        }

        public static function header() {
            $stmt = self::$PDO -> prepare("SELECT title FROM stories ORDER BY id DESC LIMIT 1");

            $stmt -> execute();

            return $stmt -> fetchColumn();
        }

        public static function circle() {
            $stmt = self::$PDO -> prepare("SELECT stories.title, stories.text, images.image_src, images.alt_text 
            FROM stories 
            LEFT JOIN images ON stories.title = images.title
            ORDER BY stories.id DESC LIMIT 3");

            return self::execute($stmt, true);
        }

        public static function story($URL) {
            $stmt = self::$PDO -> prepare("SELECT stories.title, stories.text, stories.audio_bool, audio.audio_src, audio.audio_duration
            FROM stories
            LEFT JOIN audio ON stories.title = audio.title
            WHERE stories.title LIKE ?");

            $stmt -> bindValue(1, $URL, PDO::PARAM_STR);

            return self::execute($stmt, false);
        }

        public static function storyCount($URL) {
            $stmt = self::$PDO -> prepare("UPDATE stories SET watch_count = watch_count + 1 WHERE title LIKE ?");

            $stmt -> bindValue(1, $URL, PDO::PARAM_STR);
            $stmt -> execute();
        }

        public static function audioCount($title) {
            $stmt = self::$PDO -> prepare("UPDATE audio SET listen_count = listen_count + 1 WHERE title LIKE ?");

            $stmt -> bindValue(1, $title, PDO::PARAM_STR);
            $stmt -> execute();
        }

        public static function initAnchor() {
            $stmt = self::$PDO -> prepare("SELECT stories.title, stories.text, images.image_src, images.alt_text 
            FROM stories 
            LEFT JOIN images ON stories.title = images.title
            ORDER BY stories.id DESC");

            return self::execute($stmt, true);
        }

        public static function search($search) {
            $stmt = self::$PDO -> prepare("SELECT stories.title, stories.text, images.image_src, images.alt_text 
            FROM stories 
            LEFT JOIN images ON stories.title = images.title 
            WHERE stories.title LIKE ? ORDER BY stories.id DESC");

            $stmt -> bindValue(1, $search, PDO::PARAM_STR);

            return self::execute($stmt, true);
        }

        public static function translate($keys, $language) {
            $stmt = self::$PDO -> prepare("SELECT translation FROM translations WHERE `key` = ? AND `language` = ?");

            $stmt -> bindValue(1, $keys, PDO::PARAM_STR);
            $stmt -> bindValue(2, $language, PDO::PARAM_STR);

            return self::execute($stmt, false);
        }

        public static function sitemap($write) {
            $stmt = self::$PDO -> prepare("SELECT title FROM stories WHERE title LIKE ?");

            $stmt -> bindValue(1, $write, PDO::PARAM_STR);

            return self::execute($stmt, false);
        }

        public static function titleCheck() {
            $stmt = self::$PDO -> prepare("SELECT title FROM stories ORDER BY id DESC LIMIT 3");

            return self::execute($stmt, true);
        }
    }

    Queries::init($PDO);