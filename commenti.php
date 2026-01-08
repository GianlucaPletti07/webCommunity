<?php
global $conn;
include "utilities.php";
if (!isset($_GET['idEvento'])) {
    die("Evento non specificato");
}

$idEvento = (int) $_GET['idEvento'];

// ===============================
// 3. QUERY COMMENTI (SENZA JOIN)
// ===============================
$sql = "
    SELECT nickname, commento, voto
    FROM post
    WHERE idEvento = :idEvento
";

$stmt = $conn->prepare($sql);
$stmt->execute(['idEvento' => $idEvento]);

$commenti = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Commenti evento</title>

    <style>
        body { font-family: Arial, sans-serif; }
        .commento {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div style="display: flex; gap: 2rem">
    <h2>Commenti dell'evento</h2>
    <button><a href="aggiungiCommento.php">Aggiungi</a></button>
</div>

<?php if (count($commenti) === 0): ?>
    <p>Nessun commento per questo evento.</p>
<?php else: ?>
    <?php foreach ($commenti as $c): ?>
        <div class="commento">
            <strong>Utente:</strong>
            <?= htmlspecialchars($c['nickname']) ?><br>

            <strong>Commento:</strong>
            <?= htmlspecialchars($c['commento']) ?><br>

            <strong>Voto:</strong>
            <?= htmlspecialchars($c['voto']) ?>/10
        </div>
    <?php endforeach; ?>
<?php endif; ?>

</body>
</html>
