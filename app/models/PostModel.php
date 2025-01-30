<?php

class PostModel extends Model
{

  public function create(array $data)
  {

    if (empty($data["title"]) || empty($data["content"]) || empty($data["userId"])) {
      return false;
    }

    $query = "INSERT INTO posts ( post_title , post_content , post_userId , post_tags ) VALUES ( :title , :content , :userId , :tags )";
    $insert = $this->getConnection()->prepare($query);
    $insert->bindParam(":title", $data["title"], PDO::PARAM_STR);
    $insert->bindParam(":content", $data["content"], PDO::PARAM_STR);
    $insert->bindParam(":userId", $data["userId"], PDO::PARAM_INT);
    $insert->bindParam(":tags", $data["tags"], PDO::PARAM_STR);
    $insert->execute();

    if ($insert->rowCount() == 0) {
      return false;
    }

    return true;
  }

  public function update(array $data)
  {

    if (empty($data["title"]) || empty($data["content"]) || empty($data["postId"])) {
      return false;
    }

    $query = "UPDATE posts SET post_active=1,post_title=:title,post_content=:content,post_tags=:tags WHERE post_id = :postId";
    $update = $this->getConnection()->prepare($query);
    $update->bindParam(":title", $data["title"], PDO::PARAM_STR);
    $update->bindParam(":content", $data["content"], PDO::PARAM_STR);
    $update->bindParam(":postId", $data["postId"], PDO::PARAM_INT);
    $update->bindParam(":tags", $data["tags"], PDO::PARAM_STR);
    $update->execute();

    if ($update->rowCount() == 0) {
      return false;
    }

    $query = "UPDATE reviews SET review_auth=0,review_message='' WHERE review_postId = :postId";
    $update = $this->getConnection()->prepare($query);
    $update->bindParam(":postId", $data["postId"], PDO::PARAM_INT);
    $update->execute();

    return true;
  }

  public function listViews()
  {

    $query = "SELECT * FROM posts inner join users on posts.post_userId = users.user_id inner join reviews on posts.post_id = reviews.review_postId where post_active = 1 and review_auth = 1 order by post_countView desc";
    // $query = "SELECT * FROM posts inner join users on posts.post_userId = users.user_id order by posts.post_countView desc";
    $select = $this->getConnection()->prepare($query);
    $select->execute();

    if ($select->rowCount() == 0) {
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

    $query = "SELECT * FROM posts inner join users on posts.post_userId = users.user_id inner join reviews on posts.post_id = reviews.review_postId where post_active = 1 and review_auth = 1 order by posts.post_created_at desc";
    // $query = "SELECT * FROM posts inner join users on posts.post_userId = users.user_id order by posts.post_id desc";
    $select = $this->getConnection()->prepare($query);
    $select->execute();

    if ($select->rowCount() == 0) {
      return "Não encontrado";
    }

    // $result = [];

    // for ( $i = 0 ; $i < $select->rowCount() ; $i++ ) {
    //   $result[] = $select->fetch(PDO::FETCH_ASSOC);
    // }

    return $select->fetchAll();
  }

  public function list(int $postId, bool $isAdmin = false)
  {

    $query = "SELECT * FROM posts inner join users on posts.post_userId = users.user_id inner join reviews on posts.post_id = reviews.review_postId WHERE post_id = :id AND review_auth = 1";

    if ($isAdmin) {
      $query = "SELECT * FROM posts inner join users on posts.post_userId = users.user_id left join reviews on posts.post_id = reviews.review_postId WHERE post_id = :id";
    } else {
      session_start();

      if (isset($_SESSION["user"]["user_id"])) {
        require_once(__DIR__ . "/ViewModel.php");
        $views = new ViewModel();
        $views->add($postId, $_SESSION["user"]["user_id"]);
      }
    }

    $select = $this->getConnection()->prepare($query);
    $select->bindParam(":id", $postId, PDO::PARAM_INT);
    $select->execute();

    if ($select->rowCount() == 0) {
      return false;
    }

    return $select->fetch();
  }

  public function myPublishs(int $userId)
  {

    $query = "SELECT * FROM posts inner join users on posts.post_userId = users.user_id left join reviews on posts.post_id = reviews.review_postId WHERE post_userId = :userId order by review_auth desc";
    $select = $this->getConnection()->prepare($query);
    $select->bindParam(":userId", $userId, PDO::PARAM_INT);
    $select->execute();

    if ($select->rowCount() == 0) {
      return false;
    }

    return $select->fetchAll();
  }

  public function myPublish(int $userId, int $postId)
  {

    $query = "SELECT * FROM posts inner join users on posts.post_userId = users.user_id left join reviews on posts.post_id = reviews.review_postId WHERE post_id = :postId AND post_userId = :userId limit 1";
    $select = $this->getConnection()->prepare($query);
    $select->bindParam(":userId", $userId, PDO::PARAM_INT);
    $select->bindParam(":postId", $postId, PDO::PARAM_INT);
    $select->execute();

    if ($select->rowCount() == 0) {
      return false;
    }

    return $select->fetch();
  }

  public function search($search, $tags)
  {

    $query = 'SELECT * FROM posts join reviews on posts.post_id = reviews.review_postId WHERE post_active = 1 AND review_auth = 1';

    if (!empty($tags)) {

      if (!empty($search)) {
        $query .= " AND post_title like :search AND post_content like :search";
        $search = "%" . $search . "%";
      }

      $query_tag = [];
      
      foreach ($tags as $key => $value) {
        $query_tag[$key] = "%" . $value . "%";
        $query .= " AND post_tags like :" . $key;
      }
      
      $select = $this->getConnection()->prepare($query);
      
      if (!empty($search)) {
        $select->bindParam(":search", $search, PDO::PARAM_STR);
      }
      
      foreach ($query_tag as $key => $value) {
        $select->bindParam(":".$key, $value, PDO::PARAM_STR);
      }
      
      $select->execute();

      return $select->fetchAll();
    }

    if (empty($search)) {
      return false;
    }

    $query .= " AND post_title like :search AND post_content like :search";
    $search = "%" . $search . "%";

    $select = $this->getConnection()->prepare($query);
    $select->bindParam(":search", $search, PDO::PARAM_STR);
    $select->execute();

    return $select->fetchAll();
  }

  public function occult(int $user_id, int $post_id)
  {
    $query = "UPDATE posts set post_active=2 where post_id = :postId and post_userId = :userId";
    $update = $this->getConnection()->prepare($query);
    $update->bindParam(":postId", $post_id, PDO::PARAM_INT);
    $update->bindParam(":userId", $user_id, PDO::PARAM_INT);
    $update->execute();

    if ($update->rowCount() == 0) {
      return false;
    }

    return true;
  }

  public function unhide(int $user_id, int $post_id)
  {
    $query = "UPDATE posts set post_active=1 where post_id = :postId and post_userId = :userId";
    $update = $this->getConnection()->prepare($query);
    $update->bindParam(":postId", $post_id, PDO::PARAM_INT);
    $update->bindParam(":userId", $user_id, PDO::PARAM_INT);
    $update->execute();

    if ($update->rowCount() == 0) {
      return false;
    }

    return true;
  }
}
