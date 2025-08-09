<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
/*
Template Name: YouTuneAI Streaming Page
*/

get_header(); ?>

<div class="streaming-page">
    <div class="streaming-hero">
        <h1>Live Streaming Studio</h1>
        <p>Professional streaming setup powered by AI</p>
    </div>

    <div class="streaming-container">
        <div class="main-stream">
            <div class="stream-viewer" id="streamViewer">
                <video id="localVideo" autoplay muted playsinline></video>
                <div class="stream-overlay">
                    <div class="stream-controls">
                        <button id="startStreamBtn" class="stream-btn start">🔴 Start Stream</button>
                        <button id="stopStreamBtn" class="stream-btn stop" disabled>⏹️ Stop Stream</button>
                        <button id="toggleAudioBtn" class="stream-btn">🎤 Audio</button>
                        <button id="toggleVideoBtn" class="stream-btn">📹 Video</button>
                    </div>
                </div>
            </div>

            <div class="stream-settings">
                <h3>Stream Settings</h3>
                <div class="setting-group">
                    <label>Stream Title:</label>
                    <input type="text" id="streamTitle" placeholder="Enter stream title" value="YouTuneAI Live Stream">
                </div>
                <div class="setting-group">
                    <label>Stream Quality:</label>
                    <select id="streamQuality">
                        <option value="720p">HD (720p)</option>
                        <option value="1080p" selected>Full HD (1080p)</option>
                        <option value="4K">4K Ultra HD</option>
                    </select>
                </div>
                <div class="setting-group">
                    <label>Stream Key:</label>
                    <input type="password" id="streamKey" placeholder="Enter your stream key">
                </div>
            </div>
        </div>

        <div class="stream-sidebar">
            <div class="chat-section">
                <h3>Live Chat</h3>
                <div class="chat-messages" id="chatMessages">
                    <div class="welcome-message">Welcome to YouTuneAI Live Stream!</div>
                </div>
                <div class="chat-input">
                    <input type="text" id="chatInput" placeholder="Type a message...">
                    <button id="sendChatBtn">Send</button>
                </div>
            </div>

            <div class="stream-stats">
                <h3>Stream Statistics</h3>
                <div class="stat-item">
                    <span class="stat-label">Viewers:</span>
                    <span class="stat-value" id="viewerCount">0</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Duration:</span>
                    <span class="stat-value" id="streamDuration">00:00:00</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Bitrate:</span>
                    <span class="stat-value" id="streamBitrate">0 kbps</span>
                </div>
            </div>

            <div class="ai-controls">
                <h3>AI Stream Controls</h3>
                <button class="ai-btn" onclick="aiCommand('enhance stream quality')">🔧 Enhance Quality</button>
                <button class="ai-btn" onclick="aiCommand('add overlay graphics')">🎨 Add Graphics</button>
                <button class="ai-btn" onclick="aiCommand('start background music')">🎵 Background Music</button>
                <button class="ai-btn" onclick="aiCommand('switch camera angle')">📷 Switch Camera</button>
            </div>
        </div>
    </div>
</div>

