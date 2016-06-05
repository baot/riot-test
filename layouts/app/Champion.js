define( function(require) {
    'use strict';

    var m = require('mithrill');
    var Champion = {};

    Champion.data = function(championId, region) {
        var url = '/dev-test/server/' + region + '/champion/' + championId;
        return m.request({method: 'GET', url: url});
    };

    return Champion;
});