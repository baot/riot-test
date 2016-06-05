define( function(require) {
    'use strict';

    var m = require('mithrill');
    var Region = {};
    Region.list = function() {
        var url = '/dev-test/server/regions';
        return m.request({method: 'GET', url: url});
    };

    return Region;
});