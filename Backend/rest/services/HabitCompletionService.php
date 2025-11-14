<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/HabitCompletionDao.php';

class HabitCompletionService extends BaseService {
    public function __construct() {
        parent::__construct(new HabitCompletionDao());
    }

    public function get_all() {
        return $this->dao->getAll();
    }

    public function get_by_habit_id($habit_id) {
        return $this->dao->getByHabitId($habit_id);
    }
    
    public function is_completed_on_date($habit_id, $date) {
        return $this->dao->isCompletedOnDate($habit_id, $date);
    }

    //*Makes sure the user can't double add in one day
    public function mark_completed($completion) {
        if (empty($completion['habit_id']) || empty($completion['completion_date'])) {
            throw new Exception("Habit ID and completion date are required");
        }
        
        if ($this->is_completed_on_date($completion['habit_id'], $completion['completion_date'])) {
            throw new Exception("Habit already completed on this date");
        }

        return $this->add($completion);
    }
}
?>