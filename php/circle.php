<?php header('Content-Type: text/html; charset=UTF-8');
	require_once 'connect.php';
	require_once 'queries.php';
	require_once 'helpers.php';

	$rows = $GLOBALS['queries'] -> circle();

	class Circle {
		private $rows;

		public function __construct($rows) {
			$this -> rows = $rows;
		}

		public function generateCircle($i) {
			if (isset($this -> rows[$i])) {
				return $this -> circle($this -> rows[$i]['title'], $this -> rows[$i]['text'], '<a href="nova-prica/' . setChar($this -> rows[$i]['title']), $this -> rows[$i]['image_src'], $this -> rows[$i]['alt_text']);
			}
			else if (isset($_SESSION['title']) && $i === -1) {
				return $this -> circle($_SESSION['title'], $_SESSION['text'], '<a href="/priculjica/admin/nova-prica', $_SESSION['image_src'], $_SESSION['alt_text']);
			}
		}

		private function circle($title, $text, $link, $imageSrc, $altText) {
			$title = sanitize($title ?? '');
			$text = sanitize($text ?? '');
			$link = sanitize($link ?? '');
			$imageSrc = sanitize($imageSrc ?? '');
			$altText = sanitize($altText ?? '');

			$circle = '<div class="section_2_content_circle_image_text_wrapper">

							<h2>' . $title . '</h2>

							<p>' . limitText($text, 9) . '</p>

							<div class="section_1_content_button">'

								. $link . '" tabindex="-1">
				
									<button></button>

								</a>
							
							</div>

						</div>';
					
			if (!empty($imageSrc)) {
				$circle .= '<img src="' . $imageSrc . '" alt="' . ($altText !== '' ? $altText : '') . '">';
			}

			return $circle;
		}
	}

	$circle = new Circle($rows);