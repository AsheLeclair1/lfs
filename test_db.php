<?php
require_once('config.php');

echo "<h3>Проверка соединения с БД:</h3>";
if($conn) {
    echo "✅ Соединение с БД установлено!<br>";
    
    // Проверим, есть ли таблица users
    $result = $conn->query("SHOW TABLES LIKE 'users'");
    if($result->num_rows > 0) {
        echo "✅ Таблица 'users' существует<br>";
        
        // Посмотрим количество пользователей
        $users = $conn->query("SELECT COUNT(*) as count FROM users");
        $count = $users->fetch_assoc()['count'];
        echo "✅ Найдено пользователей: $count<br><br>";
        
        // Покажем логины (без паролей)
        echo "<h4>Существующие логины:</h4>";
        $users_list = $conn->query("SELECT id, username, firstname, lastname, type FROM users");
        while($user = $users_list->fetch_assoc()) {
            echo "ID: {$user['id']}, Логин: {$user['username']}, Имя: {$user['firstname']} {$user['lastname']}, Тип: {$user['type']}<br>";
        }
    } else {
        echo "❌ Таблица 'users' НЕ найдена!";
    }
} else {
    echo "❌ Нет соединения с БД!";
}
?>
