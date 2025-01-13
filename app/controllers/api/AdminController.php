<?php

header("Content-Type: application/json");

class AdminController {

  public function login(array $dataUri)
  {

    session_start();
    $data = json_decode(file_get_contents("php://input"),true);

    if ( empty($data["email"]) || empty($data["password"]) ) {
      http_response_code(400);
      $response = ["result"=>"Falta de dados!"];
      echo json_encode($response);
      return;
    }

    require_once(__DIR__ . "/../../models/AdminModel.php");
    $model = new AdminModel();
    $result = $model->login($data);

    if ( !$result ) {
      http_response_code(500);
      $response = ["result" => "Houve um problema durante o processo"];
      echo json_encode($response);
      return;
    }

    session_start();
    $_SESSION["user"] = $result;

    http_response_code(202);
    $response = ["result" => $result];
    echo json_encode($response);
    return;
  }
  
}