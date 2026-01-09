<?php
session_start();
require_once "../utils/connexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['total_musiques'])) {
    $total_musiques = $_POST['total_musiques'];
    
    try {
        $stmt = $db->prepare("
            UPDATE stat_user 
            SET total_musiques = total_musiques + 1 
            WHERE id = ?
        ");
        $stmt->execute([$musiquesId]);
        
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Missing musiques_id']);
}
?>
