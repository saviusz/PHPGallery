<?php
session_start();
if (!empty($_SESSION["loggedUser"])) {
    header("Location: ./");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <?php include "./include/headers.php" ?>
    <link rel="stylesheet" href="./style/logreg.css">
    <title>Zaloguj | Zarejestruj</title>
    <script src="javascript/logreg.js" defer></script>
</head>

<body>
    <?php include("./include/menu.php") ?>
    <main>
        <section class="register-form">
            <h1>Zarejestruj się</h1>
            <form action="./auth/register.php" method="post">
                <div>
                    <span class="name">Login</span>
                    <input type="text" name="login" id="login" required minlength="8" maxlength="16">
                </div>
                <div>
                    <span class="name">Hasło</span>
                    <input type="password" name="password" id="password" required minlength="8" maxlength="20">
                </div>
                <div>
                    <span class="name">Powtórz hasło</span>
                    <input type="password" name="rpassword" id="rpassword" required>
                </div>
                <div>
                    <span class="name">Email</span>
                    <input type="email" name="email" id="email" required>
                </div>
                <button type="submit">Zarejestruj</button>
            </form>
            <div class="errors">
                <?php
                $errors = $_SESSION["reg_errors"] ?? [];
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

        <section class="login-form">
            <h1>Zaloguj się</h1>
            <form action="./auth/login.php" method="post">
                <div><span class="name">Login</span><input type="text" name="login" id="login" required></div>
                <div><span class="name">Hasło</span><input type="password" name="password" id="password" required></div>
                <button type="submit">Zaloguj</button>
            </form>
            <div class="errors">
                <?php
                $errors = $_SESSION["login_errors"] ?? [];
                if (is_array($errors)) {
                    foreach ($errors as $error) {
                ?>
                        <span><?= $error ?></span>
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
$_SESSION["reg_errors"] = array();
$_SESSION["login_errors"] = array();
