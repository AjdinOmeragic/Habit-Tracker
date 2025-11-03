<?php
require_once __DIR__ . '/BaseDao.php';

class HabitDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('habits');
    }

    //* Get all habits for a specific user
    public function getByUserId($user_id)
    {
        return $this->query("SELECT * FROM habits WHERE user_id = :user_id", ["user_id" => $user_id]);
    }

    //* Get habits for a user filtered by category
    public function getByCategory($user_id, $category)
    {
        return $this->query("SELECT * FROM habits WHERE user_id = :user_id AND category = :category", ["user_id" => $user_id, "category" => $category]);
    }
}