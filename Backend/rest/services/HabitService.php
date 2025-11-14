<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/HabitDao.php';

class HabitService extends BaseService {
    public function __construct() {
        parent::__construct(new HabitDao());
    }

    public function get_by_user_id($user_id) {
        return $this->dao->getByUserId($user_id);
    }

    public function get_by_category($user_id, $category) {
        return $this->dao->getByCategory($user_id, $category);
    }

    //* Makes sure nothing is missing
    public function create_habit($habit) {
        if (empty($habit['name']) || empty($habit['user_id'])) {
            throw new Exception("Habit name and user ID are required");
        }

        if (empty($habit['category'])) {
            $habit['category'] = 'general';
        }

        return $this->add($habit);
    }
}
?>