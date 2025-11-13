<?php
require_once __DIR__ . '/BaseDao.php';

class UserDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('users');
    }

    //* Find user by email address
    public function getByEmail($email)
    {
        return $this->query_unique("SELECT * FROM users WHERE email = :email", ["email" => $email]);
    }

    //* Find user by username
    public function getByUsername($username)
    {
        return $this->query_unique("SELECT * FROM users WHERE username = :username", ["username" => $username]);
    }
}