<?php

   
    $dsn = 'mysql:host=localhost;dbname=mini_chat;charset=utf8';
    $user = 'root';
    $password = '';


    try {
        $db = new PDO($dsn, $user, $password);


        $query = "SELECT * FROM messages ORDER BY date_time ASC";
        $result = $db->query($query);


        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            echo '<p>' . $row['usernames'] . ' : '  . $row['date_time'] . ' : '. $row['message'] . '</p>';
            echo '<hr>';
        }
    } catch (PDOException $e) {
        echo 'An error occurred: ' . $e->getMessage();
    }

?>