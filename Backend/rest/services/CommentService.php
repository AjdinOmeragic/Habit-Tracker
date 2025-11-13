<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/CommentDao.php';

class CommentService extends BaseService {
    public function __construct() {
        parent::__construct(new CommentDao());
    }

    public function get_by_post_id($post_id) {
        return $this->dao->getByPostId($post_id);
    }

    public function get_replies($parent_comment_id) {
        return $this->dao->getReplies($parent_comment_id);
    }

    public function get_count_by_post($post_id) {
        return $this->dao->getCountByPost($post_id);
    }

    //* This part makes sure that there is actully an value and not empty
    public function create_comment($comment) {
        if (empty($comment['content']) || empty($comment['post_id']) || empty($comment['user_id'])) {
            throw new Exception("Content, post ID, and user ID are required");
        }

        return $this->add($comment);
    }
}
?>