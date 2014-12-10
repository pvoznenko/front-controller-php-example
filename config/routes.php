<?php

/**
 * Bellow you can see all possible routes existing for following test application
 */
return [
    new App\Route('POST', BASE_API_URL . '/users/authentication', "MusicPlayer\\Controllers\\UsersController", 'authentication'),
    new App\Route('GET', BASE_API_URL . '/playlist(\?page=[0-9]+)?', "MusicPlayer\\Controllers\\PlaylistController", 'getPlaylist'),
    new App\Route('GET', BASE_API_URL . '/playlist/(?<playlistId>[0-9]+)', "MusicPlayer\\Controllers\\PlaylistController", 'getPlaylist'),
    new App\Route('POST', BASE_API_URL . '/playlist', "MusicPlayer\\Controllers\\PlaylistController", 'addPlaylist'),
    new App\Route('PUT', BASE_API_URL . '/playlist/(?<playlistId>[0-9]+)', "MusicPlayer\\Controllers\\PlaylistController", 'updatePlaylist'),
    new App\Route('DELETE', BASE_API_URL . '/playlist/(?<playlistId>[0-9]+)', "MusicPlayer\\Controllers\\PlaylistController", 'deletePlaylist'),

    new App\Route('GET', BASE_API_URL . '/playlist/(?<playlistId>[0-9]+)/songs(\?page=[0-9]+)?', "MusicPlayer\\Controllers\\PlaylistController", 'getSongsFromPlaylist'),
    new App\Route('GET', BASE_API_URL . '/playlist/(?<playlistId>[0-9]+)/songs/(?<songId>[0-9]+)', "MusicPlayer\\Controllers\\PlaylistController", 'getSongsFromPlaylist'),
    new App\Route('PUT', BASE_API_URL . '/playlist/(?<playlistId>[0-9]+)/songs', "MusicPlayer\\Controllers\\PlaylistController", 'addSongToPlaylist'),
    new App\Route('DELETE', BASE_API_URL . '/playlist/(?<playlistId>[0-9]+)/songs/(?<songId>[0-9]+)', "MusicPlayer\\Controllers\\PlaylistController", 'deleteSongFromPlaylist'),
    new App\Route('GET', BASE_API_URL . '/search/(?<type>track|album|artist)\?q=([^&]*)(?:$|&=[^&]*)*(?:&page=(\d+))?', "MusicPlayer\\Controllers\\SearchController", 'search')
];