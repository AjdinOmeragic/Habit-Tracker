<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/PostDao.php';

class PostService extends BaseService {
    public function __construct() {
        parent::__construct(new PostDao());
    }

    public function get_by_user_id($user_id) {
        return $this->dao->getByUserId($user_id);
    }

    public function search_posts($search_term) {
        return $this->dao->searchPosts($search_term);
    }

    //* Makes sure the post will actually exist
    public function create_post($post) {
        if (empty($post['title']) || empty($post['content']) || empty($post['user_id'])) {
            throw new Exception("Title, content, and user ID are required");
        }

        if (strlen($post['title']) > 200) {
            throw new Exception("Title must be less than 200 characters");
        }

        return $this->add($post);
    }
}
?>