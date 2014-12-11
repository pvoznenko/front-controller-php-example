angular.module('playlistApp.site').factory("UserAuth", ['$resource', 'ApiUrl', function($resource, ApiUrl) {

    /**
     * User API
     */
    return $resource(ApiUrl + 'users/authentication', null, {
        getAuthToken: {
            method: 'POST'
        }
    });
}]);