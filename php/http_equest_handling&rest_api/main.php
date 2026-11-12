<?php

header("Content-Type: application/json; charset=UTF-8");

$method = $_SERVER['REQUEST_METHOD'];
$headers = function_exists('getallheaders') ? getallheaders() : [];
$input = file_get_contents("php://input");
$data = json_decode($input, true);

$response = [];
$code = 200;

switch ($method) {

    case "GET":

        $response = [
            "status" => "success",
            "message" => "GET request received",
            "headers" => $headers
        ];

        break;

    case "POST":

        if (empty($input) || json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
            $code = 400;
            $response = [
                "status" => "error",
                "message" => "Invalid JSON data"
            ];
        } else {
            $code = 201;
            $response = [
                "status" => "success",
                "message" => "POST request received",
                "data" => $data
            ];
        }

        break;

    case "PUT":

        if (empty($input) || json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
            $code = 400;
            $response = [
                "status" => "error",
                "message" => "Invalid JSON data"
            ];
        } else {
            $response = [
                "status" => "success",
                "message" => "PUT request received",
                "data" => $data
            ];
        }

        break;

    case "DELETE":

        $response = [
            "status" => "success",
            "message" => "DELETE request received"
        ];

        break;

    default:

        $code = 405;

        header("Allow: GET, POST, PUT, DELETE");

        $response = [
            "status" => "error",
            "message" => "Method not allowed"
        ];

        break;
}

http_response_code($code);

echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

?>
