<?php header('Content-Type: text/html; charset=UTF-8');
    function generateHeader($t) { //Generates the header HTML
        $header = '<div class="header_logo_wrapper">
			
                            <button>

                                <img src="/priculjica/img/priculjica-logo.png" alt="">

                            </button>

                        </div>

                        <div class="header_links_wrapper">';

                            foreach ($t as $l) { //Loop for generating the links inside the header
                                $hyperLink = $l === 'NOVA PRIČA' ? '<a href="/priculjica/admin/admin-nova-prica">' :
                                '<a href="/priculjica/admin/admin-' . strtolower(setChar($l)) . '">';

                                if ($l === 'POČETNA') {
                                    $hyperLink = '<a href="/priculjica/admin/admin">';
                                }
                                else if ($l === 'O MENI' || $l === 'PIŠI MI') {
                                    $hyperLink = $l === 'O MENI' ? '<a href="/priculjica/o-meni">' : '<a href="/priculjica/pisi-mi">';
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