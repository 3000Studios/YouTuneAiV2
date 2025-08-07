<?php
/*
Template Name: Contact Page
*/

get_header(); ?>

<div class="contact-page">
    <!-- Hero Section -->
    <div class="contact-hero">
        <div class="hero-content">
            <h1 class="hero-title">
                <span class="gradient-text">Contact Us</span>
                <div class="title-glow"></div>
            </h1>
            <p class="hero-subtitle">Get in Touch with the YouTuneAI Team</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="contact-container">
        <!-- Contact Form Section -->
        <div class="contact-form-section">
            <h2>📧 Send Us a Message</h2>
            <form class="contact-form" id="contactForm">
                <div class="form-row">
                    <div class="form-group">
                        <label for="firstName">First Name *</label>
                        <input type="text" id="firstName" name="firstName" required>
                    </div>
                    <div class="form-group">
                        <label for="lastName">Last Name *</label>
                        <input type="text" id="lastName" name="lastName" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone">
                    </div>
                </div>

                <div class="form-group">
                    <label for="company">Company/Organization</label>
                    <input type="text" id="company" name="company">
                </div>

                <div class="form-group">
                    <label for="subject">Subject *</label>
                    <select id="subject" name="subject" required>
                        <option value="">Select a subject</option>
                        <option value="licensing">💼 Technology Licensing</option>
                        <option value="partnership">🤝 Partnership Opportunities</option>
                        <option value="support">🛠️ Technical Support</option>
                        <option value="demo">📺 Product Demo Request</option>
                        <option value="media">📰 Media & Press Inquiries</option>
                        <option value="investment">💰 Investment Opportunities</option>
                        <option value="general">💬 General Inquiry</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="message">Message *</label>
                    <textarea id="message" name="message" rows="6" required placeholder="Tell us about your inquiry..."></textarea>
                </div>

                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" id="newsletter" name="newsletter">
                        <span class="checkmark"></span>
                        Subscribe to YouTuneAI updates and announcements
                    </label>
                </div>

                <button type="submit" class="submit-btn">
                    <span>Send Message</span>
                    <div class="btn-glow"></div>
                </button>
            </form>
        </div>

        <!-- Contact Info Section -->
        <div class="contact-info-section">
            <h2>📍 Contact Information</h2>

            <div class="info-card">
                <div class="info-icon">✉️</div>
                <div class="info-content">
                    <h3>Email</h3>
                    <p><a href="mailto:mr.jwswain@gmail.com">mr.jwswain@gmail.com</a></p>
                    <span class="info-label">Primary Contact</span>
                </div>
            </div>

            <div class="info-card">
                <div class="info-icon">💼</div>
                <div class="info-content">
                    <h3>Licensing Inquiries</h3>
                    <p><a href="mailto:licensing@youtuneai.com">licensing@youtuneai.com</a></p>
                    <span class="info-label">Commercial Use & Patents</span>
                </div>
            </div>

            <div class="info-card">
                <div class="info-icon">🏢</div>
                <div class="info-content">
                    <h3>Company</h3>
                    <p>3000Studios</p>
                    <span class="info-label">Innovation Hub</span>
                </div>
            </div>

            <div class="info-card">
                <div class="info-icon">🌐</div>
                <div class="info-content">
                    <h3>Website</h3>
                    <p><a href="https://youtuneai.com">youtuneai.com</a></p>
                    <span class="info-label">Official Platform</span>
                </div>
            </div>

            <!-- Response Time -->
            <div class="response-info">
                <h3>📊 Response Times</h3>
                <div class="response-list">
                    <div class="response-item">
                        <span class="response-type">Licensing Inquiries:</span>
                        <span class="response-time">Within 24 hours</span>
                    </div>
                    <div class="response-item">
                        <span class="response-type">Technical Support:</span>
                        <span class="response-time">1-2 business days</span>
                    </div>
                    <div class="response-item">
                        <span class="response-type">General Inquiries:</span>
                        <span class="response-time">2-3 business days</span>
                    </div>
                    <div class="response-item">
                        <span class="response-type">Media Requests:</span>
                        <span class="response-time">Same day</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Legal Notice Section -->
    <div class="legal-notice-section">
        <div class="legal-container">
            <h2>⚖️ Legal Notice</h2>
            <div class="legal-content">
                <div class="patent-notice">
                    <h3>🔬 Patent Pending Technology</h3>
                    <p>YouTuneAI's voice-controlled website modification system is patent pending technology filed on August 1, 2025. This represents the world's first comprehensive voice-controlled web interface system.</p>
                    <p><strong>Patent Status:</strong> Pending - Application Filed</p>
                    <p><strong>Inventor:</strong> Mr. Swain (3000Studios)</p>
                    <p><strong>Commercial Use:</strong> Requires licensing agreement</p>
                </div>

                <div class="licensing-info">
                    <h3>💼 Licensing Opportunities</h3>
                    <p>We welcome partnerships and licensing discussions for our revolutionary technology. Our patent-pending system offers unique opportunities for:</p>
                    <ul>
                        <li>Web development companies</li>
                        <li>E-commerce platforms</li>
                        <li>Content management systems</li>
                        <li>AI technology integrators</li>
                        <li>Voice interface developers</li>
                    </ul>
                    <p>Contact us at <a href="mailto:mr.jwswain@gmail.com">mr.jwswain@gmail.com</a> for licensing discussions.</p>
                </div>

                <div class="copyright-info">
                    <h3>©️ Copyright Notice</h3>
                    <p>All content, technology, and intellectual property on this website is protected by copyright and patent law. Unauthorized use is prohibited and subject to legal action.</p>
                    <p><strong>Copyright © 2025 3000Studios - All Rights Reserved</strong></p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .contact-page {
        min-height: 100vh;
        background: linear-gradient(135deg, #000000 0%, #1a1a1a 50%, #000000 100%);
        padding-top: 120px;
        position: relative;
    }

    .contact-page::before {
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

    .contact-hero {
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
    }

    .contact-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 20px;
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 40px;
    }

    /* Contact Form Section */
    .contact-form-section {
        background: linear-gradient(135deg, rgba(26, 26, 26, 0.9) 0%, rgba(0, 0, 0, 0.8) 100%);
        border-radius: 20px;
        padding: 40px;
        border: 2px solid rgba(255, 215, 0, 0.3);
        box-shadow: var(--gold-glow);
    }

    .contact-form-section h2 {
        color: var(--gold-color);
        font-size: 2rem;
        margin-bottom: 30px;
        text-shadow: var(--gold-glow);
    }

    .contact-form {
        display: flex;
        flex-direction: column;
        gap: 25px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        color: var(--text-secondary);
        font-weight: 600;
        margin-bottom: 8px;
        font-size: 0.9rem;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        padding: 15px;
        background: rgba(26, 26, 26, 0.8);
        border: 2px solid rgba(229, 228, 226, 0.3);
        border-radius: 10px;
        color: white;
        font-size: 16px;
        transition: all 0.3s ease;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--accent-color);
        box-shadow: 0 0 15px rgba(0, 255, 65, 0.3);
        background: rgba(0, 255, 65, 0.05);
    }

    .form-group textarea {
        resize: vertical;
        min-height: 120px;
    }

    .checkbox-label {
        display: flex;
        align-items: center;
        cursor: pointer;
        color: var(--text-secondary);
        font-size: 0.9rem;
    }

    .checkbox-label input[type="checkbox"] {
        display: none;
    }

    .checkmark {
        width: 20px;
        height: 20px;
        background: rgba(26, 26, 26, 0.8);
        border: 2px solid rgba(229, 228, 226, 0.3);
        border-radius: 4px;
        margin-right: 12px;
        position: relative;
        transition: all 0.3s ease;
    }

    .checkbox-label input[type="checkbox"]:checked+.checkmark {
        background: var(--accent-color);
        border-color: var(--accent-color);
    }

    .checkbox-label input[type="checkbox"]:checked+.checkmark::after {
        content: '✓';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: #000;
        font-weight: bold;
    }

    .submit-btn {
        background: linear-gradient(45deg, var(--gold-color), var(--platinum-color));
        color: #000;
        border: none;
        padding: 18px 40px;
        border-radius: 25px;
        font-size: 18px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        margin-top: 10px;
    }

    .submit-btn:hover {
        transform: translateY(-3px);
        box-shadow: var(--gold-glow);
    }

    .btn-glow {
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.5s ease;
    }

    .submit-btn:hover .btn-glow {
        left: 100%;
    }

    /* Contact Info Section */
    .contact-info-section {
        background: linear-gradient(135deg, rgba(26, 26, 26, 0.9) 0%, rgba(0, 0, 0, 0.8) 100%);
        border-radius: 20px;
        padding: 40px;
        border: 2px solid rgba(0, 255, 65, 0.3);
        height: fit-content;
    }

    .contact-info-section h2 {
        color: var(--accent-color);
        font-size: 2rem;
        margin-bottom: 30px;
        text-shadow: var(--neon-glow);
    }

    .info-card {
        display: flex;
        align-items: center;
        gap: 20px;
        background: rgba(26, 26, 26, 0.6);
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 20px;
        border: 1px solid rgba(229, 228, 226, 0.2);
        transition: all 0.3s ease;
    }

    .info-card:hover {
        background: rgba(0, 255, 65, 0.1);
        border-color: var(--accent-color);
        transform: translateX(5px);
    }

    .info-icon {
        font-size: 2.5rem;
        min-width: 60px;
        text-align: center;
    }

    .info-content h3 {
        color: var(--accent-color);
        margin-bottom: 8px;
        font-size: 1.2rem;
    }

    .info-content p {
        color: white;
        margin-bottom: 5px;
    }

    .info-content a {
        color: var(--gold-color);
        text-decoration: none;
        font-weight: 600;
    }

    .info-content a:hover {
        text-shadow: var(--gold-glow);
    }

    .info-label {
        color: var(--text-secondary);
        font-size: 0.8rem;
        font-style: italic;
    }

    .response-info {
        background: rgba(30, 58, 138, 0.2);
        border-radius: 15px;
        padding: 25px;
        border: 1px solid var(--metal-blue);
    }

    .response-info h3 {
        color: var(--diamond-color);
        margin-bottom: 20px;
        font-size: 1.3rem;
    }

    .response-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .response-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px;
        background: rgba(26, 26, 26, 0.4);
        border-radius: 8px;
    }

    .response-type {
        color: var(--text-secondary);
        font-weight: 600;
    }

    .response-time {
        color: var(--accent-color);
        font-weight: 700;
    }

    /* Legal Notice Section */
    .legal-notice-section {
        margin-top: 60px;
        background: linear-gradient(135deg, rgba(26, 26, 26, 0.9) 0%, rgba(0, 0, 0, 0.8) 100%);
        border-top: 3px solid var(--gold-color);
    }

    .legal-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    .legal-notice-section h2 {
        color: var(--gold-color);
        font-size: 2.5rem;
        margin-bottom: 30px;
        text-align: center;
        text-shadow: var(--gold-glow);
    }

    .legal-content {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 30px;
    }

    .patent-notice,
    .licensing-info,
    .copyright-info {
        background: rgba(26, 26, 26, 0.6);
        border-radius: 15px;
        padding: 30px;
        border: 1px solid rgba(255, 215, 0, 0.3);
    }

    .patent-notice h3,
    .licensing-info h3,
    .copyright-info h3 {
        color: var(--gold-color);
        margin-bottom: 15px;
        font-size: 1.3rem;
    }

    .patent-notice p,
    .licensing-info p,
    .copyright-info p {
        color: var(--text-primary);
        line-height: 1.6;
        margin-bottom: 12px;
    }

    .licensing-info ul {
        color: var(--text-secondary);
        margin-left: 20px;
        margin-bottom: 15px;
    }

    .licensing-info ul li {
        margin-bottom: 5px;
    }

    .licensing-info a,
    .patent-notice strong,
    .copyright-info strong {
        color: var(--accent-color);
        font-weight: 700;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }

        .contact-container {
            grid-template-columns: 1fr;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .legal-content {
            grid-template-columns: 1fr;
        }
    }
