<?php header('Content-Type: text/html; charset=UTF-8');
	require 'php/connect.php';
    require 'php/string.php';
    require 'php/header.php';
	require 'php/footer.php';
	require 'php/translate.php';

    $stmt = $pdo -> prepare("SELECT price.naslov, price.tekst, slike.put_do_slike, slike.alt 
    FROM price 
    LEFT JOIN slike ON price.naslov = slike.naslov 
    ORDER BY price.id DESC LIMIT 3"); //Get columns from the table

    $stmt -> execute();

	$rows = $stmt -> fetchAll(PDO::FETCH_ASSOC);
	
	$URL = setChar(htmlspecialchars($rows[0]["naslov"] ?? ''));

    if (isset($rows)) {
		function generateCircle($rows, $i) {
			$title = htmlspecialchars($rows[$i]['naslov'] ?? '');
			$text = $rows[$i]['tekst'] ?? '';
			$image = htmlspecialchars($rows[$i]['put_do_slike'] ?? '');
			$imageAlt = htmlspecialchars($rows[$i]['alt'] ?? '');

			$circle = '<div class="section_2_content_circle_image_text_wrapper">

							<h2>' . $title . '</h2>

							<p>' . limitText($text, 9) . '</p>

							<div class="section_1_content_button">

								<a href="nova-prica/' . setChar($title) . '" tabindex="-1">
				
									<button></button>

								</a>
							
							</div>

						</div>';
					
			if ($image !== '') {
				$circle .= '<img src="' . $image . '" alt="' . ($imageAlt !== '' ? $imageAlt : '') . '">';
			}

			return $circle;
		}
    }
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
<link href="/priculjica/style/index.css" rel="stylesheet" type="text/css">
<link href="/priculjica/font/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	
</head>

<body>
	
	<header>
		
		<?=generateHeader($title = ['NOVA PRIČA', 'OSTALE PRIČE', 'O MENI', 'PIŠI MI'], $headerURL)?>

	</header>
	
	<main>
		
		<section>
			
			<div class="section_1_content_wrapper">
				
				<div class="section_1_content_text_wrapper">
					
					<div class="section_1_content_header">

						<h1><?=getTranslation($pdo, "index.h1", true)?><h1>
						
					</div>
					
					<div class="section_1_content_paragraph">
					
						<p><?=getTranslation($pdo, "index.p-1", true)?></p>
						
					</div>
					
					<div class="section_1_content_button">

						<a href="<?=$URL ? 'nova-prica/' . $URL : 'nova-prica/'?>" tabindex="-1">

							<button></button>

						</a>
						
					</div>
					
				</div>
				
				<div class="section_1_content_image_wrapper">
					
					<div class="section_1_content_circle_image_wrapper">
						
						<div class="section_1_content_circle_image">
							
							<img src="/priculjica/img/priculjica.png" alt="<?=getTranslation($pdo, "index.img-alt-1", true)?>">
							
						</div>
						
					</div>
					
				</div>
				
			</div>
			
		</section>
		
		<section>
			
			<div class="section_2_content_wrapper">
				
				<div class="section_2_content_image_wrapper">

					<div class="section_2_content_circle_image">

						<?=(isset($rows[0])) ? generateCircle($rows, 0) : ''?>

					</div>

					<div class="section_2_content_circle_image">

						<?=(isset($rows[1])) ? generateCircle($rows, 1) : ''?>

					</div>

					<div class="section_2_content_circle_image">

						<?=(isset($rows[2])) ? generateCircle($rows, 2) : ''?>

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
						
								<p><?=getTranslation($pdo, "index.p-2", true)?></p>

							</div>

							<div class="keen-slider__slide">

								<p><?=getTranslation($pdo, "index.p-3", true)?></p>

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
					
					<img src="/priculjica/img/o-meni-landing-page.png" alt="">
					
				</div>
				
			</div>
			
			<div class="observed observed_section_3"></div>
			
		</section>
		
	</main>

	<footer>

		<?=generateFooter(true)?>

	</footer>
	
	<script src="/priculjica/script/header.js"></script>
	
	<script src="/priculjica/script/index.js"></script>

	<script src="/priculjica/script/keen-slider.js"></script>
	
</body>
</html>
