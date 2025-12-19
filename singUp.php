<?php

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
            <span class="label-text">Provincia</span>
        </label>
        select
    </div>

    <button type="submit" class="btn btn-primary">
        Sign-up
    </button>
    </form>
</body>
</html>
