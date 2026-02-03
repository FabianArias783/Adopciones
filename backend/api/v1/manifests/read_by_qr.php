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
require_once '../../../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$database = new Database();
$db = $database->getConnection();

$data = json_decode(file_get_contents("php://input"));
$authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
$arr = explode(" ", $authHeader);
$jwt = isset($arr[1]) ? $arr[1] : "";

if($jwt){
    try {
        $decoded = JWT::decode($jwt, new Key($key, 'HS256'));

        if(!isset($data->qr_code)){
            http_response_code(400);
            echo json_encode(array("message" => "Missing QR code."));
            exit();
        }

        $query = "SELECT * FROM manifests WHERE qr_code = :qr_code LIMIT 0,1";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":qr_code", $data->qr_code);
        $stmt->execute();

        if($stmt->rowCount() > 0){
             $row = $stmt->fetch(PDO::FETCH_ASSOC);
             http_response_code(200);
             echo json_encode($row);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "Manifest not found."));
        }

    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(array("message" => "Access denied."));
    }
} else {
    http_response_code(401);
    echo json_encode(array("message" => "Access denied."));
}
?>
