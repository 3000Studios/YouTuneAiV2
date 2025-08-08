/**
 * Jest setup file
 */

// Mock jQuery
global.$ = global.jQuery = {
  ready: jest.fn((callback) => callback()),
  on: jest.fn(),
  off: jest.fn(),
  find: jest.fn(() => ({
    addClass: jest.fn(),
    removeClass: jest.fn(),
    text: jest.fn(),
    html: jest.fn(),
    show: jest.fn(),
    hide: jest.fn(),
    fadeIn: jest.fn(),
    fadeOut: jest.fn(),
  })),
  addClass: jest.fn(),
  removeClass: jest.fn(),
  toggleClass: jest.fn(),
  animate: jest.fn(),
  fadeIn: jest.fn(),
  fadeOut: jest.fn(),
  ajax: jest.fn(() => Promise.resolve({ success: true })),
};

// Mock Three.js
global.THREE = {
  Scene: jest.fn(() => ({
    add: jest.fn(),
    remove: jest.fn(),
    clear: jest.fn(),
  })),
  PerspectiveCamera: jest.fn(() => ({
    position: { set: jest.fn() },
    aspect: 1,
    updateProjectionMatrix: jest.fn(),
  })),
  WebGLRenderer: jest.fn(() => ({
    setSize: jest.fn(),
    render: jest.fn(),
    dispose: jest.fn(),
    domElement: document.createElement('canvas'),
    xr: { enabled: true, setSession: jest.fn() },
    info: { render: { triangles: 1000 } },
  })),
  BoxGeometry: jest.fn(),
  MeshPhongMaterial: jest.fn(),
  Mesh: jest.fn(() => ({
    position: { set: jest.fn() },
    rotation: { x: 0, y: 0, z: 0 },
    castShadow: true,
    userData: {},
  })),
  Color: jest.fn(),
  AmbientLight: jest.fn(),
  DirectionalLight: jest.fn(() => ({
    position: { set: jest.fn() },
    castShadow: true,
    shadow: {
      camera: {},
      mapSize: { width: 2048, height: 2048 }
    },
  })),
  Clock: jest.fn(() => ({
    getDelta: jest.fn(() => 0.016),
  })),
  Group: jest.fn(() => ({
    add: jest.fn(),
    remove: jest.fn(),
    position: { set: jest.fn() },
  })),
  GLTFLoader: jest.fn(() => ({
    load: jest.fn((url, onLoad, onProgress, onError) => {
      setTimeout(() => {
        onLoad({
          scene: {
            scale: { setScalar: jest.fn() },
            position: { set: jest.fn() },
            traverse: jest.fn(),
          },
          animations: [],
        });
      }, 100);
    }),
  })),
  OrbitControls: jest.fn(() => ({
    target: { set: jest.fn() },
    maxPolarAngle: Math.PI,
    minDistance: 1,
    maxDistance: 100,
    enableDamping: true,
    dampingFactor: 0.05,
    autoRotate: false,
    autoRotateSpeed: 1,
    update: jest.fn(),
    dispose: jest.fn(),
  })),
};

// Mock Web APIs
global.navigator = {
  ...global.navigator,
  userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
  mediaDevices: {
    getUserMedia: jest.fn(() => Promise.resolve({
      getTracks: jest.fn(() => []),
    })),
  },
  xr: {
    isSessionSupported: jest.fn(() => Promise.resolve(true)),
    requestSession: jest.fn(() => Promise.resolve({
      addEventListener: jest.fn(),
      end: jest.fn(),
    })),
  },
  clipboard: {
    writeText: jest.fn(() => Promise.resolve()),
  },
  share: jest.fn(() => Promise.resolve()),
};

// Mock MediaRecorder
global.MediaRecorder = jest.fn(() => ({
  start: jest.fn(),
  stop: jest.fn(),
  ondataavailable: null,
  onstop: null,
}));

// Mock AudioContext
global.AudioContext = jest.fn(() => ({
  createAnalyser: jest.fn(),
  createMediaStreamSource: jest.fn(),
}));

// Mock SpeechSynthesis
global.speechSynthesis = {
  speak: jest.fn(),
  cancel: jest.fn(),
  speaking: false,
};

global.SpeechSynthesisUtterance = jest.fn(() => ({
  text: '',
  rate: 1,
  pitch: 1,
  volume: 1,
  onstart: null,
  onend: null,
}));

// Mock performance API
global.performance = {
  ...global.performance,
  now: jest.fn(() => Date.now()),
  timing: {
    navigationStart: Date.now() - 5000,
    loadEventEnd: Date.now(),
  },
};

// Mock localStorage
const localStorageMock = {
  getItem: jest.fn(),
  setItem: jest.fn(),
  removeItem: jest.fn(),
  clear: jest.fn(),
};
global.localStorage = localStorageMock;

// Mock fetch
global.fetch = jest.fn(() =>
  Promise.resolve({
    ok: true,
    json: () => Promise.resolve({ success: true }),
  })
);

// Mock canvas
HTMLCanvasElement.prototype.getContext = jest.fn(() => ({
  fillRect: jest.fn(),
  clearRect: jest.fn(),
  beginPath: jest.fn(),
  arc: jest.fn(),
  fill: jest.fn(),
  stroke: jest.fn(),
}));

HTMLCanvasElement.prototype.toDataURL = jest.fn(() => 'data:image/png;base64,test');

// Mock ResizeObserver
global.ResizeObserver = jest.fn(() => ({
  observe: jest.fn(),
  unobserve: jest.fn(),
  disconnect: jest.fn(),
}));

// Mock IntersectionObserver
global.IntersectionObserver = jest.fn(() => ({
  observe: jest.fn(),
  unobserve: jest.fn(),
  disconnect: jest.fn(),
}));

// Mock window.gtag for analytics
global.gtag = jest.fn();

// Mock console methods to reduce test noise
global.console = {
  ...global.console,
  log: jest.fn(),
  error: jest.fn(),
  warn: jest.fn(),
};