<?php

session_start();

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

echo '<h2>Sveiki, ' . $_SESSION["username"] . '</h2>';

echo '<button onclick="window.location.href=\'login.php?logout=1\'">Atsijungti</button>';

?>
