<?php
/*
Template Name: Music Page
*/

get_header(); ?>

<div class="music-page">
    <!-- Hero Section -->
    <div class="music-hero">
        <div class="hero-content">
            <h1 class="hero-title">
                <span class="gradient-text">Music Studio</span>
                <div class="title-glow"></div>
            </h1>
            <p class="hero-subtitle">AI-Powered Music Creation & Distribution Platform</p>
            <div class="music-player-preview">
                <audio controls autoplay loop>
                    <source src="https://cdn.pixabay.com/audio/2022/05/27/audio_1808fbf07a.mp3" type="audio/mpeg">
                    <source src="https://cdn.pixabay.com/audio/2022/03/10/audio_4621d6a1f8.mp3" type="audio/mpeg">
                </audio>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="music-container">
        <!-- Music Creation Studio -->
        <div class="music-studio">
            <h2>🎵 Create Your Music</h2>
            <div class="creation-tools">
                <div class="tool-card">
                    <div class="tool-icon">🎹</div>
                    <h3>AI Piano</h3>
                    <p>Create melodies with AI assistance</p>
                    <button class="tool-btn" onclick="openTool('piano')">Launch Piano</button>
                </div>

                <div class="tool-card">
                    <div class="tool-icon">🥁</div>
                    <h3>Beat Maker</h3>
                    <p>Generate custom drum patterns</p>
                    <button class="tool-btn" onclick="openTool('drums')">Launch Drums</button>
                </div>

                <div class="tool-card">
                    <div class="tool-icon">🎸</div>
                    <h3>Guitar Studio</h3>
                    <p>Virtual guitar with effects</p>
                    <button class="tool-btn" onclick="openTool('guitar')">Launch Guitar</button>
                </div>

                <div class="tool-card">
                    <div class="tool-icon">🎤</div>
                    <h3>Voice Recorder</h3>
                    <p>Record and enhance vocals</p>
                    <button class="tool-btn" onclick="openTool('vocals')">Start Recording</button>
                </div>
            </div>
        </div>

        <!-- Music Library -->
        <div class="music-library">
            <h2>🎼 Your Music Library</h2>
            <div class="library-grid">
                <div class="track-item">
                    <div class="track-artwork">🎵</div>
                    <div class="track-info">
                        <h4>AI Generated Beat 1</h4>
                        <p>Electronic • 3:24</p>
                    </div>
                    <div class="track-actions">
                        <button onclick="playTrack(1)">▶️</button>
                        <button onclick="editTrack(1)">✏️</button>
                        <button onclick="shareTrack(1)">📤</button>
                    </div>
                </div>

                <div class="track-item">
                    <div class="track-artwork">🎶</div>
                    <div class="track-info">
                        <h4>Vocal Demo</h4>
                        <p>Pop • 2:45</p>
                    </div>
                    <div class="track-actions">
                        <button onclick="playTrack(2)">▶️</button>
                        <button onclick="editTrack(2)">✏️</button>
                        <button onclick="shareTrack(2)">📤</button>
                    </div>
                </div>

                <div class="track-item">
                    <div class="track-artwork">🎸</div>
                    <div class="track-info">
                        <h4>Guitar Melody</h4>
                        <p>Rock • 4:12</p>
                    </div>
                    <div class="track-actions">
                        <button onclick="playTrack(3)">▶️</button>
                        <button onclick="editTrack(3)">✏️</button>
                        <button onclick="shareTrack(3)">📤</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Distribution Hub -->
        <div class="distribution-hub">
            <h2>📡 Music Distribution</h2>
            <div class="distribution-platforms">
                <div class="platform-card">
                    <div class="platform-icon">🎵</div>
                    <h3>Spotify</h3>
                    <p>Distribute to 400M+ listeners</p>
                    <button class="distribute-btn">Connect</button>
                </div>

                <div class="platform-card">
                    <div class="platform-icon">🍎</div>
                    <h3>Apple Music</h3>
                    <p>Reach iOS users worldwide</p>
                    <button class="distribute-btn">Connect</button>
                </div>

                <div class="platform-card">
                    <div class="platform-icon">📺</div>
                    <h3>YouTube Music</h3>
                    <p>Video + audio distribution</p>
                    <button class="distribute-btn">Connect</button>
                </div>

                <div class="platform-card">
                    <div class="platform-icon">☁️</div>
                    <h3>SoundCloud</h3>
                    <p>Independent artist platform</p>
                    <button class="distribute-btn">Connect</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .music-page {
        min-height: 100vh;
        background: linear-gradient(135deg, #000000 0%, #1a1a1a 50%, #000000 100%);
        padding-top: 120px;
        position: relative;
    }

    .music-page::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background:
            radial-gradient(circle at 30% 70%, rgba(255, 215, 0, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 70% 30%, rgba(0, 255, 65, 0.1) 0%, transparent 50%);
        z-index: -1;
        pointer-events: none;
    }

    .music-hero {
        text-align: center;
        padding: 40px 20px;
        margin-bottom: 40px;
    }

    .hero-title {
        font-size: 3.5rem;
        font-weight: 900;
        margin-bottom: 20px;
        position: relative;
    }

    .gradient-text {
        background: linear-gradient(45deg, var(--gold-color), var(--platinum-color), var(--diamond-color), var(--accent-color));
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .hero-subtitle {
        font-size: 1.5rem;
        color: var(--text-secondary);
        margin-bottom: 30px;
    }

    .music-player-preview {
        margin-top: 30px;
    }

    .music-player-preview audio {
        width: 400px;
        height: 50px;
        background: linear-gradient(135deg, rgba(26, 26, 26, 0.9) 0%, rgba(30, 58, 138, 0.6) 100%);
        border-radius: 25px;
        border: 2px solid rgba(255, 215, 0, 0.3);
    }

    .music-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 40px;
    }

    .music-studio,
    .music-library,
    .distribution-hub {
        background: linear-gradient(135deg, rgba(26, 26, 26, 0.9) 0%, rgba(0, 0, 0, 0.8) 100%);
        border-radius: 20px;
        padding: 30px;
        border: 2px solid rgba(255, 215, 0, 0.3);
    }

    .music-studio h2,
    .music-library h2,
    .distribution-hub h2 {
        color: var(--gold-color);
        font-size: 2rem;
        margin-bottom: 25px;
        text-shadow: var(--gold-glow);
    }

    .creation-tools {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .tool-card {
        background: linear-gradient(135deg, rgba(26, 26, 26, 0.8) 0%, rgba(30, 58, 138, 0.4) 100%);
        border-radius: 15px;
        padding: 25px;
        text-align: center;
        border: 1px solid rgba(229, 228, 226, 0.3);
        transition: all 0.3s ease;
    }

    .tool-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--neon-glow);
    }

    .tool-icon {
        font-size: 3rem;
        margin-bottom: 15px;
    }

    .tool-card h3 {
        color: var(--accent-color);
        margin-bottom: 10px;
    }

    .tool-card p {
        color: var(--text-secondary);
        margin-bottom: 20px;
    }

    .tool-btn {
        background: linear-gradient(45deg, var(--accent-color), var(--metal-blue));
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 25px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .tool-btn:hover {
        transform: translateY(-2px);
        box-shadow: var(--neon-glow);
    }

    .library-grid {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .track-item {
        display: flex;
        align-items: center;
        background: rgba(26, 26, 26, 0.6);
        border-radius: 12px;
        padding: 15px;
        border: 1px solid rgba(229, 228, 226, 0.2);
        transition: all 0.3s ease;
    }

    .track-item:hover {
        background: rgba(0, 255, 65, 0.1);
        border-color: var(--accent-color);
    }

    .track-artwork {
        width: 60px;
        height: 60px;
        background: linear-gradient(45deg, var(--gold-color), var(--platinum-color));
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-right: 20px;
    }

    .track-info {
        flex: 1;
    }

    .track-info h4 {
        color: white;
        margin-bottom: 5px;
    }

    .track-info p {
        color: var(--text-secondary);
        font-size: 0.9rem;
    }

    .track-actions {
        display: flex;
        gap: 10px;
    }

    .track-actions button {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(229, 228, 226, 0.3);
        color: white;
        padding: 8px 12px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .track-actions button:hover {
        background: var(--accent-color);
        box-shadow: var(--neon-glow);
    }

    .distribution-platforms {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }

    .platform-card {
        background: linear-gradient(135deg, rgba(26, 26, 26, 0.8) 0%, rgba(30, 58, 138, 0.4) 100%);
        border-radius: 15px;
        padding: 25px;
        text-align: center;
        border: 1px solid rgba(185, 242, 255, 0.3);
        transition: all 0.3s ease;
    }

    .platform-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--diamond-glow);
    }

    .platform-icon {
        font-size: 2.5rem;
        margin-bottom: 15px;
    }

    .platform-card h3 {
        color: var(--diamond-color);
        margin-bottom: 10px;
    }

    .platform-card p {
        color: var(--text-secondary);
        margin-bottom: 20px;
        font-size: 0.9rem;
    }

    .distribute-btn {
        background: linear-gradient(45deg, var(--diamond-color), var(--platinum-color));
        color: #000;
        border: none;
        padding: 10px 20px;
        border-radius: 20px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .distribute-btn:hover {
        transform: translateY(-2px);
        box-shadow: var(--diamond-glow);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }

        .music-player-preview audio {
            width: 300px;
        }

        .creation-tools {
            grid-template-columns: 1fr;
        }

        .distribution-platforms {
            grid-template-columns: 1fr;
        }
    }
</style>

<script>
    // Music page functionality
    function openTool(toolType) {
        const tools = {
            piano: 'Opening AI Piano Studio...',
            drums: 'Loading Beat Maker...',
            guitar: 'Starting Guitar Studio...',
            vocals: 'Initializing Voice Recorder...'
        };

        showNotification(tools[toolType] || 'Loading tool...', 'success');

        // Simulate tool loading
        setTimeout(() => {
            showNotification(`${toolType.charAt(0).toUpperCase() + toolType.slice(1)} tool ready!`, 'success');
        }, 2000);
    }

    function playTrack(trackId) {
        showNotification(`Playing track ${trackId}`, 'success');
    }

    function editTrack(trackId) {
        showNotification(`Opening editor for track ${trackId}`, 'info');
    }

    function shareTrack(trackId) {
        showNotification(`Sharing track ${trackId}`, 'success');
    }

    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        background: ${type === 'success' ? 'linear-gradient(45deg, #00ff41, #00cc33)' : 
                     type === 'error' ? 'linear-gradient(45deg, #ff4444, #cc2222)' : 
                     'linear-gradient(45deg, #ffd700, #ffcc00)'};
        color: ${type === 'success' || type === 'error' ? 'white' : 'black'};
        padding: 15px 25px;
        border-radius: 10px;
        z-index: 10000;
        opacity: 0;
        transition: opacity 0.3s ease;
        max-width: 300px;
        font-weight: 600;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    `;
        notification.textContent = message;

        document.body.appendChild(notification);

        setTimeout(() => notification.style.opacity = '1', 100);
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => document.body.removeChild(notification), 300);
        }, 3000);
    }

    // Auto-start background music
    document.addEventListener('DOMContentLoaded', function() {
        const audio = document.querySelector('audio');
        if (audio) {
            audio.volume = 0.3;
            // Try to play, but handle autoplay restrictions
            const playPromise = audio.play();
            if (playPromise !== undefined) {
                playPromise.catch(error => {
                    console.log('Autoplay prevented:', error);
                });
            }
        }
    });
</script>

<?php get_footer(); ?>