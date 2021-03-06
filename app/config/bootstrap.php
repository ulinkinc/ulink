<?php
/**
 * This file is loaded automatically by the app/webroot/index.php file after core.php
 *
 * This file should load/create any application wide configuration settings, such as
 * Caching, Logging, loading additional configuration files.
 *
 * You should also use this file to include any files that provide global functions/constants
 * that your application uses.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.10.8.2117
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Cache Engine Configuration
 * Default settings provided below
 *
 * File storage engine.
 *
 * 	 Cache::config('default', array(
 *		'engine' => 'File', //[required]
 *		'duration'=> 3600, //[optional]
 *		'probability'=> 100, //[optional]
 * 		'path' => CACHE, //[optional] use system tmp directory - remember to use absolute path
 * 		'prefix' => 'cake_', //[optional]  prefix every cache file with this string
 * 		'lock' => false, //[optional]  use file locking
 * 		'serialize' => true, // [optional]
 * 		'mask' => 0666, // [optional] permission mask to use when creating cache files
 *	));
 *
 * APC (http://pecl.php.net/package/APC)
 *
 * 	 Cache::config('default', array(
 *		'engine' => 'Apc', //[required]
 *		'duration'=> 3600, //[optional]
 *		'probability'=> 100, //[optional]
 * 		'prefix' => Inflector::slug(APP_DIR) . '_', //[optional]  prefix every cache file with this string
 *	));
 *
 * Xcache (http://xcache.lighttpd.net/)
 *
 * 	 Cache::config('default', array(
 *		'engine' => 'Xcache', //[required]
 *		'duration'=> 3600, //[optional]
 *		'probability'=> 100, //[optional]
 *		'prefix' => Inflector::slug(APP_DIR) . '_', //[optional] prefix every cache file with this string
 *		'user' => 'user', //user from xcache.admin.user settings
 *		'password' => 'password', //plaintext password (xcache.admin.pass)
 *	));
 *
 * Memcache (http://memcached.org/)
 *
 * 	 Cache::config('default', array(
 *		'engine' => 'Memcache', //[required]
 *		'duration'=> 3600, //[optional]
 *		'probability'=> 100, //[optional]
 * 		'prefix' => Inflector::slug(APP_DIR) . '_', //[optional]  prefix every cache file with this string
 * 		'servers' => array(
 * 			'127.0.0.1:11211' // localhost, default port 11211
 * 		), //[optional]
 * 		'persistent' => true, // [optional] set this to false for non-persistent connections
 * 		'compress' => false, // [optional] compress data in Memcache (slower, but uses less memory)
 *	));
 *
 *  Wincache (http://php.net/wincache)
 *
 * 	 Cache::config('default', array(
 *		'engine' => 'Wincache', //[required]
 *		'duration'=> 3600, //[optional]
 *		'probability'=> 100, //[optional]
 *		'prefix' => Inflector::slug(APP_DIR) . '_', //[optional]  prefix every cache file with this string
 *	));
 */
Cache::config('default', array('engine' => 'File'));

/**
 * The settings below can be used to set additional paths to models, views and controllers.
 *
 * App::build(array(
 *     'Model'                     => array('/path/to/models', '/next/path/to/models'),
 *     'Model/Behavior'            => array('/path/to/behaviors', '/next/path/to/behaviors'),
 *     'Model/Datasource'          => array('/path/to/datasources', '/next/path/to/datasources'),
 *     'Model/Datasource/Database' => array('/path/to/databases', '/next/path/to/database'),
 *     'Model/Datasource/Session'  => array('/path/to/sessions', '/next/path/to/sessions'),
 *     'Controller'                => array('/path/to/controllers', '/next/path/to/controllers'),
 *     'Controller/Component'      => array('/path/to/components', '/next/path/to/components'),
 *     'Controller/Component/Auth' => array('/path/to/auths', '/next/path/to/auths'),
 *     'Controller/Component/Acl'  => array('/path/to/acls', '/next/path/to/acls'),
 *     'View'                      => array('/path/to/views', '/next/path/to/views'),
 *     'View/Helper'               => array('/path/to/helpers', '/next/path/to/helpers'),
 *     'Console'                   => array('/path/to/consoles', '/next/path/to/consoles'),
 *     'Console/Command'           => array('/path/to/commands', '/next/path/to/commands'),
 *     'Console/Command/Task'      => array('/path/to/tasks', '/next/path/to/tasks'),
 *     'Lib'                       => array('/path/to/libs', '/next/path/to/libs'),
 *     'Locale'                    => array('/path/to/locales', '/next/path/to/locales'),
 *     'Vendor'                    => array('/path/to/vendors', '/next/path/to/vendors'),
 *     'Plugin'                    => array('/path/to/plugins', '/next/path/to/plugins'),
 * ));
 *
 */

