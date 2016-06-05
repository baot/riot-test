define( function(require) {
    'use strict';

    var m = require('mithrill');
    var GameComponent = require('./GameComponent');
    var Region = require('./Region');
    var RegionComponent = {};
    
    RegionComponent.controller = function() {
        var regions = Region.list();        // region lists from api
        var regionName = m.prop("");        // regionName prop to watch for which button is clicked
        var buttonClick = false;            // clicked state of the button 
        var click = function(e, region) {   // click event handler of region button
            if (buttonClick === false) {
                regionName(region);
            } else {
                regionName("");
            }     
            buttonClick = !buttonClick;     // change clicked state of the button
        };

        return {
            regions: regions,
            click: click,
            regionName: regionName
        };
    };
    RegionComponent.view = function(ctrl) {
        return ([
            ctrl.regions().map(function(regionName, id) {
                return m('div',
                    m('button', {
                            class: 'pure-button',
                            type: 'button',
                            onclick: function(e){
                                ctrl.click(e, regionName);
                            }
                    }, regionName),
                    (ctrl.regionName() === regionName) ? m('div', {id: regionName}, m.component(GameComponent, {region: regionName})) : m('')
                );
            })
        ]);
    };
    return RegionComponent;
});