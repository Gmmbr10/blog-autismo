<?php

class ViewModel extends Model {

    public function add(int $post_id,int $user_id)
    {

        $query = "SELECT * FROM views WHERE view_postId = :post AND view_userId = :user";
        $search = $this->getConnection()->prepare($query);
        $search->bindParam(":post",$post_id,PDO::PARAM_INT);
        $search->bindParam(":user",$user_id,PDO::PARAM_INT);
        $search->execute();

        if ( $search->rowCount() != 0 ) {
            return;
        }

        $query = "INSERT INTO views ( view_postId , view_userId ) VALUES ( :post , :user )";
        $insert = $this->getConnection()->prepare($query);
        $insert->bindParam(":post",$post_id,PDO::PARAM_INT);
        $insert->bindParam(":user",$user_id,PDO::PARAM_INT);
        $insert->execute();

        if ( $insert->rowCount() == 0 ) {
            return false;
        }

        $this->updateViews($post_id);
        
        return true;

    }

    public function updateViews(int $post_id)
    {

        $countView = $this->countViews($post_id);

        $query = "UPDATE posts SET post_countView=:count WHERE post_id = :post";
        $update = $this->getConnection()->prepare($query);
        $update->bindParam(":post",$post_id,PDO::PARAM_INT);
        $update->bindParam(":count",$countView,PDO::PARAM_INT);
        $update->execute();

        if ( $update->rowCount() == 0 ) {
            return false;
        }

        return true;

    }

    private function countViews(int $post_id):int
    {
        $query = "SELECT * FROM views WHERE view_postId = :post";
        $search = $this->getConnection()->prepare($query);
        $search->bindParam(":post",$post_id,PDO::PARAM_INT);
        $search->execute();

        return $search->rowCount();
    }
    
}