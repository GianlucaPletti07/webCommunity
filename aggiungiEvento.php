<?php
global $conn;
include "utilities.php";
session_start();

if (!isset($_SESSION['utente'])) {
    die("<h1>Non sei loggato! Non puoi aggiungere nessun evento</h1>");
}
$nickname = $_SESSION['utente']['nickname'];
$stmt = $conn->query("
    SELECT *
    FROM categoria
    ORDER BY descrizione;
");
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);


$stmt1 = $conn->query("
    SELECT nome
    FROM provincia
");
$province = $stmt1->fetchAll(PDO::FETCH_ASSOC);



if($_SERVER["REQUEST_METHOD"] === "POST"){

    $titolo    = trim($_POST["titolo"]);
    $data      = trim($_POST["data"]);
    $provincia = trim($_POST["provincia"]);
    $categoria = isset($_POST['categoria']) ? $_POST['categoria'] : null;


    if (empty($titolo) || empty($data) || empty($provincia) || empty($categoria)) {
        die("Errore: tutti i campi sono obbligatori!");
    }

    $stmtCheck = $conn->prepare("
        SELECT COUNT(*) 
        FROM evento 
        WHERE titolo = :titolo AND data = :data
    ");
    $stmtCheck->execute([
        ':titolo' => $titolo,
        ':data' => $data
    ]);
    $count = $stmtCheck->fetchColumn();

    if ($count > 0) {
        die("Errore: esiste già un evento con lo stesso titolo in questa data!");
    }

    $stmtInsert = $conn->prepare("
        INSERT INTO evento (titolo, data, luogo, codCategoria, nickname)
        VALUES (:titolo, :data, :luogo, :codCategoria, :nickname)
    ");

    $stmtInsert->bindParam(':nickname', $nickname);
    $stmtInsert->bindParam(':titolo', $titolo);
    $stmtInsert->bindParam(':data', $data);
    $stmtInsert->bindParam(':luogo', $provincia);
    $stmtInsert->bindParam(':codCategoria', $categoria);

    $stmtInsert->execute();

    header("Location: visualizzazione_eventi.php");
    exit;

}


?>


<!doctype html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sing-up</title>
    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- DaisyUI -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.4.24/dist/full.min.css" rel="stylesheet">
</head>
<body>
<form method="POST" class="flex flex-col sm:flex-row gap-4 items-end">

    <div class="form-control w-full sm:w-auto">
        <label class="label">
            <span class="label-text">Titolo</span>
        </label>
        <input
            type="text"
            name="titolo"
            required
            placeholder="Inserire titolo evento"
            class="input input-bordered w-full"
        >
    </div>

    <div class="form-control w-full sm:w-auto">
        <label class="label">
            <span class="label-text">Inserire Data</span>
        </label>
        <input
            type="date"
            name="data"
            required
            placeholder="Inserire data"
            class="input input-bordered w-full"
        >
    </div>



    <div class="form-control w-full sm:w-auto">
        <label class="label">
            <span class="label-text">Provincia</span>
        </label>
        <select class="select select-bordered w-full" name="provincia" id="provincia">
            <option disabled selected>Seleziona una provincia</option>
            <?php
            foreach($province as $p){
                echo "<option>" . $p['nome'] . "</option>";
            }
            ?>
        </select>
    </div>

    <div class="dropdown w-full">
        <label tabindex="0" class="btn w-full">Seleziona categoria</label>
        <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-full">
            <?php foreach ($result as $categoria): ?>
                <li>
                    <label>
                        <input type="radio" name="categoria" value="<?= htmlspecialchars($categoria['codCategoria']) ?>">
                        <?= htmlspecialchars($categoria['descrizione']) ?>
                    </label>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>




    <button type="submit" class="btn btn-primary">
        Aggiungi
    </button>
</form>
</body>
</html>
