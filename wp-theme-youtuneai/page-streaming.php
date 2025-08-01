<?php
/*
Template Name: Streaming Page
*/
get_header(); ?>

<div class="streaming-page">
    <!-- Hero Section with Live Stream -->
    <section class="stream-hero">
        <div class="stream-container">
            <h1 class="page-title" data-aos="fade-up">🎮 Live Gaming & Streaming</h1>
            <p class="page-subtitle" data-aos="fade-up" data-aos-delay="200">
                Watch live content, interact with streamers, and join the community
            </p>
        </div>
        
        <!-- Main Live Stream Player -->
        <div class="main-stream-player" data-aos="zoom-in" data-aos-delay="400">
            <div class="stream-player">
                <iframe 
                    src="https://player.twitch.tv/?channel=youtuneai&parent=youtuneai.com" 
                    height="400" 
                    width="100%" 
                    allowfullscreen>
                </iframe>
            </div>
            
            <div class="stream-info">
                <h3>🔴 LIVE: AI-Powered Game Development</h3>
                <p>Creating games with AI assistance • 1,247 viewers</p>
                <div class="stream-actions">
                    <button class="follow-btn">Follow</button>
                    <button class="share-btn">Share</button>
                    <button class="fullscreen-btn">Fullscreen</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Streams Grid -->
    <section class="featured-streams">
        <h2 class="section-title">Featured Streams</h2>
        
        <div class="streams-grid">
            <div class="stream-card" data-aos="fade-up">
                <div class="stream-thumbnail">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/stream-1.jpg" alt="Gaming Stream">
                    <div class="live-badge">🔴 LIVE</div>
                    <div class="viewer-count">892 viewers</div>
                </div>
                <div class="stream-details">
                    <h4>Cyberpunk 2077 AI Mod</h4>
                    <p>StreamerName • Gaming</p>
                </div>
            </div>
            
            <div class="stream-card" data-aos="fade-up" data-aos-delay="100">
                <div class="stream-thumbnail">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/stream-2.jpg" alt="Music Stream">
                    <div class="live-badge">🔴 LIVE</div>
                    <div class="viewer-count">654 viewers</div>
                </div>
                <div class="stream-details">
                    <h4>AI Music Production</h4>
                    <p>MusicMaker • Music</p>
                </div>
            </div>
            
            <div class="stream-card" data-aos="fade-up" data-aos-delay="200">
                <div class="stream-thumbnail">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/stream-3.jpg" alt="Tutorial Stream">
                    <div class="live-badge">🔴 LIVE</div>
                    <div class="viewer-count">423 viewers</div>
                </div>
                <div class="stream-details">
                    <h4>AI Art Creation Tutorial</h4>
                    <p>ArtistAI • Tutorial</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stream Categories -->
    <section class="stream-categories">
        <h2 class="section-title">Browse by Category</h2>
        
        <div class="categories-grid">
            <div class="category-card" data-aos="flip-left">
                <div class="category-icon">🎮</div>
                <h3>Gaming</h3>
                <p>1,247 live streams</p>
            </div>
            
            <div class="category-card" data-aos="flip-left" data-aos-delay="100">
                <div class="category-icon">🎵</div>
                <h3>Music</h3>
                <p>892 live streams</p>
            </div>
            
            <div class="category-card" data-aos="flip-left" data-aos-delay="200">
                <div class="category-icon">🎨</div>
                <h3>Creative</h3>
                <p>567 live streams</p>
            </div>
            
            <div class="category-card" data-aos="flip-left" data-aos-delay="300">
                <div class="category-icon">📚</div>
                <h3>Education</h3>
                <p>334 live streams</p>
            </div>
        </div>
    </section>

    <!-- Chat Integration -->
    <div class="stream-chat" id="streamChat">
        <div class="chat-header">
            <h4>💬 Live Chat</h4>
            <button class="chat-toggle">−</button>
        </div>
        
        <div class="chat-messages" id="chatMessages">
            <div class="chat-message">
                <span class="username">AIFan123:</span>
                <span class="message">This AI integration is amazing! 🤖</span>
            </div>
            <div class="chat-message">
                <span class="username">GamerPro:</span>
                <span class="message">How do you trigger the voice commands?</span>
            </div>
            <div class="chat-message">
                <span class="username">TechLover:</span>
                <span class="message">Can we get a tutorial on this setup?</span>
            </div>
        </div>
        
        <div class="chat-input-area">
            <input type="text" placeholder="Type your message..." class="chat-input">
            <button class="send-btn">Send</button>
        </div>
    </div>
</div>

<style>
/* Streaming Page Styles */
.streaming-page {
    padding-top: 80px;
    min-height: 100vh;
}

.stream-hero {
    padding: 50px 20px;
    text-align: center;
    background: linear-gradient(135deg, var(--secondary-bg), var(--primary-bg));
}

.stream-container {
    max-width: 1200px;
    margin: 0 auto;
}

.main-stream-player {
    max-width: 1000px;
    margin: 40px auto;
    background: var(--glass-bg);
    border-radius: 20px;
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.stream-player iframe {
    width: 100%;
    height: 400px;
    border: none;
}

.stream-info {
    padding: 20px;
    background: rgba(0, 0, 0, 0.5);
}

.stream-info h3 {
    color: var(--accent-color);
    margin-bottom: 10px;
}

.stream-actions {
    display: flex;
    gap: 15px;
    margin-top: 15px;
}

.stream-actions button {
    padding: 8px 20px;
    background: var(--accent-color);
    border: none;
    border-radius: 20px;
    color: white;
    cursor: pointer;
    transition: all 0.3s ease;
}

.stream-actions button:hover {
    background: #ff00ff;
    transform: translateY(-2px);
}

.featured-streams, .stream-categories {
    padding: 80px 20px;
    max-width: 1200px;
    margin: 0 auto;
}

.streams-grid, .categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
    margin-top: 40px;
}

