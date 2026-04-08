<?php

session_start();

require_once __DIR__ . "/../classes/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $database = new Database();
    $conn = $database->connect();

    $username = $_POST["username"] ?? "";
    $password = $_POST["password"] ?? "";

    if ($username === "" || $password === "") {
        $message = "Užpildykite visus laukus";
    } else {
        $stmt = $conn->prepare("SELECT password FROM vartotojai WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row["password"])) {
                $_SESSION["username"] = $username;
                header("Location: dashboard.php");
                exit;
            }
        }

        $message = "Klaidingas slapyvardis arba slaptažodis";
        $stmt->close();
    }

    $conn->close();
}

echo '<h2>Prisijungimas</h2>';

if ($message !== "") {
    echo "<p>" . $message . "</p>";
}

echo '<form method="post">';
echo 'Slapyvardis: <br>';
echo '<input type="text" name="username" required>';
echo '<br><br>';
echo 'Slaptažodis: <br>';
echo '<input type="password" name="password" required>';
echo '<br><br>';
echo '<button type="submit">Prisijungti</button>';
echo ' <button type="button" onclick="window.location.href=\'register.php\'">Registracija</button>';
echo '</form>';

?>
