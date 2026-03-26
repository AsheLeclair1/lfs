<?php
class Model {
    protected $conn;
    protected $table;
    
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }
    
    public function create($data) {
        $columns = implode(", ", array_keys($data));
        $values = "'" . implode("', '", array_values($data)) . "'";
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$values})";
        
        if($this->conn->query($sql)) {
            return $this->conn->insert_id;
        }
        return false;
    }
    
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    public function getAll($order = "id DESC") {
        $sql = "SELECT * FROM {$this->table} ORDER BY {$order}";
        return $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    }
    
    public function update($id, $data) {
        $sets = [];
        foreach($data as $key => $value) {
            $sets[] = "{$key} = '{$value}'";
        }
        $setStr = implode(", ", $sets);
        $sql = "UPDATE {$this->table} SET {$setStr} WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
