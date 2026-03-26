<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once('../config.php');

class Master extends DBConnection {
    private $settings;
    
    public function __construct(){
        global $_settings;
        $this->settings = $_settings;
        parent::__construct();
    }
    
    public function __destruct(){
        parent::__destruct();
    }
    
    function capture_err(){
        if(!$this->conn->error)
            return false;
        else{
            $resp['status'] = 'failed';
            $resp['error'] = $this->conn->error;
            return json_encode($resp);
        }
    }
    
    function delete_img(){
        extract($_POST);
        if(is_file($path)){
            if(unlink($path)){
                $resp['status'] = 'success';
            }else{
                $resp['status'] = 'failed';
                $resp['error'] = 'failed to delete '.$path;
            }
        }else{
            $resp['status'] = 'failed';
            $resp['error'] = 'Unknown '.$path.' path';
        }
        return json_encode($resp);
    }
    
    function save_category(){
        extract($_POST);
        $data = "";
        foreach($_POST as $k =>$v){
            if(!in_array($k,array('id'))){
                if(!empty($data)) $data .=",";
                $v = htmlspecialchars($this->conn->real_escape_string($v));
                $data .= " `{$k}`='{$v}' ";
            }
        }
        $check = $this->conn->query("SELECT * FROM `category_list` where `name` = '{$name}' ".(!empty($id) ? " and id != {$id} " : "")." ")->num_rows;
        if($check > 0){
            $resp['status'] = 'failed';
            $resp['msg'] = "Категория с таким названием уже существует.";
            return json_encode($resp);
        }
        if(empty($id)){
            $sql = "INSERT INTO `category_list` set {$data} ";
        }else{
            $sql = "UPDATE `category_list` set {$data} where id = '{$id}' ";
        }
        $save = $this->conn->query($sql);
        if($save){
            $sid = !empty($id) ? $id : $this->conn->insert_id;
            $resp['sid'] = $sid;
            $resp['status'] = 'success';
            $resp['msg'] = empty($id) ? "Новая категория успешно добавлена." : "Категория успешно обновлена.";
        }else{
            $resp['status'] = 'failed';
            $resp['err'] = $this->conn->error."[{$sql}]";
        }
        if($resp['status'] == 'success')
            $this->settings->set_flashdata('success',$resp['msg']);
        return json_encode($resp);
    }
    
    function delete_category(){
        extract($_POST);
        $del = $this->conn->query("DELETE FROM `category_list` where id = '{$id}'");
        if($del){
            $resp['status'] = 'success';
            $this->settings->set_flashdata('success',"Категория успешно удалена.");
        }else{
            $resp['status'] = 'failed';
            $resp['error'] = $this->conn->error;
        }
        return json_encode($resp);
    }
    
    function save_inquiry(){
        extract($_POST);
        $data = "";
        foreach($_POST as $k =>$v){
            if(!in_array($k,array('id', 'visitor'))){
                if(!empty($data)) $data .=",";
                $v = htmlspecialchars($this->conn->real_escape_string($v));
                $data .= " `{$k}`='{$v}' ";
            }
        }
        if(empty($id)){
            $sql = "INSERT INTO `inquiry_list` set {$data} ";
        }else{
            $sql = "UPDATE `inquiry_list` set {$data} where id = '{$id}' ";
        }
        $save = $this->conn->query($sql);
        if($save){
            $resp['status'] = 'success';
            if(empty($id)){
                if(!isset($visitor))
                    $resp['msg'] = "Новое сообщение успешно сохранено.";
                else
                    $resp['msg'] = "Ваше сообщение отправлено. Мы свяжемся с вами в ближайшее время. Спасибо!";
            }else
                $resp['msg'] = "Сообщение успешно обновлено.";
        }else{
            $resp['status'] = 'failed';
            $resp['err'] = $this->conn->error."[{$sql}]";
        }
        if($resp['status'] == 'success')
            $this->settings->set_flashdata('success',$resp['msg']);
        return json_encode($resp);
    }
    
    function delete_inquiry(){
        extract($_POST);
        $del = $this->conn->query("DELETE FROM `inquiry_list` where id = '{$id}'");
        if($del){
            $resp['status'] = 'success';
            $this->settings->set_flashdata('success',"Сообщение успешно удалено.");
        }else{
            $resp['status'] = 'failed';
            $resp['error'] = $this->conn->error;
        }
        return json_encode($resp);
    }
    
