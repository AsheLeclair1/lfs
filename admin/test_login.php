<?php
require_once('../config.php');

echo "<h2>Тест входа в систему</h2>";

if(isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $qry = $conn->query("SELECT * FROM users WHERE username = '$username'");
    if($qry->num_rows > 0) {
        $user = $qry->fetch_assoc();
        echo "<h3>Результаты для пользователя: <b>{$user['username']}</b></h3>";
        echo "ID пользователя: {$user['id']}<br>";
        echo "Тип пользователя: {$user['type']}<br>";
        echo "Хеш пароля в БД: <code>{$user['password']}</code><br>";
        echo "Длина хеша: " . strlen($user['password']) . " символов<br>";
        
        // Проверяем разные типы хеширования
        echo "<h4>Проверка пароля:</h4>";
        
        // Проверка bcrypt
        if(password_verify($password, $user['password'])) {
            echo "<span style='color:green;'>✅ Пароль совпадает (bcrypt)!</span><br>";
        } 
        // Проверка MD5
        elseif(md5($password) == $user['password']) {
            echo "<span style='color:green;'>✅ Пароль совпадает (MD5)!</span><br>";
        }
        // Проверка MD5 в старом формате
        elseif(md5($password) === $user['password']) {
            echo "<span style='color:green;'>✅ Пароль совпадает (MD5 строгое сравнение)!</span><br>";
        }
        else {
            echo "<span style='color:red;'>❌ Пароль НЕ совпадает!</span><br>";
            echo "MD5('$password') = <code>" . md5($password) . "</code><br>";
        }
    } else {
        echo "<span style='color:red;'>❌ Пользователь '$username' не найден!</span>";
    }
    echo "<hr>";
}
?>

<h3>Форма тестирования:</h3>
<form method="post">
    <div style="margin-bottom:10px;">
        <label>Логин:</label><br>
        <input type="text" name="username" value="admin" style="padding:5px; width:200px;">
    </div>
    <div style="margin-bottom:10px;">
        <label>Пароль:</label><br>
        <input type="password" name="password" value="admin123" style="padding:5px; width:200px;">
    </div>
    <button type="submit" name="login" style="padding:10px 20px; background:blue; color:white; border:none; border-radius:5px; cursor:pointer;">
        Проверить вход
    </button>
</form>

<p style="margin-top:20px;">
    <a href="login.php">← Вернуться на страницу входа</a>
</p>
