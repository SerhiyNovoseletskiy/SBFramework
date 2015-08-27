angular
    .module('sbFramework', ['ngMaterial'])

    .controller('SideNav', function($scope, $mdSidenav) {
        $scope.close = function () {
            $mdSidenav('left').close()
                .then(function () {
                    $log.debug("close LEFT is done");
                });

        };
    })

    .config(function($mdThemingProvider) {
        $mdThemingProvider.theme('default')
            .primaryPalette('green');
    });