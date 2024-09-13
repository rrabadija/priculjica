<?php header('Content-Type: text/html; charset=UTF-8');
	require 'php/connect.php';
	require 'php/string.php';
	require 'php/header.php';
	require 'php/footer.php';
	require 'php/audio-player.php';
	require 'php/audio.php';

	$condition = '<br><p><span><img><strong><em>';

	if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['naslov'])) { //Get the URL from the naslov GET variable
		$URL = htmlspecialchars($_GET['naslov'] ?? '');
		$URL = str_replace('-', ' ', $URL); //Replace the spaces with a dash

		$stmt = $pdo -> prepare("SELECT price.naslov, price.tekst, price.audio, audio.put_do_audio, audio.trajanje
		FROM price
		LEFT JOIN audio ON price.naslov = audio.naslov
		WHERE price.naslov LIKE ?");

		$stmt -> bindValue(1, $URL, PDO::PARAM_STR);
		$stmt -> execute();

		$rows = $stmt -> fetch(PDO::FETCH_ASSOC);

		if (!$rows) { //Redirect to ostale-price.php if there is no content in the database for the URL keyword
			header('Location: /priculjica/ostale-price');

			exit;
		}

		$naslov = htmlspecialchars($rows['naslov'] ?? '');
		$text = strip_tags($rows['tekst'] ?? '', $condition);
		$audioDuration = $rows['trajanje'] ?? null;

		if ($audioDuration) { //If there is an audio duration record, save it into a session variable for audio.php
			$_SESSION['naslovAudio'] = $naslov;
			$_SESSION['audioDuration'] = $audioDuration;
		}
	}
?>
<!doctype html>
<html lang="hr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pričuljica</title>
	
<base href="/priculjica/">
<link rel="apple-touch-icon" sizes="180x180" href="/priculjica/favicon/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/priculjica/favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/priculjica/favicon/favicon-16x16.png">
<link rel="manifest" href="/priculjica/favicon/site.webmanifest">
<link href="/priculjica/style/style.css" rel="stylesheet" type="text/css">
<link href="/priculjica/style/header.css" rel="stylesheet" type="text/css">
<link href="/priculjica/style/nova-prica.css" rel="stylesheet" type="text/css">
<link href="/priculjica/style/footer.css" rel="stylesheet" type="text/css">
<link href="/priculjica/font/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

</head>

<body>
	
	<header>
		
		<?=generateHeader($title = ['POČETNA', 'OSTALE PRIČE', 'O MENI', 'PIŠI MI'], $headerURL)?>

	</header>
	
	<main>
		
		<section>
			
			<div class="section_header">
				
				<h1><?=$naslov?></h1>
				
			</div>

			<?php $rows ? audioPlayer($rows) : ''?>
			
			<div class="section_paragraph">
				
				<?=$text?>
				
			</div>
			
		</section>
		
	</main>

	<footer>
				
		<?=generateFooter(true)?>
				
	</footer>
	
	<script src="/priculjica/script/header.js"></script>
	
	<script src="/priculjica/script/nova-prica.js"></script>

	<script src="/priculjica/script/audio.js"></script>
	
</body>
</html>
