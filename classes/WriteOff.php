<?php
require_once 'Model.php';

class WriteOff extends Model {
    protected $table = 'write_off_acts';
    
    public function __construct() {
        parent::__construct();
    }
    
    public function createAct($data) {
        $data['act_number'] = 'АС-' . date('Ymd') . '-' . rand(100, 999);
        
        $sql = "INSERT INTO {$this->table} (item_id, act_number, reason, status, created_by) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isssi", 
            $data['item_id'],
            $data['act_number'],
            $data['reason'],
            $data['status'],
            $data['created_by']
        );
        
        if($stmt->execute()) {
            return $this->conn->insert_id;
        }
        return false;
    }
    
    public function getByItemId($item_id) {
        $sql = "SELECT w.*, i.title as item_name, i.room_number
                FROM {$this->table} w 
                JOIN item_list i ON w.item_id = i.id 
                WHERE w.item_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $item_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    public function getById($id) {
        $sql = "SELECT w.*, i.title as item_name, 
                       i.created_at as date_found, i.location_found,
                       i.room_number
                FROM {$this->table} w 
                JOIN item_list i ON w.item_id = i.id 
                WHERE w.id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    public function getAllWithDetails() {
        $sql = "SELECT w.*, i.title as item_name, i.room_number
                FROM {$this->table} w 
                JOIN item_list i ON w.item_id = i.id 
                ORDER BY w.act_date DESC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
