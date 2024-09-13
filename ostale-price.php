<?php header('Content-Type: text/html; charset=UTF-8');
	require 'php/connect.php';
    require 'php/links.php';
    require 'php/string.php';
    require 'php/header.php';
    require 'php/footer.php';

    $searchLinks = '';

    $stmt = $pdo -> prepare("SELECT price.naslov, price.tekst, slike.put_do_slike, slike.alt 
    FROM price 
    LEFT JOIN slike ON price.naslov = slike.naslov 
    ORDER BY price.id DESC"); //Get columns from the table

    $stmt -> execute();

    $rows = $stmt -> fetchAll(PDO::FETCH_ASSOC);

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['keyword'])) { //Generates links on search keyword
        $keyword = htmlspecialchars($_POST['keyword'] ?? '');
        $keyword = '%' . $keyword . '%';

        $stmt = $pdo -> prepare("SELECT price.naslov, price.tekst, slike.put_do_slike, slike.alt 
        FROM price 
        LEFT JOIN slike ON price.naslov = slike.naslov 
        WHERE price.naslov LIKE ?");

        $stmt -> bindParam(1, $keyword, PDO::PARAM_STR);
        $stmt-> execute();

        $results = $stmt -> fetchAll(PDO::FETCH_ASSOC);

        if ($results) {
            foreach ($results as $result) {
                $title = htmlspecialchars($result['naslov'] ?? '');
                $text = strip_tags($result['tekst'] ?? '', '<br>');
                $image = htmlspecialchars($result['put_do_slike'] ?? '');
                $imageAlt = htmlspecialchars($result['alt'] ?? '');

                $pattern = "/(?:^|\s|&nbsp;)\b" . preg_quote($keyword, '/') . "[\p{L}0-9čćžšđ_;,.]*\b/ui";

                preg_match($pattern, $title, $matches);

                if ($matches) {
                    $searchLinks .= linkTemplate($title, $text, $image, $imageAlt);
                }
            }
        }

		echo $searchLinks;

        exit;
    }

    if (isset($_POST['empty_search'])) { //Search keyword is empty
        echo createLinks($rows, $links);

        exit;
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
<link href="/priculjica/style/ostale-price.css" rel="stylesheet" type="text/css">
<link href="/priculjica/style/footer.css" rel="stylesheet" type="text/css">
<link href="/priculjica/font/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

</head>

<body>
	
	<header>

		<?=generateHeader($title = ['POČETNA', 'NOVA PRIČA', 'O MENI', 'PIŠI MI'], $headerURL)?>

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

			<?=createLinks($rows, $links)?>

		</section>
		
	</main>

    <footer>
				
        <?=generateFooter(true)?>
				
	</footer>
	
	<script src="/priculjica/script/header.js"></script>

	<script>

		const searchInput = document.querySelector('input');
		const searchResults = document.querySelector('section');
		
		searchInput.addEventListener('input', function() {
			var keyword = searchInput.value.trim();

			var xhrSearch = new XMLHttpRequest(); //AJAX for admin_index.php
            xhrSearch.open('POST', '/priculjica/ostale-price.php', true);
            xhrSearch.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhrSearch.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					searchResults.innerHTML = this.responseText;
				}
            };

			if (keyword !== '') {
				xhrSearch.send('keyword=' + encodeURIComponent(keyword));
			}
			else { //Keyword is empty
				searchResults.innerHTML = '';
				xhrSearch.send('empty_search=true');
			}
		});

	</script>
	
</body>
</html>
