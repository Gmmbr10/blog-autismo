<?php

class UserModel extends Model {

  public function list(array $data)
  {


    
  }

  public function create(array $data)
  {

    if ( empty($data["name"]) || empty($data["email"]) || empty($data["password"]) ) {
      
      return false;

    }

    $userData = [ "name" => $data["name"] , "email" => $data["email"] ];
    $userData["password"] = password_hash($data["password"],PASSWORD_BCRYPT);

    $query = "Insert into users (user_name,user_email,user_password) values (:name,:email,:password)";

    $insert = $this->getConnection()->prepare($query);
    $insert->bindParam(":name",$userData["name"],PDO::PARAM_STR);
    $insert->bindParam(":email",$userData["email"],PDO::PARAM_STR);
    $insert->bindParam(":password",$userData["password"],PDO::PARAM_STR);
    $insert->execute();

    if ( $insert->rowCount() == 0 ) {
      return false;
    }

    return true;
    
  }

  public function update(array $data)
  {

    if ( empty($data["name"]) || empty($data["email"]) || empty($data["userId"]) ) {
      
      return false;

    }

    $userData = [ "name" => $data["name"] , "email" => $data["email"] , "id" => $data["userId"] ];
    $userData["password"] = password_hash($data["password"],PASSWORD_BCRYPT);

    $query = "update users set user_name=:name,user_email=:email where user_id = :id";

    $update = $this->getConnection()->prepare($query);
    $update->bindParam(":id",$userData["id"],PDO::PARAM_INT);
    $update->bindParam(":name",$userData["name"],PDO::PARAM_STR);
    $update->bindParam(":email",$userData["email"],PDO::PARAM_STR);
    $update->execute();

    if ( $update->rowCount() == 0 ) {
      return false;
    }

    return true;
    
  }

  public function delete(int $userId)
  {

    $query = "update users set user_active=0 where user_id = :id";
    
    $delete = $this->getConnection()->prepare($query);
    $delete->bindParam(":id",$userId,PDO::PARAM_INT);
    $delete->execute();

    if ( $delete->rowCount() == 0 ) {
      return false;
    }

    return true;
    
  }
  
}