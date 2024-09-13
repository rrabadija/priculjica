<?php header('Content-Type: text/html; charset=UTF-8');
	require 'php/connect.php';
    require 'php/string.php';
    require 'php/header.php';
	require 'php/footer.php';
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
<link href="/priculjica/style/pisi-mi.css" rel="stylesheet" type="text/css">
<link href="/priculjica/style/footer.css" rel="stylesheet" type="text/css">
<link href="/priculjica/font/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	
</head>

<body>
	
	<header>

		<?=generateHeader($title = ['POČETNA', 'NOVA PRIČA', 'OSTALE PRIČE', 'O MENI'], $headerURL)?>

	</header>
	
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
			
				<img src="/priculjica/img/pisi-mi.jpg" alt="Poštanski sandučić vjeverice Pričuljice smješten u šumi i okružen gljivama i cvijećem.">
			
			</div>
			
		</div>
		
	</main>

	<footer>

		<?=generateFooter(true)?>

	</footer>
	
	<script src="/priculjica/script/header.js"></script>
	
</body>
</html>
