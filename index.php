<!DOCTYPE html>
<html>

<head>
    <title>Chat</title>
    <style>
        .container {
            display: flex;
            justify-content: space-between;
        }
        #chat-window {
            width: 70%;
            height: 400px;
            overflow-y: scroll;
            border: 1px solid #ccc;
            margin-bottom: 10px;
            padding: 10px;
        }

        #user-list {
            width: 30%;
            height: 400px;
            overflow-y: scroll;
            border: 1px solid #ccc;
            padding: 10px;
        }
    </style>
</head>

<body>
<div class="container">
    <div id="chat-window">
        <?php

        $dsn = 'mysql:host=localhost;dbname=mini_chat;charset=utf8';
        $user = 'root';
        $password = '';
        
        if (isset($_COOKIE['username'])) {
            $defaultUsername = $_COOKIE['username'];
            echo 'Welcome '. $defaultUsername. '<br>';
        } else {
            $defaultUsername = 'Pseudo';
        }
        

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
    </div>

    <div id="user-list">
        <?php
        try {
            $db = new PDO($dsn, $user, $password);


            $usersQuery = "SELECT usernames FROM usernames";
            $usersStatement = $db->query($usersQuery);
            $users = $usersStatement->fetchAll(PDO::FETCH_COLUMN);
            echo '<ul>';
            foreach ($users as $user) {
                echo '<li>' . $user . '</li>';
            }
            echo '</ul>';
        } catch (PDOException $e) {
            echo 'An error occurred: ' . $e->getMessage();
        }
        
        ?>
    </div>
</div>

    <div>
        <form action="send-message.php" method="post">
            <input type="text" name="usernames" id="usernames" placeholder="Pseudo" value= "<?php echo $defaultUsername; ?>" required><br>
            <input name="message" id="message" placeholder="Message" required></input><br>

            <button type="submit">Send</button>
        </form>

    </div>
    <script>

        function refreshChat() {
            fetch('refresh-chat.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('chat-window').innerHTML = data;
                })
                .catch(error => console.error(error));
        }


        setInterval(refreshChat, 2500);


        document.getElementById('message-form').addEventListener('submit', function(event) {
            event.preventDefault();

            const form = new FormData(this);
            fetch('send-message.php', {
                method: 'POST',
                body: form
            })
            .then(response => response.text())
            .then(data => {
                console.log(data);

                refreshChat();
            })
            .catch(error => console.error(error));


            this.reset();
        });
    </script>
   

</body>

</html>