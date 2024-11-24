<?php header('Content-Type: text/html; charset=UTF-8');
    require_once '../php/header.php';
	require_once '../php/circle.php';
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

<link rel="apple-touch-icon" sizes="180x180" href="/priculjica/assets/favicons/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/priculjica/assets/favicons/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/priculjica/assets/favicons/favicon-16x16.png">
<link rel="manifest" href="/priculjica/assets/favicon/site.webmanifest">
<link href="/priculjica/assets/css/index.css" rel="stylesheet" type="text/css">
	
</head>

<body>
	
	<header>
		
		<?=$header -> generateHeader([setLanguage("header.a-2"), setLanguage("header.a-3"), setLanguage("header.a-4"), setLanguage("header.a-5")])?>

	</header>
	
	<main>
		
		<section>
			
			<div class="section_1_content_wrapper">
				
				<div class="section_1_content_text_wrapper">
					
					<div class="section_1_content_header">

						<h1><?=setLanguage("index.h1")?><h1>
						
					</div>
					
					<div class="section_1_content_paragraph">
					
						<p><?=setLanguage("index.p-1")?></p>
						
					</div>
					
					<div class="section_1_content_button">

						<a href="/priculjica/admin/nova-prica" tabindex="-1">

							<button></button>

						</a>
						
					</div>
					
				</div>
				
				<div class="section_1_content_image_wrapper">
					
					<div class="section_1_content_circle_image_wrapper">
						
						<div class="section_1_content_circle_image">
							
							<img src="/priculjica/assets/images/priculjica.png" alt="<?=setLanguage("index.img-alt-1", true)?>">
							
						</div>
						
					</div>
					
				</div>
				
			</div>
			
		</section>
		
		<section>
			
			<div class="section_2_content_wrapper">
				
				<div class="section_2_content_image_wrapper">

					<div class="section_2_content_circle_image">

						<?=$circle -> generateCircle(-1)?>

					</div>

					<div class="section_2_content_circle_image">

						<?=$circle -> generateCircle(0)?>

					</div>

					<div class="section_2_content_circle_image">

						<?=$circle -> generateCircle(1)?>

					</div>
						
				</div>
				
			</div>
			
		</section>
		
		<section>
			
			<div class="section_3_content_wrapper">
				
				<div class="section_3_content_text_wrapper">
					
					<div class="section_3_content_text">

						<div class="keen-slider">

							<div class="keen-slider__slide">
						
								<p><?=setLanguage("index.p-2", true)?></p>

							</div>

							<div class="keen-slider__slide">

								<p><?=setLanguage("index.p-3", true)?></p>

							</div>

							<div class="keen-slider__button">

								<button>
								
									<i class="fa fa-long-arrow-right"></i>

								</button>

							</div>

						</div>
						
					</div>
					
				</div>
				
				<div class="section_3_content_image_wrapper">
					
					<img src="/priculjica/assets/images/o-meni-landing-page.png" alt="">
					
				</div>
				
			</div>
			
			<div class="observed observed_section_3"></div>
			
		</section>
		
	</main>

	<footer>

		<?=generateFooter()?>

	</footer>
	
	<script type="module" src="/priculjica/assets/js/index.js"></script>
	
</body>
</html>
