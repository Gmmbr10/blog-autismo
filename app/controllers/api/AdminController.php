<?php

header("Content-Type: application/json");

class AdminController {

  public function login(array $dataUri)
  {

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

  public function aprove(array $dataUri)
  {

    $data = json_decode(file_get_contents("php://input"),true);

    if ( empty($data) ) {
      http_response_code(400);
      $response = ["result"=>"Falta de dados"];
      echo json_encode($response);
      return;
    }

    require_once __DIR__ . "/../../core/Model.php";
    require_once __DIR__ . "/../../models/ReviewModel.php";
    $model = new ReviewModel();
    $result = $model->aprove($data["postId"]);

    if ( !$result ) {
      http_response_code(500);
      $response = ["result" => "Houve um problema durante o processo"];
      echo json_encode($response);
      return;
    }

    http_response_code(202);
    $response = ["result" => true];
    echo json_encode($response);
    return;
    
  }

  public function img(array $dataUri)
  {

    session_start();
    $photo = $_FILES["foto"];
    $admin_photo = $_SESSION["user"]["admin_img"];
    $admin_id = $_SESSION["user"]["admin_id"];

    $extensao = end(explode(".",$photo["full_path"]));
    $file_name = md5(uniqid()) . "." . $extensao;

    require_once __DIR__ . "/../../models/AdminModel.php";
    $model = new AdminModel();
    $result = $model->updatePhoto($admin_id,$file_name);

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

    if ( $admin_photo != "default.png" ) {
      
      unlink($img_path . $admin_photo);

    }

    $_SESSION["user"]["admin_img"] = $file_name;
    http_response_code(202);
    $result = json_encode(["result" => $file_name]);
    echo $result;
    return;
  }

  public function update(array $dataUri)
  {
    session_start();
    $data = json_decode(file_get_contents("php://input"), true);
    $data["userId"] = $_SESSION["user"]["admin_id"];

    if (empty($data["email"]) || empty($data["name"])) {
      http_response_code(400);
      $result = json_encode(["result" => "Falta de dados"]);
      echo $result;
      return;
    }

    require_once __DIR__ . "/../../models/AdminModel.php";
    $model = new AdminModel();
    $result = $model->update($data);

    if (!$result) {
      http_response_code(500);
      $result = json_encode(["result" => "Usuário não atualizado!",$data]);
      echo $result;
      return;
    }

    $_SESSION["user"]["admin_name"] = $data["name"];
    $_SESSION["user"]["admin_email"] = $data["email"];

    http_response_code(202);
    $result = json_encode(["result" => "Usuário atualizado!"]);
    echo $result;
    return;
  }

  public function updatePassword(array $dataUri)
  {
    session_start();
    $data = json_decode(file_get_contents("php://input"), true);
    $user_id = $_SESSION["user"]["admin_id"];

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

    require_once __DIR__ . "/../../models/AdminModel.php";
    $model = new AdminModel();
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