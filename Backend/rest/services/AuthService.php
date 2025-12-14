<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/AuthDao.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthService extends BaseService {
    private $auth_dao;
    
    public function __construct() {
        $this->auth_dao = new AuthDao();
        parent::__construct($this->auth_dao);
    }

    public function get_user_by_email($email){
        return $this->auth_dao->get_user_by_email($email);
    }

    public function register($entity) {   
        if (empty($entity['username']) || empty($entity['email']) || empty($entity['password'])) {
            return ['success' => false, 'error' => 'Username, email and password are required.'];
        }

        $email_exists = $this->auth_dao->get_user_by_email($entity['email']);
        if($email_exists){
            return ['success' => false, 'error' => 'Email already registered.'];
        }

        $username_exists = $this->auth_dao->get_user_by_username($entity['username']);
        if($username_exists){
            return ['success' => false, 'error' => 'Username already taken.'];
        }

        $entity['password_hash'] = password_hash($entity['password'], PASSWORD_BCRYPT);
        unset($entity['password']);
        
        if(!isset($entity['role'])) {
            $entity['role'] = 'user';
        }

        $entity = parent::add($entity);
        unset($entity['password_hash']);
        
        return ['success' => true, 'data' => $entity];             
    }

    public function login($entity) {   
        if (empty($entity['email']) || empty($entity['password'])) {
            return ['success' => false, 'error' => 'Email and password are required.'];
        }

        $user = $this->auth_dao->get_user_by_email($entity['email']);
        if(!$user){
            return ['success' => false, 'error' => 'Invalid email or password.'];
        }

        if(!password_verify($entity['password'], $user['password_hash'])) {
            return ['success' => false, 'error' => 'Invalid email or password.'];
        }

        unset($user['password_hash']);
        
        $jwt_payload = [
            'user' => $user,
            'iat' => time(),
            'exp' => time() + (60 * 60 * 24) // valid for day
        ];

        $token = JWT::encode(
            $jwt_payload,
            Config::JWT_SECRET(),
            'HS256'
        );

        return ['success' => true, 'data' => array_merge($user, ['token' => $token])];             
    }
}