<?php
session_start();
require_once "./utils/connexion.php";
$trucpafetcher = $db->query("SELECT * FROM musiques");
$trucbon = $trucpafetcher->fetchAll(PDO::FETCH_ASSOC);

if (!isset($_SESSION['nom'])) {
    header("Location: ./login.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./src/output.css">
</head>

<body class="bg-[#2B2D31] p-8 rounded-md ">


    <section class="flex flex-col items-center rounded-md ">
        <div class=" flex flex-col  w-196 h-196 text-center pt-25 bg-linear-to-r from-[#6A1E70] via-[#821E50] to-[#284C62]">
            <form class="flex flex-col justify-center items-center text-white gap-8" action="process/processajtmusique.php" method="POST" enctype="multipart/form-data">

                <input class="text-center bg-white/10 hover:bg-white/20 px-6 py-3 rounded-md text-white flex items-center gap-3 mt-4" type="text" name="titre" placeholder="nom d'la musique" required>

                <input class="text-center bg-white/10 hover:bg-white/20 px-6 py-3 rounded-md text-white flex items-center gap-3 mt-4" name="description" placeholder="la description "></input>

                <input class="text-center bg-white/10 hover:bg-white/20 px-6 py-3 rounded-md text-white flex items-center gap-3 mt-4" name="Album" placeholder="nom de l'album "></input>

                <input class="text-center bg-white/10 hover:bg-white/20 px-6 py-3 rounded-md text-white flex items-center gap-3 mt-4" name="Artiste" placeholder="nom de l'artiste "></input>
                  
                <input id="musique_file" class="hidden" type="file" name="musique_file" accept="audio/*" required>

                <label for="musique_file" class="cursor-pointer bg-white/10 hover:bg-white/20 px-6 py-3 rounded-md text-white flex items-center gap-3 mt-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white/90" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V7.414A2 2 0 0016.586 6L13 2.414A2 2 0 0011.586 2H4zm5 3a1 1 0 012 0v3h2a1 1 0 110 2h-2v3a1 1 0 11-2 0v-3H7a1 1 0 110-2h2V6z" clip-rule="evenodd" />
                    </svg>
                    <span id="file_label">Choisir un fichier</span>
                </label>

                <p id="file_name" class="text-sm text-white/80 mt-2">Aucun fichier choisi</p>

                <button type="submit" class="mt-4 bg-white/10 hover:bg-white/20 text-white px-8 py-3 rounded-full">Ajouter</button>

                <a class="text-white font-thin inline-block bg-clip-text pt-4 pb-15" href="./pagemain.php">Reviens
                </a>
            </form>

        </div>



    </section>


    <script>
        (function(){
            const fileInput = document.getElementById('musique_file');
            const fileLabel = document.getElementById('file_label');
            const fileName = document.getElementById('file_name');
            if (!fileInput) return;
            fileInput.addEventListener('change', () => {
                if (fileInput.files && fileInput.files.length) {
                    fileLabel.textContent = 'Fichier sélectionné';
                    fileName.textContent = fileInput.files[0].name;
                } else {
                    fileLabel.textContent = 'Choisir un fichier';
                    fileName.textContent = 'Aucun fichier choisi';
                }
            });
        })();
    </script>

</body>

</html>