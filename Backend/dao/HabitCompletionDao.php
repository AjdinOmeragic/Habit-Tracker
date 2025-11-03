<?php
require_once __DIR__ . '/BaseDao.php';

class HabitCompletionDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('habit_completions');
    }

    //* Returns the habit by ID
    public function getByHabitId($habit_id)
    {
        return $this->query("SELECT * FROM habit_completions WHERE habit_id = :habit_id ORDER BY completion_date DESC", ["habit_id" => $habit_id]);
    }

    //* Check if a habit was completed on a specific date
    public function isCompletedOnDate($habit_id, $date)
    {
        $result = $this->query_unique("SELECT * FROM habit_completions WHERE habit_id = :habit_id AND completion_date = :completion_date",["habit_id" => $habit_id, "completion_date" => $date]);
        return !empty($result); //* Returns true if done/completed
    }
}