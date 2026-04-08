<?php

class Database
{
    public function connect()
    {
        $conn = new mysqli("localhost", "root", "", "egzaminas");

        if ($conn->connect_error) {
            echo 'Nepavyko prisijungti prie DB.';
        }

        return $conn;
    }
}

?>