<?php
global $conn;
include "utilities.php";
session_start();


if (!isset($_SESSION['utente'])) {
    die("<h1>Non sei loggato! Non puoi aggiungere nessun commento</h1>");
}


if (!isset($_GET['idEvento'])) {
    die("Evento non specificato");
}

$idEvento = (int) $_GET['idEvento'];
$nickname = $_SESSION['utente']['nickname'];

if (isset($_POST['voto'], $_POST['commento'])) {

    $voto = (int) $_POST['voto'];
    $commento = $_POST['commento'];

    if ($voto < 1 || $voto > 10) {
        die("Voto non valido");
    }

    $sql = "INSERT INTO post (nickname, idEvento, commento, voto)
            VALUES (:nickname, :idEvento, :commento, :voto)";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":nickname", $nickname, PDO::PARAM_STR);
    $stmt->bindParam(":idEvento", $idEvento, PDO::PARAM_INT);
    $stmt->bindParam(":commento", $commento, PDO::PARAM_STR);
    $stmt->bindParam(":voto", $voto, PDO::PARAM_INT);

    $stmt->execute();

    header("Location: commenti.php?idEvento=" . $idEvento);
    exit;
}
?>

<form method="post">
    <label>Voto:</label>
    <select name="voto" required>
        <option value="">-- Seleziona --</option>
        <?php
        for ($i = 1; $i <= 10; $i++) {
            echo "<option value='$i'>$i</option>";
        }
        ?>
    </select>

    <br><br>

    <label>Commento:</label>
    <input type="text" name="commento" required>

    <br><br>

    <button type="submit">Invia</button>
</form>
