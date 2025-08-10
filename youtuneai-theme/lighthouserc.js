module.exports = {
  ci: {
    collect: {
      url: [
        'http://localhost:8080',
        'http://localhost:8080/games',
        'http://localhost:8080/streams',
        'http://localhost:8080/vr-room',
        'http://localhost:8080/garage',
        'http://localhost:8080/shop'
      ],
      startServerCommand: 'npm run serve',
      numberOfRuns: 3,
    },
    assert: {
      assertions: {
        'categories:performance': ['warn', {minScore: 0.9}],
        'categories:accessibility': ['error', {minScore: 0.9}],
        'categories:best-practices': ['warn', {minScore: 0.85}],
        'categories:seo': ['error', {minScore: 0.9}],
        'categories:pwa': 'off',
        
        // Performance budgets
        'resource-summary:script:size': ['warn', {maxNumericValue: 200000}], // 200KB
        'resource-summary:stylesheet:size': ['warn', {maxNumericValue: 50000}], // 50KB
        'resource-summary:image:size': ['warn', {maxNumericValue: 1000000}], // 1MB
        'resource-summary:total:size': ['warn', {maxNumericValue: 2000000}], // 2MB
        
        // Core Web Vitals
        'largest-contentful-paint': ['warn', {maxNumericValue: 2500}],
        'first-contentful-paint': ['warn', {maxNumericValue: 1800}],
        'cumulative-layout-shift': ['warn', {maxNumericValue: 0.1}],
        'total-blocking-time': ['warn', {maxNumericValue: 200}],
        
        // 3D/VR specific metrics
        'bootup-time': ['warn', {maxNumericValue: 4000}],
        'mainthread-work-breakdown': ['warn', {maxNumericValue: 4000}],
        'max-potential-fid': ['warn', {maxNumericValue: 100}],
        
        // Accessibility
        'color-contrast': 'error',
        'focus-traps': 'error',
        'focusable-controls': 'error',
        'interactive-element-affordance': 'error',
        'logical-tab-order': 'error',
        'managed-focus': 'error',
        
        // SEO
        'document-title': 'error',
        'meta-description': 'error',
        'http-status-code': 'error',
        'crawlable-anchors': 'error',
      }
    },
    upload: {
      target: 'temporary-public-storage',
    },
  },
};