<?php header('Content-Type: text/html; charset=UTF-8');
    $linkTemplate = '';
    $links = '';

    function linkTemplate($templateLink, $templateNaslov, $templateTekst, $templateImage, $templateAlt) { //Template for the link
        $templateLink = $templateLink ? htmlspecialchars($templateLink) : '';
        $templateNaslov = $templateNaslov ? htmlspecialchars($templateNaslov) : '';
        $templateTekst = $templateTekst ? limitText($templateTekst, 20) : '';
        $templateImage = $templateImage ? htmlspecialchars($templateImage) : '';
        $templateAlt = $templateAlt ? htmlspecialchars($templateAlt) : '';

        $sessionNaslov = isset($_SESSION['naslov']) ? $_SESSION['naslov'] : '';
        $sessionSlika = isset($_SESSION['slika']) && !empty($_SESSION['slika']) ? '<img src="' . htmlspecialchars($_SESSION['slika']) . '" alt="' . htmlspecialchars($_SESSION['alt']) . '">' : '';

        $templateLinkCondition = $templateLink === 'admin-nova-prica' ? '<a href="/priculjica/admin/admin-nova-prica" class="preview_link">' : 
        '<a href="/priculjica/admin/admin-nova-prica/' . setChar($templateLink) . '">'; //Condition for the preview and database data

        $templateImageCondition = $templateImage === $sessionNaslov ? $sessionSlika : '<img src="' . $templateImage . '" alt="' . $templateAlt . '">'; //Condition for the preview and database data

        $linkTemplate = '' . $templateLinkCondition . '
                <div class="section_content_text_wrapper">
                    <h2>'. $templateNaslov . '</h2>
                    <p>'. limitText($templateTekst, 20) .'</p>
                </div>
                <div class="section_content_image_wrapper">
                    ' . $templateImageCondition . '
                </div>
            </a>'; //Link template

        return $linkTemplate;
    }

    function createLinks($rows, $links) { //Creates the links on page load and when the search keyword is empty
        if (isset($_SESSION['naslov'], $_SESSION['tekst'])) {
            if (!empty($_SESSION['naslov'] || !empty($_SESSION['tekst'] || (isset($_SESSION['audio']) && $_SESSION['audio'] === true)))) { //If the session variables for title and text contain something or when the audio file is added display the preview link
                $links .= linkTemplate('admin-nova-prica', $_SESSION['naslov'], $_SESSION['tekst'], $_SESSION['naslov'], '');
            }
        }

        if (isset($rows)) { //Display the links for the data from the database
            foreach ($rows as $row) {
                if ($row) {
                    $links .= linkTemplate($row["naslov"], $row['naslov'], $row['tekst'], $row['put_do_slike'], $row['alt']);
                }
            }
        }

        return $links;
    }