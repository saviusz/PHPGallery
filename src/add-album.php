<?php
session_start();
if (empty($_SESSION["loggedUser"])) {
    header("Location: ./");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <?php include "./include/headers.php" ?>
    <link rel="stylesheet" href="./style/logreg.css">
    <title>Dodaj album</title>
</head>

<body>
    <?php include("./include/menu.php") ?>
    <main>
        <section class="register-form">
            <h1>Załóż album</h1>
            <form action="./auth/register.php" method="post">
                <div>
                    <span class="name">Nazwa</span>
                    <input type="text" name="login" id="login" required minlength="8" maxlength="16">
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
