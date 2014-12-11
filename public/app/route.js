window.app.config(['$routeProvider', function($routeProvider) {
    $routeProvider.when('/', {
        templateUrl: '/app/templates/playlist.html',
        controller: 'playlist'
    })
    .when('/search', {
        templateUrl: '/app/templates/search.html',
        controller: 'search'
    })
    .otherwise({
        redirectTo: '/'
    });
}]);