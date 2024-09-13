<?php header('Content-Type: text/html; charset=UTF-8');
    $stmt = $pdo->prepare("SELECT naslov FROM price ORDER BY id DESC LIMIT 1"); //Get the last title from the database for the 'NOVA PRIČA' header link

    $stmt->execute();
    $headerURL = $stmt->fetch(PDO::FETCH_ASSOC);

    function generateHeader($t, $headerURL) { //Generates the header HTML
        $header = '<div class="header_logo_wrapper">
			
                            <button>
                            
                                <img src="/priculjica/img/priculjica-logo.png" alt="">  
                                
                            </button>
                            
                        </div>
                        
                        <div class="header_links_wrapper">';
                            
                            foreach ($t as $l) { //Loop for generating the links inside the header
                                if ($l === 'POČETNA') {
                                    $hyperLink = '<a href="/priculjica">';
                                }
                                else if ($l === 'NOVA PRIČA') {
                                    $hyperLink = (isset($headerURL['naslov'])) ? '<a href="' . strtolower(setChar($l)) . '/' . setChar(htmlspecialchars($headerURL['naslov'])) . '">' :
                                    '<a href="nova-prica/">';
                                }
                                else {
                                    $hyperLink = '<a href="' . strtolower(setChar($l)) . '">';
                                }

                                $header .= $hyperLink .

                                                '<div class="header_links_dot"></div>

                                                ' . $l . '

                                            </a>';
                            }
                            
                        $header .= '    <button class="header_aside_button">

			                                <i class="fa fa-cog"></i>

			                                <i class="fa fa-close"></i>

		                                </button>

                                    </div>
                                    
                                    <aside>

                                        <select class="header_aside_language_select">

                                            <option value="hr">Hrvatski</option>
                                            <option value="en">Engleski</option>

                                        </select>

                                    </aside>';
            
        return $header;
    }