<?php header('Content-Type: text/html; charset=UTF-8');
    require_once 'php/header.php';
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
<link href="/priculjica/assets/css/o-meni.css" rel="stylesheet" type="text/css">
	
</head>

<body>
	
	<header>

		<?=$header -> generateHeader([setLanguage("header.a-1"), setLanguage("header.a-2"), setLanguage("header.a-3"), setLanguage("header.a-5")])?>
		
	</header>
	
	<main>
		
		<section>
			
			<div class="section_row">
				
				<div class="section_row_bubble" tabindex="0" aria-label="Slika">
					
					<img src="/priculjica/assets/images/o-meni.png" alt="Na otvorenoj knjizi sjede s lijeve strane ilustrirana vjeverica s naočalama koja čita knjigu te s desne strane Antonia Šikljan - autorica Pričuljice i njenih priča.">
					
				</div>
				
				<div class="section_row_bubble" tabindex="0" aria-label="Paragraf">
					
					<p>
						
						Zdravo! Zahvaljujući mami i tati nosim ime <strong>Antonia Šikljan</strong> koje ste možda zapazili u knjižarama ili 
						knjižnicama ako ste naišli na slikovnicu <strong>„Slonić Nonić i izgubljeni pilić“.</strong> 
						
					</p>
					
					<img class="section_row_bubble_image section_row_bubble_image_1" src="/priculjica/assets/images/slonic-nonic.png" alt="">
					
				</div>
				
			</div>
			
			<div class="section_row">
				
				<div class="section_row_bubble" tabindex="0" aria-label="Paragraf">
					
					<p>
						
						Po zanimanju sam <strong>copywriterica</strong> što znači da veliku većinu svojih dana provodim pišući i smišljajući kreativne koncepte.
						
					</p>
					
					<img class="section_row_bubble_image section_row_bubble_image_2" src="/priculjica/assets/images/priculjica-copywriter-o-meni.png" alt="">
						
				</div>
				
				<div class="section_row_bubble" tabindex="0" aria-label="Paragraf">
					
					<p>
					
						Po hobijima sam uzgajivačica sobnih biljaka, ljubiteljica životinja i prirode, čitateljica, biciklistica, 
						maštateljica o nekom sporijem životu i ponosna skrbnica crno-bijele mačke imena Mau.
						
					</p>
					
					<img class="section_row_bubble_image section_row_bubble_image_3" src="/priculjica/assets/images/mau-o-meni.png" alt="">
					
				</div>
				
			</div>
			
			<div class="section_row">
				
				<div class="section_row_bubble" tabindex="0" aria-label="Paragraf">
					
					<p>
						
						<strong>Odrastala sam u malenom gradu u blizini šume</strong> uz jednu od starijih sestara koja je hranila moj znatiželjan 
						dječji um pričama o patuljku koji živi u grmu u našem dvorištu. 

					</p>
					
					<img class="section_row_bubble_image section_row_bubble_image_4" src="/priculjica/assets/images/patuljak-o-meni.png" alt="">
					
				</div>
				
				<div class="section_row_bubble" tabindex="0" aria-label="Paragraf">
					
					<p>
						
						Stoga nije ni čudo da sam <strong>odmalena zaljubljena u priče</strong> - baš poput vjeverice Pričuljice. Ona je, kao što ste već sigurno zaključili - zapravo ja. 
						
					</p>
					
					<img class="section_row_bubble_image section_row_bubble_image_5" src="/priculjica/assets/images/priculjica-zrcalo-o-meni.png" alt="">
					
				</div>
				
			</div>
			
			<footer>
				
				<?=generateFooter()?>
				
			</footer>
			
		</section>
		
	</main>
	
	<script type="module" src="/priculjica/assets/js/header.js"></script>
	
</body>
</html>
