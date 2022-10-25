<?php
session_start();
if (!empty($_SESSION["loggedUser"])) {
    header("Location: /");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <?php include "./include/headers.php" ?>
    <title>Document</title>
</head>

<body>
    <section class="register-form">
        <form action="./auth/register.php" method="post">
            Login: <input type="text" name="login" id="login"><br>
            Hasło: <input type="password" name="password" id="password"><br>
            Powtórz hasło: <input type="password" name="rpassword" id="rpassword"><br>
            Email: <input type="email" name="email" id="email"><br>
            <button type="submit">Zarejestruj</button>
        </form>
        <div class="errors">
            <?php
            $errors = $_SESSION["reg_errors"];
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
        <form action="./auth/login.php" method="post">
            <input type="text" name="login"><br>
            <input type="password" name="password"><br>
            <button type="submit">Zaloguj</button>
        </form>
        <div class="errors">
            <?php
            $errors = $_SESSION["login_errors"];
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

    <?php include "./include/footer.php" ?>
</body>

</html>