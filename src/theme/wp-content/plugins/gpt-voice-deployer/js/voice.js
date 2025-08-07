/**
 * GPT Voice Deployer - Boss Man J Edition
 * Voice Recognition and Command Processing
 * Version: 2.0.0
 */

class GPTVoiceController {
    constructor() {
        this.recognition = null;
        this.isListening = false;
        this.isProcessing = false;
        this.currentCommand = '';
        this.apiEndpoint = gptVoice.restUrl + 'command';
        
        this.initializeVoiceRecognition();
        this.bindEvents();
        this.setupUI();
        
        console.log('🎙️ GPT Voice Deployer initialized - Boss Man J mode activated!');
    }
    
    initializeVoiceRecognition() {
        if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) {
            this.showError('❌ Speech recognition not supported in this browser. Use Chrome or Edge!');
            return;
        }
        
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        this.recognition = new SpeechRecognition();
        
        // Configure recognition
        this.recognition.continuous = false;
        this.recognition.interimResults = true;
        this.recognition.lang = gptVoice.settings.voice_language || 'en-US';
        this.recognition.maxAlternatives = 1;
        
        // Event listeners
        this.recognition.onstart = () => {
            this.isListening = true;
            this.updateStatus('🎤 Listening... Speak your command, Boss Man J!');
            this.addPulseEffect();
        };
        
        this.recognition.onresult = (event) => {
            let finalTranscript = '';
            let interimTranscript = '';
            
            for (let i = event.resultIndex; i < event.results.length; i++) {
                const transcript = event.results[i][0].transcript;
                if (event.results[i].isFinal) {
                    finalTranscript += transcript;
                } else {
                    interimTranscript += transcript;
                }
            }
            
            if (finalTranscript) {
                this.currentCommand = finalTranscript.trim();
                this.updateStatus(`📝 Command received: "${this.currentCommand}"`);
                this.processVoiceCommand(this.currentCommand);
            } else if (interimTranscript) {
                this.updateStatus(`🔄 Listening: "${interimTranscript}"`);
            }
        };
        
        this.recognition.onerror = (event) => {
            this.isListening = false;
            this.removePulseEffect();
            
            let errorMessage = 'Voice recognition error: ';
            switch (event.error) {
                case 'no-speech':
                    errorMessage += 'No speech detected. Try again!';
                    break;
                case 'audio-capture':
                    errorMessage += 'Microphone not available';
                    break;
                case 'not-allowed':
                    errorMessage += 'Microphone permission denied';
                    break;
                default:
                    errorMessage += event.error;
            }
            
            this.showError(errorMessage);
        };
        
