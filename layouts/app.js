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
        var regionName = m.prop("");
        var buttonClick = false;
        var click = function(e, region) {
            if (buttonClick === false) {
                regionName(region);
            } else {
                regionName("");
            }     
            buttonClick = !buttonClick;
        };

        return {
            regions: regions,
            click: click,
            regionName: regionName
        };
    },
    view: function(ctrl) {
        return ([
            ctrl.regions().map(function(regionName, id) {
                return m('p', 
                    m('button',
                        {
                            class: 'pure-button',
                            type: 'button',
                            onclick: function(e){
                                ctrl.click(e, regionName);
                            }
                        }, 
                        regionName
                    ),
                    (ctrl.regionName() == regionName) ? m('div', {id: regionName}, m.component(GameComponent, {region: regionName})) : m('')
                );
            })
        ]);
    }
};

// model Game
var Game = {
    list: function (region) {
        var url = '/dev-test/server/' + region + '/games';
        return m.request({method: 'GET', url: url});
    }
};

var GameComponent = {
    controller: function(args) {
        var region = args.region;
        var games = Game.list(args.region);

        var teamsOfGame = function(teams) { // seperate teams to 2 teams array
            var results = [];
            Object.keys(teams).forEach(function(teamId) {
                results.push(teams[teamId]);
            });
            return results;
        };

        var click = function(teams) {
            // TODO: display player information component
        };
        return {
            games: games,
            region: region,
            click: click,
            teamsOfGame: teamsOfGame
        };
    },

    view: function(ctrl) {
        return m('div', [
            ctrl.games().map(function(game, id) {
                return m('div',{class: 'pure-g match'},
                    [m('div', 
                        {class: 'pure-u-1 pure-u-sm-2-3'}, 
                        m('h1', game.gameMode + '/' + game.gameType)
                    ),
                    m('div', 
                        {class: 'pure-u-1 pure-u-sm-2-3'}, 
                        m('p', {class: 'match__time'}, (new Date(game.gameStartTime)).toString())
                    ),
                    m('div',
                        {class: 'pure-u-1 pure-u-sm-1-2'},
                        [ctrl.teamsOfGame(game.teams).map(function(team) {
                            return m('div', 
                                        {class: 'match__team'},
                                        [team.map(function(summoner) {
                                            return m('a',
                                                        {
                                                            class: 'match__summoner', 
                                                            href: '/match/' + summoner.summonerName //TODO: handle url
                                                        }, 
                                                        summoner.summonerName
                                                    );
                                        })]
                                    );
                        })]
                    )]
                );
            })
        ]);
    }
};



m.mount(document.getElementsByClassName('pure-u-1 pure-u-sm-3-5')[0], RegionComponent);
