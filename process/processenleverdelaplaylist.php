<?php

require_once "../utils/connexion.php";



// var_dump($_POST);
// die();
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $utilisateurs_id = $_SESSION['user_id'] ?? null;
    $musiques_id = $_POST['musiques_id'] ?? null;



    if ($utilisateurs_id === null) {
        die("y'a pas d'utilisateur");
    }

    $stmt = $db->prepare(" DELETE FROM playlist WHERE utilisateurs_id = :utilisateurs_id AND musiques_id = :musiques_id;");

    $stmt->execute([
        'utilisateurs_id' => $utilisateurs_id,
        'musiques_id' => $musiques_id

    ]);

    header("Location: ../profilgars.php");
    exit;
}
