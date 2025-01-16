<?php

require_once(__DIR__ . "/../core/Model.php");

class AdminModel extends Model
{

    public function login($userData)
    {

        $query = "SELECT * FROM admins WHERE admin_email=:email LIMIT 1";
        $list = $this->getConnection()->prepare($query);
        $list->bindParam(":email", $userData["email"], PDO::PARAM_STR);
        $list->execute();

        if ($user = $list->fetch()) {

            if (password_verify($userData["password"], $user["admin_password"])) {
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

        if ($select->rowCount() == 0) {

            return false;
        }

        return $select->fetchAll();
    }

    public function post($postId)
    {

        $query = "SELECT * FROM posts WHERE post_id = :id";
        $select = $this->getConnection()->prepare($query);

        $select->bindParam(":id", $postId, PDO::PARAM_INT);

        $select->execute();

        if ($select->rowCount() == 0) {
            return false;
        }

        return $select->fetch();
    }

    public function updatePhoto(int $admin_id, string $photo_name)
    {

        $query = "UPDATE admins SET admin_img=:img WHERE admin_id = :id";
        $update = $this->getConnection()->prepare($query);
        $update->bindParam(":img", $photo_name, PDO::PARAM_STR);
        $update->bindParam(":id", $admin_id, PDO::PARAM_INT);
        $update->execute();

        if ($update->rowCount() == 0) {
            return false;
        }

        return true;
    }

    public function updatePassword(int $user_id, $password)
    {

        $password = password_hash($password, PASSWORD_BCRYPT);

        $query = "UPDATE admins SET admin_password=:pasword WHERE admin_id = :id";
        $update = $this->getConnection()->prepare($query);
        $update->bindParam(":pasword", $password, PDO::PARAM_STR);
        $update->bindParam(":id", $user_id, PDO::PARAM_INT);
        $update->execute();

        if ($update->rowCount() == 0) {
            return false;
        }

        return true;
    }

    public function update(array $data)
    {

        if (empty($data["name"]) || empty($data["email"]) || empty($data["userId"])) {

            return false;
        }

        $userData = ["name" => $data["name"], "email" => $data["email"], "id" => $data["userId"]];

        $query = "update admins set admin_name=:name,admin_email=:email where admin_id = :id";
        $update = $this->getConnection()->prepare($query);
        $update->bindParam(":id", $userData["id"], PDO::PARAM_INT);
        $update->bindParam(":name", $userData["name"], PDO::PARAM_STR);
        $update->bindParam(":email", $userData["email"], PDO::PARAM_STR);
        $update->execute();

        if ($update->rowCount() == 0) {
            return false;
        }

        return true;
    }
}
