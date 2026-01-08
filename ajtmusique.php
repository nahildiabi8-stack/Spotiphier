<?php
session_start();
require_once "./utils/connexion.php";
$trucpafetcher = $db->query("SELECT * FROM musiques");
$trucbon = $trucpafetcher->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./src/output.css">
</head>

<body class="bg-[#2B2D31] p-8 ">


    <section class="flex flex-col items-center ">
        <div class=" flex flex-col  w-96 h-128 text-center pt-25 bg-gradient-to-r from-[#6A1E70] via-[#821E50] to-[#284C62]">
            <form class="flex flex-col justify-center items-center text-white gap-8" action="process/processajtmusique.php" method="POST" enctype="multipart/form-data">

                <input class="text-center" type="text" name="titre" placeholder="nom d'la musique" required>

                <input class="text-center" name="description" placeholder="la description "></input>

                <input class="text-center" name="Album" placeholder="nom de l'album "></input>

                  <input class="text-center" name="Artiste" placeholder="nom de l'artiste "></input>

                <input class="text-center pb-10" type="file" name="musique_file" accept="audio/*" required>

                <button type="submit">Ajouter</button>
                
                  <a class="text-white inline-block text-transparent bg-clip-text pt-4 pb-4" href="./pagemain.php">reviens en arriere
            </a>
            </form>
           
        </div>

           

    </section>



</body>

</html>