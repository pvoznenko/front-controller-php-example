angular.module('playlistApp.site').factory('User', ['$http', function ($http) {
    var userToken = null;

    /**
     * Interface for user authorizing and storing auth token
     *
     * @type {{getUserToken: Function, setUserToken: Function}}
     */
    var methods = {
         getUserToken: function () {
             return userToken;
         },
         setUserToken: function (token) {
             userToken = token;

             $http.defaults.headers.common['token'] = userToken;

             return methods;
         }
     };

    return methods;
}]);