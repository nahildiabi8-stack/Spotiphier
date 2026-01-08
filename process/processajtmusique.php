<?php
require_once "../utils/connexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $description = $_POST['description'] ?? null;
    $Album = $_POST['Album'] ?? null;
    $Artiste = $_POST['Artiste'] ?? null;



    $dossier = "../img/";
    $nomdufichier = uniqid() . "_" . basename($_FILES['musique_file']['name']);
    $destination = $dossier . $nomdufichier;
    $erreur = "j'avais la flemme de faire une page erreur donc, si tu vois sa, le fichier na pas pu etre envoyer ";

    if (move_uploaded_file($_FILES['musique_file']['tmp_name'], $destination)) {

        $stmt = $db->prepare(
            "INSERT INTO musiques (musique, description, Album, Artiste, fichier) VALUES (?, ?, ?, ?, ?)"
        );

        $stmt->execute([
            $_POST['titre'],      
            $description, $Album,$Artiste,
            "img/" . $nomdufichier 
        ]);


        header("Location: ../pagemain.php");
        exit;
    } else {
        echo $erreur;
        exit;
    }
}
