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

if ($_SERVER["REQUEST_METHOD"] === "POST") {

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
    /*header("Location: conferma.php");
    exit;*/
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
                <span class="label-text">Nome</span>
            </label>
            <input
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

                <option>Agrigento</option>
                <option>Alessandria</option>
                <option>Ancona</option>
                <option>Aosta</option>
                <option>Arezzo</option>
                <option>Ascoli Piceno</option>
                <option>Asti</option>
                <option>Avellino</option>
                <option>Bari</option>
                <option>Barletta-Andria-Trani</option>
                <option>Belluno</option>
                <option>Benevento</option>
                <option>Bergamo</option>
                <option>Biella</option>
                <option>Bologna</option>
                <option>Bolzano</option>
                <option>Brescia</option>
                <option>Brindisi</option>
                <option>Cagliari</option>
                <option>Caltanissetta</option>
                <option>Campobasso</option>
                <option>Caserta</option>
                <option>Catania</option>
                <option>Catanzaro</option>
                <option>Chieti</option>
                <option>Como</option>
                <option>Cosenza</option>
                <option>Cremona</option>
                <option>Crotone</option>
                <option>Cuneo</option>
                <option>Enna</option>
                <option>Fermo</option>
                <option>Ferrara</option>
                <option>Firenze</option>
                <option>Foggia</option>
                <option>Forlì-Cesena</option>
                <option>Frosinone</option>
                <option>Genova</option>
                <option>Gorizia</option>
                <option>Grosseto</option>
                <option>Imperia</option>
                <option>Isernia</option>
                <option>L'Aquila</option>
                <option>La Spezia</option>
                <option>Latina</option>
                <option>Lecce</option>
                <option>Lecco</option>
                <option>Livorno</option>
                <option>Lodi</option>
                <option>Lucca</option>
                <option>Macerata</option>
                <option>Mantova</option>
                <option>Massa-Carrara</option>
                <option>Matera</option>
                <option>Messina</option>
                <option>Milano</option>
                <option>Modena</option>
                <option>Monza e Brianza</option>
                <option>Napoli</option>
                <option>Novara</option>
                <option>Nuoro</option>
                <option>Oristano</option>
                <option>Padova</option>
                <option>Palermo</option>
                <option>Parma</option>
                <option>Pavia</option>
                <option>Perugia</option>
                <option>Pesaro e Urbino</option>
                <option>Pescara</option>
                <option>Piacenza</option>
                <option>Pisa</option>
                <option>Pistoia</option>
                <option>Pordenone</option>
                <option>Potenza</option>
                <option>Prato</option>
                <option>Ragusa</option>
                <option>Ravenna</option>
                <option>Reggio Calabria</option>
                <option>Reggio Emilia</option>
                <option>Rieti</option>
                <option>Rimini</option>
                <option>Roma</option>
                <option>Rovigo</option>
                <option>Salerno</option>
                <option>Sassari</option>
                <option>Savona</option>
                <option>Siena</option>
                <option>Siracusa</option>
                <option>Sondrio</option>
                <option>Sud Sardegna</option>
                <option>Taranto</option>
                <option>Teramo</option>
                <option>Terni</option>
                <option>Torino</option>
                <option>Trapani</option>
                <option>Trento</option>
                <option>Treviso</option>
                <option>Trieste</option>
                <option>Udine</option>
                <option>Varese</option>
                <option>Venezia</option>
                <option>Verbano-Cusio-Ossola</option>
                <option>Vercelli</option>
                <option>Verona</option>
                <option>Vibo Valentia</option>
                <option>Vicenza</option>
                <option>Viterbo</option>
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
</body>
</html>
