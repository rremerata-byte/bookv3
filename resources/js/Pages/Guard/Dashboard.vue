<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
    <!-- Background effects -->
    <div class="absolute inset-0 overflow-hidden">
      <div class="absolute -top-4 -right-4 w-32 h-32 bg-purple-500/20 rounded-full blur-3xl animate-pulse"></div>
      <div class="absolute top-1/3 -left-8 w-48 h-48 bg-blue-500/10 rounded-full blur-3xl animate-pulse delay-1000"></div>
      <div class="absolute bottom-1/4 xright-1/3 w-40 h-40 bg-cyan-500/15 rounded-full blur-3xl animate-pulse delay-2000"></div>
    </div>

    <!-- Header -->
    <header class="relative z-10 bg-black/20 backdrop-blur-lg border-b border-white/10">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 lg:py-4">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-3 lg:space-x-4">
            <div class="relative">
              <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-r from-purple-500 to-cyan-500 rounded-lg lg:rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-shield-alt text-white text-lg lg:text-xl"></i>
              </div>
              <div class="absolute -top-1 -right-1 w-3 h-3 lg:w-4 lg:h-4 bg-green-500 rounded-full border-2 border-slate-900 animate-pulse"></div>
            </div>
            <div>
              <h1 class="text-lg sm:text-xl lg:text-2xl font-bold bg-gradient-to-r from-white to-purple-200 bg-clip-text text-transparent">
                Security Guard Dashboard
              </h1>
              <p class="text-purple-200/80 text-xs sm:text-sm hidden sm:block">Library Exit Scanner System</p>
            </div>
          </div>
          
          <!-- User info and logout -->
          <div class="flex items-center space-x-2 lg:space-x-4">
            <div class="hidden md:block text-right">
              <p class="text-white font-medium text-sm lg:text-base">{{ $page.props.auth?.user?.fullname || 'Guard User' }}</p>
              <p class="text-purple-200/60 text-xs lg:text-sm">On Duty</p>
            </div>
            <button 
              @click="logout"
              class="px-3 lg:px-4 py-2 bg-red-500/20 hover:bg-red-500/30 border border-red-500/50 rounded-lg text-red-200 transition-all duration-300 hover:shadow-lg hover:shadow-red-500/25 text-sm lg:text-base"
            >
              <i class="fas fa-sign-out-alt mr-1 lg:mr-2"></i>
              <span class="hidden sm:inline">Logout</span>
            </button>
          </div>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">
      
      <!-- Stats Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6 mb-6 lg:mb-8">
        <!-- Scanned Books -->
        <div class="bg-black/30 backdrop-blur-lg rounded-xl lg:rounded-2xl border border-white/10 p-4 lg:p-6 hover:border-purple-500/50 transition-all duration-300">
          <div class="flex items-center justify-between mb-3 lg:mb-4">
            <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-lg lg:rounded-xl flex items-center justify-center">
              <i class="fas fa-qrcode text-white text-sm lg:text-base"></i>
            </div>
            <span class="text-green-400 text-xs lg:text-sm font-medium">Today</span>
          </div>
          <h3 class="text-xl lg:text-2xl font-bold text-white mb-1">{{ scannedToday }}</h3>
          <p class="text-purple-200/70 text-sm lg:text-base">Books Scanned</p>
        </div>

        <!-- Approved Exits -->
        <div class="bg-black/30 backdrop-blur-lg rounded-xl lg:rounded-2xl border border-white/10 p-4 lg:p-6 hover:border-cyan-500/50 transition-all duration-300">
          <div class="flex items-center justify-between mb-3 lg:mb-4">
            <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-lg lg:rounded-xl flex items-center justify-center">
              <i class="fas fa-check-circle text-white text-sm lg:text-base"></i>
            </div>
            <span class="text-blue-400 text-xs lg:text-sm font-medium">Approved</span>
          </div>
          <h3 class="text-xl lg:text-2xl font-bold text-white mb-1">{{ approvedToday }}</h3>
          <p class="text-purple-200/70 text-sm lg:text-base">Books Approved</p>
        </div>

        <!-- Blocked Attempts -->
        <div class="bg-black/30 backdrop-blur-lg rounded-xl lg:rounded-2xl border border-white/10 p-4 lg:p-6 hover:border-red-500/50 transition-all duration-300">
          <div class="flex items-center justify-between mb-3 lg:mb-4">
            <div class="w-10 h-10 lg:w-12 lg:h-12 bg-gradient-to-r from-red-500 to-pink-500 rounded-lg lg:rounded-xl flex items-center justify-center">
              <i class="fas fa-times-circle text-white text-sm lg:text-base"></i>
            </div>
            <span class="text-red-400 text-xs lg:text-sm font-medium">Blocked</span>
          </div>
          <h3 class="text-xl lg:text-2xl font-bold text-white mb-1">{{ blockedToday }}</h3>
          <p class="text-purple-200/70 text-sm lg:text-base">Books Blocked</p>
        </div>
      </div>

      <!-- Scanner Section -->
      <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 lg:gap-8">
        
        <!-- QR Scanner -->
        <div class="bg-black/30 backdrop-blur-lg rounded-xl lg:rounded-2xl border border-white/10 p-6 lg:p-8 hover:border-purple-500/50 transition-all duration-300">
          <div class="text-center mb-4 lg:mb-6">
            <div class="w-16 h-16 lg:w-20 lg:h-20 bg-gradient-to-r from-purple-500 to-cyan-500 rounded-xl lg:rounded-2xl flex items-center justify-center mx-auto mb-3 lg:mb-4 shadow-lg shadow-purple-500/25">
              <i class="fas fa-camera text-white text-xl lg:text-2xl"></i>
            </div>
            <h2 class="text-xl lg:text-2xl font-bold text-white mb-1 lg:mb-2">QR Code Scanner</h2>
            <p class="text-purple-200/70 text-sm lg:text-base">Scan book QR codes to verify exit permission</p>
          </div>

          <!-- Scanner Interface -->
          <div class="space-y-3 lg:space-y-4">
            <!-- Scanner Button -->
            <button 
              @click="toggleScanner"
              :class="[
                'w-full py-3 lg:py-4 px-4 lg:px-6 rounded-xl font-semibold text-white transition-all duration-300 transform hover:scale-105 text-sm lg:text-base',
                scannerActive 
                  ? 'bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 shadow-lg shadow-red-500/25' 
                  : 'bg-gradient-to-r from-purple-500 to-cyan-500 hover:from-purple-600 hover:to-cyan-600 shadow-lg shadow-purple-500/25'
              ]"
            >
              <i :class="scannerActive ? 'fas fa-stop mr-2 lg:mr-3' : 'fas fa-camera mr-2 lg:mr-3'"></i>
              {{ scannerActive ? 'Stop Scanner' : 'Start Scanner' }}
            </button>

            <!-- Manual Book ID Input -->
            <div class="relative">
              <input 
                v-model="manualBookId"
                @keyup.enter="scanManualBookId"
                type="text" 
                placeholder="Enter Book ID manually..."
                class="w-full px-3 lg:px-4 py-2 lg:py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-purple-200/50 focus:outline-none focus:border-purple-500 focus:bg-white/15 transition-all duration-300 text-sm lg:text-base pr-16 lg:pr-20"
              />
              <button 
                @click="scanManualBookId"
                :disabled="!manualBookId.trim()"
                class="absolute right-2 top-1/2 transform -translate-y-1/2 px-3 lg:px-4 py-1 lg:py-1.5 bg-gradient-to-r from-purple-500 to-cyan-500 rounded-lg text-white text-xs lg:text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed hover:shadow-lg transition-all duration-300"
              >
                Scan
              </button>
            </div>

            <!-- Image Upload Scanner -->
            <div class="relative">
              <input 
                ref="imageUpload"
                @change="handleImageUpload"
                type="file" 
                accept="image/*"
                class="hidden"
              />
              <button 
                @click="$refs.imageUpload.click()"
                class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white hover:bg-white/15 focus:outline-none focus:border-cyan-500 transition-all duration-300 flex items-center justify-center"
              >
                <i class="fas fa-image mr-3 text-cyan-400"></i>
                <span>Upload QR Code Image</span>
              </button>
            </div>

            <!-- Image Preview (when image is uploaded) -->
            <div 
              v-if="uploadedImage"
              class="relative w-full max-h-64 bg-black/50 rounded-xl border-2 border-cyan-500/50 overflow-hidden"
            >
              <img 
                :src="uploadedImage.preview"
                :alt="uploadedImage.name"
                class="w-full h-full object-contain"
              />
              <div class="absolute top-2 right-2 flex space-x-2">
                <button 
                  @click="processUploadedImage"
                  class="px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white text-sm rounded-lg transition-all duration-300"
                >
                  <i class="fas fa-search mr-1"></i>
                  Process QR
                </button>
                <button 
                  @click="clearUploadedImage"
                  class="px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-sm rounded-lg transition-all duration-300"
                >
                  <i class="fas fa-times"></i>
                </button>
              </div>
              
              <!-- Processing overlay -->
              <div v-if="processingImage" class="absolute inset-0 bg-black/70 flex items-center justify-center">
                <div class="text-center">
                  <div class="w-8 h-8 border-4 border-cyan-500 border-t-transparent rounded-full animate-spin mb-2"></div>
                  <p class="text-cyan-200 text-sm">Processing QR Code...</p>
                </div>
              </div>
            </div>

            <!-- Camera Preview -->
            <div 
              v-show="scannerActive && !uploadedImage"
              class="relative w-full h-64 bg-black/50 rounded-xl border-2 border-dashed border-purple-500/50 flex items-center justify-center overflow-hidden"
            >
              <div v-if="!cameraReady" class="text-center">
                <div class="w-12 h-12 border-4 border-purple-500 border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
                <p class="text-purple-200">Initializing camera...</p>
              </div>
              
              <!-- Scanning Animation -->
              <div v-if="scanning" class="absolute inset-0 flex items-center justify-center z-10">
                <div class="w-32 h-1 bg-gradient-to-r from-transparent via-cyan-400 to-transparent animate-pulse"></div>
              </div>
              
              <!-- Scanner Overlay -->
              <div class="absolute inset-4 border-2 border-cyan-400 rounded-lg">
                <div class="absolute top-0 left-0 w-6 h-6 border-t-2 border-l-2 border-cyan-400"></div>
                <div class="absolute top-0 right-0 w-6 h-6 border-t-2 border-r-2 border-cyan-400"></div>
                <div class="absolute bottom-0 left-0 w-6 h-6 border-b-2 border-l-2 border-cyan-400"></div>
                <div class="absolute bottom-0 right-0 w-6 h-6 border-b-2 border-r-2 border-cyan-400"></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Scan Results -->
        <div class="bg-black/30 backdrop-blur-lg rounded-2xl border border-white/10 p-8">
          <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
            <i class="fas fa-list-alt mr-3 text-purple-400"></i>
            Scan Results
          </h2>

          <!-- Latest Scan Result -->
          <div v-if="lastScanResult" class="mb-6">
            <div 
              :class="[
                'p-6 rounded-xl border-2 transition-all duration-500 transform',
                lastScanResult.approved 
                  ? 'bg-green-500/20 border-green-500/50 shadow-lg shadow-green-500/25' 
                  : 'bg-red-500/20 border-red-500/50 shadow-lg shadow-red-500/25'
              ]"
            >
              <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                  <i 
                    :class="[
                      'text-2xl mr-3',
                      lastScanResult.approved ? 'fas fa-check-circle text-green-400' : 'fas fa-times-circle text-red-400'
                    ]"
                  ></i>
                  <div>
                    <h3 class="text-lg font-bold text-white">
                      {{ lastScanResult.approved ? 'APPROVED' : 'NOT APPROVED' }}
                    </h3>
                    <p class="text-sm opacity-80" :class="lastScanResult.approved ? 'text-green-200' : 'text-red-200'">
                      {{ lastScanResult.message }}
                    </p>
                  </div>
                </div>
                <span class="text-xs opacity-60" :class="lastScanResult.approved ? 'text-green-200' : 'text-red-200'">
                  Just now
                </span>
              </div>
              
              <div v-if="lastScanResult.book" class="bg-black/30 rounded-lg p-4">
                <h4 class="text-white font-medium mb-2">{{ lastScanResult.book.title }}</h4>
                <div class="grid grid-cols-2 gap-4 text-sm">
                  <div>
                    <span class="text-purple-200/70">Author:</span>
                    <span class="text-white ml-2">{{ lastScanResult.book.author }}</span>
                  </div>
                  <div>
                    <span class="text-purple-200/70">Status:</span>
                    <span 
                      class="ml-2 px-2 py-1 rounded text-xs font-medium"
                      :class="getStatusBadgeClass(lastScanResult.book.availability)"
                    >
                      {{ lastScanResult.book.availability }}
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Scan History -->
          <div v-if="scanHistory.length > 0" class="space-y-3 max-h-96 overflow-y-auto">
            <h3 class="text-lg font-semibold text-white mb-3">Recent Scans</h3>
            <div 
              v-for="(scan, index) in scanHistory" 
              :key="index"
              :class="[
                'p-4 rounded-lg border transition-all duration-300 hover:shadow-lg',
                scan.approved 
                  ? 'bg-green-500/10 border-green-500/30 hover:shadow-green-500/10' 
                  : 'bg-red-500/10 border-red-500/30 hover:shadow-red-500/10'
              ]"
            >
              <div class="flex items-center justify-between">
                <div class="flex items-center">
                  <i 
                    :class="[
                      'text-sm mr-2',
                      scan.approved ? 'fas fa-check text-green-400' : 'fas fa-times text-red-400'
                    ]"
                  ></i>
                  <div>
                    <span class="text-white text-sm font-medium">{{ scan.book?.title || 'Unknown Book' }}</span>
                    <span class="text-xs ml-2 opacity-60" :class="scan.approved ? 'text-green-200' : 'text-red-200'">
                      {{ formatTime(scan.timestamp) }}
                    </span>
                  </div>
                </div>
                <span 
                  class="px-2 py-1 rounded text-xs"
                  :class="scan.approved ? 'bg-green-500/20 text-green-200' : 'bg-red-500/20 text-red-200'"
                >
                  {{ scan.approved ? 'Allowed' : 'Blocked' }}
                </span>
              </div>
            </div>
          </div>

          <!-- No scans yet -->
          <div v-else class="text-center py-12">
            <i class="fas fa-qrcode text-6xl text-purple-500/30 mb-4"></i>
            <p class="text-purple-200/70">No scans yet today</p>
            <p class="text-purple-200/50 text-sm">Start scanning books to see results here</p>
          </div>
        </div>
      </div>
    </main>

    <!-- Sound Effects -->
    <audio ref="approvedSound" preload="none">
      <!-- Sound files disabled to prevent 404 errors -->
    </audio>
    <audio ref="deniedSound" preload="none">
      <!-- Sound files disabled to prevent 404 errors -->
    </audio>
  </div>
