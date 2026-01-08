
<?php

require_once "../utils/connexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nom    = $_POST['nom'] ?? null;
    $motdepasse = $_POST['motdepasse'] ?? null;

    $stmt = $db->prepare("
        INSERT INTO utilisateurs (nom, motdepasse)
        VALUES (:nom, :motdepasse)
    ");
    
    $stmt->execute([
        'nom'  => $nom,
        'motdepasse' => password_hash($motdepasse, PASSWORD_DEFAULT)
    ]);
   header("Location: ../login.php");
}

?>