angular
    .module('sbFramework', ['ngMaterial','ngRoute'])

    .controller('SideNav', function($scope, $mdSidenav) {
        $scope.close = function () {
            $mdSidenav('left').close()
                .then(function () {
                    $log.debug("close LEFT is done");
                });

        };
    })

    .config(function($mdThemingProvider, $routeProvider, $locationProvider) {
        $mdThemingProvider.theme('default')
            .primaryPalette('green');

        $routeProvider
            .when('/', {
                'templateUrl': '/views/site/main_page.html'
            });

        $locationProvider
            .html5Mode({
                enabled: true,
                requireBase: false
            });
    });