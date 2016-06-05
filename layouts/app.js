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
                return m('div',
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
    game: function(region, summonerName) {
        var url = '/dev-test/server/' + region + '/currentGame/' + summonerName;
        return m.request({method: 'GET', url: url});
    },
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
                                                            href: '/' + ctrl.region + '/match/' + summoner.summonerName,
                                                            config: m.route
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

// model Participant
var Participant = {
    list: function (teams) {
        var results = [];
        Object.keys(teams).forEach(function(teamId) {
            results.push(teams[teamId]);
        });
        return results;
    },
    statistic: function(summonerId, region) {
        var url = '/dev-test/server/' + region + '/records/by_id/' + summonerId;
        return m.request({method: 'GET', url: url});
    }
};

// model Champion
var Champion = {
    data: function(championId, region) {
        var url = '/dev-test/server/' + region + '/champion/' + championId;
        return m.request({method: 'GET', url: url});
    }
};

var ParticipantChampion = {
    controller: function(args) {
        var statistic = m.prop("");
        Participant.statistic(args.summonerId, args.region).then(function(record) {
            statistic(record.toString());
        });
        var champion = m.prop(null);
        Champion.data(args.championId, args.region).then(function(champ){
            champion({
                avatar: champ.avatar.full,
                spells: champ.spells
            });
        });
        return {
            statistic: statistic,
            champion: champion
        };
    },
    view: function(ctrl) {
        return m('div', 
            [ctrl.champion() ? 
                m('div', {class: 'pure-u-1 pure-u-sm-1-4'},
                        m('ul',{class: 'spells'},
                            [ 
                                ctrl.champion().spells.map(function(spellIcon) {
                                    return m('li',{class:'spells__single'},
                                        m('img',{
                                            style: 'width: 25px',
                                            src: 'http://ddragon.leagueoflegends.com/cdn/6.11.1/img/spell/' + spellIcon.full
                                        }));
                                })
                            ]
                        ),
                        m('img', {src: 'http://ddragon.leagueoflegends.com/cdn/6.11.1/img/champion/' + ctrl.champion().avatar})
                ) : m(''),
                m('div',{class: 'pure-u-1 pure-u-sm-1-4'}, 
                    m('h1', ctrl.statistic().replace(/,/g, ' ')))
        ]);
    }
};

var ParticipantChampionListComponent = {
    controller: function() {
        var region = m.route.param('region');
        var summonerName = m.route.param('summonerName');
        var teams = m.prop();
        Game.game(region, summonerName).then(function(game) {
            var data = Participant.list(game.teams);
            teams(data);
        });
        return {
            summonerName: summonerName,
            region: region,
            teams: teams
        };
    },
    
    view: function(ctrl) {
        return m("div", [
            ctrl.teams() ? ctrl.teams().map(function(team) {
                return m('div', {class: 'team'}, team.map(function(summoner){
                    return m('div', {class: 'pure-g player'},[
                            
                            m.component(ParticipantChampion, {region: ctrl.region, championId: summoner.championId, summonerId: summoner.summonerId}),
                            m('h',{class: 'pure-u-1 pure-u-sm-1-4'}, summoner.summonerName)
                    ]);
                }));
            }) : m('div')

        ]);               
    }
    };

m.route(document.getElementById('app'), '/', {
    '/' : RegionComponent,
    '/:region/match/:summonerName' : ParticipantChampionListComponent
});
