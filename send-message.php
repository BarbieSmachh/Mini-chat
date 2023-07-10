<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $_POST['message'];
    $usernames = $_POST['usernames'];
    $dsn = 'mysql:host=localhost;dbname=mini_chat;charset=utf8';
    $user = 'root';
    $password = '';
    $ipUser = $_SERVER['REMOTE_ADDR'];
    $username = $_POST['usernames'];
    setcookie('username', $username, time() + (86400 * 30), '/');


    try {
        $db = new PDO($dsn, $user, $password);

        $checkQuery = "SELECT id FROM usernames WHERE usernames = :usernames LIMIT 1";
        $checkStatement = $db->prepare($checkQuery);
        $checkStatement->execute(array(':usernames' => $usernames));
        $userExists = $checkStatement->rowCount() > 0;


        if (!$userExists) {
            $insertQuery = "INSERT INTO usernames (usernames) VALUES (:usernames)";
            $insertStatement = $db->prepare($insertQuery);
            $insertStatement->execute(array(':usernames' => $usernames));
        }

        $query = "INSERT INTO messages (usernames, message, date_time, ipUser) VALUES (:usernames, :message, NOW(), :ipUser)";
        $statement = $db->prepare($query);
        $statement->execute(array( ':usernames' => $usernames, ':message' => $message, ':ipUser' => $ipUser));

        header('Location: index.php');
        exit();
    } catch (PDOException $e) {
        echo 'An error occurred: ' . $e->getMessage();
    }
    
}
?>