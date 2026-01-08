<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="./src/output.css" rel="stylesheet">
</head>

<body class="bg-[#2B2D31] text-white flex flex-col justify-center items-center h-screen">
    <h1 class="text-5xl text-center justify-center pb-20 bg-linear-to-r from-[#6A1E70] via-[#821E50] to-[#284C62] inline-block text-transparent bg-clip-text ">Créer un compte</h1>

    <form class="flex flex-col gap-4 text-center pb-5" action="./process/processajtcompte.php" method="POST">

        <input name="nom" id="nom" type="text"
            class="w-full p-4 text-1xl sm:text-2xl md:text-3xl font-thin rounded-3xl bg-linear-to-r from-[#6A1E70] via-[#821E50] to-[#284C62] text-white placeholder-57595E "
            placeholder="nom" type="text">
        <input name="motdepasse" id="motdepasse" type="password"
            class="w-full p-4 text-1xl sm:text-2xl md:text-3xl font-thin rounded-3xl bg-linear-to-r from-[#6A1E70] via-[#821E50] to-[#284C62] text-white placeholder-57595E "
            placeholder="motdepasse" type="text">
        <button class="w-full p-4 text-1xl sm:text-2xl md:text-3xl font-thin rounded-3xl bg-linear-to-r from-[#6A1E70] via-[#821E50] to-[#284C62] text-white" type="submit">Créer le compte</button>
    </form>
    <a class="bg-linear-to-r from-[#6A1E70] via-[#821E50] to-[#284C62] inline-block text-transparent bg-clip-text" href="./login.php">Deja un compte? viens la</a>
</body>

</html>
