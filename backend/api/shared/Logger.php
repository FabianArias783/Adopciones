<?php
class Logger {
    private $conn;
    private $table_name = "audit_logs";

    public function __construct($db){
        $this->conn = $db;
    }

    public function log($user_id, $action, $table=null, $record_id=null, $details=null){
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    user_id = :user_id,
                    action = :action,
                    table_name = :table,
                    record_id = :record_id,
                    details = :details,
                    ip_address = :ip";

        $stmt = $this->conn->prepare($query);

        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

        $stmt->bindParam(":user_id", $user_id);
        $stmt->bindParam(":action", $action);
        $stmt->bindParam(":table", $table);
        $stmt->bindParam(":record_id", $record_id);
        $stmt->bindParam(":details", $details);
        $stmt->bindParam(":ip", $ip);

        // Execute and ignore errors to not block main flow
        $stmt->execute();
    }
}
?>
