<?php
require_once PROJECT_ROOT_PATH . "/Model/Database.php";

class TransactionModel extends Database
{
    /**
     * @throws Exception
     */
    public function getAll($params): array
    {
        return $this->select("
            SELECT 
                transactions.id, sum, 
                transactions.expense, 
                show_in_balance, create_at, 
                a.title AS account,
                c.title AS category,
                c.icon AS icon
            FROM transactions  
            LEFT JOIN accounts a ON a.id = transactions.account_id
            LEFT JOIN categories c ON c.id = transactions.category_id
            WHERE transactions.user_id = ?
            AND a.user_id = ?
            AND c.user_id = ?
            GROUP BY transactions.id 
            LIMIT ?
        ", $params, PDO::FETCH_ASSOC);
    }

    /**
     * @throws Exception
     */
    public function getSumByMonthAndExpenseType($params): array
    {
        return $this->select("
            SELECT 
                CAST(SUM(transactions.sum) AS DECIMAL(10, 2)) AS total
            FROM transactions  
            LEFT JOIN accounts a ON a.id = transactions.account_id
            LEFT JOIN categories c ON c.id = transactions.category_id
            WHERE transactions.user_id = ?
            AND a.user_id = ?
            AND c.user_id = ?
            AND EXTRACT(year FROM transactions.create_at) = ?
            AND EXTRACT(month FROM transactions.create_at) = ?
            AND transactions.expense = ?
            LIMIT ?
        ", $params);
    }

    /**
     * @throws Exception
     */
    public function getAmount($params): array
    {
        return $this->select("
            SELECT 
                CAST(SUM(transactions.sum) AS DECIMAL(10, 2)) AS total
            FROM transactions  
            WHERE transactions.user_id = ?
            AND EXTRACT(year FROM transactions.create_at) = ?
            AND transactions.expense = ?
            GROUP BY EXTRACT(month FROM transactions.create_at)
            LIMIT ?
        ", $params);
    }
}