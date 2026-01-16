<?php
session_start();
global $conn;
require_once "utilities.php";

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


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    //Controlli


    // Pulizia dei dati
    $nome = trim(isset($_POST["nome"]) ? $_POST["nome"] : '');
    $cognome = trim(isset($_POST["cognome"]) ? $_POST["cognome"] : '');
    $username = trim(isset($_POST["username"]) ? $_POST["username"] : '');
    $email = trim(isset($_POST["email"]) ? $_POST["email"] : '');
    $provincia = trim(isset($_POST["provincia"]) ? $_POST["provincia"] : '');
    $categorie = isset($_POST["interessi"]) ? array_map('trim', $_POST["interessi"]) : [];
    $password_raw = password_hash(trim(isset($_POST["password"]) ? $_POST["password"] : ''), PASSWORD_DEFAULT); // hash della password

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
            "nickname" => $username,
            "email" => $email,
            "provincia" => $provincia,
            "categorie" => $categorie,
    ];

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
    <form id="form-iscrizione" method="POST" class="flex flex-col sm:flex-row gap-4 items-end">

        <div class="form-control w-full sm:w-auto">
            <label class="label">
                <span class="label-text">Nome</span>
            </label>
            <input
                    id="nome"
                    type="text"
                    name="nome"
                    required
                    placeholder="Inserire nome"
                    class="input input-bordered w-full"
            >
        </div>

        <div class="form-control w-full sm:w-auto">
            <label class="label">
                <span class="label-text">Cognome</span>
            </label>
            <input
                id="cognome"
                type="text"
                name="cognome"
                required
                placeholder="Inserire cognome"
                class="input input-bordered w-full"
            >
        </div>

    <div class="form-control w-full sm:w-auto">
        <label class="label">
            <span class="label-text">Nome utente</span>
        </label>
        <input
            id="nickname"
            type="text"
            name="username"
            required
            placeholder="Inserire username"
            class="input input-bordered w-full"
        >
    </div>

    <div class="form-control w-full sm:w-auto">
        <label class="label">
            <span class="label-text">Email</span>
        </label>
        <input
                id="email"
                type="text"
                name="email"
                required
                placeholder="Inserire una email vaida"
                class="input input-bordered w-full"
        >
    </div>

    <div class="form-control w-full sm:w-auto">
        <label class="label">
            <span class="label-text">Password</span>
        </label>
        <input
            id="password"
            type="password"
            name="password"
            required
            placeholder="Inserire password"
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
            <label tabindex="0" class="btn w-full">Seleziona categorie</label>
            <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-full">
                <?php
                foreach ($result as $categoria) {
                    echo "<li>
            <label>
                <input type='checkbox' name='interessi[]' value='" . htmlspecialchars($categoria['codCategoria']) . "'>
                " . htmlspecialchars($categoria['descrizione']) . "
            </label>
          </li>";
                }
                ?>

            </ul>
        </div>



        <button type="submit" class="btn btn-primary">
        Sign-up
    </button>
    </form>

    <script>
        document.getElementById("form-iscrizione").addEventListener("submit", function (e) {
            let nome = document.getElementById("nome").value.trim();
            let cognome = document.getElementById("cognome").value.trim();
            let nickname = document.getElementById("nickname").value.trim();
            let password = document.getElementById("password").value;
            let email = document.getElementById("email").value.trim();
            let errors = [];
            if (nome.length > 20) {
                errors.push("Il nome è troppo lungo");
            }
            if (cognome.length > 20) {
                errors.push("Il cognome è troppo lungo");
            }
            if (nickname.length > 20) {
                errors.push("Il nickname è troppo lungo");
            }
            if (password.length < 8) {
                errors.push("La password deve avere almeno 8 caratteri");
            }
            if (!/[A-Z]/.test(password)) {
                errors.push("La password deve contenere una lettera maiuscola");
            }
            if (!/[a-z]/.test(password)) {
                errors.push("La password deve contenere una lettera minuscola");
            }
            if (!/[0-9]/.test(password)) {
                errors.push("La password deve contenere un numero");
            }
            if (!/[^a-zA-Z0-9]/.test(password)) {
                errors.push("La password deve contenere un carattere speciale");
            }
            let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                errors.push("Email non valida");
            }
            if(errors.length > 0){
                e.preventDefault();
                alert(errors.join("\n"));
            }
        });
    </script>
</body>
</html>
