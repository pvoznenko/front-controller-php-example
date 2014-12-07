<?php

/**
 * Bellow you can see all possible routes existing for following test application
 */
return [
    new app\Route('POST', '/api/users/authentication', "MusicPlayer\\controllers\\UsersController", 'authentication'),
    new app\Route('GET', '/api/playlist', "MusicPlayer\\controllers\\PlaylistController", 'getPlaylist'),
    new app\Route('GET', '/api/playlist/(?<playlistId>[0-9]+)', "MusicPlayer\\controllers\\PlaylistController", 'getPlaylist'),
    new app\Route('POST', '/api/playlist', "MusicPlayer\\controllers\\PlaylistController", 'addPlaylist'),
    new app\Route('PUT', '/api/playlist/(?<playlistId>[0-9]+)', "MusicPlayer\\controllers\\PlaylistController", 'updatePlaylist'),
    new app\Route('DELETE', '/api/playlist/(?<playlistId>[0-9]+)', "MusicPlayer\\controllers\\PlaylistController", 'deletePlaylist'),
    new app\Route('PUT', '/api/playlist/(?<playlistId>[0-9]+)/song', "MusicPlayer\\controllers\\PlaylistController", 'addSongToPlaylist'),
    new app\Route('DELETE', '/api/playlist/(?<playlistId>[0-9]+)/song/(?<songId>[0-9]+)', "MusicPlayer\\controllers\\PlaylistController", 'deleteSongFromPlaylist')
];