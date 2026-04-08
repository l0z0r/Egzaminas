<?php

require_once __DIR__ . "/../classes/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $database = new Database();
    $conn = $database->connect();

    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($username === "" || $password === "") {
        $message = "Užpildykite visus laukus";
    } else {
        $check = $conn->prepare("SELECT id FROM vartotojai WHERE username = ?");
        $check->bind_param("s", $username);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $message = "Toks slapyvardis jau užimtas";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO vartotojai (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $hash);

            if ($stmt->execute()) {
                $message = "Vartotojas užregistruotas";
            } else {
                $message = "Nepavyko užregistruoti";
            }

            $stmt->close();
        }

        $check->close();
    }

    $conn->close();
}

echo '<h2>Registracija</h2>';

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
echo '<button type="submit">Registruotis</button>';
echo ' <button type="button" onclick="window.location.href=\'login.php\'">Prisijungti</button>';
echo '</form>';

?>
