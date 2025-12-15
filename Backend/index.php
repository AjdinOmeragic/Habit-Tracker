<?php
require_once 'vendor/autoload.php';
require_once __DIR__ . '/config.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Authorization, Content-Type");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Include service files and register them
require_once __DIR__ . '/rest/services/AuthService.php';
require_once __DIR__ . '/rest/services/UserService.php';
require_once __DIR__ . '/rest/services/HabitService.php';
require_once __DIR__ . '/rest/services/HabitCompletionService.php';
require_once __DIR__ . '/rest/services/PostService.php';
require_once __DIR__ . '/rest/services/PostLikeService.php';
require_once __DIR__ . '/rest/services/CommentService.php';
require_once __DIR__ . '/middleware/AuthMiddleware.php';
require_once __DIR__ . '/data/Roles.php';

// Register services
Flight::register('auth_service', 'AuthService');
Flight::register('userService', 'UserService');
Flight::register('habitService', 'HabitService');
Flight::register('habitCompletionService', 'HabitCompletionService');
Flight::register('postService', 'PostService');
Flight::register('postLikeService', 'PostLikeService');
Flight::register('commentService', 'CommentService');
Flight::register('auth_middleware', 'AuthMiddleware');

Flight::route('/*', function() {
    // Allow access to auth routes and Swagger docs without authentication
    $url = Flight::request()->url;
    if(
        strpos($url, '/auth/login') === 0 ||
        strpos($url, '/auth/register') === 0 ||
        strpos($url, '/docs') === 0 ||
        strpos($url, '/swagger') === 0 ||
        $url === '/'
    ) {
        return TRUE;
    } else {
        try {
            // Get token from Authorization header
            $headers = getallheaders();
            $authHeader = isset($headers['Authorization']) ? $headers['Authorization'] : '';
            
            if (empty($authHeader) || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
                Flight::halt(401, "Missing or invalid authentication header");
            }
            
            $token = $matches[1];
            if(Flight::auth_middleware()->verifyToken($token))
                return TRUE;
        } catch (\Exception $e) {
            Flight::halt(401, $e->getMessage());
        }
    }
});

// Include route files
require_once __DIR__ . '/rest/routes/AuthRoutes.php';
require_once __DIR__ . '/rest/routes/UserRoutes.php';
require_once __DIR__ . '/rest/routes/HabitRoutes.php';
require_once __DIR__ . '/rest/routes/HabitCompletionRoutes.php';
require_once __DIR__ . '/rest/routes/PostRoutes.php';
require_once __DIR__ . '/rest/routes/PostLikeRoutes.php';
require_once __DIR__ . '/rest/routes/CommentRoutes.php';

Flight::route('/', function() {
    echo 'Hello from Habit Tracker API!';
});

/*--------------------------------------------------------------------------------*/
/* --------------------- TOKEN READING -------------------------------------------*/
/*--------------------------------------------------------------------------------*/
Flight::before('start', function() {
    $headers = getallheaders();
    $authHeader = $headers['Authorization'] ?? '';
    
    if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
        $token = $matches[1];
        
        try {
            $secretKey = "AjdinWasHere";
            $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));
            Flight::set('user', $decoded->user);
            
        } catch (Exception $e) {
            error_log("JWT Error: " . $e->getMessage());
        }
    }
});

// Start FlightPHP
Flight::start();
?>