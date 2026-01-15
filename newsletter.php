<link rel="stylesheet" href="style.css">
<?php
global $conn;
include "utilities.php";
$messaggio = "";
$result = null;
session_start();

if (!isset($_SESSION['utente'])) {
    die("<h1>Non sei loggato! Non puoi vedere la newsletter</h1>");
}
$provincia = $_SESSION['utente']['provincia'];

$sql = "SELECT * FROM evento WHERE luogo = :provincia AND data BETWEEN CURRENT_DATE AND CURRENT_DATE+7";
$stmt = $conn->prepare($sql);
$stmt->bindparam(":provincia", $provincia);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php if (empty($result)): ?>
    <p>Nessun evento nei prossimi 7 giorni nella provincia di <?=$provincia?>.</p>
<?php else: ?>
    <h1>Gli eventi nella tua provincia che si svolgeranno entro settimana prossima sono: <?=count($result)?></h1>
    <div class="eventi-container">
        <?php foreach ($result as $evento): ?>
            <div class="evento-card">
                <h3><?= htmlspecialchars($evento['titolo']) ?></h3>
                <p><strong>Luogo:</strong> <?= htmlspecialchars($evento['luogo']) ?></p>
                <p><strong>Data:</strong> <?= date("d/m/Y", strtotime($evento['data'])) ?></p>
                <p><?= nl2br(htmlspecialchars($evento['descrizione'] ?? "")) ?></p>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

