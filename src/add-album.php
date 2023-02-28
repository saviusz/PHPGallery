<?php
session_start();
if (empty($_SESSION["loggedUser"])) {
    header("Location: ./logreg.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <?php include "./include/headers.php" ?>
    <title>Dodaj album</title>
</head>

<body>
    <?php include("./include/menu.php") ?>
    <main>
        <section class="register-form">
            <h1>Załóż album</h1>
            <form action="./files/add-album.php" method="post">
                <div>
                    <span class="name">Nazwa</span>
                    <input type="text" name="title" id="title" required minlength="1" maxlength="100">
                </div>
                <button type="submit">Dodaj album</button>
            </form>
            <div class="errors">
                <?php
                $errors = $_SESSION["album_errors"] ?? [];
                if (is_array($errors)) {
                    foreach ($errors as $error) {
                ?>
                        <div><?= $error ?></div>
                <?php
                    }
                }

                ?>
            </div>
        </section>
    </main>
    <?php include "./include/footer.php" ?>
</body>

</html>

<?php
$_SESSION["album_errors"] = array();
