<?php
require_once 'vendor/autoload.php';

// Include service files and register them
require_once __DIR__ . '/rest/services/UserService.php';
require_once __DIR__ . '/rest/services/HabitService.php';
require_once __DIR__ . '/rest/services/HabitCompletionService.php';
require_once __DIR__ . '/rest/services/PostService.php';
require_once __DIR__ . '/rest/services/PostLikeService.php';
require_once __DIR__ . '/rest/services/CommentService.php';

// Register services
Flight::register('userService', 'UserService');
Flight::register('habitService', 'HabitService');
Flight::register('habitCompletionService', 'HabitCompletionService');
Flight::register('postService', 'PostService');
Flight::register('postLikeService', 'PostLikeService');
Flight::register('commentService', 'CommentService');

// Include route files
require_once __DIR__ . '/rest/routes/UserRoutes.php';
require_once __DIR__ . '/rest/routes/HabitRoutes.php';
require_once __DIR__ . '/rest/routes/HabitCompletionRoutes.php';
require_once __DIR__ . '/rest/routes/PostRoutes.php';
require_once __DIR__ . '/rest/routes/PostLikeRoutes.php';
require_once __DIR__ . '/rest/routes/CommentRoutes.php';

// Default route
Flight::route('/', function() {
    echo 'Hello from Habit Tracker API!';
});

// Start FlightPHP
Flight::start();
?>