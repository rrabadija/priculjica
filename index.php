<?php header('Content-Type: text/html; charset=UTF-8');
	require_once 'php/template.php';
    require_once 'php/header.php';
	require_once 'php/circle.php';
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
<link href="/app/css/index.css" rel="stylesheet" type="text/css">
	
</head>

<body>
	
	<?=Header::generateHeader('index')?>
	
	<main>
		
		<section>
			
			<div class="section-1--text">
					
				<h1><?=setLanguage("index.h1")?></h1>
					
				<p><?=setLanguage("index.p-1")?></p>
					
				<a href="<?=$_SESSION['user_role'] === 'user' ? ($rows[0]["title"] ?? '' ? 'nova-prica/' . setChar(sanitize($rows[0]["title"])) : 'nova-prica') : ('nova-prica')?>" tabindex="-1">

					<button class="button-pill"><i></i></button>

				</a>
					
			</div>
				
			<div class="section-1--image">
							
				<img src="/assets/images/priculjica.png" alt="<?=setLanguage("index.img-alt-1")?>">
					
			</div>
			
		</section>

		<?=Circle::generateCircle()?>
		
		<section>

			<div class="section-3--text keen-slider">
						
				<p class="keen-slider__slide"><?=setLanguage("index.p-2")?></p>

				<p class="keen-slider__slide"><?=setLanguage("index.p-3")?></p>

				<button><i class="fa fa-long-arrow-right"></i></button>

			</div>
				
			<div class="section-3--image">

				<img src="/assets/images/o-meni-landing-page.png" alt="">

			</div>
			
			<div class="section-3--observed"></div>
			
		</section>
		
	</main>

	<?=Template::render('footer.html', ['date' => date("Y"), 'footer.p' => setLanguage("footer.p")])?>

	<?=Login::generateLogin()?>
	
	<script type="module" src="/app/js/index.js"></script>
	
</body>
</html>
