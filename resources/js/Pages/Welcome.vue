<template>
    <header>
      <nav class="navbar">
        <a class="logo" href="#">
          <img src="/img/loho.png" alt="Logo" class="logo-img">
        </a>
        
      </nav>
    </header>
    
    <!-- School Logo positioned under navbar -->
    <div class="school-logo-container">
      <img src="/img/snsu.png" alt="Surigao del Norte State University" class="school-logo">
    </div>
  
    <section class="hero-section">
      <div class="content boxed-content">
        <div style="text-align: center;">
          <h1>BOOK CLOUD</h1>
          <br>
          <h4>ENCHANCING LIBRARY ACCESS AND MANAGEMENT EFFICIENCY</h4>
        </div>
        <br>
      </div>
      <button @click="handleGetStarted" class="get-started-btn" :disabled="isAnimating">
        <span class="btn-text">{{ isAnimating ? 'Loading...' : 'GET STARTED' }}</span>
        <span class="btn-arrow">→</span>
        <div class="btn-glow"></div>
        <div class="btn-ripple" v-if="isAnimating"></div>
      </button>
      
      <!-- Book Animation Container -->
      <div class="book-animation-container" v-if="showAnimation">
        <div class="animated-book">
          <div class="book-cover">
            <div class="book-title">BOOK CLOUD</div>
          </div>
          <div class="book-spine"></div>
          <div class="book-pages">
            <div class="page page-1"></div>
            <div class="page page-2"></div>
            <div class="page page-3"></div>
            <div class="page page-4"></div>
            <div class="page page-5"></div>
          </div>
        </div>
        <div class="sparkles">
          <div class="sparkle" v-for="i in 8" :key="i" :style="{ animationDelay: i * 0.15 + 's' }"></div>
        </div>
        <div class="magic-trail"></div>
        <div class="page-particles">
          <div class="particle" v-for="i in 5" :key="'particle-' + i" :style="{ animationDelay: (i * 0.2 + 0.5) + 's' }"></div>
        </div>
      </div>
      
      <!-- Loading overlay -->
      <div class="loading-overlay" v-if="showAnimation">
        <div class="loading-text">Opening your digital library...</div>
      </div>
    </section>
  </template>
  
  <script>
  import { Link } from '@inertiajs/vue3'
  import { router } from '@inertiajs/vue3'

  export default {
    name: "Home",
    components: {
      Link
    },
    data() {
      return {
        showAnimation: false,
        isAnimating: false
      }
    },
    methods: {
      handleGetStarted() {
        if (this.isAnimating) return;
        
        this.isAnimating = true;
        this.showAnimation = true;
        
        // Add some visual feedback
        const button = event.target;
        button.style.transform = 'scale(0.95)';
        
        setTimeout(() => {
          button.style.transform = 'scale(1)';
        }, 150);
        
        // Start the animation sequence
        setTimeout(() => {
          // After animation completes, navigate to login with a smooth transition
          router.visit('/login', {
            onStart: () => {
              // Keep the overlay during navigation
            },
            onFinish: () => {
              this.showAnimation = false;
              this.isAnimating = false;
            }
          });
        }, 2200); // 2.2 second animation
      }
    }
  };
  </script>
  
  <style scoped>
  @import "/public/css/home.css";  /* Use @ to refer to the resources directory */
  
  /* Header and Logo Styles */
  header {
    position: relative;
    z-index: 10;
  }
  
  .school-logo-container {
    position: fixed;
    top: 80px;
    right: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    z-index: 100;
  }
  
  .school-logo {
    width: 200px;
    height: 200px;
    object-fit: contain;
    border-radius: 50%;
    box-shadow: 0 2px 8px rgba(255, 215, 0, 0.3);
    transition: transform 0.3s ease;
  }
  
  .school-logo:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(255, 215, 0, 0.5);
  }
  
  /* Responsive Design for SNSU Logo */
  /* Tablet and smaller laptops */
  @media (max-width: 1024px) {
    .school-logo-container {
      top: 70px;
      right: 15px;
    }
    
    .school-logo {
      width: 150px;
      height: 150px;
    }
  }
  
  /* Mobile phones and small tablets */
  @media (max-width: 768px) {
    .school-logo-container {
      top: 60px;
      right: 10px;
    }
    
    .school-logo {
      width: 100px;
      height: 100px;
    }
  }
  
  /* Small mobile phones */
  @media (max-width: 480px) {
    .school-logo-container {
      top: 55px;
      right: 8px;
    }
    
    .school-logo {
      width: 80px;
      height: 80px;
    }
  }
  
  /* Book Animation Styles */
  .book-animation-container {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 1000;
    pointer-events: none;
  }
  
  .animated-book {
    width: 60px;
    height: 80px;
    position: relative;
    animation: bookFly 2s ease-in-out forwards;
    transform-origin: center;
  }
  
  .book-cover {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #d4af37 0%, #ffd700 50%, #b8860b 100%);
    border-radius: 4px 8px 8px 4px;
    position: relative;
    box-shadow: 
      0 4px 20px rgba(212, 175, 55, 0.4),
      0 2px 10px rgba(0,0,0,0.3);
    animation: bookOpen 2s ease-in-out forwards;
  }
  
  .book-cover::before {
    content: '';
    position: absolute;
    top: 8%;
    left: 12%;
    right: 12%;
    bottom: 12%;
    background: linear-gradient(135deg, rgba(255,255,255,0.2), rgba(255,255,255,0.05));
    border-radius: 2px;
    border: 1px solid rgba(255,255,255,0.3);
  }
  
  .book-title {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 8px;
    font-weight: bold;
    color: #1a1a1a;
    text-shadow: 0 1px 2px rgba(255,255,255,0.3);
    text-align: center;
    line-height: 1.2;
  }
  
  .book-spine {
    position: absolute;
    top: 0;
    left: -4px;
    width: 4px;
    height: 100%;
    background: linear-gradient(180deg, #b8860b 0%, #8b6914 100%);
    border-radius: 2px 0 0 2px;
    box-shadow: inset 0 0 2px rgba(0,0,0,0.3);
  }
  
  .book-pages {
    position: absolute;
    top: 2px;
    right: 2px;
    bottom: 2px;
    left: 2px;
    background: #f9f7f4;
    border-radius: 2px 6px 6px 2px;
    overflow: hidden;
  }
  
  .page {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(45deg, #fafafa 0%, #f5f5f5 100%);
    border-radius: 2px 6px 6px 2px;
    transform-origin: left center;
    opacity: 0;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
  }
  
  .page::before {
    content: '';
    position: absolute;
    top: 10%;
    left: 10%;
    right: 10%;
    height: 1px;
    background: linear-gradient(90deg, transparent, #ddd, transparent);
  }
  
  .page::after {
    content: '';
    position: absolute;
    top: 20%;
    left: 10%;
    right: 30%;
    bottom: 60%;
    background: linear-gradient(45deg, #f0f0f0, #e8e8e8);
    border-radius: 1px;
  }
  
  .page-1 {
    animation: pageFlip1 2s ease-in-out forwards;
    animation-delay: 0.3s;
  }
  
  .page-2 {
    animation: pageFlip2 2s ease-in-out forwards;
    animation-delay: 0.5s;
  }
  
  .page-3 {
    animation: pageFlip3 2s ease-in-out forwards;
    animation-delay: 0.7s;
  }
  
  .page-4 {
    animation: pageFlip4 2s ease-in-out forwards;
    animation-delay: 0.9s;
  }
  
  .page-5 {
    animation: pageFlip5 2s ease-in-out forwards;
    animation-delay: 1.1s;
  }
  
  .sparkles {
    position: absolute;
    top: -20px;
    left: -20px;
    right: -20px;
    bottom: -20px;
  }
  
  .sparkle {
    position: absolute;
    width: 4px;
    height: 4px;
    background: linear-gradient(45deg, #ffd700, #ffed4a);
    border-radius: 50%;
    animation: sparkleAnimation 1.5s ease-in-out infinite;
    box-shadow: 0 0 8px rgba(255, 215, 0, 0.9);
  }
  
  .sparkle:nth-child(1) { top: 0%; left: 20%; }
  .sparkle:nth-child(2) { top: 20%; right: 0%; }
  .sparkle:nth-child(3) { bottom: 0%; left: 30%; }
  .sparkle:nth-child(4) { bottom: 20%; right: 20%; }
  .sparkle:nth-child(5) { top: 50%; left: 0%; }
  .sparkle:nth-child(6) { top: 30%; right: 30%; }
  .sparkle:nth-child(7) { top: 10%; left: 50%; }
  .sparkle:nth-child(8) { bottom: 10%; right: 10%; }
  
  .magic-trail {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 100px;
    height: 2px;
    background: linear-gradient(90deg, transparent, #ffd700, #ffed4a, #d4af37, transparent);
    transform: translate(-50%, -50%);
    animation: trailAnimation 2s ease-in-out forwards;
    opacity: 0;
    box-shadow: 0 0 12px rgba(255, 215, 0, 0.8);
  }
  
  .page-particles {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
  }
  
  .particle {
    position: absolute;
    width: 3px;
    height: 8px;
    background: linear-gradient(45deg, #ffd700, #ffed4a);
    border-radius: 50%;
    opacity: 0;
    animation: particleFloat 1.5s ease-out forwards;
    box-shadow: 0 0 4px rgba(255, 215, 0, 0.6);
  }
  
  .particle:nth-child(1) { top: 20%; left: 40%; }
  .particle:nth-child(2) { top: 60%; left: 20%; }
  .particle:nth-child(3) { top: 30%; right: 30%; }
  .particle:nth-child(4) { bottom: 40%; left: 60%; }
  .particle:nth-child(5) { top: 70%; right: 40%; }
  
  .loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 999;
    animation: fadeIn 0.3s ease-in-out forwards;
  }
  
  .loading-text {
    color: white;
    font-size: 24px;
    font-weight: 600;
    text-align: center;
    margin-top: 100px;
    animation: textPulse 1.5s ease-in-out infinite;
    text-shadow: 0 2px 10px rgba(16, 185, 129, 0.5);
    position: relative;
  }
  
  .loading-text::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 3px;
    background: linear-gradient(90deg, transparent, #10b981, transparent);
    border-radius: 2px;
    animation: loadingBar 1.5s ease-in-out infinite;
  }
  
  /* Enhanced Button Styling */
  .get-started-btn {
    position: relative;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 18px 36px;
    background: linear-gradient(135deg, #10b981 0%, #059669 50%, #047857 100%);
    color: white;
    border: none;
    border-radius: 50px;
    font-size: 18px;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 
      0 8px 25px rgba(16, 185, 129, 0.4),
      0 4px 12px rgba(0, 0, 0, 0.15),
      inset 0 1px 0 rgba(255, 255, 255, 0.2);
    overflow: hidden;
    min-width: 220px;
    z-index: 1;
  }
  
  .get-started-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, #34d399 0%, #10b981 50%, #059669 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
    border-radius: 50px;
    z-index: -1;
  }
  
  .get-started-btn:hover::before {
    opacity: 1;
  }
  
  .get-started-btn:hover {
    transform: translateY(-2px) scale(1.05);
    box-shadow: 
      0 12px 35px rgba(16, 185, 129, 0.5),
      0 6px 20px rgba(0, 0, 0, 0.2),
      inset 0 1px 0 rgba(255, 255, 255, 0.3);
  }
  
  .get-started-btn:active {
    transform: translateY(-1px) scale(1.02);
    box-shadow: 
      0 6px 20px rgba(16, 185, 129, 0.4),
      0 3px 10px rgba(0, 0, 0, 0.15);
  }
  
  .btn-icon {
    font-size: 20px;
    animation: iconBounce 2s infinite ease-in-out;
    transition: transform 0.3s ease;
  }
  
  .get-started-btn:hover .btn-icon {
    transform: scale(1.2) rotate(5deg);
    animation-duration: 0.8s;
  }
  
  .btn-text {
    position: relative;
    z-index: 2;
    transition: all 0.3s ease;
  }
  
  .btn-arrow {
    font-size: 18px;
    font-weight: bold;
    transition: transform 0.3s ease;
    opacity: 0.8;
  }
  
  .get-started-btn:hover .btn-arrow {
    transform: translateX(4px);
    opacity: 1;
  }
  
  .btn-glow {
    position: absolute;
    top: -2px;
    left: -2px;
    right: -2px;
    bottom: -2px;
    background: linear-gradient(45deg, #10b981, #34d399, #6ee7b7, #34d399, #10b981);
    background-size: 300% 300%;
    border-radius: 52px;
    opacity: 0;
    z-index: -2;
    animation: glowRotate 3s linear infinite;
    transition: opacity 0.3s ease;
  }
  
  .get-started-btn:hover .btn-glow {
    opacity: 0.7;
  }
  
  .btn-ripple {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transform: translate(-50%, -50%);
    animation: rippleEffect 1s ease-out infinite;
  }
  
  /* Button disabled state enhancement */
  .get-started-btn:disabled {
    opacity: 0.8;
    cursor: not-allowed;
    background: linear-gradient(135deg, #6b7280 0%, #4b5563 50%, #374151 100%);
    box-shadow: 
      0 4px 15px rgba(107, 114, 128, 0.3),
      0 2px 8px rgba(0, 0, 0, 0.1);
    transform: none;
  }
  
  .get-started-btn:disabled:hover {
    transform: none;
    box-shadow: 
      0 4px 15px rgba(107, 114, 128, 0.3),
      0 2px 8px rgba(0, 0, 0, 0.1);
  }
  
  .get-started-btn:disabled::before {
    opacity: 0;
  }
  
  .get-started-btn:disabled .btn-glow {
    opacity: 0;
  }
  
  @keyframes bookFly {
    0% {
      transform: translate(-50%, -50%) scale(0.5) rotate(0deg);
      opacity: 0;
    }
    10% {
      transform: translate(-50%, -50%) scale(1) rotate(5deg);
      opacity: 1;
    }
    30% {
      transform: translate(-50%, -50%) scale(1.1) rotate(-3deg) translateY(-10px);
      opacity: 1;
    }
    50% {
      transform: translate(-50%, -50%) scale(1.2) rotate(2deg) translateY(-20px);
      opacity: 1;
    }
    70% {
      transform: translate(-50%, -50%) scale(1.1) rotate(-1deg) translateY(-10px) translateX(20px);
      opacity: 1;
    }
    90% {
      transform: translate(-50%, -50%) scale(1.4) rotate(0deg) translateX(80px) translateY(-40px);
      opacity: 0.7;
    }
    100% {
      transform: translate(-50%, -50%) scale(2) rotate(0deg) translateX(150px) translateY(-80px);
      opacity: 0;
    }
  }
  
  @keyframes bookOpen {
    0% {
      transform: rotateY(0deg);
      box-shadow: 0 4px 20px rgba(0,0,0,0.3);
    }
    25% {
      transform: rotateY(-15deg);
      box-shadow: 0 8px 30px rgba(0,0,0,0.4);
    }
    50% {
      transform: rotateY(-8deg);
      box-shadow: 0 12px 40px rgba(0,0,0,0.5);
    }
    75% {
      transform: rotateY(-3deg);
      box-shadow: 0 8px 30px rgba(0,0,0,0.4);
    }
    100% {
      transform: rotateY(0deg);
      box-shadow: 0 4px 20px rgba(0,0,0,0.3);
    }
  }
  
  @keyframes trailAnimation {
    0% {
      transform: translate(-50%, -50%) scaleX(0);
      opacity: 0;
    }
    30% {
      transform: translate(-50%, -50%) scaleX(1);
      opacity: 0.8;
    }
    70% {
      transform: translate(-50%, -50%) scaleX(2) translateX(30px);
      opacity: 0.6;
    }
    100% {
      transform: translate(-50%, -50%) scaleX(3) translateX(70px);
      opacity: 0;
    }
  }
  
  @keyframes particleFloat {
    0% {
      opacity: 0;
      transform: translateY(0px) rotate(0deg);
    }
    30% {
      opacity: 1;
      transform: translateY(-10px) rotate(90deg);
    }
    70% {
      opacity: 0.7;
      transform: translateY(-25px) rotate(180deg);
    }
    100% {
      opacity: 0;
      transform: translateY(-50px) rotate(360deg);
    }
  }
  
  @keyframes bookIcon {
    0%, 100% {
      transform: translate(-50%, -50%) scale(1);
    }
    50% {
      transform: translate(-50%, -50%) scale(1.2);
    }
  }
  
  @keyframes pageFlip1 {
    0% { transform: rotateY(0deg); z-index: 5; }
    50% { transform: rotateY(-90deg); z-index: 5; }
    100% { transform: rotateY(-180deg); z-index: 1; }
  }

  @keyframes pageFlip2 {
    0% { transform: rotateY(0deg); z-index: 4; }
    50% { transform: rotateY(-90deg); z-index: 4; }
    100% { transform: rotateY(-180deg); z-index: 1; }
  }

  @keyframes pageFlip3 {
    0% { transform: rotateY(0deg); z-index: 3; }
    50% { transform: rotateY(-90deg); z-index: 3; }
    100% { transform: rotateY(-180deg); z-index: 1; }
  }

  @keyframes pageFlip4 {
    0% { transform: rotateY(0deg); z-index: 2; }
    50% { transform: rotateY(-90deg); z-index: 2; }
    100% { transform: rotateY(-180deg); z-index: 1; }
  }

  @keyframes pageFlip5 {
    0% { transform: rotateY(0deg); z-index: 1; }
    50% { transform: rotateY(-90deg); z-index: 1; }
    100% { transform: rotateY(-180deg); z-index: 0; }
  }
  
  @keyframes sparkleAnimation {
    0%, 100% {
      transform: scale(0) rotate(0deg);
      opacity: 0;
    }
    50% {
      transform: scale(1) rotate(180deg);
      opacity: 1;
    }
  }
  
  @keyframes fadeIn {
    from {
      opacity: 0;
    }
    to {
      opacity: 1;
    }
  }
  
  @keyframes textPulse {
    0%, 100% {
      opacity: 0.8;
      transform: scale(1);
    }
    50% {
      opacity: 1;
      transform: scale(1.05);
    }
  }
  
  @keyframes iconBounce {
    0%, 20%, 50%, 80%, 100% {
      transform: translateY(0) rotate(0deg);
    }
    10% {
      transform: translateY(-3px) rotate(2deg);
    }
    30% {
      transform: translateY(-2px) rotate(-1deg);
    }
    60% {
      transform: translateY(-1px) rotate(1deg);
    }
  }
  
  @keyframes glowRotate {
    0% {
      background-position: 0% 50%;
    }
    50% {
      background-position: 100% 50%;
    }
    100% {
      background-position: 0% 50%;
    }
  }
  
  @keyframes rippleEffect {
    0% {
      width: 0;
      height: 0;
      opacity: 1;
    }
    100% {
      width: 100px;
      height: 100px;
      opacity: 0;
    }
  }
  
  @keyframes loadingBar {
    0%, 100% {
      width: 20px;
      opacity: 0.5;
    }
    50% {
      width: 80px;
      opacity: 1;
    }
  }
  </style>
  