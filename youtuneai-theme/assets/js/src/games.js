/**
 * Games System JavaScript
 */
(function() {
    'use strict';

    class YouTuneAIGames {
        constructor() {
            this.games = [];
            this.currentGame = null;
            this.init();
        }

        async init() {
            await this.loadGames();
            this.setupUI();
            console.log('YouTuneAI Games system initialized');
        }

        async loadGames() {
            try {
                const response = await fetch('/wp-json/youtuneai/v1/games');
                this.games = await response.json();
            } catch (error) {
                console.error('Failed to load games:', error);
            }
        }

        setupUI() {
            // Game modal handlers
            document.addEventListener('click', (e) => {
                if (e.target.matches('[data-game-play]')) {
                    const gameId = e.target.dataset.gamePlay;
                    this.playGame(gameId);
                }
            });
        }

        playGame(gameId) {
            const game = this.games.find(g => g.id == gameId);
            if (!game) return;

            this.currentGame = game;
            this.showGameModal(game);
        }

        showGameModal(game) {
            // TODO: Implement game modal
            console.log('Playing game:', game.title);
        }
    }

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', () => {
        new YouTuneAIGames();
    });

})();