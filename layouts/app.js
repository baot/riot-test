'use strict';

// model Region
var Region = {
    list: function() {
        var url = '/dev-test/server/regions';
        return m.request({method: 'GET', url: url});
    }
};

var RegionComponent = {
    controller: function() {
        var regions = Region.list();
        var click = function(e, regionName) {
            // TODO: render GameComponent below the button just clicked 
        };

        return {
            regions: regions,
            click: click
        };
    },
    view: function(ctrl) {
        return ([
            ctrl.regions().map(function(regionName, id) {
                return m('p', 
                    m('button',{
                        class: 'pure-button',
                        type: 'button',
                        onclick: function(){
                            ctrl.click(regionName);
                        }
                    }, regionName)
                );
            })
        ]);
    }
};

m.mount(document.getElementsByClassName('pure-u-1 pure-u-sm-3-5')[0], RegionComponent);
