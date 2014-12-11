angular.module('playlistApp.site').controller('playlist', ['$scope', 'User', 'Playlist', function ($scope, User, Playlist) {
    /**
     * Initialize scope
     */
    $scope['error'] = false;
    $scope['success'] = false;
    $scope['playlist'] = [];
    $scope['newPlaylist'] = '';

    /**
     * Loading existing playlist
     */
    var loadPlaylist = function() {
        Playlist.get(function(data){
            if (typeof data.playlist == 'undefined') {
                return;
            }

            $scope['playlist'] = data.playlist;

            loadSongs();
        });
    };

    /**
     * Assign songs to existing playlist
     */
    var loadSongs = function() {
        angular.forEach($scope['playlist'], function(playlist, index) {
            Playlist.getSongs({"playlistId": playlist.id}, function(data){
                if (typeof data.songs == 'undefined') {
                    return;
                }

                $scope['playlist'][index]['songs'] = data.songs;
            });
        });
    };

    var token = User.getUserToken();

    /**
     * If user not authorized yet, then it his first login so no playlist yet
     */
    if (token != null) {
        loadPlaylist();
    }

    /**
     * Method to handle errors
     *
     * @param {Object} err
     */
    var onError = function(err) {
        $scope['error'] = typeof err.data != 'undefined' && typeof err.data.error != 'undefined' ? err.data.error : err;
    };

    /**
     * Method to add new playlist
     */
    $scope['addPlaylist'] = function() {
        if ($scope['newPlaylist'] == '') {
            onError('Playlist name should be specified!');
            return;
        }

        Playlist.add({"name": $scope['newPlaylist']}, function(response) {
            $scope['success'] = 'Playlist with name "' + $scope['newPlaylist'] + '" was successfully created!';

            if (typeof response.playlist != 'undefined') {
                $scope['playlist'].push(response.playlist);
            }

            $scope['newPlaylist'] = '';
        }, onError);
    };

    /**
     * Method to update existing playlist
     *
     * @param {Number} index - number of playlist in array
     */
    $scope['savePlaylist'] = function(index) {
        var newName = $scope['playlist'][index].name,
            playlistId = $scope['playlist'][index].id;

        Playlist.save({"playlistId": playlistId, "newName": newName}, function() {
            $scope['success'] = 'Playlist with new name "' + newName + '" was successfully updated!';
        }, onError);
    };

    /**
     * Remove existing playlist
     *
     * @param {Number} playlistId - playlist id
     */
    $scope['removePlaylist'] = function(playlistId) {
        Playlist.remove({"playlistId": playlistId}, function(){
            var playlist = [];

            angular.forEach($scope['playlist'], function(playlist) {
                if (playlist.id == playlistId) {
                    return true;
                }

                this.push(playlist);
            }, playlist);

            $scope['playlist'] = playlist;

            $scope['success'] = 'Playlist was  successfully removed!';
        }, onError);
    };

    /**
     * Remove song from the playlist
     *
     * @param {Number} playlistId - playlist id
     * @param {Number} songId - song id
     */
    $scope['removeFromPlaylist'] = function(playlistId, songId) {
        Playlist.removeSong({"playlistId": playlistId, "songId": songId}, function(){
            loadPlaylist();

            $scope['success'] = 'Song was successfully removed!';
        }, onError);
    };
}]);