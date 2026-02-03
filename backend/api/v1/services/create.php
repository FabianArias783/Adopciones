<?php
// headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

include_once '../../config/database.php';
include_once '../../config/core.php';
include_once '../../shared/Logger.php';
require_once '../../../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$database = new Database();
$db = $database->getConnection();
$logger = new Logger($db);

$data = json_decode(file_get_contents("php://input"));
$authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
$arr = explode(" ", $authHeader);
$jwt = isset($arr[1]) ? $arr[1] : "";

if($jwt){
    try {
        $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
        $user_id = $decoded->data->id;

        // Validation
        if(
            !isset($data->manifest_id) ||
            !isset($data->status) ||
            !isset($data->gps_lat) ||
            !isset($data->gps_lng)
        ){
             http_response_code(400);
             echo json_encode(array("message" => "Incomplete data."));
             exit();
        }

        // Insert Service
        // We initially insert with a placeholder service_number, then update it
        $query = "INSERT INTO services
                SET
                    service_number = :temp_sn,
                    manifest_id = :manifest_id,
                    driver_id = :driver_id,
                    status = :status,
                    start_time = :start_time,
                    end_time = NOW(),
                    gps_lat = :gps_lat,
                    gps_lng = :gps_lng,
                    gps_accuracy = :gps_accuracy,
                    notes = :notes,
                    failure_reason = :failure_reason";

        $stmt = $db->prepare($query);

        // Bind params
        $temp_sn = "TEMP-" . uniqid();
        $stmt->bindParam(":temp_sn", $temp_sn);
        $stmt->bindParam(":manifest_id", $data->manifest_id);
        $stmt->bindParam(":driver_id", $user_id);
        $stmt->bindParam(":status", $data->status);

        $start_time = isset($data->start_time) ? $data->start_time : date("Y-m-d H:i:s");
        $stmt->bindParam(":start_time", $start_time);

        $stmt->bindParam(":gps_lat", $data->gps_lat);
        $stmt->bindParam(":gps_lng", $data->gps_lng);
        $stmt->bindParam(":gps_accuracy", $data->gps_accuracy);

        $notes = isset($data->notes) ? $data->notes : "";
        $stmt->bindParam(":notes", $notes);

        $failure_reason = isset($data->failure_reason) ? $data->failure_reason : null;
        $stmt->bindParam(":failure_reason", $failure_reason);

        if($stmt->execute()){
            $last_id = $db->lastInsertId();
            $year = date("Y");
            $formatted_number = sprintf("MARP-%s-%06d", $year, $last_id);

            $update_q = "UPDATE services SET service_number = :sn WHERE id = :id";
            $up_stmt = $db->prepare($update_q);
            $up_stmt->bindParam(":sn", $formatted_number);
            $up_stmt->bindParam(":id", $last_id);
            $up_stmt->execute();

            $logger->log($user_id, "CREATE_SERVICE", "services", $last_id, "Service $formatted_number created");

            // Push Notification Stub (To be implemented with Firebase)
            // sendPush($driver_id, "Service Created: $formatted_number");
            $logger->log($user_id, "PUSH_NOTIFICATION", "system", 0, "Stub: Notification for Service $formatted_number");

            http_response_code(201);
            echo json_encode(array("message" => "Service created.", "id" => $last_id, "service_number" => $formatted_number));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to create service."));
        }

    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(array("message" => "Access denied.", "error" => $e->getMessage()));
    }
} else {
    http_response_code(401);
    echo json_encode(array("message" => "Access denied."));
}
?>
