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

    if (!$result) {
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

  public function img(array $dataUri)
  {

    session_start();
    $photo = $_FILES["foto"];
    $user_photo = $_SESSION["user"]["user_img"];
    $user_id = $_SESSION["user"]["user_id"];

    $extensao = end(explode(".",$photo["full_path"]));
    $file_name = md5(uniqid()) . "." . $extensao;

    require_once __DIR__ . "/../../models/UserModel.php";
    $model = new UserModel();
    $result = $model->updatePhoto($user_id,$file_name);

    if ( $result == false ) {
      http_response_code(500);
      $result = json_encode(["result" => "Upload não realizado"]);
      echo $result;
      return;
    }

    $img_path = __DIR__ . "/../../../public/assets/imgs/profile/";
    
    if ( move_uploaded_file($photo["tmp_name"],$img_path . $file_name) == false ) {
      http_response_code(500);
      $result = json_encode(["result" => "Upload não realizado"]);
      echo $result;
      return;
    }

    if ( $user_photo != "default.png" ) {
      
      unlink($img_path . $user_photo);

    }

    $_SESSION["user"]["user_img"] = $file_name;
    http_response_code(202);
    $result = json_encode(["result" => $file_name]);
    echo $result;
    return;
  }

  public function type(array $dataUri)
  {

    $data = json_decode(file_get_contents("php://input"),true);

    if ( $data["type"] != "Professor" && $data["type"] != "Professor(a) Mediador(a)" && $data["type"] != "Responsável" ) {
      http_response_code(400);
      $response = ["result"=>"Dado não é válido"];
      echo json_encode($response);
      return;
    }

    session_start();
    $user_id = $_SESSION["user"]["user_id"];
    require_once __DIR__ . "/../../models/UserModel.php";
    $model = new UserModel();
    $result = $model->type( [ "userId" => $user_id , "type" => $data["type"] ] );

    if ( $result == false ) {
      http_response_code(500);
      $result = json_encode(["result" => "Registro não realizado"]);
      echo $result;
      return;
    }

    $_SESSION["user"]["user_type"] = $data["type"];
    
    http_response_code(202);
    $response = ["result"=>"Sucesso"];
    echo json_encode($response);
    return;
  }

  public function update(array $dataUri)
  {
    session_start();
    $data = json_decode(file_get_contents("php://input"), true);
    $data["userId"] = $_SESSION["user"]["user_id"];

    if (empty($data["email"]) || empty($data["name"])) {
      http_response_code(400);
      $result = json_encode(["result" => "Falta de dados"]);
      echo $result;
      return;
    }

    require_once __DIR__ . "/../../models/UserModel.php";
    $model = new UserModel();
    $result = $model->update($data);

    if (!$result) {
      http_response_code(500);
      $result = json_encode(["result" => "Usuário não atualizado!",$data]);
      echo $result;
      return;
    }

    $_SESSION["user"]["user_name"] = $data["name"];
    $_SESSION["user"]["user_email"] = $data["email"];

    http_response_code(202);
    $result = json_encode(["result" => "Usuário atualizado!"]);
    echo $result;
    return;
  }

  public function updatePassword(array $dataUri)
  {
    session_start();
    $data = json_decode(file_get_contents("php://input"), true);
    $user_id = $_SESSION["user"]["user_id"];

    if (empty($data["password"]) || empty($data["password_confirm"])) {
      http_response_code(400);
      $result = json_encode(["result" => "Falta de dados"]);
      echo $result;
      return;
    }

    if ($data["password"] != $data["password_confirm"]) {
      http_response_code(400);
      $result = json_encode(["result" => "Senhas diferentes"]);
      echo $result;
      return;
    }

    require_once __DIR__ . "/../../models/UserModel.php";
    $model = new UserModel();
    $result = $model->updatePassword($user_id,$data["password"]);

    if (!$result) {
      http_response_code(500);
      $result = json_encode(["result" => "Usuário não atualizado!"]);
      echo $result;
      return;
    }

    http_response_code(202);
    $result = json_encode(["result" => "Usuário atualizado!"]);
    echo $result;
    return;
  }
}
