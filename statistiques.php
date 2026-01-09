
<?php

require_once "./utils/connexion.php";

session_start();

$stmt = $db->prepare("SELECT id, nom FROM utilisateurs WHERE nom = ? LIMIT 1");
$stmt->execute([$_SESSION['nom']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$userId = $user['id'] ?? null;

$userTotal = 0;
if ($userId) {
    $s = $db->prepare("SELECT total_musiques FROM stat_user WHERE utilisateur_id = ? LIMIT 1");
    $s->execute([$userId]);
    $row = $s->fetch(PDO::FETCH_ASSOC);
    $userTotal = $row['total_musiques'] ?? 0;
}


$topStmt = $db->query("SELECT su.total_musiques, u.nom FROM stat_user su JOIN utilisateurs u ON su.utilisateur_id = u.id ORDER BY su.total_musiques DESC LIMIT 10");
$topUsers = $topStmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./src/output.css">
</head>

<body class="bg-[#2B2D31] p-8">

    <section class="flex flex-col items-center">
        <div class="w-96 p-6 rounded-2xl bg-linear-to-r from-[#6A1E70] via-[#821E50] to-[#284C62] text-white text-center">
            <h1 class="text-2xl font-bold mb-4">Tes statistiques</h1>

            <p class="text-sm">Bonjour, <strong><?= htmlspecialchars($user['nom'] ?? 'pas log in') ?>!</strong></p>
            <p class="text-3xl font-bold my-4">Tu a écouter <span class="text-white/90"><?= number_format($userTotal, 0, ',', ' ') ?> musiques!</span></p>

            <hr class="border-white/20 my-4">

            <h2 class="text-xl font-semibold mb-3">Top écouteurs</h2>
            <?php if (count($topUsers) === 0): ?>
                <p class="text-sm">Aucun utilisateur enregistré pour le moment.</p>
            <?php else: ?>
                <ol class="text-left">
                    <?php foreach ($topUsers as $idx => $tu): ?>
                        <li class="mb-2">#<?= $idx + 1 ?> — <strong><?= htmlspecialchars($tu['nom']) ?></strong> : <?= number_format($tu['total_musiques'], 0, ',', ' ') ?> écoutes</li>
                    <?php endforeach; ?>
                </ol>
            <?php endif; ?>

            <a class="inline-block mt-6 bg-white/10 hover:bg-white/20 px-4 py-2 rounded" href="./pagemain.php">Retour</a>
        </div>
    </section>

</body>

</html>
<?php
require_once "./utils/connexion.php";


if (!isset($_SESSION['nom'])) {
    header("Location: ./login.php");
    exit;
}
    $musiqueecounter = 0;
    ?>

   
