<?php
session_start();
require_once("./common/mysqli.php");

$sort = $_GET["sort"] ?? "title_asc";

[$sort_value, $sort_dir] = explode("_", $sort);

switch ($sort_value) {
    case 'author':
        $sort_value = "users.login";
        break;
    case 'date':
        $sort_value = "albums.createdAt";
        break;
    case 'title':
    default:
        $sort_value = "albums.title";
        break;
}

$sort_dir = $sort_dir == "asc" ? "ASC" : "DESC";

$numberOnPage = 20;
$page = $_GET["page"] ?? 1;

$res = $mysqli->query("SELECT CEILING(COUNT(*) / $numberOnPage) as number FROM (
    SELECT MIN(photos.id) as miniature
    FROM photos
    INNER JOIN albums
    ON photos.albumId = albums.id
    WHERE photos.isAccepted=1
    GROUP BY albums.id
) as T");

$numberOfPages = $res->fetch_assoc()["number"];

$page = intval($page > $numberOfPages && $page < 1 ? 1 : $page);

$stmt = $mysqli->prepare(
    "SELECT MIN(photos.id) as miniature,albums.id,albums.title,albums.authorId,albums.createdAt,users.login
    FROM photos
    INNER JOIN albums
    ON photos.albumId = albums.id
    INNER JOIN users
    ON users.id = albums.authorId
    WHERE photos.isAccepted=1
    GROUP BY albums.id
    ORDER BY $sort_value $sort_dir
    LIMIT ? OFFSET ?"
);
$startFrom = ($numberOnPage * ($page - 1));

$stmt->bind_param("ii", $numberOnPage, $startFrom);
$stmt->execute();
$images = $stmt->get_result();

function value($val)
{
    global $sort;
    return "value=" . $val . " " . ($val != $sort ?: "selected");
}
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <?php include "./include/headers.php" ?>
    <link rel="stylesheet" href="./style/gallery.css">
    <title>Galeria</title>
    <script src="./javascript/gallery.js"></script>
</head>

<body>
    <?php include("./include/menu.php") ?>
    <main>
        <div class="sorting">Sortuj: <select name="sort" id="sort" onchange="updateSort(this)">
                <option <?= value("title_asc") ?>>Tytuł od A do Z</option>
                <option <?= value("title_desc") ?>>Tytuł od Z do A</option>
                <option <?= value("date_asc") ?>>Od najstarszych do najnowszych</option>
                <option <?= value("date_desc") ?>>Od najnowszych do najstarszych</option>
                <option <?= value("author_asc") ?>>Autor od A do Z</option>
                <option <?= value("author_desc") ?>>Autor od Z do A</option>
            </select>
        </div>
        <section class="gallery">
            <?php while ($row = $images->fetch_assoc()) : ?>
                <a href="<?= "./album.php?id={$row["id"]}" ?>" class="image-wrapper">
                    <img src="<?= "./photo/{$row["id"]}/{$row["miniature"]}.min.webp" ?>" alt="">
                    <div class="tooltip">
                        <div><span class="title"><?= $row["title"] ?></span></div>
                        <div><span class="name">Utworzony</span><span class="value"><?= $row["createdAt"] ?></span></div>
                        <div><span class="name">Autor</span><span class="value"><?= $row["login"] ?></span></div>
                    </div>
                </a>
            <?php endwhile; ?>
        </section>
        <div class="pagination">
            <?php if ($page > 1) : ?> <button onclick="setPage(<?= $page - 1 ?>)">Poprzednia</button><?php endif ?>
            <?php for ($iter = max(1, $page - 2); $iter < (min($page + 2, $numberOfPages) + 1); $iter++) : ?>
                <button onclick="setPage(<?= $iter ?>)" class="<?= $page == $iter ? "selected" : "" ?>"><?= $iter ?></button>
            <?php endfor;  ?>
            <?php if ($page < $numberOfPages) : ?><button onclick="setPage(<?= $page + 1 ?>)">Następna</button><?php endif ?>
        </div>
    </main>
    <?php include("./include/footer.php") ?>
</body>

</html>