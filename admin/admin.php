<?php header('Content-Type: text/html; charset=UTF-8');
	require '../php/connect.php';
    require '../php/string.php';
	require 'php/admin-header.php';
	require '../php/footer.php';

    session_start(); //Starts the session

    try {
        $stmt = $pdo->prepare("SELECT price.naslov, price.tekst, slike.put_do_slike, slike.alt 
        FROM price 
        LEFT JOIN slike ON price.naslov = slike.naslov 
        ORDER BY price.id DESC LIMIT 3"); //Get columns from the table

        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['title'], $_POST['textarea'])) { //Get data from admin.html with AJAX
            $naslov = $_POST['title'] ?? '';
            $tekst = $_POST['textarea'] ?? '';

            $_SESSION['naslov'] = $naslov; //Session variables
            $_SESSION['tekst'] = $tekst;
        }

		if (isset($_SESSION['naslov'])) { //Generate the preview circle
			function generateCircleSession() {
				$circle = '<div class="section_2_content_circle_image_text_wrapper">
	
								<h2>' . htmlspecialchars($_SESSION['naslov']) . '</h2>
	
								<p>' . limitText($_SESSION['tekst'] ?? '', 12) . '</p>
	
								<div class="section_1_content_button">
	
									<a href="/priculjica/admin/admin-nova-prica" tabindex="-1">
				
										<button></button>
	
									</a>
							
								</div>
	
							</div>';
					
							if (isset($_SESSION['slika']) && !empty($_SESSION['slika'])) { //Do not generate an empty image element if there is no image present
								$circle .= '<img src="' . htmlspecialchars($_SESSION['slika']) . '" alt="' . htmlspecialchars($_SESSION['alt']) . '">';
							}
	
				return $circle;
			}
		}

        if (isset($rows)) { //Generate the circles with records from the database
			function generateCircle($rows, $i) {
				$circle = '<div class="section_2_content_circle_image_text_wrapper">
	
								<h2>' . htmlspecialchars($rows[$i]['naslov']) . '</h2>
	
								<p>' . limitText($rows[$i]['tekst'], 9) . '</p>
	
								<div class="section_1_content_button">
	
									<a href="/priculjica/admin/admin-nova-prica/' . setChar(htmlspecialchars($rows[$i]["naslov"])) . '" tabindex="-1">
				
										<button></button>
	
									</a>
							
								</div>
	
							</div>';
					
				if ($rows[$i]['put_do_slike'] !== null) { //Do not generate an empty image element if there is no image inside the text record
					$circle .= '<img src="' . htmlspecialchars($rows[$i]['put_do_slike']) . '" alt="' . ($rows[$i]['alt'] !== null ? htmlspecialchars($rows[$i]['alt']) : '') . '">';
				}
	
				return $circle;
			}
        }

    } catch (PDOException $e) {
        die("Error fetching data: " . $e->getMessage());
    }
?>
<!doctype html>
<html lang="hr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pričuljica - Admin</title>
	
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
		
		<?=generateHeader($title = ['NOVA PRIČA', 'OSTALE PRIČE', 'O MENI', 'PIŠI MI'])?>

	</header>
	
	<main>
		
		<section>
			
			<div class="section_1_content_wrapper">
				
				<div class="section_1_content_text_wrapper">
					
					<div class="section_1_content_header">
						
						<h1>UPOZNAJ PRIČULJICU</h1>
						
					</div>
					
					<div class="section_1_content_paragraph">
					
						<p>
						
							Pričuljica je malena vjeverica zaljubljena u čitanje i pripovijedanje. 
							Dok ostale vjeverice trčkaraju šumom u potrazi za žirevima, ona traži priče. 
							Otkada je shvatila da je šuma u kojoj živi puna zanimljivih životinja i njihovih priča, 
							većinu svog vremena provodi kako bi te priče zapisala i ispričala tebi.

						</p>
						
					</div>
					
					<div class="section_1_content_button">

						<a href="/priculjica/admin/admin-nova-prica" tabindex="-1">

							<button></button>

						</a>
						
					</div>
					
				</div>
				
				<div class="section_1_content_image_wrapper">
					
					<div class="section_1_content_circle_image_wrapper">
						
						<div class="section_1_content_circle_image">
							
							<img src="/priculjica/img/priculjica.png" alt="">
							
						</div>
						
					</div>
					
				</div>
				
			</div>
			
		</section>
		
		<section>
			
			<div class="section_2_content_wrapper">
				
				<div class="section_2_content_image_wrapper">
					
					<div class="section_2_content_circle_image">

						<?=(!empty($_SESSION['naslov']) || !empty($_SESSION['tekst']) || !empty($_SESSION['audio'])) ? generateCircleSession() : ''?>
						
					</div>
					
					<div class="section_2_content_circle_image">

						<?=(isset($rows[0])) ? generateCircle($rows, 0) : ''?>

					</div>

					<div class="section_2_content_circle_image">

						<?=(isset($rows[1])) ? generateCircle($rows, 1) : ''?>

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
						
								<p>

									Pričuljica zna da djeca jako vole zanimljivu i zabavnu naraciju i zato je uz svaku priču 
									pripremila i <strong>audio verziju priče s pozadinskom glazbom i posebnim audio efektima.
									</strong> Na taj način dijete će dublje 
									doživjeti i proživjeti priču te lakše shvatiti pouku i poruku priče.

								</p>

							</div>

							<div class="keen-slider__slide">

								<p>

									<strong>Sve naracije snima Pričuljica</strong> glavom, bradom (i kitnjastim repom!) jer je već navikla 
									svoj glas koristiti za pripovijedanje priča mlađim generacijama 
									u svojoj obitelji, no i svima ostalima koji pronalaze užitak u slušanju priča.

								</p>

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
