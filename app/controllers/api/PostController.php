<?php

header("Content-Type: application/json");

class PostController {

  public function create(array $dataUri)
  {

    session_start();
    $data = json_decode(file_get_contents("php://input"),true);

    if ( empty($data["title"]) || empty($data["content"]) || empty($data["tags"]) ) {
      http_response_code(400);
      $response = ["result"=>"Falta de dados!"];
      echo json_encode($response);
      return;
    }

    require_once(__DIR__ . "/../../models/PostModel.php");
    $model = new PostModel();
    $result = $model->create($data);

    if ( !$result ) {
      http_response_code(500);
      $response = ["result" => "Houve um problema durante o processo"];
      echo json_encode($response);
      return;
    }

    http_response_code(201);
    $response = ["result" => $result];
    echo json_encode($response);
    return;
  }

  public function update(array $dataUri)
  {

    session_start();
    $data = json_decode(file_get_contents("php://input"),true);

    if ( empty($data["title"]) || empty($data["content"]) || empty($data["postId"]) || empty($data["tags"]) ) {
      http_response_code(400);
      $response = ["result"=>"Falta de dados!"];
      echo json_encode($response);
      return;
    }

    require_once(__DIR__ . "/../../models/PostModel.php");
    $model = new PostModel();
    $result = $model->update($data);

    if ( !$result ) {
      http_response_code(500);
      $response = ["result" => "Houve um problema durante o processo",$data];
      echo json_encode($response);
      return;
    }

    http_response_code(202);
    $response = ["result" => $result];
    echo json_encode($response);
    return;
  }

  public function occult(array $dataUri)
  {
    session_start();
    $data = json_decode(file_get_contents("php://input"),true);

    if ( empty($data["postId"]) ) {
      http_response_code(400);
      $response = ["result"=>"Falta de dados!"];
      echo json_encode($response);
      return;
    }

    require_once(__DIR__ . "/../../models/PostModel.php");
    $model = new PostModel();
    $result = $model->occult($_SESSION["user"]["user_id"],$data["postId"]);

    if ( !$result ) {
      http_response_code(500);
      $response = ["result" => "Houve um problema durante o processo",$data];
      echo json_encode($response);
      return;
    }

    http_response_code(202);
    $response = ["result" => $result];
    echo json_encode($response);
    return;
  }


  public function unhide(array $dataUri)
  {
    session_start();
    $data = json_decode(file_get_contents("php://input"),true);

    if ( empty($data["postId"]) ) {
      http_response_code(400);
      $response = ["result"=>"Falta de dados!"];
      echo json_encode($response);
      return;
    }

    require_once(__DIR__ . "/../../models/PostModel.php");
    $model = new PostModel();
    $result = $model->unhide($_SESSION["user"]["user_id"],$data["postId"]);

    if ( !$result ) {
      http_response_code(500);
      $response = ["result" => "Houve um problema durante o processo",$data];
      echo json_encode($response);
      return;
    }

    http_response_code(202);
    $response = ["result" => $result];
    echo json_encode($response);
    return;
  }
  
}