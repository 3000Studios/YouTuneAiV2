/**
 * GPT Voice Deployer - Frontend Controller
 * Boss Man J Edition - Public Voice Commands
 * Version: 2.0.0
 */

class GPTVoiceFrontend {
    constructor() {
        this.recognition = null;
        this.isListening = false;
        this.isProcessing = false;
        this.apiEndpoint = gptVoiceFrontend.restUrl + 'command';
        
        this.initializeVoiceRecognition();
        this.bindEvents();
        this.addFloatingButton();
        
        console.log('🎙️ GPT Voice Frontend initialized - Public voice commands ready!');
    }
    
    initializeVoiceRecognition() {
        if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) {
            console.warn('❌ Speech recognition not supported in this browser');
            return;
        }
        
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        this.recognition = new SpeechRecognition();
        
        this.recognition.continuous = false;
        this.recognition.interimResults = true;
        this.recognition.lang = 'en-US';
        this.recognition.maxAlternatives = 1;
        
        this.recognition.onstart = () => {
            this.isListening = true;
            this.updateStatus('🎤 Listening for voice commands...');
            this.addPulseEffect();
        };
        
        this.recognition.onresult = (event) => {
            let finalTranscript = '';
            
            for (let i = event.resultIndex; i < event.results.length; i++) {
                if (event.results[i].isFinal) {
                    finalTranscript += event.results[i][0].transcript;
                }
            }
            
            if (finalTranscript) {
                this.processVoiceCommand(finalTranscript.trim());
            }
        };
        
        this.recognition.onerror = (event) => {
            this.isListening = false;
            this.removePulseEffect();
            this.showError('Voice recognition error: ' + event.error);
        };
        
