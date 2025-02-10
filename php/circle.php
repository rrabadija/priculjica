<?php header('Content-Type: text/html; charset=UTF-8');
	require_once 'connect.php';
	require_once 'queries.php';
	require_once 'helpers.php';
	require_once 'template.php';

	class Circle {
		private static $rows;

		public static function init() {
            self::$rows = Queries::circle();
        }

		private static function template() {
			return Template::render('circle.html',
				[
					'title' => '',
					'text' => '',
					'href' => '',
					'imageSrc' => '',
					'altText' => ''
				]
			);
		}

		public static function generateCircle() {
			return self::template();
		}
	}

	Circle::init();