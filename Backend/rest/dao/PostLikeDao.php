<?php
require_once __DIR__ . '/BaseDao.php';

class PostLikeDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('post_likes');
    }

    //* Gets the total number of likes for a post
    public function getLikesCount($post_id)
    {
        $result = $this->query_unique("SELECT COUNT(*) as like_count FROM post_likes WHERE post_id = :post_id", ["post_id" => $post_id]);
        return $result['like_count'];
    }

    //* Checks if a specific user has liked a post
    public function getUserLike($post_id, $user_id)
    {
        return $this->query_unique("SELECT * FROM post_likes WHERE post_id = :post_id AND user_id = :user_id", ["post_id" => $post_id, "user_id" => $user_id]);
    }
}