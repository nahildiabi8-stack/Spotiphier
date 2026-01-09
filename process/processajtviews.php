<?php
session_start();
require_once "../utils/connexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['musiques_id'])) {
    $musiquesId = $_POST['musiques_id'];
    
    try {
        $stmt = $db->prepare("
            UPDATE musiques 
            SET views = views + 1 
            WHERE id = ?
        ");
        $stmt->execute([$musiquesId]);
        // Si l'utilisateur est connecté, on met à jour/insère la statistique
        if (isset($_SESSION['nom'])) {
            // Récupérer l'id de l'utilisateur
            $userStmt = $db->prepare("SELECT id FROM utilisateurs WHERE nom = ? LIMIT 1");
            $userStmt->execute([$_SESSION['nom']]);
            $user = $userStmt->fetch(PDO::FETCH_ASSOC);
            $userId = $user['id'] ?? null;

            if ($userId) {
                // Vérifier si une ligne existe déjà dans stat_user
                $statStmt = $db->prepare("SELECT id, total_musiques FROM stat_user WHERE utilisateur_id = ? LIMIT 1");
                $statStmt->execute([$userId]);
                $stat = $statStmt->fetch(PDO::FETCH_ASSOC);

                if ($stat) {
                    // Mettre à jour
                    $updateStat = $db->prepare("UPDATE stat_user SET total_musiques = total_musiques + 1 WHERE id = ?");
                    $updateStat->execute([$stat['id']]);
                } else {
                    // Insérer une nouvelle ligne
                    $insertStat = $db->prepare("INSERT INTO stat_user (utilisateur_id, total_musiques) VALUES (?, 1)");
                    $insertStat->execute([$userId]);
                }
            }
        }

        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Missing musiques_id']);
}
?>
