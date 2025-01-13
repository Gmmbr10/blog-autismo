<?php

require_once(__DIR__ . "/../core/Model.php");

class AdminModel extends Model {

    public function login($userData)
    {

        $query = "SELECT * FROM admins WHERE admin_email=:email LIMIT 1";
        $list = $this->getConnection()->prepare($query);
        $list->bindParam(":email",$userData["email"],PDO::PARAM_STR);
        $list->execute();

        if ( $user = $list->fetch() ) {

            if (password_verify($userData["password"],$user["admin_password"])){
                return $user;
            }

            return false;

        }

        return false;
    
    }

    public function postsNotReview()
    {

        $query = "SELECT * FROM posts left join reviews on posts.post_id = reviews.review_postId";
        $select = $this->getConnection()->prepare($query);
        $select->execute();

        if ( $select->rowCount() == 0 ) {

            return false;

        }

        return $select->fetchAll();
        
    }

    public function post($postId)
    {

        $query = "SELECT * FROM posts WHERE post_id = :id";
        $select = $this->getConnection()->prepare($query);

        $select->bindParam(":id",$postId,PDO::PARAM_INT);

        $select->execute();

        if ( $select->rowCount() == 0 ) {
            return false;
        }

        return $select->fetch();

    }
    
}