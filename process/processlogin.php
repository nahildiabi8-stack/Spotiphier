<?php
session_start();
require_once "../utils/connexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nom = $_POST['nom'] ?? '';
    $motdepasse = $_POST['motdepasse'] ?? '';

    $stmt = $db->prepare("
        SELECT * FROM utilisateurs
        WHERE nom = :nom
    ");

    $stmt->execute([
        'nom' => $nom
    ]);

    $legars = $stmt->fetch();

    if (password_verify($motdepasse, $legars['motdepasse'])) {
        $_SESSION['user_id'] = $legars['id'];
        $_SESSION['nom'] = $legars['nom'];
        

        header("Location: ../pagemain.php");
        exit;
    } else {
        header("Location: ../erreurlogin.html");
        exit;
    }
}
