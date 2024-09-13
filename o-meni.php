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
<link href="/priculjica/style/o-meni.css" rel="stylesheet" type="text/css">
<link href="/priculjica/style/footer.css" rel="stylesheet" type="text/css">
<link href="/priculjica/font/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	
</head>

<body>
	
	<header>

		<?=generateHeader($title = ['POČETNA', 'NOVA PRIČA', 'OSTALE PRIČE', 'PIŠI MI'], $headerURL)?>
		
	</header>
	
	<main>
		
		<section>
			
			<div class="section_row">
				
				<div class="section_row_bubble" tabindex="0" aria-label="Slika">
					
					<img src="/priculjica/img/o-meni.png" alt="Na otvorenoj knjizi sjede s lijeve strane ilustrirana vjeverica s naočalama koja čita knjigu te s desne strane Antonia Šikljan - autorica Pričuljice i njenih priča.">
					
				</div>
				
				<div class="section_row_bubble" tabindex="0" aria-label="Paragraf">
					
					<p>
						
						Zdravo! Zahvaljujući mami i tati nosim ime <strong>Antonia Šikljan</strong> koje ste možda zapazili u knjižarama ili 
						knjižnicama ako ste naišli na slikovnicu <strong>„Slonić Nonić i izgubljeni pilić“.</strong> 
						
					</p>
					
					<img class="section_row_bubble_image section_row_bubble_image_1" src="/priculjica/img/slonic-nonic.png" alt="">
					
				</div>
				
			</div>
			
			<div class="section_row">
				
				<div class="section_row_bubble" tabindex="0" aria-label="Paragraf">
					
					<p>
						
						Po zanimanju sam <strong>copywriterica</strong> što znači da veliku većinu svojih dana provodim pišući i smišljajući kreativne koncepte.
						
					</p>
					
					<img class="section_row_bubble_image section_row_bubble_image_2" src="/priculjica/img/priculjica-copywriter-o-meni.png" alt="">
						
				</div>
				
				<div class="section_row_bubble" tabindex="0" aria-label="Paragraf">
					
					<p>
					
						Po hobijima sam uzgajivačica sobnih biljaka, ljubiteljica životinja i prirode, čitateljica, biciklistica, 
						maštateljica o nekom sporijem životu i ponosna skrbnica crno-bijele mačke imena Mau.
						
					</p>
					
					<img class="section_row_bubble_image section_row_bubble_image_3" src="/priculjica/img/mau-o-meni.png" alt="">
					
				</div>
				
			</div>
			
			<div class="section_row">
				
				<div class="section_row_bubble" tabindex="0" aria-label="Paragraf">
					
					<p>
						
						<strong>Odrastala sam u malenom gradu u blizini šume</strong> uz jednu od starijih sestara koja je hranila moj znatiželjan 
						dječji um pričama o patuljku koji živi u grmu u našem dvorištu. 

					</p>
					
					<img class="section_row_bubble_image section_row_bubble_image_4" src="/priculjica/img/patuljak-o-meni.png" alt="">
					
				</div>
				
				<div class="section_row_bubble" tabindex="0" aria-label="Paragraf">
					
					<p>
						
						Stoga nije ni čudo da sam <strong>odmalena zaljubljena u priče</strong> - baš poput vjeverice Pričuljice. Ona je, kao što ste već sigurno zaključili - zapravo ja. 
						
					</p>
					
					<img class="section_row_bubble_image section_row_bubble_image_5" src="/priculjica/img/priculjica-zrcalo-o-meni.png" alt="">
					
				</div>
				
			</div>
			
			<footer>
				
				<?=generateFooter(true)?>
				
			</footer>
			
		</section>
		
	</main>
	
	<script src="/priculjica/script/header.js"></script>
	
</body>
</html>
