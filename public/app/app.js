window.app = angular.module('playlistApp', ['playlistApp.site'])

    /**
     * API url
     */
    .constant('ApiUrl', '/api/v1/');

angular.module('playlistApp.site', ['ngRoute', 'ngResource', 'chieffancypants.loadingBar']);