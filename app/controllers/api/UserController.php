<?php

header("Content-Type: application/json");

class UserController
{

  public function create(array $dataUri)
  {
    $data = json_decode(file_get_contents("php://input"), true);

    if (empty($data["name"]) || empty($data["email"]) || empty($data["password"]) || empty($data["password_confirm"])) {

      http_response_code(400);
      $result = json_encode(["result" => "Falta de dados"]);
      echo $result;
      return;
    }

    if ($data["password"] != $data["password_confirm"]) {

      http_response_code(400);
      $result = json_encode(["result" => "As senhas devem ser iguais"]);
      echo $result;
      return;
    }

    require_once(__DIR__ . "/../../models/UserModel.php");
    $model = new UserModel();

    $result = $model->create($data);

    if (!$result) {

      $result = json_encode(["result" => "Houve um erro durante o processo", $data]);
      echo $result;
      return;
    }

    http_response_code(201);
    $result = json_encode(["result" => "Dados cadastrados com sucesso!"]);

    echo $result;
    return;
  }

  public function search(array $dataUri)
  {

    $data = json_decode(file_get_contents("php://input"), true);

    if (empty($data["email"]) || empty($data["password"])) {
      http_response_code(400);
      $result = json_encode(["result" => "Falta de dados"]);
      echo $result;
      return;
    }

    require_once __DIR__ . "/../../models/UserModel.php";
    $model = new UserModel();

    $result = $model->list($data);

    if ( !$result ) {
      http_response_code(400);
      $result = json_encode(["result" => "Usuário não encontrado!"]);
      echo $result;
      return;
    }

    session_start();
    $_SESSION["user"] = $result;

    http_response_code(202);
    return;
  }
}
