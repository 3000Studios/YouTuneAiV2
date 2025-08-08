/**
 * YouTuneAI Main JavaScript Tests
 */

describe('YouTuneAI Main System', () => {
  let mockElement;
  
  beforeEach(() => {
    // Set up DOM elements
    document.body.innerHTML = `
      <div class="mobile-menu-toggle"></div>
      <div class="mobile-menu"></div>
      <a href="#test-section">Test Link</a>
      <div id="test-section"></div>
      <div class="animate-fade-up"></div>
      <div class="floating-element"></div>
      <div class="glow-text"></div>
    `;
    
    // Mock jQuery methods
    global.$ = jest.fn((selector) => ({
      on: jest.fn(),
      addClass: jest.fn(),
      removeClass: jest.fn(),
      toggleClass: jest.fn(),
      find: jest.fn(() => ({
        focus: jest.fn(),
      })),
      animate: jest.fn(),
      scrollTop: jest.fn(),
      each: jest.fn((callback) => {
        if (typeof callback === 'function') {
          callback.call(mockElement, 0, mockElement);
        }
      }),
      offset: jest.fn(() => ({ top: 100 })),
    }));
    
    // Mock window methods
    global.window.scrollTo = jest.fn();
    global.window.innerHeight = 768;
    global.window.innerWidth = 1024;
    
    mockElement = document.createElement('div');
  });

  afterEach(() => {
    document.body.innerHTML = '';
    jest.clearAllMocks();
  });

  test('should initialize YouTuneAI object', () => {
    // Load the main script
    require('../assets/js/src/main.js');
    
    expect(window.YouTuneAI).toBeDefined();
    expect(window.YouTuneAI.config).toBeDefined();
    expect(window.YouTuneAI.utils).toBeDefined();
    expect(window.YouTuneAI.api).toBeDefined();
  });

  test('should have correct configuration', () => {
    require('../assets/js/src/main.js');
    
    const config = window.YouTuneAI.config;
    expect(config.apiUrl).toContain('admin-ajax.php');
    expect(config).toHaveProperty('nonce');
    expect(config).toHaveProperty('themeUrl');
  });

  test('utils should provide helper functions', () => {
    require('../assets/js/src/main.js');
    
    const utils = window.YouTuneAI.utils;
    
    expect(typeof utils.debounce).toBe('function');
    expect(typeof utils.throttle).toBe('function');
    expect(typeof utils.isInViewport).toBe('function');
    expect(typeof utils.formatBytes).toBe('function');
  });

  test('formatBytes should format correctly', () => {
    require('../assets/js/src/main.js');
    
    const formatBytes = window.YouTuneAI.utils.formatBytes;
    
    expect(formatBytes(0)).toBe('0 Bytes');
    expect(formatBytes(1024)).toBe('1 KB');
    expect(formatBytes(1024 * 1024)).toBe('1 MB');
    expect(formatBytes(1024 * 1024 * 1024)).toBe('1 GB');
  });

  test('debounce should delay function execution', (done) => {
    require('../assets/js/src/main.js');
    
    const debounce = window.YouTuneAI.utils.debounce;
    const mockFn = jest.fn();
    const debouncedFn = debounce(mockFn, 100);
    
    debouncedFn();
    debouncedFn();
    debouncedFn();
    
    expect(mockFn).not.toHaveBeenCalled();
    
    setTimeout(() => {
      expect(mockFn).toHaveBeenCalledTimes(1);
      done();
    }, 150);
  });

  test('throttle should limit function calls', (done) => {
    require('../assets/js/src/main.js');
    
    const throttle = window.YouTuneAI.utils.throttle;
    const mockFn = jest.fn();
    const throttledFn = throttle(mockFn, 100);
    
    throttledFn();
    throttledFn();
    throttledFn();
    
    expect(mockFn).toHaveBeenCalledTimes(1);
    
    setTimeout(() => {
      throttledFn();
      expect(mockFn).toHaveBeenCalledTimes(2);
      done();
    }, 150);
  });

  test('API methods should be available', () => {
    require('../assets/js/src/main.js');
    
    const api = window.YouTuneAI.api;
    
    expect(typeof api.call).toBe('function');
    expect(typeof api.getGames).toBe('function');
    expect(typeof api.getLiveStreams).toBe('function');
    expect(typeof api.getVRRooms).toBe('function');
  });

  test('API call should make AJAX request', async () => {
    require('../assets/js/src/main.js');
    
    const api = window.YouTuneAI.api;
    
    // Mock successful API response
    global.$ = jest.fn(() => ({
      ajax: jest.fn(() => Promise.resolve({ success: true, data: { test: 'data' } }))
    }));
    
    const result = await api.call('test-endpoint', { param: 'value' }, 'POST');
    expect(result).toBeDefined();
  });
});

