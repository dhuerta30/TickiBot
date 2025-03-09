<?php 

require "artify/classes/Queryfy.php";
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$userMessage = $data["message"];

$Queryfy = new Queryfy();
$Queryfy->connect("localhost", "root", "", "tickibot_db");
$Queryfy->where("user_message", "%$userMessage%", "LIKE");
$result = $Queryfy->select("messages");

$botResponse = "No tengo una respuesta exacta para eso, pero puedo intentar ayudarte.";

if (!empty($result)) {
    // Obtener la primera respuesta relevante
    $botResponse = $result[0]['bot_response'];
}

echo json_encode(["response" => $botResponse]);
?>
