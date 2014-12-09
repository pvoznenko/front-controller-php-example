<?php

/**
 * Where to store DB file with SQLight
 */
define('DB_FILE_PATH', ROOT . '/tmp/musicPlayer.DB');

/**
 * DB connection configuration
 */
define('DB_CONNECTION', 'sqlite:' . DB_FILE_PATH);

/**
 * Where to store DB file with SQLight
 */
define('MIGRATION_FOLDER_PATH', ROOT . '/config/migration');

/**
 * Version of current API
 */
define('API_VERSION', '1');

/**
 * API base url
 */
define('BASE_API_URL', '/api/v' . API_VERSION);

/**
 * Spotify application Client ID
 */
define('SPOTIFY_CLIENT_ID', '92641fc3f06547b6a5af81d7a182b3b8');

/**
 * Spotify application Client Secrete
 */
define('SPOTIFY_CLIENT_SECRETE', '21245aa56d354d8d8d827d16adb196d7');

/**
 * Redis host
 */
define('REDIS_HOST', '127.0.0.1');

/**
 * Redis port
 */
define('REDIS_PORT', 6379);