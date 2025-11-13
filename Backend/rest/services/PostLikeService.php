<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/PostLikeDao.php';

class PostLikeService extends BaseService {
    public function __construct() {
        parent::__construct(new PostLikeDao());
    }

    public function get_all() {
        return $this->dao->getAll();
    }

    public function get_likes_count($post_id) {
        return $this->dao->getLikesCount($post_id);
    }

    public function get_user_like($post_id, $user_id) {
        return $this->dao->getUserLike($post_id, $user_id);
    }

    //* No duplicate Likes 
    public function toggle_like($post_id, $user_id) {
        $existing_like = $this->get_user_like($post_id, $user_id);
        
        if ($existing_like) {
            $this->delete($existing_like['id']);
            return ['action' => 'unliked', 'likes_count' => $this->get_likes_count($post_id)];
        } 
        
        else {
            $like_data = [
                'post_id' => $post_id,
                'user_id' => $user_id
            ];
            $this->add($like_data);
            return ['action' => 'liked', 'likes_count' => $this->get_likes_count($post_id)];
        }
    }
}
?>