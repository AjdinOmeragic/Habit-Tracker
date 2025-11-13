<?php
require_once __DIR__ . '/BaseDao.php';

class CommentDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('comments');
    }

    //*Get all comments for a specific post*/ 
    public function getByPostId($post_id)
    {
        return $this->query("SELECT * FROM comments WHERE post_id = :post_id ORDER BY created_at DESC", ["post_id" => $post_id]);
    }

    //*Get replies to a specific comment (nested comments)*/
    public function getReplies($parent_comment_id)
    {
        return $this->query("SELECT * FROM comments WHERE parent_comment_id = :parent_comment_id ORDER BY created_at ASC", ["parent_comment_id" => $parent_comment_id]);
    }

    //* Get comments count for a post */
    public function getCountByPost($post_id)
    {
        $result = $this->query_unique("SELECT COUNT(*) as comment_count FROM comments WHERE post_id = :post_id", ["post_id" => $post_id]);
        return $result['comment_count'];
    }
}