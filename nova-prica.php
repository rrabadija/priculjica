<?php header('Content-Type: text/html; charset=UTF-8');
	require_once 'php/header.php';
	require_once 'php/toolbar.php';
	require_once 'php/story.php';
	require_once 'php/footer.php';
	require_once 'php/language.php';
	require_once 'php/login.php';
?>
<!doctype html>
<html lang="<?=$_SESSION['language']?>">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pričuljica<?=$_SESSION['user_role'] === 'admin' ? ' - Admin' : ''?></title>

<script src="/app/js/theme.js"></script>

<link rel="apple-touch-icon" sizes="180x180" href="/assets/favicons/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/assets/favicons/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/assets/favicons/favicon-16x16.png">
<link rel="manifest" href="/assets/favicon/site.webmanifest">
<link href="/app/css/nova-prica.css" rel="stylesheet" type="text/css">

<?=$_SESSION['user_role'] === 'admin' ? "<link href='/app/css/toolbar.css' rel='stylesheet' type='text/css'>" : ''?>

</head>

<body>
	
	<header>
		
		<?=$header -> generateHeader([setLanguage("header.a-1"), setLanguage("header.a-3"), setLanguage("header.a-4"), setLanguage("header.a-5")])?>

	</header>

	<?=$_SESSION['user_role'] === 'admin' ? $toolbar -> generateToolbarStory() : ''?>
	
	<main>
		
		<section>
			
			<div class="section_header">
				
				<h1 <?=$_SESSION['user_role'] === 'admin' ? "contenteditable='true' spellcheck='false' data-placeholder-title='Naslov...'" : ''?>>

					<?=$_SESSION['user_role'] === 'user' ? (isset($rows) ? $title : '') : $_SESSION['title'] ?? ''?>

				</h1>
				
			</div>

			<?=$_SESSION['user_role'] === 'user' ? (isset($rows) ? audioPlayer($audioBool, $audioSrc) : '') : audioPlayer($_SESSION['audioBool'] ?? '', $_SESSION['audioSrc'] ?? '')?>
			
			<div <?=$_SESSION['user_role'] === 'admin' ? "contenteditable='true' spellcheck='false' data-placeholder-title='Tekst...'" : ''?> class="section_paragraph">
				
				<?=$_SESSION['user_role'] === 'user' ? (isset($rows) ? $text : '') : $_SESSION['text'] ?? ''?>
				
			</div>
			
		</section>
		
	</main>

	<footer>
				
		<?=generateFooter()?>
				
	</footer>

	<?=$login -> generateLogin()?>
	
	<script type="module" src="/app/js/story.js"></script>

	<?=$_SESSION['user_role'] === 'admin' ? "<script type='module' src='/app/js/editor.js'></script>" : ''?>
	
</body>
</html>
