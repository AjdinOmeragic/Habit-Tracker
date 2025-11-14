<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/UserDao.php';

class UserService extends BaseService {
    public function __construct() {
        parent::__construct(new UserDao());
    }

    public function get_by_email($email) {
        return $this->dao->getByEmail($email);
    }

    public function get_by_username($username) {
        return $this->dao->getByUsername($username);
    }

    //* Checks if user name or email are taken if not will hash the password
    public function register_user($user) {
        if ($this->get_by_email($user['email'])) {
            throw new Exception("Email already registered");
        }

        if ($this->get_by_username($user['username'])) {
            throw new Exception("Username already taken");
        }

        $user['password_hash'] = password_hash($user['password'], PASSWORD_DEFAULT);
        unset($user['password']);

        return $this->add($user);
    }
}
?>