        this.recognition.onend = () => {
            this.isListening = false;
            this.removePulseEffect();
            if (!this.isProcessing) {
                this.updateStatus('✅ Ready for next command');
            }
        };
    }
    
    bindEvents() {
        // Start voice command button
        jQuery(document).on('click', '#startVoiceBtn, #startVoiceCommand', (e) => {
            e.preventDefault();
            this.startListening();
        });
        
        // Execute code buttons
        jQuery(document).on('click', '.execute-code', (e) => {
            const codeId = jQuery(e.target).data('code-id');
            this.executeGeneratedCode(codeId);
        });
        
        // Floating button events
        jQuery(document).on('click', '#gpt-voice-float-button', () => {
            this.openVoiceModal();
        });
        
        // Modal events
        jQuery(document).on('click', '.gpt-voice-close', () => {
            this.closeVoiceModal();
        });
        
        // Keyboard shortcuts
        jQuery(document).keydown((e) => {
            // Ctrl + Shift + V to start voice command
            if (e.ctrlKey && e.shiftKey && e.keyCode === 86) {
                e.preventDefault();
                this.startListening();
            }
            
            // Escape to stop listening
            if (e.keyCode === 27 && this.isListening) {
                this.stopListening();
            }
        });
        
        // Click outside modal to close
        jQuery(window).click((e) => {
            if (e.target.id === 'gpt-voice-modal') {
                this.closeVoiceModal();
            }
        });
    }
    
    setupUI() {
        // Add custom styles if not already added
        if (!jQuery('#gpt-voice-dynamic-styles').length) {
            const styles = `
                <style id="gpt-voice-dynamic-styles">
                    .gpt-voice-pulse {
                        animation: gpt-voice-pulse 1.5s infinite;
                    }
                    
                    @keyframes gpt-voice-pulse {
                        0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(255, 0, 128, 0.7); }
                        70% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(255, 0, 128, 0); }
                        100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(255, 0, 128, 0); }
                    }
                    
                    .gpt-voice-processing {
                        background: linear-gradient(45deg, #ff6b35, #f7931e) !important;
                        animation: gpt-voice-processing 2s infinite;
                    }
                    
                    @keyframes gpt-voice-processing {
                        0%, 100% { opacity: 1; }
                        50% { opacity: 0.7; }
                    }
                    
                    .gpt-voice-success {
                        background: linear-gradient(45deg, #00ff41, #00d4ff) !important;
                        animation: gpt-voice-success 0.5s ease-in-out;
                    }
                    
                    @keyframes gpt-voice-success {
                        0% { transform: scale(1); }
                        50% { transform: scale(1.1); }
                        100% { transform: scale(1); }
                    }
                    
                    .gpt-voice-error {
                        background: linear-gradient(45deg, #ff4757, #ff3838) !important;
                        animation: gpt-voice-error 0.3s ease-in-out 3;
                    }
                    
                    @keyframes gpt-voice-error {
                        0%, 100% { transform: translateX(0); }
                        25% { transform: translateX(-5px); }
                        75% { transform: translateX(5px); }
                    }
                    
                    .command-item {
                        background: rgba(255,255,255,0.1);
                        padding: 10px;
                        margin: 10px 0;
                        border-radius: 8px;
                        border-left: 4px solid #00ff41;
                    }
                    
                    .code-item {
                        background: rgba(0,0,0,0.3);
                        padding: 15px;
                        margin: 15px 0;
                        border-radius: 8px;
                        border: 1px solid #333;
                    }
                    
                    .code-item pre {
                        background: rgba(0,0,0,0.5);
                        padding: 10px;
                        border-radius: 4px;
                        font-size: 12px;
                        max-height: 200px;
                        overflow-y: auto;
                    }
                    
                    .gpt-voice-typing {
                        display: inline-block;
                    }
                    
                    .gpt-voice-typing:after {
                        content: '|';
                        animation: gpt-voice-blink 1s infinite;
                    }
                    
                    @keyframes gpt-voice-blink {
                        0%, 50% { opacity: 1; }
                        51%, 100% { opacity: 0; }
                    }
                </style>
            `;
            jQuery('head').append(styles);
        }
        
        // Setup initial status
        this.updateStatus('🚀 Voice Command System Ready - Boss Man J Edition');
    }
    
    startListening() {
        if (!this.recognition) {
            this.showError('❌ Voice recognition not available');
            return;
        }
        
        if (this.isListening) {
            this.showError('👂 Already listening...');
            return;
        }
        
        if (this.isProcessing) {
            this.showError('⚡ Currently processing a command...');
            return;
        }
        
        try {
            this.recognition.start();
            this.updateStatus('🎤 Starting voice recognition...');
        } catch (error) {
            this.showError('❌ Could not start voice recognition: ' + error.message);
        }
    }
    
    stopListening() {
        if (this.recognition && this.isListening) {
            this.recognition.stop();
            this.updateStatus('⏹️ Stopped listening');
        }
    }
    
    async processVoiceCommand(command) {
        if (!command || command.trim().length === 0) {
            this.showError('❌ Empty command received');
            return;
        }
        
        this.isProcessing = true;
        this.updateStatus('⚡ Processing command with GPT-4...');
        this.addProcessingEffect();
        
        try {
            const response = await this.sendCommandToAPI(command);
            
            if (response.status === 'success') {
                this.handleSuccessfulCommand(response);
            } else {
                this.showError('❌ Command processing failed: ' + (response.message || 'Unknown error'));
            }
        } catch (error) {
            this.showError('❌ API Error: ' + error.message);
        } finally {
            this.isProcessing = false;
            this.removeProcessingEffect();
        }
    }
    
    async sendCommandToAPI(command) {
        const response = await fetch(this.apiEndpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': gptVoice.nonce
            },
            body: JSON.stringify({
                command: command,
                timestamp: new Date().toISOString()
            })
        });
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        
        return await response.json();
    }
    
    handleSuccessfulCommand(response) {
        this.updateStatus('✅ Command processed successfully!');
        this.addSuccessEffect();
        
        // Display the result
        this.displayCommandResult(response);
        
        // Auto-refresh recent commands if on admin page
        if (jQuery('#recentCommands').length) {
            this.refreshRecentCommands();
        }
        
        // Auto-refresh code review if on admin page
        if (jQuery('#codeReview').length) {
            this.refreshCodeReview();
        }
        
        // Show notification
        this.showNotification('🎉 Voice command executed successfully!', 'success');
        
        // Log to console for debugging
        console.log('✅ GPT Voice Command Result:', response);
    }
    
    displayCommandResult(response) {
        const resultContainer = jQuery('#voiceResult');
        if (!resultContainer.length) return;
        
        const resultHTML = `
            <div class="command-result">
                <h4>📝 Command: "${response.command}"</h4>
                <div class="gpt-response">
                    <h5>🤖 GPT Response:</h5>
                    <pre>${this.formatGPTResponse(response.gpt_response)}</pre>
                </div>
                ${response.result.code_id ? `
                    <div class="execution-options">
                        <button class="gpt-voice-btn execute-code" data-code-id="${response.result.code_id}">
                            ⚡ Execute Generated Code
                        </button>
                        <button class="gpt-voice-btn preview-code" data-code-id="${response.result.code_id}">
                            👁️ Preview Code
                        </button>
                    </div>
                ` : ''}
                <div class="timestamp">🕐 ${response.timestamp}</div>
            </div>
        `;
        
        resultContainer.html(resultHTML);
    }
    
    formatGPTResponse(response) {
        try {
            const parsed = JSON.parse(response);
            return JSON.stringify(parsed, null, 2);
        } catch (e) {
            return response;
        }
    }
    
    async executeGeneratedCode(codeId) {
        if (!codeId) {
            this.showError('❌ No code ID provided');
            return;
        }
        
        this.updateStatus('⚡ Executing generated code...');
        
        try {
            const response = await fetch(gptVoice.restUrl + 'execute', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce': gptVoice.nonce
                },
                body: JSON.stringify({
                    code_id: codeId
                })
            });
            
            const result = await response.json();
            
            if (result.status === 'executed') {
                this.updateStatus('✅ Code executed successfully!');
                this.showNotification('🚀 Code deployed and active!', 'success');
                
                // Show execution result
                this.displayExecutionResult(result);
                
                // Refresh page after successful execution
                setTimeout(() => {
                    if (confirm('🔄 Code executed! Refresh page to see changes?')) {
                        window.location.reload();
                    }
                }, 2000);
            } else {
                this.showError('❌ Code execution failed: ' + (result.message || 'Unknown error'));
            }
        } catch (error) {
            this.showError('❌ Execution Error: ' + error.message);
        }
    }
    
    displayExecutionResult(result) {
        const resultContainer = jQuery('#voiceResult');
        if (!resultContainer.length) return;
        
        const executionHTML = `
            <div class="execution-result">
                <h4>⚡ Execution Result</h4>
                <div class="result-details">
                    <pre>${JSON.stringify(result.result, null, 2)}</pre>
                </div>
                <div class="success-message">
                    ✅ Code has been deployed and is now active on your site!
                </div>
            </div>
        `;
        
        resultContainer.append(executionHTML);
    }
    
    openVoiceModal() {
        jQuery('#gpt-voice-modal').show();
        // Focus on the modal for keyboard accessibility
        jQuery('#gpt-voice-modal').focus();
    }
    
    closeVoiceModal() {
        jQuery('#gpt-voice-modal').hide();
        // Stop listening if modal is closed
        if (this.isListening) {
            this.stopListening();
        }
    }
    
    updateStatus(message) {
        const statusElements = jQuery('#voiceStatus, .voice-status');
        statusElements.html(message);
        
        // Add typing effect for longer messages
        if (message.length > 50) {
            statusElements.addClass('gpt-voice-typing');
            setTimeout(() => {
                statusElements.removeClass('gpt-voice-typing');
            }, 2000);
        }
        
        console.log('📢 Status:', message);
    }
    
    showError(message) {
        this.updateStatus('❌ ' + message);
        this.addErrorEffect();
        
        // Show toast notification
        this.showNotification(message, 'error');
        
        console.error('🚨 Voice Error:', message);
    }
    
    showNotification(message, type = 'info') {
        // Create toast notification
        const toast = jQuery(`
            <div class="gpt-voice-toast gpt-voice-toast-${type}">
                ${message}
            </div>
        `);
        
        // Add toast styles if not exists
        if (!jQuery('#gpt-voice-toast-styles').length) {
            jQuery('head').append(`
                <style id="gpt-voice-toast-styles">
                    .gpt-voice-toast {
                        position: fixed;
                        top: 20px;
                        right: 20px;
                        padding: 15px 20px;
                        border-radius: 8px;
                        color: white;
                        font-weight: bold;
                        z-index: 10001;
                        box-shadow: 0 4px 20px rgba(0,0,0,0.3);
                        transform: translateX(100%);
                        transition: transform 0.3s ease;
                    }
                    .gpt-voice-toast-success {
                        background: linear-gradient(45deg, #00ff41, #00d4ff);
                        color: #1a1a1a;
                    }
                    .gpt-voice-toast-error {
                        background: linear-gradient(45deg, #ff4757, #ff3838);
                    }
                    .gpt-voice-toast-info {
                        background: linear-gradient(45deg, #3742fa, #5f27cd);
                    }
                    .gpt-voice-toast.show {
                        transform: translateX(0);
                    }
                </style>
            `);
        }
        
        jQuery('body').append(toast);
        
        // Animate in
        setTimeout(() => toast.addClass('show'), 100);
        
        // Remove after 4 seconds
        setTimeout(() => {
            toast.removeClass('show');
            setTimeout(() => toast.remove(), 300);
        }, 4000);
    }
    
    addPulseEffect() {
        jQuery('#startVoiceBtn, #startVoiceCommand, #gpt-voice-float-button').addClass('gpt-voice-pulse');
    }
    
    removePulseEffect() {
        jQuery('#startVoiceBtn, #startVoiceCommand, #gpt-voice-float-button').removeClass('gpt-voice-pulse');
    }
    
    addProcessingEffect() {
        jQuery('#startVoiceBtn, #startVoiceCommand, #gpt-voice-float-button').addClass('gpt-voice-processing');
    }
    
    removeProcessingEffect() {
        jQuery('#startVoiceBtn, #startVoiceCommand, #gpt-voice-float-button').removeClass('gpt-voice-processing');
    }
    
    addSuccessEffect() {
        const buttons = jQuery('#startVoiceBtn, #startVoiceCommand, #gpt-voice-float-button');
        buttons.addClass('gpt-voice-success');
        setTimeout(() => {
            buttons.removeClass('gpt-voice-success');
        }, 1000);
    }
    
    addErrorEffect() {
        const buttons = jQuery('#startVoiceBtn, #startVoiceCommand, #gpt-voice-float-button');
        buttons.addClass('gpt-voice-error');
        setTimeout(() => {
            buttons.removeClass('gpt-voice-error');
        }, 1000);
    }
    
    refreshRecentCommands() {
        // Refresh recent commands section
        fetch(gptVoice.restUrl + 'history', {
            headers: {
                'X-WP-Nonce': gptVoice.nonce
            }
        })
        .then(response => response.json())
        .then(data => {
            const container = jQuery('#recentCommands');
            if (container.length && data.history) {
                const recent = data.history.slice(-5).reverse();
                let html = '';
                recent.forEach(cmd => {
                    html += `
                        <div class="command-item">
                            <strong>${cmd.timestamp}</strong><br>
                            ${cmd.command}
                        </div>
                    `;
                });
                container.html(html || '<p>No commands yet. Start speaking!</p>');
            }
        })
        .catch(error => console.error('Error refreshing commands:', error));
    }
    
    refreshCodeReview() {
        // This would refresh the code review section
        // Implementation depends on the specific admin page structure
        window.location.reload();
    }
}

