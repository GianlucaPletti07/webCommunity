<?php
session_start();
global $conn;
require_once "utilities.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $password = password_hash(trim($_POST["password"]), PASSWORD_DEFAULT);
    $nickname = trim($_POST["username"]);


    $sql = "
    SELECT *
    FROM utente
    WHERE password = :password
    AND nickname = :nickname";

    $stmt = $conn->prepare($sql);
    $stmt->execute(['idEvento' => $idEvento]);

    // Pulizia dei dati
    $nome = trim($_POST["nome"]);
    $cognome = trim($_POST["cognome"]);
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $provincia = trim($_POST["provincia"]);
    $categorie = isset($_POST["interessi"]) ? array_map('trim', $_POST["interessi"]) : [];
    $password = password_hash(trim($_POST["password"]), PASSWORD_DEFAULT); // hash della password

    $stmtInsert = $conn->prepare("
        INSERT INTO utente(nickname, nome, cognome, email, provincia, password)
        VALUES (:nickname, :nome, :cognome, :email,:provincia, :password)
    ");

    $stmtInsert->bindParam(':nickname', $username);
    $stmtInsert->bindParam(':nome', $nome);
    $stmtInsert->bindParam(':cognome', $cognome);
    $stmtInsert->bindParam(':email', $email);
    $stmtInsert->bindParam(':provincia', $provincia);
    $stmtInsert->bindParam(':password', $password);

    $stmtInsert->execute();

    foreach ($categorie as $categoria){
        $stmtInsert = $conn->prepare("
            INSERT INTO membro_categoria(nickname, codCategoria)
            VALUES (:nickname, :codCategoria)
        ");
        $stmtInsert->bindParam(':nickname', $username);
        $stmtInsert->bindParam(':codCategoria', $categoria);

        $stmtInsert->execute();
    }

// Salvataggio in sessione
    $_SESSION["utente"] = [
        "nome" => $nome,
        "cognome" => $cognome,
        "username" => $username,
        "email" => $email,
        "provincia" => $provincia,
        "categorie" => $categorie,
        "password" => $password
    ];

    // Password: MAI in chiaro
    $_SESSION["utente"]["password"] = password_hash($_POST["password"], PASSWORD_DEFAULT);

    // (opzionale) redirect
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
