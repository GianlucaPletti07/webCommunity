<?php
global $conn;
include "utilities.php";
$messaggio = "";
$result = null;


$sql = "SELECT idEvento, titolo AS Titolo, luogo AS Luogo, data AS Data, codCategoria AS Categoria, nickname as Nickname
        FROM evento WHERE 1=1";

$parametri = [];

if (!empty($_GET['luogo'])) {
    $sql .= " AND luogo = :luogo";
    $parametri[':luogo'] = $_GET['luogo'];
}

if (!empty($_GET['categoria'])) {
    $sql .= " AND codCategoria = :categoria";
    $parametri[':categoria'] = $_GET['categoria'];
}

$sql .= " ORDER BY Data";

$stmt = $conn->prepare($sql);
$stmt->execute($parametri);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<form method="get">
    Luogo:
    <select name="luogo">
        <option value="">Tutti</option>
        <?php
        $luoghi = $conn->query("SELECT DISTINCT luogo FROM evento ORDER BY luogo");
        foreach ($luoghi->fetchAll(PDO::FETCH_ASSOC) as $l) {
            $selected = (!empty($_GET['luogo']) && $_GET['luogo'] == $l['luogo']) ? "selected" : "";
            echo "<option value='{$l['luogo']}' $selected>{$l['luogo']}</option>";
        }
        ?>
    </select>

    Categoria:
    <select name="categoria">
        <option value="">Tutte</option>
        <?php
        $cat = $conn->query("SELECT codCategoria, descrizione FROM categoria");
        foreach ($cat->fetchAll(PDO::FETCH_ASSOC) as $c) {
            $selected = (!empty($_GET['categoria']) && $_GET['categoria'] == $c['codCategoria']) ? "selected" : "";
            echo "<option value='{$c['codCategoria']}' $selected>{$c['descrizione']}</option>";
        }
        ?>
    </select>

    <button type="submit">Filtra</button>
</form>

<button><a href="newsletter.php">NewsLetter</a></button>
<button><a href="aggiungiEvento.php">Aggiungi Evento</a></button>

<br>


<?php

if ($result) {
    echo "<table><tr>";
    echo "<th>Titolo</th>";
    echo "<th>Luogo</th>";
    echo "<th>Data</th>";
    echo "<th>Categoria</th>";
    echo "<th>Nickname</th>";
    echo "<th>Commenti</th>";
    echo "</tr>";

    foreach ($result as $row) {
        echo "<tr>";
        echo "<td>" . $row['Titolo'] . "</td>";
        echo "<td>" . $row['Luogo'] . "</td>";
        $data = new DateTime($row['Data']);
        $dataItaliana = $data->format('d-m-Y');
        echo "<td>$dataItaliana</td>";
        $sql1 = "SELECT descrizione FROM categoria WHERE codCategoria = :codiceCategoria";
        $stmtCat = $conn->prepare($sql1);
        $stmtCat->bindParam(":codiceCategoria", $row['Categoria']);
        $stmtCat->execute();
        $resultCat = $stmtCat->fetchAll(PDO::FETCH_ASSOC);
        echo "<td>" . $resultCat[0]['descrizione'] . "</td>";
        echo "<td>" . $row['Nickname'] . "</td>";
        echo "<td>
        <a href='commenti.php?idEvento=" . $row['idEvento'] . "'>
            <button>Visualizza</button>
        </a>
      </td>";

        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<div class='no-results'>Nessun risultato trovato.</div>";
}
?>
