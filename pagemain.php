<?php
session_start();
require_once "./utils/connexion.php";


if (!isset($_SESSION['nom'])) {
    header("Location: ./login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./src/output.css">
</head>

<body class="bg-linear-to-r from-[#13101F] via-[#1e1332] to-[#751d53] p-8 pb-48">

    <audio id="audioPlayer"></audio>

    <header class="flex justify-center items-center text-5xl pt-8 pb-24 font-bold">
        <h1 class="bg-linear-to-r from-[#ffffff]  to-[#EED3F8] text-transparent bg-clip-text">
            Bienvenue sur Spotiphi, <?= htmlspecialchars($_SESSION['nom']) ?>!
        </h1>
    </header>



    <?php
    require_once './utils/connexion.php';

    $search = $_GET['search'] ?? '';

    $musiquesparpage = 6;


    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $page = max(1, $page);


    $search = $_GET['search'] ?? '';


    $offset = ($page - 1) * $musiquesparpage;



    if (!empty($search)) {
        $stmt = $db->prepare("
        SELECT * FROM musiques
        WHERE musique LIKE :search
           OR Artiste LIKE :search
        ORDER BY id DESC
        LIMIT :limit OFFSET :offset
    ");
        $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
    } else {
        $stmt = $db->prepare("
        SELECT * FROM musiques
        ORDER BY id DESC
        LIMIT :limit OFFSET :offset
    ");
    }

    $stmt->bindValue(':limit', $musiquesparpage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    $musiques = $stmt->fetchAll(PDO::FETCH_ASSOC);



    if (!empty($search)) {
        $countStmt = $db->prepare("
        SELECT COUNT(*) FROM musiques
        WHERE Artiste LIKE :search
           OR musique LIKE :search
    ");
        $countStmt->execute([
            'search' => '%' . $search . '%'
        ]);
    } else {
        $countStmt = $db->query("SELECT COUNT(*) FROM musiques");
    }

    $totalMusiques = $countStmt->fetchColumn();
    $totalPages = ceil($totalMusiques / $musiquesparpage);
    ?>




    <form method="get" class="flex flex-col gap-4">
        <input class="w-full p-4 text-1xl sm:text-2xl md:text-3xl font-thin rounded-3xl bg-linear-to-r from-[#6A1E70] via-[#821E50] to-[#284C62] text-white placeholder-57595E" type="text" name="search" placeholder="Chercher une musique"
            value="<?= htmlspecialchars($search) ?>">
        <button class="w-full p-4 text-1xl sm:text-2xl md:text-3xl font-thin rounded-3xl bg-linear-to-r from-[#6A1E70] via-[#821E50] to-[#284C62] text-white" type="submit">Rechercher</button>
    </form>

    <br>
    <section class="border-solid border-[#44434b]  border-8 rounded-2xl">
        <div class="bg-white/13">

           <div class="grid grid-cols-1 md:grid-cols-3 gap-8 justify-items-center pb-8 pt-5">
<?php foreach ($musiques as $musique): ?>
    <div class="border-solid border-[#ffffff8e] border rounded-2xl">
        <div class="bg-linear-to-r from-[#6A1E70] via-[#821E50] to-[#284C62] w-64 h-64 p-4 rounded-2xl flex flex-col gap-3 text-white">

            <h2 class="font-bold"><?= htmlspecialchars($musique['musique']) ?></h2>
            <p class="text-sm">Description: <?= htmlspecialchars($musique['description']) ?></p>
            <p class="text-sm">Album: <?= htmlspecialchars($musique['Album']) ?></p>
            <p class="text-sm">Artiste: <?= htmlspecialchars($musique['Artiste']) ?></p>

            <button type="button"
                onclick="playtruc('<?= htmlspecialchars($musique['fichier']) ?>')"
                class="mt-auto bg-black/30 rounded py-1">
                Play
            </button>

            <button type="button"
                onclick="pauselamusique()"
                class="bg-black/30 rounded py-1">
                Pause
            </button>

            <!-- ✅ FORMULAIRE PAR MUSIQUE -->
            <form action="./process/processajtmusiquedansplaylist.php" method="POST">
                <input type="hidden" name="musiques_id" value="<?= $musique['id'] ?>">
                <button type="submit"
                    class="bg-black/30 rounded w-full mt-2">
                    Mettre dans la playlist
                </button>
            </form>

        </div>
    </div>
<?php endforeach; ?>
</div>

        </div>
    </section>

    <br>

    <div>
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a class="bg-linear-to-r from-[#6A1E70] via-[#821E50] to-[#284C62] text-transparent bg-clip-text text-3xl" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"
                style="<?= $i == $page ? 'font-weight:bold;' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>

    <br>






    <div class="text-center pb-8">
        <a href="./ajtmusique.php" class="bg-linear-to-r from-[#ffffff]  to-[#EED3F8] font-bold text-transparent bg-clip-text text-3xl">
            Ajouter une musique
        </a>
    </div>



    <div class="text-center pb-8">
        <a href="./process/processdeconnecte.php" class="bg-linear-to-r from-[#ffffff]  to-[#EED3F8] font-bold text-transparent bg-clip-text text-3xl">
            Déconnecte-Toi
        </a>
    </div>

     <div class="text-center pb-8">
        <a href="./profilgars.php" class="bg-linear-to-r from-[#ffffff]  to-[#EED3F8] font-bold text-transparent bg-clip-text text-3xl">
            Ta playlist
        </a>
    </div>

    <div class="bg-black justify-center items-center self-center w-96 h-96 border-solid border-[#44434b]  border-8 rounded-2xl  hidden" id="volumeaudio">
        <div class="justify-center items-center" id="volumeaudio">
            <p class="  bg-linear-to-r font-bold from-[#6A1E70] via-[#821E50] to-[#284C62] text-transparent bg-clip-text text-2xl">
                Volume d'Audio
            </p>

            <input id="audiobar" type="range" value="100" min="0" max="100" step="1"
                class="-full max-w-xl">
        </div>

    </div>
    <div class=" w-full fixed bottom-0 left-0 right-0 bg-[#13101F] text-white  p-6 ">
        <div class="flex flex-col items-center gap-4">

            <p class="bg-linear-to-r font-bold from-[#6A1E70] via-[#821E50] to-[#284C62] text-transparent bg-clip-text text-2xl">
                Moment d'Audio
            </p>

            <input id="progressBar" type="range" value="0" min="0" step="1"
                class="w-full h max-w-xl">

            <div class="flex flex-row self-end">
                <p class="text-white text-2xl" onclick="ouvremenu()">☰</p>
            </div>


        </div>
    </div>




    <script>
        const audio = document.getElementById("audioPlayer");
        const progressBar = document.getElementById("progressBar");
        const volume = document.getElementById("audiobar");
        const volumeaudio = document.getElementById("volumeaudio");

        function playtruc(file) {
            if (!file) return;
            audio.src = file;
            audio.play();
        }

        function pauselamusique() {
            audio.pause();
        }

        function ouvremenu() {
            volumeaudio.classList.toggle("hidden");
        }

        audio.addEventListener("timeupdate", () => {
            if (!isNaN(audio.duration)) {
                progressBar.max = Math.floor(audio.duration);
                progressBar.value = Math.floor(audio.currentTime);
            }
        });

        progressBar.addEventListener("input", () => {
            audio.currentTime = progressBar.value;
        });

        volume.addEventListener("input", e => {
            audio.volume = e.target.value / 100;
        });
    </script>

</body>

</html>