</template>

<script>
export default {
  name: 'GuardDashboard',
  props: {
    flash: {
      type: Object,
      default: () => ({})
    },
    scanResult: {
      type: Object,
      default: null
    }
  },
  data() {
    return {
      scannerActive: false,
      cameraReady: false,
      scanning: false,
      manualBookId: '',
      lastScanResult: null,
      scanHistory: [],
      scannedToday: 0,
      approvedToday: 0,
      blockedToday: 0,
      uploadedImage: null,
      processingImage: false,
    }
  },
  methods: {
    toggleScanner() {
      if (this.scannerActive) {
        this.stopScanner();
      } else {
        this.startScanner();
      }
    },
    
    async startScanner() {
      this.scannerActive = true;
      this.cameraReady = false;
      
      // Simulate camera initialization
      setTimeout(() => {
        this.cameraReady = true;
      }, 1500);
      
      // Here you would integrate with a real QR scanner library
      // For now, we'll simulate it
    },
    
    stopScanner() {
      this.scannerActive = false;
      this.cameraReady = false;
      this.scanning = false;
    },
    
    async scanManualBookId() {
      if (!this.manualBookId.trim()) return;
      
      this.scanning = true;
      
      try {
        const response = await fetch(`/guard/scan/${this.manualBookId}`, {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': this.$page.props.csrf_token || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
          }
        });
        
        const result = await response.json();
        this.handleScanResult(result);
      } catch (error) {
        console.error('Scan error:', error);
        this.handleScanResult({
          status: 'error',
          approved: false,
          message: 'Error scanning book',
          book: null
        });
      }
      
      this.manualBookId = '';
      this.scanning = false;
    },
    
    handleScanResult(result) {
      // Add timestamp
      result.timestamp = new Date();
      
      // Update latest result
      this.lastScanResult = result;
      
      // Add to history (keep only last 10)
      this.scanHistory.unshift({...result});
      if (this.scanHistory.length > 10) {
        this.scanHistory.pop();
      }
      
      // Update stats
      this.scannedToday++;
      if (result.approved) {
        this.approvedToday++;
        this.playApprovedSound();
      } else {
        this.blockedToday++;
        this.playDeniedSound();
      }
      
      // Store in localStorage for persistence
      this.saveScanData();
    },
    
    getStatusBadgeClass(status) {
      switch (status) {
        case 'Borrowed':
          return 'bg-blue-500/20 text-blue-200';
        case 'Available':
          return 'bg-green-500/20 text-green-200';
        case 'Reserved':
          return 'bg-yellow-500/20 text-yellow-200';
        case 'Pending':
          return 'bg-orange-500/20 text-orange-200';
        default:
          return 'bg-gray-500/20 text-gray-200';
      }
    },
    
    formatTime(timestamp) {
      return new Date(timestamp).toLocaleTimeString('en-US', {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
      });
    },
    
    playApprovedSound() {
      // Sound disabled to prevent errors
      // Implement later with actual sound files
    },
    
    playDeniedSound() {
      // Sound disabled to prevent errors
      // Implement later with actual sound files
    },
    
    saveScanData() {
      const data = {
        scannedToday: this.scannedToday,
        approvedToday: this.approvedToday,
        blockedToday: this.blockedToday,
        scanHistory: this.scanHistory,
        date: new Date().toDateString()
      };
      localStorage.setItem('guardScanData', JSON.stringify(data));
    },
    
    loadScanData() {
      const stored = localStorage.getItem('guardScanData');
      if (stored) {
        const data = JSON.parse(stored);
        // Only load if it's from today
        if (data.date === new Date().toDateString()) {
          this.scannedToday = data.scannedToday || 0;
          this.approvedToday = data.approvedToday || 0;
          this.blockedToday = data.blockedToday || 0;
          this.scanHistory = data.scanHistory || [];
        }
      }
    },
    
    logout() {
      this.$inertia.post(route('logout'));
    },

    handleImageUpload(event) {
      const file = event.target.files[0];
      if (!file) return;

      // Validate file type
      if (!file.type.startsWith('image/')) {
        alert('Please upload a valid image file');
        return;
      }

      // Validate file size (max 10MB)
      if (file.size > 10 * 1024 * 1024) {
        alert('Image file size should be less than 10MB');
        return;
      }

      // Create preview URL
      const reader = new FileReader();
      reader.onload = (e) => {
        this.uploadedImage = {
          file: file,
          preview: e.target.result,
          name: file.name
        };
      };
      reader.readAsDataURL(file);

      // Clear the input for potential re-upload
      event.target.value = '';
    },

    async processUploadedImage() {
      if (!this.uploadedImage) return;

      this.processingImage = true;

      try {
        // Create FormData for file upload
        const formData = new FormData();
        formData.append('qr_image', this.uploadedImage.file);

        // Use Inertia's router for automatic CSRF handling
        this.$inertia.post('/guard/scan-image', formData, {
          preserveState: true,
          preserveScroll: true,
          onSuccess: (page) => {
            // Check for the scan result in the page response
            if (page.props.flash && page.props.flash.scanResult) {
              this.handleScanResult(page.props.flash.scanResult);
              this.clearUploadedImage();
            } else if (page.props.scanResult) {
              this.handleScanResult(page.props.scanResult);
              this.clearUploadedImage();
            }
            this.processingImage = false;
          },
          onError: (errors) => {
            console.error('Upload errors:', errors);
            this.handleScanResult({
              status: 'error',
              approved: false,
              message: `Failed to process QR code: ${Object.values(errors)[0] || 'Upload failed'}`,
              book: null
            });
            this.processingImage = false;
          }
        });

      } catch (error) {
        console.error('Error processing image:', error);
        this.handleScanResult({
          status: 'error',
          approved: false,
          message: `Failed to process QR code: ${error.message}`,
          book: null
        });
        this.processingImage = false;
      }
    },

    clearUploadedImage() {
      this.uploadedImage = null;
    },
  },
  
  mounted() {
    this.loadScanData();
    
    // Check for flash messages (scan results from image upload)
    if (this.flash && this.flash.scanResult) {
      this.handleScanResult(this.flash.scanResult);
    }
    
    // Check for direct scan result prop
    if (this.scanResult) {
      this.handleScanResult(this.scanResult);
    }
  },
  
  beforeUnmount() {
    this.stopScanner();
  }
}
</script>

<style scoped>
/* Custom scrollbar */
.overflow-y-auto::-webkit-scrollbar {
  width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-track {
  background: rgba(255, 255, 255, 0.1);
  border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
  background: rgba(147, 51, 234, 0.5);
  border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
  background: rgba(147, 51, 234, 0.7);
}

/* Animation for scan results */
@keyframes slideInRight {
  from {
    transform: translateX(100%);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 1;
  }
}

.scan-result {
  animation: slideInRight 0.5s ease-out;
}
</style>
