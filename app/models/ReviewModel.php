<?php

class ReviewModel extends Model {

    public function aprove($postId)
    {

        $reviewExists = $this->reviewExists($postId);
        
        if ( $reviewExists != false ) {

            $query = "UPDATE reviews SET review_auth = 1 WHERE review_id = :review";
            $aprove = $this->getConnection()->prepare($query);
            $aprove->bindParam(":review",$reviewExists["review_id"],PDO::PARAM_INT);
            $aprove->execute();
            
            if ( $aprove->rowCount() == 0 ) {
                return false;
            }
            
            return true;
        }

        $query = "INSERT INTO reviews ( review_postId , review_auth ) VALUES ( :post , 1 )";
        $aprove = $this->getConnection()->prepare($query);
        $aprove->bindParam(":post",$postId,PDO::PARAM_INT);
        $aprove->execute();

        if ( $aprove->rowCount() == 0 ) {
            return false;
        }

        return true;
        
    }

    private function reviewExists($postId)
    {

        $query = "SELECT * FROM reviews WHERE review_postId = :post";
        $select = $this->getConnection()->prepare($query);
        $select->bindParam(":post",$postId,PDO::PARAM_INT);
        $select->execute();

        if ( $select->rowCount() == 0 ) {
            return false;
        }

        return $select->fetch();
        
    }
    
}