/**
 * Custom Inflector rules, can be set to correctly pluralize or singularize table, model, controller names or whatever other
 * string is passed to the inflection functions
 *
 * Inflector::rules('singular', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 * Inflector::rules('plural', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 *
 */

/**
 * Plugins need to be loaded manually, you can either load them one by one or all of them in a single call
 * Uncomment one of the lines below, as you need. make sure you read the documentation on CakePlugin to use more
 * advanced ways of loading plugins
 *
 * CakePlugin::loadAll(); // Loads all plugins at once
 * CakePlugin::load('DebugKit'); //Loads a single plugin named DebugKit
 *
 */

CakePlugin::load('Mongodb');


 // custom functions
function prd($var) {
    pr($var);
    die;
}

// defined vars
// theulink.com GOOGLE_MAP_API_KEY - 'ABQIAAAAJZHppX6qQ2j8YZe0T5gGYRQV8rZfp7KNipKKWaBNmcOh9zalzBS98OtvEZAOD4PDe7YWyLzhtSNyGw'
if (!defined('GOOGLE_MAP_API_KEY')) {
    define('GOOGLE_MAP_API_KEY', 'ABQIAAAAJZHppX6qQ2j8YZe0T5gGYRQV8rZfp7KNipKKWaBNmcOh9zalzBS98OtvEZAOD4PDe7YWyLzhtSNyGw');
}
if (!defined('SERVER_NAME')) {
    define('SERVER_NAME', $_SERVER['SERVER_NAME']);
}
if (!defined('DEFAULT_URL')) {
    define('DEFAULT_URL', 'http://' . $_SERVER['SERVER_NAME'] . '/');
}
if (!defined('ZEND_LIB_DIR')) {
    define('ZEND_LIB_DIR', '/app/webroot/ulink-youtube/library/');
}
if (!defined('BASE_URL')) {
    define('BASE_URL', '/app/webroot/img/editorfiles/');
}
if (!defined('FACEBOOK_APP_ID')) {
    define('FACEBOOK_APP_ID', '243782652334409');
}
if (!defined('FACEBOOK_APP_URL')) {
    define('FACEBOOK_APP_URL', 'http://' . $_SERVER['SERVER_NAME'] . '/app/webroot/xd_receiver.htm');
}
if (!defined('FACEBOOK_APP_SECRET')) {
    define('FACEBOOK_APP_SECRET', 'c50f97cbd574f6f67234a34da3265dba');
}

if (!defined('YOUTUBE_DEV_KEY')) {
    define('YOUTUBE_DEV_KEY', 'AI39si6454XolD2jv7ALSa3vC-hlxP6sH2YGTtqvUpPhHNdWm98yTYXmu_8WepCMetnTHOI9O9OXSCnPX9KZn8NLpeMBkt0eRQ');
}

if (!defined('YOUTUBE_USERNAME')) {
    define('YOUTUBE_USERNAME', 'bennie.ulink@gmail.com');
}
if (!defined('YOUTUBE_PASSWORD')) {
    define('YOUTUBE_PASSWORD', 'uLink1983');
}

if (!defined('RECENT_REVIEWS_TITLE')) {
    define('RECENT_REVIEWS_TITLE', 'uLink - Recent Reviews');
}

if (!defined('NEWLY_ADDED_SCHOOL')) {
    define('NEWLY_ADDED_SCHOOL', 'uLink - Newly Added Schools');
}

if (!defined('TOP_RATED_SCHOOLS')) {
    define('TOP_RATED_SCHOOLS', 'uLink - Top Rated Schools');
}

