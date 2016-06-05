define( function(require) {
    'use strict';

    var m = require('mithrill');
    var RegionComponent = require('./RegionComponent');
    var SummonerChampionListComponent = require('./SummonerChampionListComponent');

    m.route(document.getElementById('app'), '/', {
        '/' : RegionComponent,
        '/:region/match/:summonerName' : SummonerChampionListComponent
    });
});