<style>
    .streaming-page {
        min-height: 100vh;
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        color: white;
        padding: 20px;
    }

    .streaming-hero {
        text-align: center;
        margin-bottom: 40px;
    }

    .streaming-hero h1 {
        font-size: 3em;
        margin-bottom: 10px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    }

    .streaming-container {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 30px;
        max-width: 1400px;
        margin: 0 auto;
    }

    .main-stream {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        padding: 20px;
        backdrop-filter: blur(10px);
    }

    .stream-viewer {
        position: relative;
        background: #000;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 20px;
    }

    #localVideo {
        width: 100%;
        height: 400px;
        object-fit: cover;
    }

    .stream-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
        padding: 20px;
    }

    .stream-controls {
        display: flex;
        gap: 10px;
        justify-content: center;
    }

    .stream-btn {
        padding: 10px 20px;
        border: none;
        border-radius: 25px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s;
    }

    .stream-btn.start {
        background: #e74c3c;
        color: white;
    }

    .stream-btn.stop {
        background: #95a5a6;
        color: white;
    }

    .stream-btn:not(.start):not(.stop) {
        background: #3498db;
        color: white;
    }

    .stream-btn:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }

    .stream-settings {
        background: rgba(255, 255, 255, 0.1);
        padding: 20px;
        border-radius: 10px;
    }

    .setting-group {
        margin-bottom: 15px;
    }

    .setting-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    .setting-group input,
    .setting-group select {
        width: 100%;
        padding: 10px;
        border: none;
        border-radius: 5px;
        background: rgba(255, 255, 255, 0.9);
        color: #333;
    }

    .stream-sidebar {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .chat-section,
    .stream-stats,
    .ai-controls {
        background: rgba(255, 255, 255, 0.1);
        padding: 20px;
        border-radius: 15px;
        backdrop-filter: blur(10px);
    }

    .chat-messages {
        height: 200px;
        overflow-y: auto;
        background: rgba(0, 0, 0, 0.3);
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 10px;
    }

    .welcome-message {
        color: #f39c12;
        font-style: italic;
        text-align: center;
        padding: 10px;
    }

    .chat-input {
        display: flex;
        gap: 10px;
    }

    .chat-input input {
        flex: 1;
        padding: 10px;
        border: none;
        border-radius: 5px;
        background: rgba(255, 255, 255, 0.9);
        color: #333;
    }

    .chat-input button {
        padding: 10px 20px;
        background: #27ae60;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .stat-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        padding: 5px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 5px;
    }

    .ai-btn {
        width: 100%;
        margin-bottom: 10px;
        padding: 12px;
        background: linear-gradient(45deg, #8e44ad, #3498db);
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: bold;
        transition: all 0.3s;
    }

    .ai-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }

    @media (max-width: 768px) {
        .streaming-container {
            grid-template-columns: 1fr;
        }

        .stream-controls {
            flex-wrap: wrap;
        }

        .stream-btn {
            font-size: 0.9em;
            padding: 8px 15px;
        }
    }
</style>

<script>
    let localStream;
    let isStreaming = false;
    let streamStartTime;
    let streamTimer;

    // Initialize streaming functionality
    async function initializeStreaming() {
        try {
            localStream = await navigator.mediaDevices.getUserMedia({
                video: {
                    width: 1920,
                    height: 1080
                },
                audio: true
            });

            const video = document.getElementById('localVideo');
            video.srcObject = localStream;

            console.log('Camera and microphone access granted');
        } catch (error) {
            console.error('Error accessing media devices:', error);
            alert('Could not access camera/microphone. Please check permissions.');
        }
    }

    // Start streaming
    function startStream() {
        if (!localStream) {
            alert('Please allow camera access first');
            return;
        }

        isStreaming = true;
        streamStartTime = Date.now();

        document.getElementById('startStreamBtn').disabled = true;
        document.getElementById('stopStreamBtn').disabled = false;

        // Start stream timer
        streamTimer = setInterval(updateStreamStats, 1000);

        // Simulate stream initialization
        addChatMessage('System', 'Stream started successfully!');
        updateViewerCount();

        console.log('Stream started');
    }

    // Stop streaming
    function stopStream() {
        isStreaming = false;

        document.getElementById('startStreamBtn').disabled = false;
        document.getElementById('stopStreamBtn').disabled = true;

        if (streamTimer) {
            clearInterval(streamTimer);
        }

        addChatMessage('System', 'Stream ended');
        document.getElementById('viewerCount').textContent = '0';

        console.log('Stream stopped');
    }

    // Toggle audio
    function toggleAudio() {
        if (localStream) {
            const audioTracks = localStream.getAudioTracks();
            audioTracks.forEach(track => {
                track.enabled = !track.enabled;
            });

            const btn = document.getElementById('toggleAudioBtn');
            btn.textContent = audioTracks[0].enabled ? '🎤 Audio' : '🔇 Muted';
        }
    }

    // Toggle video
    function toggleVideo() {
        if (localStream) {
            const videoTracks = localStream.getVideoTracks();
            videoTracks.forEach(track => {
                track.enabled = !track.enabled;
            });

            const btn = document.getElementById('toggleVideoBtn');
            btn.textContent = videoTracks[0].enabled ? '📹 Video' : '📹 Off';
        }
    }

    // Update stream statistics
    function updateStreamStats() {
        if (isStreaming && streamStartTime) {
            const elapsed = Date.now() - streamStartTime;
            const hours = Math.floor(elapsed / 3600000);
            const minutes = Math.floor((elapsed % 3600000) / 60000);
            const seconds = Math.floor((elapsed % 60000) / 1000);

            document.getElementById('streamDuration').textContent =
                `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

            // Simulate bitrate
            const bitrate = Math.floor(Math.random() * 1000) + 2000;
            document.getElementById('streamBitrate').textContent = `${bitrate} kbps`;
        }
    }

    // Update viewer count
    function updateViewerCount() {
        if (isStreaming) {
            const viewers = Math.floor(Math.random() * 50) + 1;
            document.getElementById('viewerCount').textContent = viewers;
            setTimeout(updateViewerCount, 30000); // Update every 30 seconds
        }
    }

    // Add chat message
    function addChatMessage(username, message) {
        const chatMessages = document.getElementById('chatMessages');
        const messageElement = document.createElement('div');
        messageElement.innerHTML = `<strong>${username}:</strong> ${message}`;
        messageElement.style.marginBottom = '5px';
        chatMessages.appendChild(messageElement);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    // Send chat message
    function sendChatMessage() {
        const input = document.getElementById('chatInput');
        const message = input.value.trim();

        if (message) {
            addChatMessage('You', message);
            input.value = '';

            // Simulate responses
            setTimeout(() => {
                const responses = [
                    'Great stream!',
                    'Loving the AI features!',
                    'How did you set this up?',
                    'Amazing quality!',
                    'Keep it up!'
                ];
                const randomResponse = responses[Math.floor(Math.random() * responses.length)];
                addChatMessage('Viewer' + Math.floor(Math.random() * 100), randomResponse);
            }, 2000);
        }
    }

    // AI command execution
    function aiCommand(command) {
        addChatMessage('AI System', `Executing command: ${command}`);

        // Simulate AI processing
        setTimeout(() => {
            addChatMessage('AI System', `Command "${command}" executed successfully!`);
        }, 1500);
    }

    // Event listeners
    document.getElementById('startStreamBtn').addEventListener('click', startStream);
    document.getElementById('stopStreamBtn').addEventListener('click', stopStream);
    document.getElementById('toggleAudioBtn').addEventListener('click', toggleAudio);
    document.getElementById('toggleVideoBtn').addEventListener('click', toggleVideo);
    document.getElementById('sendChatBtn').addEventListener('click', sendChatMessage);

    document.getElementById('chatInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            sendChatMessage();
        }
    });

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', initializeStreaming);
</script>

<?php get_footer(); ?>