        this.recognition.onend = () => {
            this.isListening = false;
            this.removePulseEffect();
            if (!this.isProcessing) {
                this.updateStatus('✅ Ready for voice commands');
            }
        };
    }
    
    bindEvents() {
        // Float button click
        jQuery(document).on('click', '#gpt-voice-float-button', () => {
            this.openVoiceModal();
        });
        
        // Start voice command
        jQuery(document).on('click', '#startVoiceCommand', () => {
            this.startListening();
        });
        
        // Close modal
        jQuery(document).on('click', '.gpt-voice-close', () => {
            this.closeVoiceModal();
        });
        
        // Keyboard shortcuts (only for logged-in users)
        if (this.isUserLoggedIn()) {
            jQuery(document).keydown((e) => {
                if (e.ctrlKey && e.shiftKey && e.keyCode === 86) {
                    e.preventDefault();
                    this.startListening();
                }
            });
        }
        
        // Click outside modal
        jQuery(window).click((e) => {
            if (e.target.id === 'gpt-voice-modal') {
                this.closeVoiceModal();
            }
        });
    }
    
    addFloatingButton() {
        // Only add if user has permission
        if (!this.canUseVoiceCommands()) {
            return;
        }
        
        const buttonHtml = `
            <div id="gpt-voice-float-button" class="gpt-voice-float" title="Voice Commands (Ctrl+Shift+V)">
                🎙️
                <div class="gpt-voice-tooltip">Voice Commands</div>
            </div>
        `;
        
        jQuery('body').append(buttonHtml);
    }
    
    canUseVoiceCommands() {
        // Check if user is logged in and has appropriate permissions
        return jQuery('body').hasClass('logged-in') || jQuery('#wpadminbar').length > 0;
    }
    
    isUserLoggedIn() {
        return jQuery('body').hasClass('logged-in');
    }
    
    startListening() {
        if (!this.recognition) {
            this.showError('Voice recognition not available');
            return;
        }
        
        if (!this.canUseVoiceCommands()) {
            this.showError('Voice commands require login');
            return;
        }
        
        if (this.isListening) {
            return;
        }
        
        try {
            this.recognition.start();
        } catch (error) {
            this.showError('Could not start voice recognition');
        }
    }
    
    async processVoiceCommand(command) {
        if (!command || command.trim().length === 0) {
            this.showError('Empty command received');
            return;
        }
        
        this.isProcessing = true;
        this.updateStatus('⚡ Processing voice command...');
        this.addProcessingEffect();
        
        try {
            const response = await fetch(this.apiEndpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce': gptVoiceFrontend.nonce
                },
                body: JSON.stringify({
                    command: command,
                    frontend: true
                })
            });
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }
            
            const result = await response.json();
            
            if (result.status === 'success') {
                this.handleSuccess(result);
            } else {
                this.showError('Command failed: ' + (result.message || 'Unknown error'));
            }
        } catch (error) {
            this.showError('Command error: ' + error.message);
        } finally {
            this.isProcessing = false;
            this.removeProcessingEffect();
        }
    }
    
    handleSuccess(result) {
        this.updateStatus('✅ Voice command executed!');
        this.addSuccessEffect();
        
        // Show result in modal
        this.displayResult(result);
        
        // Show notification
        this.showNotification('🎉 Voice command successful!', 'success');
        
        // Auto-refresh if significant changes were made
        if (this.shouldRefreshPage(result)) {
            setTimeout(() => {
                if (confirm('Changes applied! Refresh page to see results?')) {
                    window.location.reload();
                }
            }, 2000);
        }
    }
    
    shouldRefreshPage(result) {
        // Check if the command result indicates page refresh is needed
        const refreshActions = ['theme_update', 'css_inject', 'js_inject'];
        const action = result.result?.parsed_response?.action;
        return refreshActions.includes(action);
    }
    
    displayResult(result) {
        const resultContainer = jQuery('#voiceResult');
        if (!resultContainer.length) return;
        
        const resultHtml = `
            <div class="voice-command-result">
                <h4>📝 Command: "${result.command}"</h4>
                <div class="result-summary">
                    <div class="success-indicator">✅ Successfully processed</div>
                    <div class="timestamp">🕐 ${result.timestamp}</div>
                </div>
                ${result.result.code_id ? `
                    <div class="code-info">
                        <p>💡 Code generated and ready for execution</p>
                        <small>Code ID: ${result.result.code_id}</small>
                    </div>
                ` : ''}
            </div>
        `;
        
        resultContainer.html(resultHtml);
    }
    
    openVoiceModal() {
        jQuery('#gpt-voice-modal').show();
        this.updateStatus('🎙️ Ready to listen - Speak your command!');
    }
    
    closeVoiceModal() {
        jQuery('#gpt-voice-modal').hide();
        if (this.isListening) {
            this.recognition.stop();
        }
    }
    
    updateStatus(message) {
        jQuery('#voiceStatus').html(message);
        console.log('📢 Frontend Status:', message);
    }
    
    showError(message) {
        this.updateStatus('❌ ' + message);
        this.addErrorEffect();
        this.showNotification(message, 'error');
    }
    
    showNotification(message, type = 'info') {
        // Remove existing notifications
        jQuery('.gpt-voice-frontend-toast').remove();
        
        const toast = jQuery(`
            <div class="gpt-voice-frontend-toast gpt-voice-toast-${type}">
                ${message}
            </div>
        `);
        
        // Add styles if needed
        if (!jQuery('#gpt-voice-frontend-toast-styles').length) {
            jQuery('head').append(`
                <style id="gpt-voice-frontend-toast-styles">
                    .gpt-voice-frontend-toast {
                        position: fixed;
                        top: 100px;
                        right: 20px;
                        padding: 15px 20px;
                        border-radius: 8px;
                        color: white;
                        font-weight: bold;
                        z-index: 10001;
                        box-shadow: 0 4px 20px rgba(0,0,0,0.3);
                        transform: translateX(100%);
                        transition: transform 0.3s ease;
                        max-width: 300px;
                        font-size: 14px;
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
                    .gpt-voice-frontend-toast.show {
                        transform: translateX(0);
                    }
                </style>
            `);
        }
        
        jQuery('body').append(toast);
        
        setTimeout(() => toast.addClass('show'), 100);
        setTimeout(() => {
            toast.removeClass('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
    
    addPulseEffect() {
        jQuery('#gpt-voice-float-button').addClass('gpt-voice-pulse');
    }
    
    removePulseEffect() {
        jQuery('#gpt-voice-float-button').removeClass('gpt-voice-pulse');
    }
    
    addProcessingEffect() {
        jQuery('#gpt-voice-float-button').addClass('gpt-voice-processing');
    }
    
    removeProcessingEffect() {
        jQuery('#gpt-voice-float-button').removeClass('gpt-voice-processing');
    }
    
    addSuccessEffect() {
        const button = jQuery('#gpt-voice-float-button');
        button.addClass('gpt-voice-success');
        setTimeout(() => button.removeClass('gpt-voice-success'), 1000);
    }
    
    addErrorEffect() {
        const button = jQuery('#gpt-voice-float-button');
        button.addClass('gpt-voice-error');
        setTimeout(() => button.removeClass('gpt-voice-error'), 1000);
    }
}

// Initialize when document is ready
jQuery(document).ready(function() {
    // Only initialize if we have the required configuration
    if (typeof gptVoiceFrontend !== 'undefined') {
        window.gptVoiceFrontendController = new GPTVoiceFrontend();
    } else {
        console.log('ℹ️ GPT Voice Frontend not configured for this page');
    }
});

// Quick voice command for logged-in users
window.quickVoiceCommand = function() {
    if (window.gptVoiceFrontendController) {
        window.gptVoiceFrontendController.startListening();
    }
};
