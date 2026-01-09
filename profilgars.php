<?php
session_start();
require_once "./utils/connexion.php";

if (!isset($_SESSION['nom'])) {
    header("Location: ./login.php");
    exit;
}

$stmt = $db->prepare("SELECT id FROM utilisateurs WHERE nom = ?");
$stmt->execute([$_SESSION['nom']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$userId = $user['id'] ?? null;

$playlistMusiques = [];
if ($userId) {
    $stmt = $db->prepare("
        SELECT m.* FROM musiques m
        JOIN playlist p ON m.id = p.musiques_id
        WHERE p.utilisateurs_id = ?
        ORDER BY p.id ASC
    ");
    $stmt->execute([$userId]);
    $playlistMusiques = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ma Playlist</title>
    <link rel="stylesheet" href="./src/output.css">
</head>

<body class="bg-linear-to-r from-[#13101F] via-[#1e1332] to-[#751d53] p-8 pb-48">

    <audio id="audioPlayer"></audio>

    <header class="flex pb-24 font-bold gap-8">
        <div class="flex flex-row self-end md:hidden">
            <p class="text-white text-2xl" onclick="ouvremenutop()">☰</p>
        </div>

        <div class="hidden bg-black w-full rounded-2xl p-5 justify-center items-center md:hidden" id="menudutop">
            <a href="./pagemain.php" class="bg-linear-to-r from-[#ffffff] to-[#EED3F8] text-transparent bg-clip-text">
                Accueil
            </a>
            <a href="./ajtmusique.php" class="bg-linear-to-r from-[#ffffff] to-[#EED3F8] text-transparent bg-clip-text">
                Ajoute une musique
            </a>
            <a href="./playlistaleatoire.php" class="bg-linear-to-r from-[#ffffff] to-[#EED3F8] text-transparent bg-clip-text">
                Playlist Aléatoire
            </a>
            <a href="./lepluspopulaire.php" class="bg-linear-to-r from-[#ffffff] to-[#EED3F8] text-transparent bg-clip-text">
                Les musiques les plus populaires
            </a>

             <a href="./statistiques.php" class="bg-linear-to-r text-1xl from-[#ffffff]  to-[#EED3F8] text-transparent bg-clip-text">
                Tes statistiques
            </a>
            <a href="./process/processdeconnecte.php" class="bg-linear-to-r text-1xl from-[#ffffff] to-[#EED3F8] text-transparent bg-clip-text">
                Déconnecte toi
            </a>
        </div>
        <div class="hidden justify-center items-center text-1xl pt-8 font-bold gap-8 md:flex">
            <a href="./pagemain.php" class="bg-linear-to-r from-[#ffffff] to-[#EED3F8] text-transparent bg-clip-text">
                Accueil
            </a>
            <a href="./ajtmusique.php" class="bg-linear-to-r from-[#ffffff] to-[#EED3F8] text-transparent bg-clip-text">
                Ajoute une musique
            </a>
            <a href="./playlistaleatoire.php" class="bg-linear-to-r from-[#ffffff] to-[#EED3F8] text-transparent bg-clip-text">
                Playlist Aléatoire
            </a>
            <a href="./lepluspopulaire.php" class="bg-linear-to-r from-[#ffffff] to-[#EED3F8] text-transparent bg-clip-text">
                Les musiques les plus populaires
            </a>

        <a href="./statistiques.php" class="bg-linear-to-r text-1xl from-[#ffffff]  to-[#EED3F8] text-transparent bg-clip-text">
                Tes statistiques
            </a>
            <a href="./process/processdeconnecte.php" class="bg-linear-to-r text-1xl from-[#ffffff] to-[#EED3F8] text-transparent bg-clip-text">
                Déconnecte toi
            </a>
        </div>
    </header>

    <div class="flex flex-row text-5xl text-center justify-center items-center pb-8 font-bold">
        <h1 class="self-center text-center justify-center items-center pt-8 bg-linear-to-r from-[#ffffff] to-[#EED3F8] text-transparent bg-clip-text">
            Ta Playlist, <?= htmlspecialchars($_SESSION['nom']) ?>!
        </h1>
    </div>

    <br>
    <section class="border-solid border-[#44434b] border-8 rounded-2xl">
        <div class="bg-white/13">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 justify-items-center pb-8 pt-5">
                <?php if (!empty($playlistMusiques)): ?>
                    <?php foreach ($playlistMusiques as $index => $musique): ?>
                        <div class="border-solid border-[#ffffff8e] border rounded-2xl">
                            <div class="bg-linear-to-r from-[#6A1E70] via-[#821E50] to-[#284C62] w-64 h-64 p-4 rounded-2xl flex flex-col gap-3 text-white relative">

                                <div class=" flex flex-row gap-3  absolute top-4 right-4 bg-yellow-400/20 rounded-lg py-1 px-3 border border-yellow-400/40">
                                    <img src="./yeux-rouges.png" class="w-5 h-5" alt="">
                                    <span class="text-white font-bold text-sm"><?= number_format($musique['views'] ?? 0, 0, ',', ' ') ?></span>
                                </div>

                                <h2 class="font-bold text-lg mb-2"><?= htmlspecialchars($musique['musique']) ?></h2>
                                <p class="text-xs text-gray-200"><strong class="font-bold">Description :</strong> <?= htmlspecialchars($musique['description']) ?></p>
                                <p class="text-xs text-gray-200"><strong class="font-bold">Album :</strong> <?= htmlspecialchars($musique['Album']) ?></p>
                                <p class="text-xs text-gray-200"><strong class="font-bold">Artiste :</strong> <?= htmlspecialchars($musique['Artiste']) ?></p>

                                <div class="flex gap-2 mt-auto">
                                    <button type="button"
                                        onclick="playtruc('<?= htmlspecialchars($musique['fichier']) ?>', <?= $musique['id'] ?>)"
                                        class="flex-1 bg-black/30 rounded py-1">
                                        Play
                                    </button>

                                    <button type="button"
                                        onclick="pauselamusique()"
                                        class="flex-1 bg-black/30 rounded py-1">
                                        Pause
                                    </button>
                                </div>

                                <div class="flex gap-2">
                                    <form action="./process/processenleverdelaplaylist.php" method="POST" class="flex-1">
                                        <input type="hidden" name="musiques_id" value="<?= $musique['id'] ?>">
                                        <button type="submit"
                                            class="bg-red-600/30 rounded w-full py-1 text-sm">
                                            Enlever
                                        </button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-full text-center py-12">
                        <p class="text-white text-2xl">Ta playlist est vide!</p>
                        <a href="./pagemain.php" class="bg-linear-to-r from-[#6A1E70] via-[#821E50] to-[#284C62] text-white py-2 px-6 rounded-lg mt-4 inline-block">
                            Ajouter des musiques
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <br>

    <div class="w-full fixed bottom-0 left-0 right-0 bg-[#13101F] text-white p-3">
        <div class="flex flex-col items-center gap-2">
            <p class="bg-linear-to-r font-bold from-[#6A1E70] via-[#821E50] to-[#284C62] text-transparent bg-clip-text text-lg">
                Moment d'Audio et playlist
            </p>

            <div class="text-center w-full">
                <p class="text-white text-sm" id="currentSongTitle">Aucune chanson</p>
                <p class="text-gray-400 text-xs" id="currentSongArtist"></p>
            </div>

            <input id="progressBar" type="range" value="0" min="0" step="1"
                class="w-full h max-w-xl">

            <div class="flex flex-row justify-center gap-2">
                <button onclick="previousSong()" class="bg-linear-to-r from-[#6A1E70] via-[#821E50] to-[#284C62] px-4 py-1 rounded-lg text-white font-bold text-sm">
                    Précédent
                </button>
                <button id="playPauseBtn" onclick="togglePlayPause()" class="bg-linear-to-r from-[#6A1E70] via-[#821E50] to-[#284C62] px-4 py-1 rounded-lg text-white font-bold text-sm">
                    Play
                </button>
                <button onclick="nextSong()" class="bg-linear-to-r from-[#6A1E70] via-[#821E50] to-[#284C62] px-4 py-1 rounded-lg text-white font-bold text-sm">
                    Suivant
                </button>
            </div>

            <div class="flex flex-row self-end">
                <p class="text-white text-xl" onclick="ouvremenu()">☰</p>
            </div>
        </div>
    </div>

    <div class="bg-black justify-center items-center self-center w-96 h-96 border-solid border-[#44434b] border-8 rounded-2xl hidden" id="volumeaudio">
        <div class="justify-center items-center" id="volumeaudio">
            <p class="bg-linear-to-r font-bold from-[#6A1E70] via-[#821E50] to-[#284C62] text-transparent bg-clip-text text-2xl">
                Volume d'Audio
            </p>

            <input id="audiobar" type="range" value="100" min="0" max="100" step="1"
                class="w-full max-w-xl">
        </div>
    </div>

    <script>
        const audio = document.getElementById("audioPlayer");
        const progressBar = document.getElementById("progressBar");
        const volume = document.getElementById("audiobar");
        const volumeaudio = document.getElementById("volumeaudio");
        const les3trucs = document.getElementById("menudutop");
        const playPauseBtn = document.getElementById("playPauseBtn");
        const currentSongTitle = document.getElementById("currentSongTitle");
        const currentSongArtist = document.getElementById("currentSongArtist");
        const playlist = <?= json_encode($playlistMusiques) ?>;
        let currentIndex = 0;

        function playtruc(file, musicId) {
            if (!file) return;
            audio.src = file;
            audio.play();
            updatePlayPauseButton();

            // Envoyer une requête pour incrémenter les vues
            if (musicId) {
                fetch('./process/processajtviews.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'musiques_id=' + musicId
                }).catch(error => console.error('Error updating views:', error));
            }
        }

        function pauselamusique() {
            audio.pause();
            updatePlayPauseButton();
        }

        function togglePlayPause() {
            if (audio.paused) {
                if (playlist.length > 0 && !audio.src) {
                    playSongAtIndex(0);
                } else {
                    audio.play();
                }
            } else {
                audio.pause();
            }
            updatePlayPauseButton();
        }

        function updatePlayPauseButton() {
            if (audio.paused) {
                playPauseBtn.textContent = "Play";
            } else {
                playPauseBtn.textContent = "Pause";
            }
        }

        function playSongAtIndex(index) {
            if (index < 0 || index >= playlist.length) return;

            currentIndex = index;
            const song = playlist[currentIndex];

            audio.src = song.fichier;
            currentSongTitle.textContent = song.musique;
            currentSongArtist.textContent = song.Artiste;

            audio.play();
            updatePlayPauseButton();

            // Envoyer une requête pour incrémenter les vues
            if (song.id) {
                fetch('./process/processajtviews.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'musiques_id=' + song.id
                }).catch(error => console.error('Error updating views:', error));
            }
        }

        function nextSong() {
            if (currentIndex < playlist.length - 1) {
                playSongAtIndex(currentIndex + 1);
            } else if (playlist.length > 0) {
                playSongAtIndex(0);
            }
        }

        function previousSong() {
            if (currentIndex > 0) {
                playSongAtIndex(currentIndex - 1);
            } else if (playlist.length > 0) {
                playSongAtIndex(playlist.length - 1);
            }
        }

        function ouvremenu() {
            volumeaudio.classList.toggle("hidden");
        }

        function ouvremenutop() {
            les3trucs.classList.toggle("hidden");
        }

        audio.addEventListener("ended", () => {
            nextSong();
        });

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

        if (playlist.length > 0) {
            currentSongTitle.textContent = playlist[0].musique;
            currentSongArtist.textContent = playlist[0].Artiste;
        }
    </script>

</body>

</html>