    function save_item(){
        extract($_POST);
        $data = "";
        
        $allowed_fields = ['category_id', 'title', 'room_number', 'location_found', 'description', 'status', 'found_date'];
        
        foreach($_POST as $k => $v){
            if(in_array($k, $allowed_fields)){
                if(!empty($data)) $data .=",";
                $v = htmlspecialchars($this->conn->real_escape_string($v));
                $data .= " `{$k}`='{$v}' ";
            }
        }
        
        if(empty($id)){
            if(!empty($data)) $data .=",";
            $data .= " `created_by` = '{$_settings->userdata('id')}' ";
        }
        
        if(empty($id)){
            $sql = "INSERT INTO `item_list` set {$data} ";
        }else{
            $sql = "UPDATE `item_list` set {$data} where id = '{$id}' ";
        }
        
        $save = $this->conn->query($sql);
        if($save){
            $resp['status'] = 'success';
            $iid = empty($id) ? $this->conn->insert_id : $id;
            $resp['iid'] = $iid;
            
            if(!empty($_FILES['image']['tmp_name'])){
                if(!is_dir(base_app."uploads/items"))
                    mkdir(base_app."uploads/items", 0777, true);
                $fname = "uploads/items/$iid.png";
                $accept = array('image/jpeg','image/png');
                if(in_array($_FILES['image']['type'], $accept)){
                    if($_FILES['image']['type'] == 'image/jpeg')
                        $uploadfile = imagecreatefromjpeg($_FILES['image']['tmp_name']);
                    elseif($_FILES['image']['type'] == 'image/png')
                        $uploadfile = imagecreatefrompng($_FILES['image']['tmp_name']);
                    
                    if(isset($uploadfile) && $uploadfile){
                        list($width,$height) = getimagesize($_FILES['image']['tmp_name']);
                        $temp = imagescale($uploadfile, $width, $height);
                        if(is_file(base_app.$fname))
                            unlink(base_app.$fname);
                        $upload = imagepng($temp, base_app.$fname);
                        if($upload){
                            $this->conn->query("UPDATE `item_list` set `image_path` = CONCAT('{$fname}', '?v=',unix_timestamp(CURRENT_TIMESTAMP)) where id = '{$iid}'");
                        }
                        imagedestroy($temp);
                    }
                }
            }
            
            $resp['msg'] = empty($id) ? "Находка успешно добавлена." : "Находка успешно обновлена.";
        }else{
            $resp['status'] = 'failed';
            $resp['msg'] = $this->conn->error;
            $resp['sql'] = $sql;
        }
        
        if($resp['status'] == 'success')
            $this->settings->set_flashdata('success',$resp['msg']);
        
        return json_encode($resp);
    }
    
    function delete_item(){
        extract($_POST);
        $del = $this->conn->query("DELETE FROM `item_list` where id = '{$id}'");
        if($del){
            $resp['status'] = 'success';
            $this->settings->set_flashdata('success',"Предмет успешно удален.");
        }else{
            $resp['status'] = 'failed';
            $resp['error'] = $this->conn->error;
        }
        return json_encode($resp);
    }
    
    function save_page(){
        extract($_POST);
        if(!is_dir(base_app.'pages'))
            mkdir(base_app.'pages');
        if(isset($page['welcome'])){
            $content = $page['welcome'];
            $save = file_put_contents(base_app.'pages/welcome.html', $content);
        }
        if(isset($page['about'])){
            $content = $page['about'];
            $save = file_put_contents(base_app.'pages/about.html', $content);
        }
        $this->settings->set_flashdata('success', "Содержимое страницы успешно обновлено");
        return json_encode(['status' => 'success']);
    }
}

$Master = new Master();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
switch ($action) {
    case 'delete_img':
        echo $Master->delete_img();
        break;
    case 'save_category':
        echo $Master->save_category();
        break;
    case 'delete_category':
        echo $Master->delete_category();
        break;
    case 'save_page':
        echo $Master->save_page();
        break;
    case 'save_item':
        echo $Master->save_item();
        break;
    case 'delete_item':
        echo $Master->delete_item();
        break;
    case 'save_inquiry':
        echo $Master->save_inquiry();
        break;
    case 'delete_inquiry':
        echo $Master->delete_inquiry();
        break;
    default:
        break;
}
?>
