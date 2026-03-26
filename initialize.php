<?php
// $dev_data = array('id'=>'-1','firstname'=>'Developer','lastname'=>'','username'=>'dev_oretnom','password'=>'5da283a2d990e8d8512cf967df5bc0d0','last_login'=>'','date_updated'=>'','date_added'=>'');

// Динамическое определение base_url
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$path = rtrim(dirname($_SERVER['PHP_SELF']), '/\\'); // Получаем путь если сайт в поддиректории

if(!defined('base_url')) define('base_url','http://192.168.1.24/Lost-And-Found-main/');
if(!defined('base_app')) define('base_app', str_replace('\\','/',__DIR__).'/' );
// if(!defined('dev_data')) define('dev_data',$dev_data);
if(!defined('DB_SERVER')) define('DB_SERVER',"localhost");
if(!defined('DB_USERNAME')) define('DB_USERNAME',"root");
if(!defined('DB_PASSWORD')) define('DB_PASSWORD',"");
if(!defined('DB_NAME')) define('DB_NAME',"lfis_db");
?>
// Подключение к базе данных
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Запуск сессии
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Класс SystemSettings
class SystemSettings {
    private $conn;
    public function __construct($conn) {
        $this->conn = $conn;
    }
    public function userdata($key) {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }
    public function set_userdata($key, $value) {
        $_SESSION[$key] = $value;
    }
    public function info($key) {
        $qry = $this->conn->query("SELECT * FROM system_info WHERE meta_field = '$key'");
        if($qry && $qry->num_rows > 0){
            $row = $qry->fetch_assoc();
            return $row['meta_value'];
        }
        return null;
    }
    public function set_flashdata($key, $value) {
        $_SESSION[$key] = $value;
    }
    public function chk_flashdata($key) {
        return isset($_SESSION[$key]);
    }
    public function flashdata($key) {
        $val = $_SESSION[$key];
        unset($_SESSION[$key]);
        return $val;
    }
}

$_settings = new SystemSettings($conn);