</style>

<script>
    // Contact form functionality
    document.addEventListener('DOMContentLoaded', function() {
        const contactForm = document.getElementById('contactForm');

        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Get form data
            const formData = new FormData(contactForm);
            const data = {};

            for (let [key, value] of formData.entries()) {
                data[key] = value;
            }

            // Show submission animation
            const submitBtn = contactForm.querySelector('.submit-btn');
            const originalText = submitBtn.innerHTML;

            submitBtn.innerHTML = '<span>Sending...</span>';
            submitBtn.disabled = true;

            // Simulate form submission
            setTimeout(() => {
                submitBtn.innerHTML = '<span>Message Sent! ✓</span>';
                submitBtn.style.background = 'linear-gradient(45deg, #00ff41, #00cc33)';

                // Show success notification
                showNotification('Message sent successfully! We\'ll get back to you soon.', 'success');

                // Reset form after 3 seconds
                setTimeout(() => {
                    contactForm.reset();
                    submitBtn.innerHTML = originalText;
                    submitBtn.style.background = 'linear-gradient(45deg, var(--gold-color), var(--platinum-color))';
                    submitBtn.disabled = false;
                }, 3000);

            }, 2000);
        });
    });

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
        padding: 20px 30px;
        border-radius: 15px;
        z-index: 10000;
        opacity: 0;
        transition: opacity 0.3s ease;
        max-width: 350px;
        font-weight: 600;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        font-size: 16px;
    `;
        notification.textContent = message;

        document.body.appendChild(notification);

        setTimeout(() => notification.style.opacity = '1', 100);
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => document.body.removeChild(notification), 300);
        }, 5000);
    }
</script>

<?php get_footer(); ?>