<?php

class PostModel extends Model {

  public function create(array $data)
  {

    if ( empty($data["title"]) || empty($data["content"]) || empty($data["userId"]) ) {
      return false;
    }

    $query = "INSERT INTO posts ( post_title , post_content , post_userId ) VALUES ( :title , :content , :userId )";
    $insert = $this->getConnection()->prepare($query);
    $insert->bindParam(":title",$data["title"],PDO::PARAM_STR);
    $insert->bindParam(":content",$data["content"],PDO::PARAM_STR);
    $insert->bindParam(":userId",$data["userId"],PDO::PARAM_INT);
    $insert->execute();

    if ( $insert->rowCount() == 0 ) {
      return false;
    }

    return true;
    
  }
  
}