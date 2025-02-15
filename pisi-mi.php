<?php header('Content-Type: text/html; charset=UTF-8');
	require_once 'php/template.php';
    require_once 'php/header.php';
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
<link href="/app/css/pisi-mi.css" rel="stylesheet" type="text/css">
	
</head>

<body>
	
	<?=Header::generateHeader('pisi-mi')?>
	
	<main>
		
		<div class="main_input_wrapper">

			<h1>PIŠI MI</h1>

			<p>

				Moj online poštanski sandučić ti je uvijek otvoren!<br> 
				Slobodno se javi ako želiš <strong>postaviti pitanje, podijeliti dojmove</strong> ili <strong>pozdraviti.</strong><br> 
				Naravno, možeš se javiti i ako želiš <strong>originalnu, personaliziranu priču za dijete.</strong><br> 
				Kao prava Pričuljica, ispričat ću posebnu priču o djetetu koristeći informacije koje mi napišeš.<br> 
				<strong>Veselim se tvojoj poruci!</strong>

			</p>
			
			<div class="main_input_wrapper_row">
			
				<input type="text" placeholder="Ime">
			
				<input type="text" placeholder="Prezime">
				
			</div>
			
			<input type="email" placeholder="E-mail">
			
			<textarea placeholder="Poruka"></textarea>
					
			<button></button>
			
		</div>
		
		<div class="main_image_wrapper">
			
			<div class="main_image_wrapper_image">
			
				<img src="/assets/images/pisi-mi.jpg" alt="Poštanski sandučić vjeverice Pričuljice smješten u šumi i okružen gljivama i cvijećem.">
			
			</div>
			
		</div>
		
	</main>

	<?=Template::render('footer.html', ['date' => date("Y"), 'footer.p' => setLanguage("footer.p")])?>

	<?=Login::generateLogin()?>
	
	<script type="module" src="/app/js/header.js"></script>
	
</body>
</html>
