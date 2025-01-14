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

  public function listViews()
  {

    $query = "SELECT * FROM posts inner join users on posts.post_userId = users.user_id inner join reviews on posts.post_id = reviews.review_postId where review_auth = 1 order by post_countView desc";
    // $query = "SELECT * FROM posts inner join users on posts.post_userId = users.user_id order by posts.post_countView desc";
    $select = $this->getConnection()->prepare($query);
    $select->execute();

    if ( $select->rowCount() == 0 ) {
      return "Não encontrado";
    }

    // $result = [];

    // for ( $i = 0 ; $i < $select->rowCount() ; $i++ ) {
    //   $result[] = $select->fetch(PDO::FETCH_ASSOC);
    // }

    return $select->fetchAll();
  }

  public function listPublish()
  {

    $query = "SELECT * FROM posts inner join users on posts.post_userId = users.user_id inner join reviews on posts.post_id = reviews.review_postId where review_auth = 1 order by posts.post_id desc";
    // $query = "SELECT * FROM posts inner join users on posts.post_userId = users.user_id order by posts.post_id desc";
    $select = $this->getConnection()->prepare($query);
    $select->execute();

    if ( $select->rowCount() == 0 ) {
      return "Não encontrado";
    }

    // $result = [];

    // for ( $i = 0 ; $i < $select->rowCount() ; $i++ ) {
    //   $result[] = $select->fetch(PDO::FETCH_ASSOC);
    // }

    return $select->fetchAll();
  }
  
  public function list(int $postId)
  {

    $query = "SELECT * FROM posts inner join users on posts.post_userId = users.user_id WHERE post_id = :id";
    $select = $this->getConnection()->prepare($query);
    $select->bindParam(":id",$postId,PDO::PARAM_INT);
    $select->execute();

    if ( $select->rowCount() == 0 ) {
      return false;
    }

    return $select->fetch();
    
  }
  
}