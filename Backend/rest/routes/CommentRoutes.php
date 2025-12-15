<?php
require_once __DIR__ . '/../../data/Roles.php';

/**
 * @OA\Get(
 *     path="/comments",
 *     tags={"comments"},
 *     summary="Get all comments - ADMIN ONLY",
 *     security={{"ApiKey": {}}},
 *     @OA\Response(
 *         response=200,
 *         description="Array of all comments in the database"
 *     )
 * )
 */
Flight::route('GET /comments', function() {
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN]);
    Flight::json(Flight::commentService()->get_all());
});

/**
 * @OA\Get(
 *     path="/comments/{id}",
 *     tags={"comments"},
 *     summary="Get comment by ID - AUTHENTICATED USERS",
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the comment",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Returns the comment with the given ID"
 *     )
 * )
 */
Flight::route('GET /comments/@id', function($id) {
    $user = Flight::get('user');
    Flight::json(Flight::commentService()->get_by_id($id));
});

/**
 * @OA\Post(
 *     path="/comments",
 *     tags={"comments"},
 *     summary="Create a new comment - AUTHENTICATED USERS",
 *     security={{"ApiKey": {}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"content", "post_id"},
 *             @OA\Property(property="content", type="string", example="This is a great post!"),
 *             @OA\Property(property="post_id", type="integer", example=1),
 *             @OA\Property(property="parent_comment_id", type="integer", example=null)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Comment created successfully"
 *     )
 * )
 */
Flight::route('POST /comments', function() {
    $current_user = Flight::get('user');
    $data = Flight::request()->data->getData();
    
    // Auto-set user_id from the token
    $data['user_id'] = $current_user->id;
    
    Flight::json(Flight::commentService()->create_comment($data));
});

/**
 * @OA\Get(
 *     path="/comments/post/{post_id}",
 *     tags={"comments"},
 *     summary="Get comments by post ID - PUBLIC",
 *     @OA\Parameter(
 *         name="post_id",
 *         in="path",
 *         required=true,
 *         description="ID of the post",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Array of comments for the specified post"
 *     )
 * )
 */
Flight::route('GET /comments/post/@post_id', function($post_id) {
    Flight::json(Flight::commentService()->get_by_post_id($post_id));
});

/**
 * @OA\Get(
 *     path="/comments/{id}/replies",
 *     tags={"comments"},
 *     summary="Get replies to comment - PUBLIC",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the parent comment",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Array of replies to the specified comment"
 *     )
 * )
 */
Flight::route('GET /comments/@id/replies', function($id) {
    // Public: Anyone can view comment replies
    Flight::json(Flight::commentService()->get_replies($id));
});

/**
 * @OA\Get(
 *     path="/comments/post/{post_id}/count",
 *     tags={"comments"},
 *     summary="Get comment count for post - PUBLIC",
 *     @OA\Parameter(
 *         name="post_id",
 *         in="path",
 *         required=true,
 *         description="ID of the post",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Comment count for the specified post"
 *     )
 * )
 */
Flight::route('GET /comments/post/@post_id/count', function($post_id) {
    // Public: Anyone can see comment counts
    $count = Flight::commentService()->get_count_by_post($post_id);
    Flight::json(['post_id' => $post_id, 'comment_count' => $count]);
});

/**
 * @OA\Put(
 *     path="/comments/{id}",
 *     tags={"comments"},
 *     summary="Update a comment by ID - OWNER OR ADMIN",
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the comment",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="content", type="string", example="Updated comment content")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Comment updated successfully"
 *     )
 * )
 */
Flight::route('PUT /comments/@id', function($id) {
    $current_user = Flight::get('user');
    $comment = Flight::commentService()->get_by_id($id);
    
    if (!$comment) {
        Flight::halt(404, "Comment not found");
    }
    if ($current_user->role !== Roles::ADMIN && $current_user->id != $comment['user_id']) {
        Flight::halt(403, "Unauthorized");
    }
    
    $data = Flight::request()->data->getData();
    Flight::json(Flight::commentService()->update($data, $id));
});

/**
 * @OA\Delete(
 *     path="/comments/{id}",
 *     tags={"comments"},
 *     summary="Delete a comment by ID - OWNER OR ADMIN",
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the comment",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Comment deleted successfully"
 *     )
 * )
 */
Flight::route('DELETE /comments/@id', function($id) {
    $current_user = Flight::get('user');
    $comment = Flight::commentService()->get_by_id($id);
    
    if (!$comment) {
        Flight::halt(404, "Comment not found");
    }
    if ($current_user->role !== Roles::ADMIN && $current_user->id != $comment['user_id']) {
        Flight::halt(403, "Unauthorized");
    }
    
    Flight::commentService()->delete($id);
    Flight::json(['message' => 'Comment deleted successfully']);
});

/**
 * @OA\Get(
 *     path="/comments/user/{user_id}",
 *     tags={"comments"},
 *     summary="Get comments by user ID - OWNER OR ADMIN",
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="user_id",
 *         in="path",
 *         required=true,
 *         description="ID of the user",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Array of comments by the specified user"
 *     )
 * )
 */
Flight::route('GET /comments/user/@user_id', function($user_id) {
    $current_user = Flight::get('user');
    if ($current_user->role !== Roles::ADMIN && $current_user->id != $user_id) {
        Flight::halt(403, "Unauthorized");
    }
    
    Flight::json(Flight::commentService()->get_by_user_id($user_id));
});
?>