<?php
require_once __DIR__ . '/../../data/Roles.php';

/**
 * @OA\Get(
 *     path="/posts",
 *     tags={"posts"},
 *     summary="Get all posts - AUTHENTICATED USERS",
 *     security={{"ApiKey": {}}},
 *     @OA\Response(
 *         response=200,
 *         description="Array of all posts in the database"
 *     )
 * )
 */
Flight::route('GET /posts', function() {
    $user = Flight::get('user'); // Middleware check any auth user can see it all 
    Flight::json(Flight::postService()->get_all());
});

/**
 * @OA\Get(
 *     path="/posts/{id}",
 *     tags={"posts"},
 *     summary="Get post by ID - AUTHENTICATED USERS",
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the post",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Returns the post with the given ID"
 *     )
 * )
 */
Flight::route('GET /posts/@id', function($id) {
    $user = Flight::get('user'); // Middleware check
    Flight::json(Flight::postService()->get_by_id($id));
});

/**
 * @OA\Post(
 *     path="/posts",
 *     tags={"posts"},
 *     summary="Create a new post - AUTHENTICATED USERS",
 *     security={{"ApiKey": {}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"title", "content"},
 *             @OA\Property(property="title", type="string", example="My First Post"),
 *             @OA\Property(property="content", type="string", example="This is the content of my post"),
 *             @OA\Property(property="user_id", type="integer", example=1, description="Auto-set from token, optional")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Post created successfully"
 *     )
 * )
 */
Flight::route('POST /posts', function() {
    $user = Flight::get('user'); // Middleware check
    $data = Flight::request()->data->getData();
    $data['user_id'] = $user->id;
    Flight::json(Flight::postService()->create_post($data));
});

/**
 * @OA\Get(
 *     path="/posts/user/{user_id}",
 *     tags={"posts"},
 *     summary="Get posts by user ID - AUTHENTICATED USERS",
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
 *         description="Array of posts for the specified user"
 *     )
 * )
 */
Flight::route('GET /posts/user/@user_id', function($user_id) {
    $user = Flight::get('user');
    Flight::json(Flight::postService()->get_by_user_id($user_id));
});

/**
 * @OA\Get(
 *     path="/posts/search",
 *     tags={"posts"},
 *     summary="Search posts - AUTHENTICATED USERS",
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="q",
 *         in="query",
 *         required=false,
 *         description="Search term",
 *         @OA\Schema(type="string", example="habit")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Array of posts matching search term"
 *     )
 * )
 */
Flight::route('GET /posts/search', function() {
    $user = Flight::get('user');
    $search_term = Flight::request()->query['q'] ?? '';
    Flight::json(Flight::postService()->search_posts($search_term));
});

/**
 * @OA\Put(
 *     path="/posts/{id}",
 *     tags={"posts"},
 *     summary="Update a post by ID - OWNER OR ADMIN",
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the post",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="title", type="string", example="Updated post title"),
 *             @OA\Property(property="content", type="string", example="Updated post content")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Post updated successfully"
 *     )
 * )
 */
Flight::route('PUT /posts/@id', function($id) {
    $user = Flight::get('user');
    $post = Flight::postService()->get_by_id($id);
    
    if (!$post) {
        Flight::halt(404, "Post not found");
    }
    
    // Only post owner or admin can update
    if ($user->role !== Roles::ADMIN && $user->id != $post['user_id']) {
        Flight::halt(403, "Unauthorized - You can only edit your own posts");
    }
    
    $data = Flight::request()->data->getData();
    Flight::json(Flight::postService()->update($data, $id));
});

/**
 * @OA\Delete(
 *     path="/posts/{id}",
 *     tags={"posts"},
 *     summary="Delete a post by ID - OWNER OR ADMIN",
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the post",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Post deleted successfully"
 *     )
 * )
 */
Flight::route('DELETE /posts/@id', function($id) {
    $user = Flight::get('user');
    $post = Flight::postService()->get_by_id($id);
    
    if (!$post) {
        Flight::halt(404, "Post not found");
    }
    if ($user->role !== Roles::ADMIN && $user->id != $post['user_id']) {
        Flight::halt(403, "Unauthorized - You can only delete your own posts");
    }
    
    Flight::postService()->delete($id);
    Flight::json(['message' => 'Post deleted successfully']);
});
?>