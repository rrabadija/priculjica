<?php header('Content-Type: text/html; charset=UTF-8');
	require '../php/connect.php';
	require 'php/admin-controls.php';
	require '../php/string.php';
	require 'php/admin-header.php';
	require '../php/footer.php';
	require 'php/admin-audio-player.php';

	session_start(); //Starts the session

	$condition = '<br><p><span><img><strong><em>';

	try {
		if ($_SERVER["REQUEST_METHOD"] == "POST") { //Get data from admin.html with AJAX
			if (isset($_POST['title'], $_POST['textarea'])) { //Title and text
				$naslov = $_POST['title'] ?? '';
				$tekst = $_POST['textarea'] ?? '';

				$_SESSION['naslov'] = $naslov; //Session variables
				$_SESSION['tekst'] = $tekst;
			}

			if (isset($_POST['image'])) { //Image and its attributes
				$slika = $_POST['image'] ?? '';
				$alt = $_POST['alt'] ?? '';

				$_SESSION['slika'] = $slika; //Session variables
				$_SESSION['alt'] = $alt;
			}

			if (isset($_POST['audio'], $_POST['audioSrc'])) { //Audio and its attributes
				$audio = isset($_POST['audio']) ? filter_var($_POST['audio'], FILTER_VALIDATE_BOOLEAN) : false;
				$audioSrc = $_POST['audioSrc'] ?? '';

				$_SESSION['audio'] = $audio; //Session variables
				$_SESSION['audioSrc'] = $audioSrc;
			}

			if (isset($_POST['resetSession'])) { //Unset the session variables upon creating a record in the database of those session variables
				$resetSession = isset($_POST['resetSession']) ? filter_var($_POST['resetSession'], FILTER_VALIDATE_BOOLEAN) : false;

				if ($resetSession) {
					unset($_SESSION['naslov']);
					unset($_SESSION['tekst']);
					unset($_SESSION['slika']);
					unset($_SESSION['alt']);
					unset($_SESSION['audio']);
					unset($_SESSION['audioSrc']);
				}
			}
		}

		function dbContent($pdo, $content, $condition) {
			$URL = htmlspecialchars($_GET['naslov'] ?? '');
			$URL = str_replace('-', ' ', $URL); //Prepare the URL

			$stmt = $pdo->prepare("SELECT price.naslov, price.tekst, price.audio, audio.put_do_audio
			FROM price
			LEFT JOIN audio ON price.naslov = audio.naslov
			WHERE price.naslov LIKE ?");

			$stmt->bindValue(1, $URL, PDO::PARAM_STR);
			$stmt->execute();
			$rows = $stmt->fetch(PDO::FETCH_ASSOC);
		
			if ($rows) {
				switch ($content) {
					case 'naslov': //Title from the database
						echo (isset($rows['naslov'])) ? htmlspecialchars($rows['naslov']) : '';
						break;

					case 'tekst': //Text from the database (and the images within)
						echo (isset($rows['tekst'])) ? strip_tags($rows['tekst'], $condition) : '';
						break;

					case 'audio': //Audio source from the database
						return (isset($rows['naslov']) && isset($rows['put_do_audio'])) ? htmlspecialchars($rows['put_do_audio']) : '';
						break;

					case 'audioBool': //For passing the audio bool state from the price table into the audio-player.php function
						return $rows['audio'];
						break;
				}
			}

			return null;
		}

	} catch (PDOException $e) {
		die("Error fetching data: " . $e->getMessage());
	}
?>
<!doctype html>
<html lang="hr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pričuljica - Admin</title>
	
<base href="/priculjica/">
<link rel="apple-touch-icon" sizes="180x180" href="/priculjica/favicon/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/priculjica/favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/priculjica/favicon/favicon-16x16.png">
<link rel="manifest" href="/priculjica/favicon/site.webmanifest">
<link href="/priculjica/style/style.css" rel="stylesheet" type="text/css">
<link href="/priculjica/style/header.css" rel="stylesheet" type="text/css">
<link href="/priculjica/style/nova-prica.css" rel="stylesheet" type="text/css">
<link href="/priculjica/admin/style/admin-nova-prica.css" rel="stylesheet" type="text/css">
<link href="/priculjica/font/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="/priculjica/style/footer.css" rel="stylesheet" type="text/css">
<link href="/priculjica/font/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

</head>

<body>
	
	<header>
		
		<?=generateHeader($title = ['POČETNA', 'OSTALE PRIČE', 'O MENI', 'PIŠI MI'])?>

	</header>

	<div class="admin_controls_height"></div>

	<div class="admin_controls">

		<div class="admin_controls_buttons_container">

			<button class="admin_delete_button"><i class="fa fa-trash"></i></button>

			<button class="admin_edit_button"><i class="fa fa-pencil"></i></button>

			<button class="admin_save_button"><i class="fa fa-save"></i></button>

			<button class="admin_audio_button"><i class="fa fa-volume-up"></i></button>

			<button class="admin_image_button"><i class="fa fa-camera"></i></button>

			<button class="admin_clear_button"><i class="fa fa-close"></i></button>

			<button class="admin_italic_button"><i class="fa fa-italic"></i></button>

			<button class="admin_bold_button"><i class="fa fa-bold"></i></button>

			<button class="admin_align_right_button" value="right"><i class="fa fa-align-right"></i></button>

			<button class="admin_align_center_button" value="center"><i class="fa fa-align-center"></i></button>

			<button class="admin_align_left_button" value=""><i class="fa fa-align-left"></i></button>

			<button class="admin_align_justify_button" value="justify"><i class="fa fa-align-justify"></i></button>

			<input type="number" min="1" max="100" value="100">

		</div>

	</div>

	<div class="admin_controls_controls_container">

		<div class="admin_controls_image">

			<input class="admin_controls_input" type="file" id="file_image" name="file_image">

			<label class="admin_controls_input_label" for="file_image">

				<i class="fa fa-plus"></i>

			</label>

			<input type="text" placeholder="alt...">

			<input type="text" placeholder="opis...">

			<button class="admin_controls_insert admin_controls_image_insert_image"></button>

		</div>

		<div class="admin_controls_audio">

			<input class="admin_controls_input" type="file" id="file_audio" name="file_audio">

			<label class="admin_controls_input_label" for="file_audio">

				<i class="fa fa-volume-up"></i>

			</label>

			<p></p>

			<button class="admin_controls_insert admin_controls_audio_insert_audio"></button>

		</div>

	</div>

	<button class="admin_controls_button"></button>
	
	<main>
		
		<section>
			
			<div class="section_header">
				
				<h1 contenteditable="true" spellcheck="false" data-placeholder-title="Naslov..."><?=(isset($_GET['naslov'])) ? dbContent($pdo, 'naslov', $condition) : htmlspecialchars($_SESSION['naslov'] ?? '')?></h1>
				
			</div>

			<?php audioPlayer($pdo, $condition)?>

			<div class="section_paragraph" contenteditable="true" spellcheck="false" data-placeholder-paragraph="Tekst...">

				<?=(isset($_GET['naslov'])) ? dbContent($pdo, 'tekst', $condition) : (isset($_SESSION['tekst']) ? strip_tags($_SESSION['tekst'], $condition) : '')?>
					
			</div>
			
		</section>
		
	</main>

	<footer>
				
		<?=generateFooter(true)?>
				
	</footer>
	
	<script src="/priculjica/script/header.js"></script>
	
	<script src="/priculjica/script/nova-prica.js"></script>

	<script src="/priculjica/admin/script/jquery-3.7.1.min.js"></script>

	<script src="/priculjica/admin/script/admin-nova-prica.js"></script>

	<script src="/priculjica/admin/script/admin-text-editor.js"></script>

	<script src="/priculjica/admin/script/admin-audio.js"></script>
	
</body>
</html>