if (!defined('SUGGEST_A_SCHOOL')) {
    define('SUGGEST_A_SCHOOL', 'uLink - Suggest a School');
}

if (!defined('IPINFODB_KEY')) {
    define('IPINFODB_KEY', '361b0020ff924f4fbb90c25b9b3f07a604a89e9c0b0aa8bc304d1192bb26e96f');
}

// S3 Uploader Auth keys
if(!defined('AWS_ACCESS_KEY')) {
    define('AWS_ACCESS_KEY', 'AKIAIWNS6CENCJVPALBA');
}
if(!defined('AWS_SECRET_KEY')) {
    define('AWS_SECRET_KEY', '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');
}
if(!defined('S3_IMAGE_BUCKET')) {
    define('S3_IMAGE_BUCKET', 'ulink_images');
}

// first define the base url for images
if (!defined('URL_IMAGES')) {
    if(inDevMode()) {
        define('URL_IMAGES', 'http://localhost:8888/img/');
    } else {
        define('URL_IMAGES', 'http://www.theulink.com/img/');
    }
}
if (!defined('URL_IMAGES_S3')) {
    if(inDevMode()) {
        define('URL_IMAGES_S3', 'http://localhost:8888/img/');
    } else {  
        define('URL_IMAGES_S3', 'https://s3.amazonaws.com/ulink_images/img/'); 
    }
}

// define the image urls for defaults
if (!defined('URL_DEFAULT_USER_IMAGE')) {
    define('URL_DEFAULT_USER_IMAGE', URL_IMAGES.'defaults/default_user.jpg');
}
if (!defined('URL_DEFAULT_SNAP_IMAGE')) {
    define('URL_DEFAULT_SNAP_IMAGE', URL_IMAGES.'defaults/default_snap.png');
}
if (!defined('URL_DEFAULT_EVENT_IMAGE')) {
    define('URL_DEFAULT_EVENT_IMAGE', URL_IMAGES.'defaults/default_campus_event.png');
}
if (!defined('URL_DEFAULT_FEATURED_EVENT_IMAGE')) {
    define('URL_DEFAULT_FEATURED_EVENT_IMAGE', URL_IMAGES.'defaults/default_featured_event.png');
}

// define the user model image urls
if (!defined('URL_USER_IMAGE')) {
    define('URL_USER_IMAGE', URL_IMAGES_S3.'files/users/');
}
if (!defined('URL_USER_IMAGE_THUMB')) {
    define('URL_USER_IMAGE_THUMB', URL_IMAGES_S3.'files/users/thumbs/');
}
if (!defined('URL_USER_IMAGE_MEDIUM')) {
    define('URL_USER_IMAGE_MEDIUM', URL_IMAGES_S3.'files/users/medium/');
}

// define the event model image urls
if (!defined('URL_EVENT_IMAGE')) {
    define('URL_EVENT_IMAGE', URL_IMAGES_S3.'files/events/');
}
if (!defined('URL_EVENT_IMAGE_THUMB')) {
    define('URL_EVENT_IMAGE_THUMB', URL_IMAGES_S3.'files/events/thumbs/');
}
if (!defined('URL_EVENT_IMAGE_MEDIUM')) {
    define('URL_EVENT_IMAGE_MEDIUM', URL_IMAGES_S3.'files/events/medium/');
}
// define the snapshot model image urls
if (!defined('URL_SNAP_IMAGE')) {
    define('URL_SNAP_IMAGE', URL_IMAGES_S3.'files/snaps/');
}
if (!defined('URL_SNAP_IMAGE_THUMB')) {
    define('URL_SNAP_IMAGE_THUMB', URL_IMAGES_S3.'files/snaps/thumbs/');
}
if (!defined('URL_SNAP_IMAGE_MEDIUM')) {
    define('URL_SNAP_IMAGE_MEDIUM', URL_IMAGES_S3.'files/snaps/medium/');
}

// define the school model image urls
if (!defined('URL_SCHOOL_IMAGE')) {
    define('URL_SCHOOL_IMAGE', URL_IMAGES_S3.'files/schools/');
}