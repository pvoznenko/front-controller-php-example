<?php

/**
 * Bellow you can see all possible routes existing for following test application
 */
return [
    new app\Route('POST', BASE_API_URL . '/users/authentication', "MusicPlayer\\controllers\\UsersController", 'authentication'),
    new app\Route('GET', BASE_API_URL . '/playlist(\?page=[0-9]+)?', "MusicPlayer\\controllers\\PlaylistController", 'getPlaylist'),
    new app\Route('GET', BASE_API_URL . '/playlist/(?<playlistId>[0-9]+)', "MusicPlayer\\controllers\\PlaylistController", 'getPlaylist'),
    new app\Route('POST', BASE_API_URL . '/playlist', "MusicPlayer\\controllers\\PlaylistController", 'addPlaylist'),
    new app\Route('PUT', BASE_API_URL . '/playlist/(?<playlistId>[0-9]+)', "MusicPlayer\\controllers\\PlaylistController", 'updatePlaylist'),
    new app\Route('DELETE', BASE_API_URL . '/playlist/(?<playlistId>[0-9]+)', "MusicPlayer\\controllers\\PlaylistController", 'deletePlaylist'),

    new app\Route('GET', BASE_API_URL . '/playlist/(?<playlistId>[0-9]+)/songs(\?page=[0-9]+)?', "MusicPlayer\\controllers\\PlaylistController", 'getSongsFromPlaylist'),
    new app\Route('GET', BASE_API_URL . '/playlist/(?<playlistId>[0-9]+)/songs/(?<songId>[0-9]+)', "MusicPlayer\\controllers\\PlaylistController", 'getSongsFromPlaylist'),
    new app\Route('PUT', BASE_API_URL . '/playlist/(?<playlistId>[0-9]+)/songs', "MusicPlayer\\controllers\\PlaylistController", 'addSongToPlaylist'),
    new app\Route('DELETE', BASE_API_URL . '/playlist/(?<playlistId>[0-9]+)/songs/(?<songId>[0-9]+)', "MusicPlayer\\controllers\\PlaylistController", 'deleteSongFromPlaylist'),
    new app\Route('GET', BASE_API_URL . '/search/(?<type>track|album|artist)\?q=([^&]*)(?:$|&=[^&]*)*(?:&page=(\d+))?', "MusicPlayer\\controllers\\SearchController", 'search')
];