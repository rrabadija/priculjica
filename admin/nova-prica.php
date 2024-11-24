<?php header('Content-Type: text/html; charset=UTF-8');
	require_once '../php/header.php';
	require_once '../php/toolbar.php';
	require_once '../php/story.php';
	require_once '../php/footer.php';
	require_once '../php/language.php';
?>
<!doctype html>
<html lang="<?=$_SESSION['language']?>">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pričuljica - Admin</title>

<base href="/priculjica/">

<script src="/priculjica/assets/js/theme.js"></script>
	
<base href="/priculjica/">
<link rel="apple-touch-icon" sizes="180x180" href="/priculjica/assets/favicons/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/priculjica/assets/favicons/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/priculjica/assets/favicons/favicon-16x16.png">
<link rel="manifest" href="/priculjica/assets/favicon/site.webmanifest">
<link href="/priculjica/assets/css/toolbar.css" rel="stylesheet" type="text/css">

</head>

<body>
	
	<header>
		
		<?=$header -> generateHeader([setLanguage("header.a-1", true), setLanguage("header.a-3", true), setLanguage("header.a-4", true), setLanguage("header.a-5", true)])?>

	</header>

	<?=$toolbar -> generateToolbarStory()?>
	
	<main>
		
		<section>
			
			<div class="section_header">
				
				<h1 contenteditable="true" spellcheck="false" data-placeholder-title="Naslov..."><?=isset($rows) ? $title : sanitize($_SESSION['title'] ?? '')?></h1>
				
			</div>

			<?php isset($rows) ? audioPlayer($audioBool, $audioSrc) : (isset($_SESSION['audio_src']) ? audioPlayer($_SESSION['audio_bool'], $_SESSION['audio_src']) : '')?>
			
			<div contenteditable="true" spellcheck="false" data-placeholder-paragraph="Tekst..." class="section_paragraph">
				
				<?=isset($rows) ? $text : sanitizeHTML($_SESSION['text'] ?? '')?>
				
			</div>
			
		</section>
		
	</main>

	<footer>
				
		<?=generateFooter()?>
				
	</footer>
	
	<script type="module" src="/priculjica/assets/js/editor.js"></script>
	
</body>
</html>
