<?php header('Content-Type: text/html; charset=UTF-8');
    require (__DIR__ . "/../../php/connect.php");

    $table = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['keywordInfo'])) {
        echo generateTable($pdo, false);
    }
    else if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['empty_search_info'])) {
        echo generateTable($pdo, true);
    }

    function generateTable($pdo, $default) {
        $sql = "SELECT price.naslov, price.audio, price.timestamp_created, price.timestamp_edited, price.pogledano_puta,
        audio.trajanje, audio.poslusano_puta, (COALESCE(audio.trajanje, 0) * COALESCE(audio.poslusano_puta, 0) * 0.85) AS ukupno_poslusano_puta
        FROM price
        LEFT JOIN audio ON price.naslov = audio.naslov";

        if (!$default) {
            $sql .= " WHERE price.naslov LIKE ?";
        }

        $stmt = $pdo -> prepare($sql);

        if (!$default) {
            $keyword = htmlspecialchars($_POST['keywordInfo'] ?? '');
            $keywordLike = '%' . $keyword . '%';

            $stmt -> bindValue(1, $keywordLike, PDO::PARAM_STR);
        }

        $stmt -> execute();

        $rows = $stmt -> fetchAll(PDO::FETCH_ASSOC);

        $table = '<table>
                    <tr>
                        <th>Naslov</th>
                        <th>Datum kreiranja</th>
                        <th>Datum zadnjeg uređivanja</th>
                        <th>Pogledano puta</th>
                        <th>Trajanje audio zapisa</th>
                        <th>Poslušano puta</th>
                        <th>Ukupno poslušano puta</th>
                    </tr>';

        foreach ($rows as $row) {
            $duration = '-';
        
            if ($row['audio'] === 1) {
                $min = floor($row['trajanje'] / 60);
                $sec = $row['trajanje'] % 60;
                $duration = "$min:$sec";
            }

            $table .= '<tr>
                            <td>' . $row['naslov'] . '</td>
                            <td>' . $row['timestamp_created'] . '</td>
                            <td>' . $row['timestamp_edited'] . '</td>
                            <td>' . $row['pogledano_puta'] . '</td>
                            <td>' . $duration . '</td>
                            <td>' . (($row['audio'] === 1) ? $row['poslusano_puta'] : '-') . '</td>
                            <td>' . (($row['audio'] === 1) ? intval($row['ukupno_poslusano_puta']) : '-') . '</td>
                        </tr>';
        }

        return $table .= '</table>';
    }