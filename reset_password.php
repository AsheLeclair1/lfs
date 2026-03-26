<?php
require_once('config.php');

echo "<h2>Сброс пароля администратора</h2>";

// Пробуем обновить пароль разными способами
$username = 'admin';
$new_password = 'admin123';

// Способ 1: bcrypt
$bcrypt_hash = password_hash($new_password, PASSWORD_DEFAULT);

// Способ 2: MD5
$md5_hash = md5($new_password);

// Обновляем пароль
$sql = "UPDATE users SET password = '$bcrypt_hash' WHERE username = '$username'";
if($conn->query($sql)) {
    echo "✅ Пароль обновлен (bcrypt)<br>";
} else {
    echo "❌ Ошибка: " . $conn->error . "<br>";
}

// Проверим результат
$check = $conn->query("SELECT username, password FROM users WHERE username = '$username'");
if($check->num_rows > 0) {
    $user = $check->fetch_assoc();
    echo "<h3>Новые данные:</h3>";
    echo "Логин: {$user['username']}<br>";
    echo "Новый хеш: <code>{$user['password']}</code><br>";
    echo "<p style='color:green; font-size:18px;'>✅ Пароль сброшен на: <b>admin123</b></p>";
}

// Удалите этот файл после использования!
echo "<p style='color:red;'><b>⚠️ ВАЖНО: Удалите этот файл после использования!</b></p>";
?>
