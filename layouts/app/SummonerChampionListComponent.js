define( function(require) {
    'use strict';
    var m = require('mithrill');
    var Game = require('./Game');
    var Summoner = require('./Summoner');
    var Champion = require('./Champion');

    var SummonerChampion = {};

    SummonerChampion.controller = function(args) {
        var statistic = m.prop("");
        var champion = m.prop(null);

        Summoner.statistic(args.summonerId, args.region).then(function(record) {
            statistic(record.toString());
        });
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
    };

    SummonerChampion.view = function(ctrl) {
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
    };

    var SummonerChampionListComponent = {};

    SummonerChampionListComponent.controller = function() {
        var region = m.route.param('region');
        var summonerName = m.route.param('summonerName');
        var teams = m.prop();
        Game.game(region, summonerName).then(function(game) {
            var data = Summoner.list(game.teams);
            teams(data);
        });
        return {
            summonerName: summonerName,
            region: region,
            teams: teams
        };
    };
    
    SummonerChampionListComponent.view = function(ctrl) {
        return m("div", [
            ctrl.teams() ? ctrl.teams().map(function(team) {
                return m('div', {class: 'team'}, team.map(function(summoner){
                    return m('div', {class: 'pure-g player'},[
                            
                            m.component(SummonerChampion, {region: ctrl.region, championId: summoner.championId, summonerId: summoner.summonerId}),
                            m('h',{class: 'pure-u-1 pure-u-sm-1-4'}, summoner.summonerName)
                    ]);
                }));
            }) : m('div')

        ]);               
    };
    
    return SummonerChampionListComponent;
});