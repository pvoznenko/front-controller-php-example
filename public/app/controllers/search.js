angular.module('playlistApp.site').controller('search', ['$scope', 'Search', 'Playlist', function ($scope, Search, Playlist) {
    /**
     * Initialize scope
     */
    $scope['init'] = function() {
        $scope['error'] = false;
        $scope['success'] = false;
        $scope['results'] = [];
        $scope['availablePlaylist'] = [];
        $scope['offset'] = 0;
        $scope['limit'] = 20;
        $scope['paging'] = {};
    };

    $scope['searchTerm'] = '';
    $scope['searchType'] = 'track';

    $scope['init']();

    /**
     * Method to handle errors
     */
    var onError = function(err) {
        $scope['error'] = typeof err.data != 'undefined' && typeof err.data.error != 'undefined' ? err.data.error : err;
    };

    /**
     * Method to run search
     */
    $scope['doSearch'] = function(){
        if ($scope['searchTerm'] == '') {
            onError('Search should be specified!');
            return;
        }

        var data = {
            "offset": $scope['offset'],
            "q": $scope['searchTerm'],
            "type": $scope['searchType']
        };

        Playlist.get(function(data){
            if (typeof data.playlist != 'undefined') {
                $scope['availablePlaylist'] = data.playlist;
            }
        });

        Search.search(data, function(response) {
            if (typeof response.info != 'undefined') {
                $scope['paging'] = response.info;
            }

            if (typeof response.result != 'undefined') {
                $scope['results'] = response.result;
            }
        }, onError);
    };

    /**
     * Method to add songs to playlist
     */
    $scope['addToPlaylist'] = function(song, index) {
        var select = angular.element(document.querySelector('#playlist-select-' + index)),
            playlistIndex = select.val();

        if (playlistIndex == '') {
            $scope['error'] = 'Please select playlist to add song in!';
            return;
        }

        var playlist = $scope['availablePlaylist'][playlistIndex],
            data = {
                "track": song.name,
                "album": song.album.name,
                "artist": song.artists.map(function(obj){ return obj.name; }).join('; '),
                "playlistId": playlist.id
            };

        Playlist.addSong(data, function() {
            $scope['success'] = 'Song "' + song.name + '" successfully added to playlist "' + playlist.name + '".';
        }, onError);
    };


    /**
     * Simple pagination
     */
    $scope['pagination'] = function(step) {
        $scope['limit'] = $scope['paging'].limit;
        $scope['offset'] += (step > 0 ? 1 : -1) * $scope['limit'];
        if ($scope['offset'] < 0) {
            $scope['offset'] = 0;
        } else {
            $scope['doSearch']();
        }
    };

    /**
     * Method to perform search of tracks by album or artist
     */
    $scope['searchTrackBy'] = function(type, value) {
        $scope['searchTerm'] = type + ':"' + value + '"';
        $scope['searchType'] = 'track';
        $scope['doSearch']();
    };
}]).filter('capitalize', function() {
    /**
     * Method to capitalize first letter of word
     */
    return function(input) {
        return (!!input) ? input.replace(/([^\W_]+[^\s-]*) */g, function(txt) {
            return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
        }) : '';
    }
});