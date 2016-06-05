define( function(require) {
    'use strict';

    var m = require('mithrill');
    var Summoner = {};
    
    Summoner.list = function (teams) {        
        var results = [];
        Object.keys(teams).forEach(function(teamId) {
            results.push(teams[teamId]);
        });
        return results;
    };

    Summoner.statistic = function(summonerId, region) {      // get recent w/l records of summoner 
        var url = '/dev-test/server/' + region + '/records/by_id/' + summonerId;
        return m.request({method: 'GET', url: url});
    };

    return Summoner;
});