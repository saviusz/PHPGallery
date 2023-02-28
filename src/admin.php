<?php
session_start();

require_once("./common/mysqli.php");

if (empty($_SESSION["loggedUser"])) {
    header("Location: ./logreg.php");
    exit();
}

if ($_SESSION["loggedUser"]["role"] != "admin" && $_SESSION["loggedUser"]["role"] != "moderator") {
    header("Location: ./account.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <?php include "./include/headers.php" ?>
    <title>Panel admina</title>
    <link rel="stylesheet" href="./style/admin-panel.css">
    <link rel="stylesheet" href="./style/account.css">

    <script src="./javascript/admin.js"></script>
</head>

<body>
    <nav>
        <section>
            <h3> Na tej stronie </h3>
            <?php if ($_SESSION["loggedUser"]["role"] == "admin") : ?><a href="#albums">Albumy</a><?php endif; ?>
            <a href="#photos">Zdjęcia do sprawdzenia</a>
            <a href="#comments">Komentarze</a>
            <?php if ($_SESSION["loggedUser"]["role"] == "admin") : ?><a href="#users">Użytkownicy</a><?php endif; ?>
        </section>
        <section>
            <h3> Inne strony </h3>
            <a href="./account.php">Moje konto</a>
            <a href="./index.php">Strona główna</a>
        </section>
    </nav>
    <main>
        <?php
        $stmt = $mysqli->prepare(
            "SELECT albums.id, albums.title, count(
                CASE 
                WHEN photos.isAccepted = 0
                THEN 1
                END
            ) as checkCount
            FROM albums LEFT JOIN photos ON photos.albumId = albums.id 
            GROUP BY albums.id
            ORDER BY checkCount DESC, title"
        );

        $stmt->execute();
        $albums = $stmt->get_result();
        ?>
        <?php if ($_SESSION["loggedUser"]["role"] == "admin") : ?><section id="albums">
                <h2>Albumy</h2>
                <div class="table">
                    <?php while ($album = $albums->fetch_assoc()) : ?>
                        <div>
                            <div class="text"><?= $album["title"] ?>
                                <?php if ($album["checkCount"] > 0) : ?>
                                    <br>
                                    <span class="subtitle">Do sprawdzenia: <?= $album["checkCount"] ?></span>
                                <?php endif; ?>
                            </div>
                            <button onclick="changeAlbumTitle(<?= $album['id'] ?>, '<?= $album['title'] ?>' )">Zmień nazwę</button>
                            <button onclick="deleteAlbum(<?= $album['id'] ?>)">Usuń album</button>
                        </div>
                    <?php endwhile; ?>
                </div>
            </section>
        <?php endif; ?>
        <?php
        $albumId = $_GET["albumId"] ?? 0;

        $stmt->execute();
        $albums = $stmt->get_result();

        if ($albumId == 0) {
            $stmt = $mysqli->prepare("SELECT photos.id, photos.description, photos.albumId, photos.isAccepted FROM photos WHERE isAccepted=0 ORDER BY isAccepted DESC");
        } else {
            $stmt = $mysqli->prepare("SELECT photos.id, photos.description, photos.albumId, photos.isAccepted FROM photos WHERE albumId=(?)");
            $stmt->bind_param("i", $albumId);
        }

        $stmt->execute();

        $photos = $stmt->get_result();

        ?>
        <section id="photos">
            <h2>Zdjęcia w albumach</h2>
            <div>
                Wybierz album:
                <select onchange="changeAlbum(this)">
                    <option <?= 0 == $albumId ? "selected" : "" ?> value="0">Do sprawdzenia</option>
                    <?php while ($album = $albums->fetch_assoc()) : ?>
                        <option value="<?= $album["id"] ?>" <?= $album["id"] == $albumId ? "selected" : "" ?>><?= $album["title"] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="table">
                <?php while ($photo = $photos->fetch_assoc()) : ?>
                    <div class="photo-row">
                        <img src="./photo/<?= $photo["albumId"] ?>/<?= $photo["id"] ?>.min.webp" alt="">
                        <span class="text"><?= $photo["description"] ?></span>
                        <?php if (!$photo["isAccepted"]) : ?>
                            <button onclick="acceptPhoto(<?= $photo['id'] ?>)">Akceptuj zdjecie</button>
                        <?php endif; ?>
                        <button onclick="deletePhoto(<?= $photo['id'] ?>)">Usuń zdjęcie</button>
                    </div>
                <?php endwhile;
                if ($photos->num_rows < 1) : ?>
                    Brak zdjęć do pokazania
                <?php endif; ?>
            </div>
        </section>

        <?php

        $commentsMode = $_GET["commentsMode"] ?? "all";

        if ($commentsMode == "all") {
            $commentStmt = $mysqli->prepare("SELECT photos_comments.*, users.login FROM photos_comments JOIN users ON photos_comments.authorId = users.id");
        } else {
            $commentStmt = $mysqli->prepare(
                "SELECT photos_comments.*, users.login
                FROM photos_comments JOIN users ON photos_comments.authorId = users.id
                WHERE photos_comments.isAccepted=0"
            );
        }
        $commentStmt->execute();

        $comments = $commentStmt->get_result();
        ?>
        <section id="comments">
            <h2>Komentarze</h2>
            <div>
                Wszystkie: <input type="radio" name="comments_type" value="all" onchange="changeCommentsMode()" <?= $commentsMode == "all" ? "checked" : "" ?>>&ensp;
                Do sprawdzenia: <input type="radio" name="comments_type" value="check" onchange="changeCommentsMode()" <?= $commentsMode == "check" ? "checked" : "" ?>>
            </div>
            <div class=" table">
                <?php while ($comment = $comments->fetch_assoc()) : ?>
                    <div>
                        <div class="text"><?= $comment["content"] ?>
                            <br>
                            <span class="subtitle"><?= $comment["login"] ?></span>
                        </div>
                        <?php if ($_SESSION["loggedUser"]["role"] == "admin") : ?>
                            <button onclick="editComment(<?= $comment['id'] ?>)">Edytuj komentarz</button>
                        <?php endif; ?>
                        <?php if (!$comment["isAccepted"]) : ?>
                            <button onclick="acceptComment(<?= $comment['id'] ?>)">Akceptuj komentarz</button>
                        <?php endif; ?>
                        <button onclick="deleteComment(<?= $comment['id'] ?>)">Usuń komentarz</button>
                    </div>
                <?php endwhile;
                if ($comments->num_rows < 1) : ?>
                    Brak komentarzy do pokazania
                <?php endif; ?>
            </div>
        </section>

        <?php
        $userMode = $_GET["userMode"] ?? "all";

        if ($userMode == "all") {
            $usersStmt = $mysqli->prepare("SELECT users.id, users.login, users.email, users.role, users.active FROM users");
        } else {
            $usersStmt = $mysqli->prepare("SELECT users.id, users.login, users.email, users.role, users.active FROM users WHERE users.role=(?)");
            $usersStmt->bind_param("s", $userMode);
        }

        $usersStmt->execute();

        $users = $usersStmt->get_result();

        ?>
        <?php if ($_SESSION["loggedUser"]["role"] == "admin") : ?>
            <section id="users">
                <h2>Użytkownicy</h2>
                <div>
                    Wszyscy: <input type="radio" name="user_type" value="all" <?= $userMode == "all" ? "checked" : "" ?> onchange="changeUserMode()">&ensp;
                    Moderatorzy: <input type="radio" name="user_type" value="moderator" <?= $userMode == "moderator" ? "checked" : "" ?> onchange="changeUserMode()">&ensp;
                    Admini: <input type="radio" name="user_type" value="admin" <?= $userMode == "admin" ? "checked" : "" ?> onchange="changeUserMode()">
                </div>
                <div class="table">
                    <?php while ($user = $users->fetch_assoc()) : ?>
                        <div>
                            <div class="text"><?= $user["login"] ?> (<?= $user["role"] ?>)
                                <br>
                                <span class="subtitle"><?= $user["email"] ?></span>
                            </div>
                            <select onchange='changeUserRole(<?= $user["id"] ?>, this)'>
                                <option value="user" <?= $user["role"] == "user" ? "selected" : "" ?>>User</option>
                                <option value="moderator" <?= $user["role"] == "moderator" ? "selected" : "" ?>>Moderator</option>
                                <option value="admin" <?= $user["role"] == "admin" ? "selected" : "" ?>>Admin</option>
                            </select>
                            <?php if ($user["active"] == 1) : ?>
                                <button onclick="blockUser(<?= $user['id'] ?>)">Zablokuj</button>
                            <?php else : ?>
                                <button onclick="unblockUser(<?= $user['id'] ?>)">Odblokuj</button>
                            <?php endif; ?>
                            <button onclick="deleteUser(<?= $user['id'] ?>)">Usuń</button>
                        </div>
                    <?php endwhile;
                    if ($users->num_rows < 1) : ?>
                        Brak użytkowników do pokazania
                    <?php endif; ?>
                </div>
            </section>
        <?php endif; ?>
    </main>
</body>

</html>