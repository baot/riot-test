define( function(require) {
    'use strict';

    var m = require('mithrill');
    var Game = {};
    Game.game = function(region, summonerName) {    //get a game based on region and summonerName
        var url = '/dev-test/server/' + region + '/currentGame/' + summonerName;
        return m.request({method: 'GET', url: url});
    };
    Game.list = function (region) {                 // get list of current games based on region
        var url = '/dev-test/server/' + region + '/games';
        return m.request({method: 'GET', url: url});
    };

    return Game;
});