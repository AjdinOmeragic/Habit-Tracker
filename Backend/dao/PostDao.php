<?php
require_once __DIR__ . '/BaseDao.php';

class PostDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('posts');
    }

    public function getByUserId($user_id)
    {
        return $this->query("SELECT * FROM posts WHERE user_id = :user_id ORDER BY created_at DESC", ["user_id" => $user_id]);
    }

    //* Search posts by title or content
    public function searchPosts($search_term)
    {
        return $this->query("SELECT * FROM posts WHERE title LIKE :search OR content LIKE :search ORDER BY created_at DESC", ["search" => "%" . $search_term . "%"]);
    }
}