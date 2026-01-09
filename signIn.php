<?php
session_start();
global $conn;
require_once "utilities.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $password = trim($_POST["password"]);
    $nickname = trim($_POST["username"]);

    $stmt = $conn->prepare("
        SELECT *
        FROM utente
        WHERE nickname = :nickname
    ");

    $stmt->bindParam(':nickname', $nickname);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!empty($result) && password_verify($password, $result["password"]) ){
        // Salvataggio in sessione
        $_SESSION["utente"] = [
                "nome" => $result["nome"],
                "cognome" => $result["cognome"],
                "username" => $result["nickname"],
                "email" => $result["email"],
                "provincia" => $result["provincia"],
                "categorie" => []
        ];

        $stmt = $conn->prepare("
        SELECT c.codCategoria
        FROM categoria c, membro_categoria mc
        WHERE mc.nickname = :nickname
        AND c.codCategoria = mc.codCategoria
    ");

        $stmt->bindParam(':nickname', $nickname);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if(!empty($result))
            $_SESSION["utente"]["categorie"] = $result;

        // (opzionale) redirect
        header("Location: visualizzazione_eventi.php");
        exit;
    }
    else
        echo "Errore nel login";



}


?>

<!doctype html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sing-in</title>
    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- DaisyUI -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.4.24/dist/full.min.css" rel="stylesheet">
</head>
<body>
<form method="POST" class="flex flex-col sm:flex-row gap-4 items-end">


    <div class="form-control w-full sm:w-auto">
        <label class="label">
            <span class="label-text">Nome utente</span>
        </label>
        <input
            type="text"
            name="username"
            required
            placeholder="Inserire username"
            class="input input-bordered w-full"
        >
    </div>


    <div class="form-control w-full sm:w-auto">
        <label class="label">
            <span class="label-text">Password</span>
        </label>
        <input
            type="password"
            name="password"
            required
            placeholder="Inserire password"
            class="input input-bordered w-full"
        >
    </div>


    <button type="submit" class="btn btn-primary">
        Sign-in
    </button>
</form>
</body>
</html>
