<?php
require_once PROJECT_ROOT_PATH . "/Model/Database.php";

class UserModel extends Database
{
    /**
     * @throws Exception
     */
    public function getAll($params): array
    {
        return $this->select("
            SELECT 
                id, username
            FROM users  
            WHERE username = ?
            AND password = ?
        ", $params, PDO::FETCH_ASSOC);
    }
}