// Global functions for WordPress admin bar and other integrations
window.openVoiceModal = function() {
    if (window.gptVoiceController) {
        window.gptVoiceController.openVoiceModal();
    }
};

window.startVoiceCommand = function() {
    if (window.gptVoiceController) {
        window.gptVoiceController.startListening();
    }
};

// Initialize when ready
jQuery(document).ready(function() {
    // Check if we have the required variables
    if (typeof gptVoice === 'undefined') {
        console.error('❌ GPT Voice configuration not found');
        return;
    }
    
    // Initialize the voice controller
    window.gptVoiceController = new GPTVoiceController();
    
    // Add keyboard shortcut info to admin pages
    if (jQuery('.wrap').length) {
        jQuery('.wrap').first().append(`
            <div class="gpt-voice-shortcuts" style="position: fixed; bottom: 20px; left: 20px; background: rgba(0,0,0,0.8); color: white; padding: 10px; border-radius: 8px; font-size: 12px; z-index: 9998;">
                <div><strong>🎙️ Voice Shortcuts:</strong></div>
                <div>Ctrl+Shift+V - Start Voice Command</div>
                <div>Escape - Stop Listening</div>
            </div>
        `);
    }
    
    console.log('🚀 GPT Voice Deployer ready - Boss Man J can now command the digital realm!');
});
