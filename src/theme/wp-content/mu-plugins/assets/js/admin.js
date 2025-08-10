/**
 * Admin JavaScript for YouTune Admin Control Center
 */

jQuery(document).ready(function($) {
    'use strict';
    
    // Show message function
    function showMessage(message, type) {
        var messageHtml = '<div class="youtune-message ' + (type === 'error' ? 'error' : '') + '">' + message + '</div>';
        $('#youtune-admin-messages').html(messageHtml);
        
        // Auto-hide success messages after 5 seconds
        if (type !== 'error') {
            setTimeout(function() {
                $('#youtune-admin-messages').fadeOut();
            }, 5000);
        }
    }
    
    // Clear messages function
    function clearMessages() {
        $('#youtube-admin-messages').empty();
    }
    
    // Global function for action buttons
    window.youtuneAdminAction = function(action, nonce) {
        var button = $('button[data-action="' + action + '"]');
        var originalText = button.text();
        
        // Disable button and show loading state
        button.prop('disabled', true).text('Processing...');
        button.closest('.youtube-admin-card').addClass('loading');
        
        clearMessages();
        
        // AJAX request
        $.ajax({
            url: youtuneAdmin.ajaxurl,
            type: 'POST',
            data: {
                action: 'youtune_' + action,
                nonce: nonce,
            },
            timeout: 30000, // 30 second timeout
            success: function(response) {
                if (response.success) {
                    showMessage(response.message, 'success');
                    
                    // Handle special actions
                    if (response.data && response.data.redirect) {
                        setTimeout(function() {
                            window.location.href = response.data.redirect;
                        }, 2000);
                    }
                } else {
                    showMessage(response.message || 'An error occurred.', 'error');
                }
            },
            error: function(xhr, status, error) {
                var errorMessage = 'Request failed: ';
                if (status === 'timeout') {
                    errorMessage += 'Request timed out. The action may still be processing.';
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage += xhr.responseJSON.message;
                } else {
                    errorMessage += error || 'Unknown error';
                }
                showMessage(errorMessage, 'error');
            },
            complete: function() {
                // Re-enable button and remove loading state
                button.prop('disabled', false).text(originalText);
                button.closest('.youtube-admin-card').removeClass('loading');
            }
        });
    };
    
    // Handle action button clicks (backup method)
    $('.youtube-admin-button').on('click', function(e) {
        e.preventDefault();
        var action = $(this).data('action');
        var nonce = $(this).data('nonce');
        
        if (action && nonce) {
            youtuneAdminAction(action, nonce);
        }
    });
    
    // Confirmation for destructive actions
    $('button[data-action="flush_caches"], button[data-action="run_full_test"]').on('click', function(e) {
        var action = $(this).data('action');
        var confirmMessage = '';
        
        if (action === 'flush_caches') {
            confirmMessage = 'Are you sure you want to flush all caches? This may temporarily slow down your site.';
        } else if (action === 'run_full_test') {
            confirmMessage = 'Are you sure you want to run the full test suite? This may take several minutes.';
        }
        
        if (confirmMessage && !confirm(confirmMessage)) {
            e.stopImmediatePropagation();
            return false;
        }
    });
    
    // Auto-refresh page status (optional enhancement)
    // TODO: Implement real-time status updates via WebSocket or polling
    function refreshStatus() {
        // Placeholder for status refresh functionality
        // Could poll an endpoint for system status updates
    }
    
    // Keyboard shortcuts (optional enhancement)
    $(document).on('keydown', function(e) {
        // Ctrl/Cmd + D for Deploy Now
        if ((e.ctrlKey || e.metaKey) && e.key === 'd') {
            e.preventDefault();
            $('button[data-action="deploy_now"]').click();
        }
        
        // Ctrl/Cmd + R for Run Full Test
        if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
            e.preventDefault();
            $('button[data-action="run_full_test"]').click();
        }
        
        // Ctrl/Cmd + F for Flush Caches
        if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
            e.preventDefault();
            $('button[data-action="flush_caches"]').click();
        }
    });
    
    // Add keyboard shortcut hints to buttons (optional enhancement)
    $('button[data-action="deploy_now"]').attr('title', 'Deploy Now (Ctrl+D)');
    $('button[data-action="run_full_test"]').attr('title', 'Run Full Test (Ctrl+R)');  
    $('button[data-action="flush_caches"]').attr('title', 'Flush Caches (Ctrl+F)');
});

// Global helper functions for external extensions
window.youtuneAdminHelpers = {
    showMessage: function(message, type) {
        var messageHtml = '<div class="youtune-message ' + (type === 'error' ? 'error' : '') + '">' + message + '</div>';
        jQuery('#youtune-admin-messages').html(messageHtml);
    },
    
    clearMessages: function() {
        jQuery('#youtune-admin-messages').empty();
    },
    
    disableAllButtons: function() {
        jQuery('.youtube-admin-button').prop('disabled', true);
    },
    
    enableAllButtons: function() {
        jQuery('.youtube-admin-button').prop('disabled', false);
    }
};