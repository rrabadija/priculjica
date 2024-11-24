<?php header('Content-Type: text/html; charset=UTF-8');
	require_once 'php/header.php';
	require_once 'php/story.php';
	require_once 'php/footer.php';
	require_once 'php/language.php';
?>
<!doctype html>
<html lang="<?=$_SESSION['language']?>">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pričuljica</title>

<base href="/priculjica/">

<script src="/priculjica/assets/js/theme.js"></script>
	
<base href="/priculjica/">
<link rel="apple-touch-icon" sizes="180x180" href="/priculjica/assets/favicons/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/priculjica/assets/favicons/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/priculjica/assets/favicons/favicon-16x16.png">
<link rel="manifest" href="/priculjica/assets/favicon/site.webmanifest">
<link href="/priculjica/assets/css/nova-prica.css" rel="stylesheet" type="text/css">

</head>

<body>
	
	<header>
		
		<?=$header -> generateHeader([setLanguage("header.a-1"), setLanguage("header.a-3"), setLanguage("header.a-4"), setLanguage("header.a-5")])?>

	</header>
	
	<main>
		
		<section>
			
			<div class="section_header">
				
				<h1><?=isset($rows) ? $title : ''?></h1>
				
			</div>

			<?php isset($rows) ? audioPlayer($audioBool, $audioSrc) : ''?>
			
			<div class="section_paragraph">
				
				<?=isset($rows) ? $text : ''?>
				
			</div>
			
		</section>
		
	</main>

	<footer>
				
		<?=generateFooter()?>
				
	</footer>
	
	<script type="module" src="/priculjica/assets/js/story.js"></script>
	
</body>
</html>
