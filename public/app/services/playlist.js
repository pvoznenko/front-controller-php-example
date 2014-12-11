angular.module('playlistApp.site').factory("Playlist", ['$resource', 'ApiUrl', function($resource, ApiUrl) {

    /**
     * Playlist API
     */
    return $resource(ApiUrl + 'playlist/:playlistId', {
        playlistId: '@playlistId'
    }, {
        add: {
            method: 'POST'
        },
        save: {
            method: 'PUT',
            url: ApiUrl + 'playlist/:playlistId'
        },
        addSong: {
            method: 'PUT',
            url: ApiUrl + 'playlist/:playlistId/songs'
        },
        getSongs: {
            method: 'GET',
            url: ApiUrl + 'playlist/:playlistId/songs'
        },
        removeSong: {
            method: 'DELETE',
            url: ApiUrl + 'playlist/:playlistId/songs/:songId'
        }
    });
}]);