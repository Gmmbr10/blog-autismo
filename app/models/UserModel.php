<?php

class UserModel extends Model {

  public function list($userData)
  {

    if ( !empty($userId) && is_numeric($userData) ) {
      return false;
    }

    $query = "SELECT * FROM users WHERE user_email=:email LIMIT 1";
    $list = $this->getConnection()->prepare($query);
    $list->bindParam(":email",$userData["email"],PDO::PARAM_STR);
    $list->execute();

    if ( $user = $list->fetch() ) {

      if (password_verify($userData["password"],$user["user_password"])){

        return $user;
        
      }

      return false;

    }

    return false;
    
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

  public function type(array $data)
  {

    if ( empty($data["userId"]) || empty($data["type"]) ) {
      return false;
    }

    $userData = [ "id" => $data["userId"] , "type" => $data["type"] ];

    $query = "update users set user_type=:type where user_id = :id";
    $update = $this->getConnection()->prepare($query);
    $update->bindParam(":id",$userData["id"],PDO::PARAM_INT);
    $update->bindParam(":type",$userData["type"],PDO::PARAM_STR);
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

  public function updatePhoto(int $user_id, string $photo_name)
  {

    $query = "UPDATE users SET user_img=:img WHERE user_id = :id";
    $update = $this->getConnection()->prepare($query);
    $update->bindParam(":img",$photo_name,PDO::PARAM_STR);
    $update->bindParam(":id",$user_id,PDO::PARAM_INT);
    $update->execute();

    if ( $update->rowCount() == 0 ) {
      return false;
    }

    return true;
    
  }

  public function updatePassword(int $user_id,$password)
  {

    $password = password_hash($password,PASSWORD_BCRYPT);

    $query = "UPDATE users SET user_password=:pasword WHERE user_id = :id";
    $update = $this->getConnection()->prepare($query);
    $update->bindParam(":pasword",$password,PDO::PARAM_STR);
    $update->bindParam(":id",$user_id,PDO::PARAM_INT);
    $update->execute();

    if ( $update->rowCount() == 0 ) {
      return false;
    }

    return true;

  }
  
}