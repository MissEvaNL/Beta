<?php
/**
 *       Applicatie Bootstrap
 * Alle rechten voorbehouden aand TalpaCMS
 */

require_once dirname(__DIR__) . '/vendor/autoload.php';
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');
ini_set('display_errors', 1);

/**
 *  Applicatie Routeren
 */


new \Core\Querybuilder;
new \App\Config;

/**
 *  Geven van de sessie via database
 */

$session = new \App\Session;
$session::init();

$router = new Core\Router();
$router->add('', ['controller' => 'Dashboard', 'action' => 'index']);
$router->add('client', ['controller' => 'Client', 'action' => 'index']);
$router->add('noflash', ['controller' => 'Client', 'action' => 'noflash']);
$router->add('dashboard', ['controller' => 'Dashboard', 'action' => 'index']);
$router->add('shop', ['controller' => 'Store', 'action' => 'index']);
$router->add('online', ['controller' => 'Online', 'action' => 'index']);

$router->add('feeds', ['controller' => 'Feed', 'action' => 'home']);
$router->add('feeds/page/{page:\d+}', ['controller' => 'Feed', 'action' => 'home']);
$router->add('feeds/post/timeline', ['controller' => 'Feed', 'action' => 'postFeedTimeline']);
$router->add('feeds/post/id/{feedid:\d+}', ['controller' => 'Profile', 'action' => 'postfeed']);
$router->add('feeds/{page}/{id:\d+}/post', ['controller' => 'Feed', 'action' => 'postFeedTimeline']);

$router->add('login', ['controller' => 'Login', 'action' => 'new']);
$router->add('staff', ['controller' => 'Staff', 'action' => 'index']);
$router->add('register', ['controller' => 'Signup', 'action' => 'new']);
$router->add('logout', ['controller' => 'Logout', 'action' => 'destroy']); 
$router->add('account/transactions', ['controller' => 'Account', 'action' => 'transactionsOverview']);

$router->add('profile/{username:\w+}', ['controller' => 'Profile', 'action' => 'index']);

$router->add('news', ['controller' => 'Article', 'action' => 'overview']);
$router->add('news/article/{id:\d+}', ['controller' => 'Article', 'action' => 'article']);
$router->add('news/article/{id:\d+}/delete', ['controller' => 'Article', 'action' => 'delete']);
$router->add('news/page/{page:\d+}', ['controller' => 'Article', 'action' => 'overview']);
$router->add('news/article/{id:\d+}/reply', ['controller' => 'Article', 'action' => 'reply']);

$router->add('forum', ['controller' => 'Forums', 'action' => 'index']);
$router->add('forum/category/{id:\d+}', ['controller' => 'Forums', 'action' => 'category']);
$router->add('forum/category/{id:\d+}/new', ['controller' => 'Forums', 'action' => 'createTopicView']);
$router->add('forum/category/{id:\d+}/create', ['controller' => 'Forums', 'action' => 'createTopic']);
$router->add('forum/thread/{id:\d+}', ['controller' => 'Forums', 'action' => 'topic']);
$router->add('forum/thread/{id:\d+}/page/{page:\d+}', ['controller' => 'Forums', 'action' => 'topic']);
$router->add('forum/thread/{id:\d+}/postreply', ['controller' => 'Forums', 'action' => 'postreply']);
$router->add('forum/thread/{id:\d+}/{reply}', ['controller' => 'Forums', 'action' => 'topic']);
$router->add('forum/thread/edit/{id:\d+}', ['controller' => 'Forums', 'action' => 'edit']);
$router->add('forum/thread/delete', ['controller' => 'Forums', 'action' => 'delete']);
$router->add('forum/thread/like', ['controller' => 'Forums', 'action' => 'like']);
$router->add('forum/thread/close', ['controller' => 'Forums', 'action' => 'close']);
$router->add('forum/thread/sticky', ['controller' => 'Forums', 'action' => 'sticky']);

$router->add('shop/postreply', ['controller' => 'Store', 'action' => 'postreply']);
$router->add('shop/redeem', ['controller' => 'Store', 'action' => 'redeemCoupon']);

$router->add('password/reset/{token:[\da-f]+}', ['controller' => 'Password', 'action' => 'reset']);
$router->add('{sub}/tag/all/{tag}', ['controller' => 'Tag', 'action' => 'all']);
$router->add('{controller}/{action}');
$router->add('my/{controller/{action}');

$router->add('housekeeping', ['controller' => 'Dashboard', 'action' => 'index', 'path' => 'Housekeeping']);
$router->add('housekeeping/remote/user/{username}', ['controller' => 'Remote', 'action' => 'user', 'path' => 'Housekeeping']);
$router->add('housekeeping/remote/user/{username}/unban', ['controller' => 'Remote', 'action' => 'unban', 'path' => 'Housekeeping']);
$router->add('housekeeping/remote/user/{username}/{do}', ['controller' => 'Remote', 'action' => 'user', 'path' => 'Housekeeping']);
$router->add('housekeeping/control/user/{username}/{do}', ['controller' => 'Remote', 'action' => 'change', 'path' => 'Housekeeping']);
$router->add('housekeeping/manage/news', ['controller' => 'Article', 'action' => 'index', 'path' => 'Housekeeping']);
$router->add('housekeeping/manage/widgets', ['controller' => 'Widgets', 'action' => 'index', 'path' => 'Housekeeping']);
$router->add('housekeeping/manage/forums', ['controller' => 'Forums', 'action' => 'manage', 'path' => 'Housekeeping']);
$router->add('housekeeping/manage/forums/catid/{id:\d+}', ['controller' => 'Forums', 'action' => 'manage', 'path' => 'Housekeeping']);
$router->add('housekeeping/forums/{do}/create', ['controller' => 'Forums', 'action' => 'create', 'path' => 'Housekeeping']);
$router->add('housekeeping/forums/delete/{do}/{id:\d+}', ['controller' => 'Forums', 'action' => 'delete', 'path' => 'Housekeeping']);
$router->add('housekeeping/manage/news/create', ['controller' => 'Article', 'action' => 'create', 'path' => 'Housekeeping']);
$router->add('housekeeping/manage/permissions', ['controller' => 'Permissions', 'action' => 'index', 'path' => 'Housekeeping']);
$router->add('housekeeping/manage/permissions/{action}', ['controller' => 'Permissions', 'path' => 'Housekeeping']);
$router->add('housekeeping/manage/permissions/{id:\d+}/{action}', ['controller' => 'Permissions', 'path' => 'Housekeeping']);
$router->add('housekeeping/list/{role}/{id:\d+}/users', ['controller' => 'Permissions', 'action' => 'list', 'path' => 'Housekeeping']);
$router->add('housekeeping/api/getwidget/{do}', ['controller' => 'Api', 'action' => 'getWidget', 'path' => 'Housekeeping']);
$router->add('housekeeping/widget/{id:\d+}/delete', ['controller' => 'Widgets', 'action' => 'delete', 'path' => 'Housekeeping']);
$router->add('housekeeping/{controller}/{action}', ['path' => 'Housekeeping']);

$router->dispatch($_SERVER['QUERY_STRING']);