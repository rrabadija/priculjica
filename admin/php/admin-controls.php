<?php header('Content-Type: text/html; charset=UTF-8');
    require (__DIR__ . "/../../php/connect.php");

    $condition = '<br><p><span><img><strong><em>';

    try {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['buttonId'])) { //Get data from admin.html with AJAX
            $buttonId = isset($_POST['buttonId']) ? intval($_POST['buttonId']) : null; //Ensure that buttonId is an integer

            $naslov = trim(htmlspecialchars($_POST['title'])) ?? '';
            $tekst = trim(strip_tags($_POST['textarea'], $condition)) ?? '';
            $slika = trim(htmlspecialchars($_POST['image'])) ?? '';
            $alt = trim(htmlspecialchars($_POST['alt'])) ?? '';

            $audio = trim(htmlspecialchars(filter_var($_POST['audio'], FILTER_VALIDATE_BOOLEAN))) ?? false;
            $audioSrc = '/priculjica/audio/' . trim(htmlspecialchars($_POST['audioSrc'])) ?? '';
            $audioLen = trim(htmlspecialchars(intval($_POST['audioLen']))) ?? null;

            if ($buttonId === 1) { //Save text, image and audio to the database
                $stmt = $pdo -> prepare("SELECT naslov FROM price WHERE naslov LIKE ?"); //Check if the title already exists in the database

                $stmt -> bindValue(1, $naslov, PDO::PARAM_STR);
                $stmt -> execute();

                $naslovCheck = $stmt -> fetch(PDO::FETCH_ASSOC);

                if (!$naslovCheck) {
                    if (isset($naslov) && !empty($naslov)) {
                        $stmt = $pdo -> prepare("INSERT INTO price (naslov, tekst, audio) VALUES (?, ?, ?)"); //Insert into the price table

                        $stmt -> bindValue(1, $naslov, PDO::PARAM_STR);
                        $stmt -> bindValue(2, $tekst, PDO::PARAM_STR);
                        $stmt -> bindValue(3, $audio, PDO::PARAM_INT);
                        $stmt -> execute();
                        
                        echo "Upisana priča!\n";
                    }

                    if (isset($slika) && !empty($slika)) { //Insert into the slike table
                        $stmt = $pdo -> prepare("INSERT INTO slike (naslov, put_do_slike, alt) VALUES (?, ?, ?)");

                        $stmt -> bindValue(1, $naslov, PDO::PARAM_STR);
                        $stmt -> bindValue(2, $slika, PDO::PARAM_STR);
                        $stmt -> bindValue(3, $alt, PDO::PARAM_STR);
                        $stmt -> execute();

                        echo "Upisana slika!\n";
                    }

                    if (isset($audio, $audioSrc, $audioLen) && $audio && !empty($audioSrc) && !empty($audioLen)) { //Insert into the audio table
                        $stmt = $pdo -> prepare("INSERT INTO audio (naslov, put_do_audio, trajanje) VALUES (?, ?, ?)");

                        $stmt -> bindValue(1, $naslov, PDO::PARAM_STR);
                        $stmt -> bindValue(2, $audioSrc, PDO::PARAM_STR);
                        $stmt -> bindValue(3, $audioLen, PDO::PARAM_INT);
                        $stmt -> execute();

                        echo "Upisan audio!\n";
                    }
                }
                else {
                    echo "Takav zapis već postoji!\n";
                }
            }
            else if ($buttonId === 2) { //Edit text and image from the database
                $edit = htmlspecialchars($_POST['edit']) ?? '';

                $stmt1 = $pdo -> prepare("SELECT naslov FROM slike WHERE naslov LIKE ?"); //Check if the title already exists in the slike table, if not add a record

                $stmt1 -> bindValue(1, $edit, PDO::PARAM_STR);
                $stmt1 -> execute();

                $naslovSlikaCheck = $stmt1 -> fetch(PDO::FETCH_ASSOC);

                $stmt2 = $pdo -> prepare("SELECT naslov FROM audio WHERE naslov LIKE ?"); //Check if the title already exists in the audio table, if not add a record

                $stmt2 -> bindValue(1, $edit, PDO::PARAM_STR);
                $stmt2 -> execute();

                $naslovAudioCheck = $stmt2 -> fetch(PDO::FETCH_ASSOC);

                if (!empty($edit)) {
                    if (isset($naslov) && !empty($naslov)) { //Edit prica table
                        $stmt = $pdo -> prepare("UPDATE price SET naslov = ?, tekst = ?, audio = ? WHERE naslov = ?");

                        $stmt -> bindValue(1, $naslov, PDO::PARAM_STR);
                        $stmt -> bindValue(2, $tekst, PDO::PARAM_STR);
                        $stmt -> bindValue(3, $audio, PDO::PARAM_INT);
                        $stmt -> bindValue(4, $edit, PDO::PARAM_STR);
                        $stmt -> execute();

                        echo "Promijenjena priča!\n";
                    }

                    if (isset($slika) && !empty($slika)) { //Edit slike table
                        if (isset($naslovSlikaCheck) && !$naslovSlikaCheck) { //If slike table does not have the matching title, write the record
                            $stmt = $pdo -> prepare("INSERT INTO slike (naslov, put_do_slike, alt) VALUES (?, ?, ?)");
    
                            $stmt -> bindValue(1, $naslov, PDO::PARAM_STR);
                            $stmt -> bindValue(2, $slika, PDO::PARAM_STR);
                            $stmt -> bindValue(3, $alt, PDO::PARAM_STR);
                            $stmt -> execute();
    
                            echo "Upisana slika!\n";
                        }
                        else {
                            $stmt = $pdo -> prepare("UPDATE slike SET naslov = ?, put_do_slike = ?, alt = ? WHERE naslov = ?");

                            $stmt -> bindValue(1, $naslov, PDO::PARAM_STR);
                            $stmt -> bindValue(2, $slika, PDO::PARAM_STR);
                            $stmt -> bindValue(3, $alt, PDO::PARAM_STR);
                            $stmt -> bindValue(4, $edit, PDO::PARAM_STR);
                            $stmt -> execute();

                            echo "Promijenjena slika!\n";
                        }
                    }
                    else if (isset($naslovSlikaCheck) && $naslovSlikaCheck) { //If the image is removed from the text, delete its record from the slike table
                        $stmt = $pdo -> prepare("DELETE FROM slike WHERE naslov = ?");

                        $stmt -> bindValue(1, $edit, PDO::PARAM_STR);
                        $stmt -> execute();

                        echo "Izbrisana slika!\n";
                    }

                    if (isset($audio, $audioSrc, $audioLen) && $audio && !empty($audioSrc) && !empty($audioLen)) { //Edit audio table
                        if (isset($naslovAudioCheck) && !$naslovAudioCheck) { //If audio table does not have the matching title, write the record
                            $stmt = $pdo -> prepare("INSERT INTO audio (naslov, put_do_audio, trajanje) VALUES (?, ?, ?)");
    
                            $stmt -> bindValue(1, $naslov, PDO::PARAM_STR);
                            $stmt -> bindValue(2, $audioSrc, PDO::PARAM_STR);
                            $stmt -> bindValue(3, $audioLen, PDO::PARAM_INT);
                            $stmt -> execute();
    
                            echo "Upisan audio!\n";
                        }
                        else {
                            $stmt = $pdo -> prepare("UPDATE audio SET naslov = ?, put_do_audio = ?, trajanje = ? WHERE naslov = ?");

                            $stmt -> bindValue(1, $naslov, PDO::PARAM_STR);
                            $stmt -> bindValue(2, $audioSrc, PDO::PARAM_STR);
                            $stmt -> bindValue(3, $audioLen, PDO::PARAM_INT);
                            $stmt -> bindValue(4, $edit, PDO::PARAM_STR);
                            $stmt -> execute();

                            echo "Promijenjen audio!\n";
                        }
                    }
                    else if (isset($naslovAudioCheck) && $naslovAudioCheck) { //If the audio is removed, delete its record from the audio table
                        $stmt = $pdo -> prepare("DELETE FROM audio WHERE naslov = ?");

                        $stmt -> bindValue(1, $edit, PDO::PARAM_STR);
                        $stmt -> execute();

                        echo "Izbrisan audio!\n";
                    }
                }
            }
            else if ($buttonId === 3) { //Delete text and image from the database
                $izbrisi = htmlspecialchars($_POST['delete']) ?? '';

                if (!empty($izbrisi)) {
                    $stmt = $pdo -> prepare("DELETE FROM price WHERE naslov = ?; DELETE FROM slike WHERE naslov = ?; DELETE FROM audio WHERE naslov = ?");
                    $stmt -> bindParam(1, $izbrisi, PDO::PARAM_STR);
                    $stmt -> bindParam(2, $izbrisi, PDO::PARAM_STR);
                    $stmt -> bindParam(3, $izbrisi, PDO::PARAM_STR);
                    $stmt -> execute();

                    echo "Izbrisan zapis!\n";
                }
            }
        }

    } catch (PDOException $e) {
        die("Connection failed: " . $e -> getMessage());
        echo $sql . "<br>" . $e -> getMessage();
    }