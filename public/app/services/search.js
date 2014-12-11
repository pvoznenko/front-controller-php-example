angular.module('playlistApp.site').factory("Search", ['$resource', 'ApiUrl', function($resource, ApiUrl) {

    /**
     * Search API
     */
    return $resource(ApiUrl + 'search/:type', {
        type: '@type'
    }, {
        search: {
            method: 'GET',
            params: {
                q: '@q',
                offset: '@offset'
            }
        }
    });
}]);