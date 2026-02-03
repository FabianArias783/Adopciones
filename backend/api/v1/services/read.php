<?php
// headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

include_once '../../config/database.php';
include_once '../../config/core.php';
require_once '../../../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$database = new Database();
$db = $database->getConnection();

$authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
$arr = explode(" ", $authHeader);
$jwt = isset($arr[1]) ? $arr[1] : "";

if($jwt){
    try {
        $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
        $user_id = $decoded->data->id;
        $role = $decoded->data->role;

        $query = "SELECT s.id, s.service_number, s.status, s.created_at, s.notes, s.failure_reason,
                         m.client_name, m.address, m.qr_code,
                         u.full_name as driver_name
                  FROM services s
                  LEFT JOIN manifests m ON s.manifest_id = m.id
                  LEFT JOIN users u ON s.driver_id = u.id";

        if($role == 'driver'){
            $query .= " WHERE s.driver_id = :user_id";
        }

        $query .= " ORDER BY s.created_at DESC";

        $stmt = $db->prepare($query);
        if($role == 'driver'){
            $stmt->bindParam(":user_id", $user_id);
        }
        $stmt->execute();
        $num = $stmt->rowCount();

        if($num > 0){
            $services_arr = array();
            $services_arr["records"] = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                extract($row);
                $service_item = array(
                    "id" => $id,
                    "service_number" => $service_number,
                    "client_name" => $client_name,
                    "address" => $address,
                    "qr_code" => $qr_code,
                    "driver_name" => $driver_name,
                    "status" => $status,
                    "created_at" => $created_at,
                    "notes" => $notes,
                    "failure_reason" => $failure_reason
                );
                array_push($services_arr["records"], $service_item);
            }
            http_response_code(200);
            echo json_encode($services_arr);
        } else {
            http_response_code(200); // Return 200 with empty list instead of 404 for UX
            echo json_encode(array("records" => []));
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
