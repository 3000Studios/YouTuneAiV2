<!-- Footer -->
<footer class="footer-container">
    <!-- Footer Video Background -->
    <video class="footer-video" autoplay muted loop>
        <source src="<?php echo get_template_directory_uri(); ?>/assets/video/footer-background.mp4" type="video/mp4">
        <!-- Fallback to CodePen inspired video -->
        <source src="https://www.learningcontainer.com/wp-content/uploads/2020/05/sample-mp4-file.mp4" type="video/mp4">
    </video>
    
    <div class="footer-content">
        <div class="footer-grid">
            <div class="footer-section">
                <h3>YouTuneAI</h3>
                <p>AI-Powered Content Creation Platform</p>
                <div class="social-links">
                    <a href="#" class="social-link">📺 YouTube</a>
                    <a href="#" class="social-link">📸 Instagram</a>
                    <a href="#" class="social-link">🐦 Twitter</a>
                    <a href="#" class="social-link">💬 Discord</a>
                </div>
            </div>
            
            <div class="footer-section">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="<?php echo home_url('/shop'); ?>">Shop</a></li>
                    <li><a href="<?php echo home_url('/streaming'); ?>">Live Streams</a></li>
                    <li><a href="<?php echo home_url('/music'); ?>">Music</a></li>
                    <li><a href="<?php echo home_url('/ai-tools'); ?>">AI Tools</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h4>Support</h4>
                <ul>
                    <li><a href="<?php echo home_url('/contact'); ?>">Contact Us</a></li>
                    <li><a href="<?php echo home_url('/help'); ?>">Help Center</a></li>
                    <li><a href="<?php echo home_url('/privacy'); ?>">Privacy Policy</a></li>
                    <li><a href="<?php echo home_url('/terms'); ?>">Terms of Service</a></li>
                </ul>
            </div>
            
            <div class="footer-section">
                <h4>Stay Connected</h4>
                <p>Get updates on new features and content</p>
                <div class="newsletter">
                    <input type="email" placeholder="Enter your email" class="newsletter-input">
                    <button class="newsletter-btn">Subscribe</button>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> YouTuneAI. All rights reserved.</p>
            <p>Powered by AI • Built with ❤️</p>
        </div>
    </div>
</footer>

<style>
/* Footer Styles */
.footer-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 40px;
    max-width: 1200px;
    margin: 0 auto;
    padding: 40px 20px;
}

.footer-section h3 {
    color: var(--accent-color);
    font-size: 1.8rem;
    margin-bottom: 15px;
}

.footer-section h4 {
    color: var(--accent-color);
    font-size: 1.2rem;
    margin-bottom: 15px;
}

.footer-section ul {
    list-style: none;
    padding: 0;
}

.footer-section ul li {
    margin-bottom: 8px;
}

.footer-section ul li a {
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    transition: color 0.3s ease;
}

.footer-section ul li a:hover {
    color: var(--accent-color);
}

.social-links {
    display: flex;
    gap: 15px;
    margin-top: 15px;
}

.social-link {
    color: white;
    text-decoration: none;
    padding: 8px 12px;
    background: var(--glass-bg);
    border-radius: 20px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
}

.social-link:hover {
    background: var(--accent-color);
    transform: translateY(-2px);
}

.newsletter {
    display: flex;
    gap: 10px;
    margin-top: 15px;
}

.newsletter-input {
    flex: 1;
    padding: 10px 15px;
    background: var(--glass-bg);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 20px;
    color: white;
}

.newsletter-input::placeholder {
    color: rgba(255, 255, 255, 0.6);
}

.newsletter-btn {
    padding: 10px 20px;
    background: linear-gradient(45deg, var(--accent-color), #ff00ff);
    border: none;
    border-radius: 20px;
    color: white;
    cursor: pointer;
    transition: all 0.3s ease;
}

.newsletter-btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--neon-glow);
}

.footer-bottom {
    text-align: center;
    padding-top: 30px;
    border-top: 1px solid rgba(255, 255, 255, 0.2);
    margin-top: 30px;
    color: rgba(255, 255, 255, 0.6);
}

.footer-bottom p {
    margin: 5px 0;
}
</style>

<!-- Background Music Audio Element -->
<audio id="backgroundMusic" loop>
    <source src="<?php echo get_template_directory_uri(); ?>/assets/audio/background-music.mp3" type="audio/mpeg">
    <source src="<?php echo get_template_directory_uri(); ?>/assets/audio/background-music.ogg" type="audio/ogg">
</audio>

<!-- Click Sound Effect -->
<audio id="clickSound" preload="auto">
    <source src="<?php echo get_template_directory_uri(); ?>/assets/audio/click-sound.mp3" type="audio/mpeg">
</audio>

<script>
// Global background music control
document.addEventListener('DOMContentLoaded', function() {
    const music = document.getElementById('backgroundMusic');
    const clickSound = document.getElementById('clickSound');
    
    // Start background music on first user interaction
    let musicStarted = false;
    function startMusic() {
        if (!musicStarted) {
            music.volume = 0.2;
            music.play().catch(e => console.log('Music autoplay prevented'));
            musicStarted = true;
        }
    }
    
    // Add click sounds to buttons
    document.addEventListener('click', function(e) {
        startMusic();
        
        if (e.target.tagName === 'BUTTON' || e.target.tagName === 'A') {
            clickSound.currentTime = 0;
            clickSound.volume = 0.3;
            clickSound.play().catch(e => console.log('Click sound failed'));
        }
    });
    
    // Newsletter subscription
    const newsletterBtn = document.querySelector('.newsletter-btn');
    if (newsletterBtn) {
        newsletterBtn.addEventListener('click', function() {
            const email = document.querySelector('.newsletter-input').value;
            if (email) {
                showNotification('Thanks for subscribing!', 'success');
                document.querySelector('.newsletter-input').value = '';
            } else {
                showNotification('Please enter your email', 'error');
            }
        });
    }
});

// Smooth scroll enhancement
document.addEventListener('click', function(e) {
    if (e.target.tagName === 'A' && e.target.getAttribute('href').startsWith('#')) {
        e.preventDefault();
        const targetId = e.target.getAttribute('href').substring(1);
        const targetElement = document.getElementById(targetId);
        if (targetElement) {
            targetElement.scrollIntoView({ 
                behavior: 'smooth',
                block: 'start'
            });
        }
    }
});
</script>

<?php wp_footer(); ?>
</body>
</html>
