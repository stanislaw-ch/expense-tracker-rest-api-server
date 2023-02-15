<?php
require_once PROJECT_ROOT_PATH . "/Model/Database.php";

class CategoryModel extends Database
{
    /**
     * @throws Exception
     */
    public function getAll($params): array
    {
        return $this->select("
            SELECT 
                *
            FROM categories  
            WHERE user_id = ?
            ORDER BY id 
        ", ["i", ...$params]);
    }
}