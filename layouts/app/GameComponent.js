define( function(require) {
    'use strict';

    var m = require('mithrill');
    var Game = require('./Game');
    var GameComponent = {};
    GameComponent.controller = function(args) {
        var region = args.region;
        var games = Game.list(args.region);

        var teamsOfGame = function(teams) { // seperate teams obj to 2 teams array
            var results = [];
            Object.keys(teams).forEach(function(teamId) {
                results.push(teams[teamId]);
            });
            return results;
        };

        return {
            games: games,
            region: region,
            teamsOfGame: teamsOfGame
        };
    };

    // TODO: refactor to have variables for virtual dom
    GameComponent.view = function(ctrl) {
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
    };

    return GameComponent;
});