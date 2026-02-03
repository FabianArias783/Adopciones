<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

include_once '../../config/database.php';
include_once '../../config/core.php';
include_once '../../shared/Logger.php';
require_once '../../../vendor/autoload.php';

use Firebase\JWT\JWT;

$database = new Database();
$db = $database->getConnection();
$logger = new Logger($db);

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->username) || !isset($data->password)) {
    http_response_code(400);
    echo json_encode(array("message" => "Incomplete data."));
    exit();
}

$query = "SELECT id, username, password, role, full_name FROM users WHERE username = :username LIMIT 0,1";
$stmt = $db->prepare($query);

$username = htmlspecialchars(strip_tags($data->username));
$stmt->bindParam(':username', $username);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $id = $row['id'];
    $username = $row['username'];
    $password_hash = $row['password'];
    $role = $row['role'];
    $full_name = $row['full_name'];

    if (password_verify($data->password, $password_hash)) {
        $logger->log($id, "LOGIN", "users", $id, "Successful login");

        $token = array(
            "iss" => $iss,
            "aud" => $aud,
            "iat" => $iat,
            "nbf" => $nbf,
            "exp" => $exp,
            "data" => array(
                "id" => $id,
                "username" => $username,
                "role" => $role,
                "full_name" => $full_name
            )
        );

        http_response_code(200);
        $jwt = JWT::encode($token, $key, 'HS256');
        echo json_encode(
            array(
                "message" => "Successful login.",
                "jwt" => $jwt,
                "role" => $role,
                "user" => array(
                    "id" => $id,
                    "username" => $username,
                    "full_name" => $full_name
                )
            )
        );
    } else {
        http_response_code(401);
        echo json_encode(array("message" => "Login failed."));
    }
} else {
    http_response_code(401);
    echo json_encode(array("message" => "Login failed."));
}
?>