.stream-card {
    background: var(--glass-bg);
    border-radius: 15px;
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
    cursor: pointer;
}

.stream-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0, 212, 255, 0.3);
}

.stream-thumbnail {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.stream-thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.stream-card:hover .stream-thumbnail img {
    transform: scale(1.1);
}

.live-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    background: #ff0000;
    color: white;
    padding: 4px 12px;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: bold;
}

.viewer-count {
    position: absolute;
    bottom: 10px;
    right: 10px;
    background: rgba(0, 0, 0, 0.7);
    color: white;
    padding: 4px 8px;
    border-radius: 10px;
    font-size: 0.8rem;
}

.stream-details {
    padding: 20px;
}

.stream-details h4 {
    color: var(--accent-color);
    margin-bottom: 5px;
}

.category-card {
    background: var(--glass-bg);
    border-radius: 15px;
    padding: 40px 20px;
    text-align: center;
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
    cursor: pointer;
}

.category-card:hover {
    transform: scale(1.05);
    box-shadow: 0 0 30px rgba(0, 212, 255, 0.5);
}

.category-icon {
    font-size: 3rem;
    margin-bottom: 15px;
}

.category-card h3 {
    color: var(--accent-color);
    margin-bottom: 10px;
}

/* Chat Styles */
.stream-chat {
    position: fixed;
    right: 20px;
    bottom: 20px;
    width: 300px;
    height: 400px;
    background: var(--glass-bg);
    border-radius: 15px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(20px);
    display: flex;
    flex-direction: column;
    z-index: 1000;
}

.chat-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
}

.chat-toggle {
    background: none;
    border: none;
    color: white;
    font-size: 1.5rem;
    cursor: pointer;
}

.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 15px;
}

.chat-message {
    margin-bottom: 10px;
    font-size: 0.9rem;
}

.username {
    color: var(--accent-color);
    font-weight: bold;
}

.message {
    color: rgba(255, 255, 255, 0.9);
}

.chat-input-area {
    display: flex;
    padding: 15px;
    border-top: 1px solid rgba(255, 255, 255, 0.2);
}

.chat-input {
    flex: 1;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 20px;
    padding: 8px 15px;
    color: white;
    margin-right: 10px;
}

.send-btn {
    background: var(--accent-color);
    border: none;
    border-radius: 20px;
    padding: 8px 15px;
    color: white;
    cursor: pointer;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .stream-chat {
        width: 90%;
        right: 5%;
        bottom: 10px;
    }
    
    .main-stream-player {
        margin: 20px;
    }
    
    .stream-actions {
        flex-wrap: wrap;
    }
}
</style>

<script>
// Streaming page functionality
document.addEventListener('DOMContentLoaded', function() {
    // Initialize chat
    initializeChat();
    
    // Initialize stream interactions
    initializeStreamFeatures();
});

function initializeChat() {
    const chatInput = document.querySelector('.chat-input');
    const sendBtn = document.querySelector('.send-btn');
    const chatMessages = document.querySelector('.chat-messages');
    
    function sendMessage() {
        const message = chatInput.value.trim();
        if (message) {
            const messageElement = document.createElement('div');
            messageElement.className = 'chat-message';
            messageElement.innerHTML = `
                <span class="username">You:</span>
                <span class="message">${message}</span>
            `;
            
            chatMessages.appendChild(messageElement);
            chatMessages.scrollTop = chatMessages.scrollHeight;
            
            chatInput.value = '';
        }
    }
    
    sendBtn.addEventListener('click', sendMessage);
    chatInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });
    
    // Chat toggle
    document.querySelector('.chat-toggle').addEventListener('click', function() {
        const chat = document.getElementById('streamChat');
        chat.style.display = chat.style.display === 'none' ? 'flex' : 'none';
    });
}

function initializeStreamFeatures() {
    // Stream card click handlers
    document.querySelectorAll('.stream-card').forEach(card => {
        card.addEventListener('click', function() {
            // Open stream in main player
            showNotification('Opening stream...', 'info');
        });
    });
    
    // Category card click handlers
    document.querySelectorAll('.category-card').forEach(card => {
        card.addEventListener('click', function() {
            const category = this.querySelector('h3').textContent;
            showNotification(`Browsing ${category} streams...`, 'info');
        });
    });
    
    // Stream action buttons
    document.querySelectorAll('.stream-actions button').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const action = this.textContent.toLowerCase();
            
            switch(action) {
                case 'follow':
                    showNotification('Following streamer!', 'success');
                    this.textContent = 'Following';
                    this.style.background = '#00ff00';
                    break;
                case 'share':
                    if (navigator.share) {
                        navigator.share({
                            title: 'YouTuneAI Live Stream',
                            url: window.location.href
                        });
                    } else {
                        navigator.clipboard.writeText(window.location.href);
                        showNotification('Stream link copied to clipboard!', 'success');
                    }
                    break;
                case 'fullscreen':
                    // Request fullscreen for stream player
                    const player = document.querySelector('.stream-player iframe');
                    if (player.requestFullscreen) {
                        player.requestFullscreen();
                    }
                    break;
            }
        });
    });
}
</script>

<?php get_footer(); ?>