describe('YouTuneAI Performance Tracking', () => {
  test('should track metrics', () => {
    require('../assets/js/src/main.js');
    
    const trackMetric = window.YouTuneAI.trackMetric;
    
    // Mock AJAX for metric tracking
    const mockAjax = jest.fn();
    global.$ = jest.fn(() => ({ ajax: mockAjax }));
    
    trackMetric('test_metric', 100);
    
    expect(mockAjax).toHaveBeenCalledWith({
      url: expect.stringContaining('admin-ajax.php'),
      type: 'POST',
      data: expect.objectContaining({
        action: 'youtuneai_track_metric',
        metric: 'test_metric',
        value: 100
      })
    });
  });
});

describe('YouTuneAI Modal System', () => {
  beforeEach(() => {
    document.body.innerHTML = `
      <button data-modal-open="test-modal">Open Modal</button>
      <div id="test-modal" class="modal">
        <button data-modal-close>Close</button>
        <div class="modal-overlay"></div>
        <input type="text" />
      </div>
    `;
  });

  test('should open modal', () => {
    require('../assets/js/src/main.js');
    
    const modal = document.getElementById('test-modal');
    window.YouTuneAI.openModal('test-modal');
    
    expect(modal.classList.contains('active')).toBe(true);
    expect(document.body.classList.contains('modal-open')).toBe(true);
  });

  test('should close modal', () => {
    require('../assets/js/src/main.js');
    
    const modal = document.getElementById('test-modal');
    modal.classList.add('active');
    document.body.classList.add('modal-open');
    
    window.YouTuneAI.closeModal();
    
    expect(modal.classList.contains('active')).toBe(false);
    expect(document.body.classList.contains('modal-open')).toBe(false);
  });
});

describe('YouTuneAI Accessibility', () => {
  test('should add skip to content link', () => {
    require('../assets/js/src/main.js');
    
    // Simulate initialization
    window.YouTuneAI.initAccessibility();
    
    const skipLink = document.querySelector('.skip-to-content');
    expect(skipLink).toBeTruthy();
    expect(skipLink.getAttribute('href')).toBe('#main');
  });

  test('should handle keyboard events', () => {
    document.body.innerHTML = '<button class="btn">Test Button</button>';
    
    require('../assets/js/src/main.js');
    
    const button = document.querySelector('.btn');
    const clickSpy = jest.spyOn(button, 'click');
    
    // Simulate Enter key press
    const enterEvent = new KeyboardEvent('keydown', { key: 'Enter' });
    button.dispatchEvent(enterEvent);
    
    expect(clickSpy).toHaveBeenCalled();
  });
});

describe('YouTuneAI Animation System', () => {
  test('should observe elements for animations', () => {
    document.body.innerHTML = '<div class="animate-fade-up"></div>';
    
    require('../assets/js/src/main.js');
    
    // Mock IntersectionObserver
    const mockObserve = jest.fn();
    global.IntersectionObserver = jest.fn(() => ({
      observe: mockObserve,
      unobserve: jest.fn(),
      disconnect: jest.fn(),
    }));
    
    window.YouTuneAI.initScrollEffects();
    
    expect(global.IntersectionObserver).toHaveBeenCalled();
  });

  test('should add floating animation to elements', () => {
    document.body.innerHTML = '<div class="floating-element"></div>';
    
    require('../assets/js/src/main.js');
    
    const mockAddClass = jest.fn();
    global.$ = jest.fn(() => ({
      each: jest.fn((callback) => callback.call({ addClass: mockAddClass }, 0)),
      addClass: mockAddClass,
    }));
    
    window.YouTuneAI.initAnimations();
    
    // Should process floating elements
    expect(global.$).toHaveBeenCalledWith('.floating-element');
  });
});