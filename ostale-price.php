<?php header('Content-Type: text/html; charset=UTF-8');
    require_once 'php/header.php';
	require_once 'php/search.php';
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
<link href="/app/css/ostale-price.css" rel="stylesheet" type="text/css">

</head>

<body>
	
	<header>

		<?=$header -> generateHeader([setLanguage("header.a-1"), setLanguage("header.a-2"), setLanguage("header.a-4"), setLanguage("header.a-5")])?>

	</header>
	
	<main>

        <p class="main_text">

            Ovdje možeš pronaći sve priče koje je Pričuljica do sada ispričala.<br>
            Uživaj u čitanju i slušanju priča!

        </p>
		
		<div class="main_searchbar_wrapper">
			
			<input type="text" placeholder="Traži priču" spellcheck="false">
			
		</div>
		
		<section>

			<?=$anchor -> generateAnchor()?>

		</section>
		
	</main>

    <footer>
				
        <?=generateFooter()?>
				
	</footer>

	<?=$login -> generateLogin()?>
	
	<script type="module" src="/app/js/header.js"></script>

	<script src="/app/js/search.js"></script>
	
</body>
</html>
