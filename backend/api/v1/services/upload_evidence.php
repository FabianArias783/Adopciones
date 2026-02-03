<?php
header("Access-Control-Allow-Origin: *");
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

$authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
$arr = explode(" ", $authHeader);
$jwt = isset($arr[1]) ? $arr[1] : "";

if($jwt){
    try {
        $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
        $user_id = $decoded->data->id;

        if(!isset($_POST['service_id']) || !isset($_POST['type']) || !isset($_FILES['file'])){
             http_response_code(400);
             echo json_encode(array("message" => "Missing data."));
             exit();
        }

        $service_id = $_POST['service_id'];
        $type = $_POST['type'];

        // Upload logic
        $target_dir = "../../../uploads/";
        $file_ext = strtolower(pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION));
        $new_filename = uniqid() . "." . $file_ext;
        $target_file = $target_dir . $new_filename;

        $check = getimagesize($_FILES["file"]["tmp_name"]);
        if($check === false) {
             http_response_code(400);
             echo json_encode(array("message" => "File is not an image."));
             exit();
        }

        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            // Insert DB
            $query = "INSERT INTO evidences SET service_id = :sid, type = :type, file_path = :path";
            $stmt = $db->prepare($query);
            $stmt->bindParam(":sid", $service_id);
            $stmt->bindParam(":type", $type);
            $rel_path = "uploads/" . $new_filename;
            $stmt->bindParam(":path", $rel_path);

            if($stmt->execute()){
                http_response_code(201);
                echo json_encode(array("message" => "File uploaded."));
            } else {
                 http_response_code(503);
                 echo json_encode(array("message" => "DB Error."));
            }
        } else {
            http_response_code(500);
            echo json_encode(array("message" => "Upload failed."));
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
