<?php header('Content-Type: text/html; charset=UTF-8');
	require '../php/connect.php';
    require 'php/admin-links.php';
    require '../php/string.php';
	require 'php/admin-header.php';
    require 'php/admin-info.php';
    require '../php/footer.php';

    session_start(); //Starts the session

    $searchLinks = '';

    try {
        $stmt = $pdo->prepare("SELECT price.naslov, price.tekst, slike.put_do_slike, slike.alt 
        FROM price 
        LEFT JOIN slike ON price.naslov = slike.naslov 
        ORDER BY price.id DESC"); //Get columns from the table

        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['title'], $_POST['textarea'])) { //Get data from admin.html with AJAX
            $naslov = $_POST['title'] ?? '';
            $tekst = $_POST['textarea'] ?? '';
        
            $_SESSION['naslov'] = $naslov; //Session variables
            $_SESSION['tekst'] = $tekst;
        }

        if (isset($_POST['keyword'])) { //Generates links on search keyword
            $keyword = htmlspecialchars($_POST['keyword']);

            $stmtSearch = $pdo->prepare("SELECT price.naslov, price.tekst, slike.put_do_slike, slike.alt 
            FROM price 
            LEFT JOIN slike ON price.naslov = slike.naslov 
            WHERE price.naslov LIKE ?"); //Get columns from the table

            $keywordLike = '%' . $keyword . '%';
            $stmtSearch->bindParam(1, $keywordLike, PDO::PARAM_STR);
            $stmtSearch->execute();
            $results = $stmtSearch->fetchAll(PDO::FETCH_ASSOC);

            if ($results) { //Generate the links based on the links.php
                foreach ($results as $result) {
                    $resultNaslov = htmlspecialchars($result['naslov']);
                    $pattern = "/(?:^|\s|&nbsp;)\b" . preg_quote($keyword, '/') . "[\p{L}0-9čćžšđ_;,.]*\b/ui";
                    preg_match($pattern, $resultNaslov, $matches);

                    if(!empty($matches)) {
                        $searchLinks .= linkTemplate($result["naslov"], $result['naslov'], $result['tekst'], $result['put_do_slike'], $result['alt']);
                    }
                }
            }

            echo $searchLinks;
            exit;
        }

        if (isset($_POST['empty_search'])) { //Search keyword is empty, display the initial links
            echo createLinks($rows, $links);
            exit;
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
<link href="/priculjica/style/ostale-price.css" rel="stylesheet" type="text/css">
<link href="/priculjica/admin/style/admin-ostale-price.css" rel="stylesheet" type="text/css">
<link href="/priculjica/style/footer.css" rel="stylesheet" type="text/css">
<link href="/priculjica/font/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

</head>

<body>
	
	<header>
		
		<?=generateHeader($title = ['POČETNA', 'NOVA PRIČA', 'O MENI', 'PIŠI MI'])?>
		
	</header>

    <div class="admin_info_height"></div>

    <div class="admin_info">

        <div class="admin_info_buttons_container">

            <button class="admin_pdf_button"><i class="fa fa-file-pdf-o"></i></button>

            <button class="admin_stats_button"><i class="fa fa-pie-chart"></i></button>

            <button class="admin_stats_button"><i class="fa fa-sort-alpha-asc"></i></button>

            <button class="admin_search_button"><i class="fa fa-search"></i></button>

        </div>

    </div>

    <div class="admin_info_data_container">

        <div class="admin_info_data_table">

            <?=generateTable($pdo, true)?>

        </div>

    </div>

    <button class="admin_info_button">

        <i class="fa fa-info"></i>

    </button>
	
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

    <script src="/priculjica/admin/script/admin-ostale-price.js"></script>

	<script>

		const searchInput = document.querySelector('input');
		const searchResults = document.querySelector('section');
        const infoResults = document.querySelector('.admin_info_data_table');
		
		searchInput.addEventListener('input', function() {
			var keyword = searchInput.value.trim();

			var xhrSearch = new XMLHttpRequest(); //AJAX for admin-ostale-price.php
            xhrSearch.open('POST', '/priculjica/admin/admin-ostale-price.php', true);
            xhrSearch.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhrSearch.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
                    console.log("Search results:", this.responseText);
					searchResults.innerHTML = this.responseText;
				}
            };

            var xhrInfo = new XMLHttpRequest(); //AJAX for admin-info.php
            xhrInfo.open('POST', '/priculjica/admin/php/admin-info.php', true);
            xhrInfo.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhrInfo.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
                    console.log("Info results:", this.responseText);
					infoResults.innerHTML = this.responseText;
				}
            };

			if (keyword !== '') {
				xhrSearch.send('keyword=' + encodeURIComponent(keyword));
                xhrInfo.send('keywordInfo=' + encodeURIComponent(keyword));
			}
			else { //Keyword is empty, send a flag to display the initial links
				searchResults.innerHTML = '';
				xhrSearch.send('empty_search=true');
                xhrInfo.send('empty_search_info=true');
			}
		});

	</script>
	
</body>
</html>
