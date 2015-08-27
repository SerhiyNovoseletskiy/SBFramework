<html ng-app="sbFramework">
<head>
    <title>SBFramework</title>
    <link rel="stylesheet" href="assets/angular-material/angular-material.min.css">
</head>
<body layout="row">

<md-sidenav class="md-sidenav-left md-whiteframe-z2" md-component-id="left" md-is-locked-open="$mdMedia('gt-md')">

    <md-toolbar ng-controller="SideNav">
        <div class="md-toolbar-tools">
            <md-button class="md-icon-button" hide-gt-sm ng-click="close();" aria-label="Settings">
                <md-icon md-svg-icon="icons/ic_arrow_back_24px.svg"></md-icon>
            </md-button>
            <h2>
                <span>SBFramework</span>
            </h2>
        </div>
    </md-toolbar>

    <md-content>
        <img src="/assets/site/img/sbFramework.png" style="max-width: 100%;">
    </md-content>

</md-sidenav>


<script src="/assets/angular/angular.min.js"></script>
<script src="/assets/angular/angular-aria.min.js"></script>
<script src="/assets/angular/angular-animate.min.js"></script>
<script src="/assets/angular-material/angular-material.min.js"></script>
<script src="/assets/site/app.js"></script>
</body>
</html>