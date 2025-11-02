<template>
  <!-- Responsive centered logo with subtle animation -->
  <Link href="/" class="absolute top-3 sm:top-5 left-1/2 transform -translate-x-1/2 md:left-5 md:transform-none z-50 transition-transform hover:scale-105">
    <img src="/img/logo2.png" alt="BOOK CLOUD Logo" class="w-[150px] sm:w-[180px] md:w-[220px] h-auto drop-shadow-2xl">
  </Link>

  <!-- Full-bleed background with enhanced overlay and animated gradient -->
  <div class="min-h-screen flex items-center justify-center bg-cover bg-center relative px-4 py-20 sm:py-24 md:py-0 overflow-hidden" style="background-image: url('/img/sign.jpg');">
    <!-- Animated gradient overlay -->
    <div class="absolute inset-0 bg-gradient-to-br from-[#061424]/70 via-[#0a2540]/85 to-[#081c2d]/90"></div>
    
    <!-- Decorative animated circles -->
    <div class="absolute top-20 left-10 w-72 h-72 bg-teal-500/10 rounded-full blur-3xl animate-pulse"></div>
    <div class="absolute bottom-20 right-10 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl animate-pulse delay-700"></div>

    <div class="relative w-full max-w-4xl mx-auto">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-8 items-center">
        <!-- Left / visual column (hidden on small screens) -->
        <div class="hidden lg:flex flex-col justify-center space-y-6 px-4 lg:px-6 animate-fade-in">
          <div class="space-y-2">
            <h2 class="text-3xl lg:text-4xl xl:text-5xl font-extrabold text-white leading-tight bg-gradient-to-r from-white via-teal-100 to-blue-100 bg-clip-text text-transparent">
              Welcome Back
            </h2>
            <div class="h-1 w-24 bg-gradient-to-r from-teal-400 to-blue-500 rounded-full"></div>
          </div>
          <p class="text-sm lg:text-base text-gray-300 leading-relaxed">Sign in to continue to Book Cloud. Find, borrow and reserve books with ease.</p>
          <ul class="text-xs lg:text-sm text-gray-400 space-y-3 mt-4">
            <li class="flex items-start group">
              <div class="flex-shrink-0 w-6 h-6 rounded-full bg-teal-500/20 flex items-center justify-center mr-3 mt-0.5 group-hover:bg-teal-500/30 transition-colors">
                <i class="fas fa-check text-teal-400 text-xs"></i>
              </div>
              <span class="group-hover:text-gray-300 transition-colors">Quick search across collections</span>
            </li>
            <li class="flex items-start group">
              <div class="flex-shrink-0 w-6 h-6 rounded-full bg-teal-500/20 flex items-center justify-center mr-3 mt-0.5 group-hover:bg-teal-500/30 transition-colors">
                <i class="fas fa-check text-teal-400 text-xs"></i>
              </div>
              <span class="group-hover:text-gray-300 transition-colors">Scan QR codes with your phone</span>
            </li>
            <li class="flex items-start group">
              <div class="flex-shrink-0 w-6 h-6 rounded-full bg-teal-500/20 flex items-center justify-center mr-3 mt-0.5 group-hover:bg-teal-500/30 transition-colors">
                <i class="fas fa-check text-teal-400 text-xs"></i>
              </div>
              <span class="group-hover:text-gray-300 transition-colors">Manage requests and reservations</span>
            </li>
          </ul>
        </div>

        <!-- Right / form column with glass morphism effect -->
        <div class="bg-[#081c2d]/95 backdrop-blur-xl rounded-2xl shadow-2xl p-5 sm:p-6 md:p-8 border border-gray-700/50 hover:border-teal-500/30 transition-all duration-300 animate-slide-up">
          <!-- Glass effect overlay -->
          <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent rounded-2xl pointer-events-none"></div>
          
          <div class="relative">
            <div class="mb-6 sm:mb-8 text-center lg:text-left">
              <h3 class="text-xl sm:text-2xl font-bold text-white mb-2">Sign in to your account</h3>
              <p class="text-xs sm:text-sm text-gray-400">Enter your credentials to access the library portal</p>
            </div>

            <form @submit.prevent="loginUser" id="loginForm" class="space-y-4 sm:space-y-5">
              <!-- Email -->
              <div class="group">
                <label for="email" class="block text-xs font-medium text-gray-300 mb-2">Email</label>
                <div class="relative">
                  <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-teal-400 transition-colors">
                    <i class="fas fa-envelope text-sm"></i>
                  </div>
                  <input
                    id="email"
                    name="email"
                    type="email"
                    v-model="email"
                    placeholder="SNSU email only"
                    autocomplete="email"
                    required
                    class="w-full pl-10 pr-4 py-3 sm:py-3.5 text-sm rounded-lg border border-gray-600 bg-[#0a2a43]/50 text-gray-200 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-teal-400 focus:border-transparent transition-all duration-300 hover:bg-[#0a2a43]/70"
                  />
                </div>
              </div>

              <!-- Password -->
              <div class="group">
                <label for="password" class="block text-xs font-medium text-gray-300 mb-2">Password</label>
                <div class="relative">
                  <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-teal-400 transition-colors">
                    <i class="fas fa-lock text-sm"></i>
                  </div>
                  <input
                    id="password"
                    name="password"
                    :type="passwordVisible ? 'text' : 'password'"
                    v-model="password"
                    placeholder="Your password"
                    autocomplete="current-password"
                    required
                    class="w-full pl-10 pr-12 py-3 sm:py-3.5 text-sm rounded-lg border border-gray-600 bg-[#0a2a43]/50 text-gray-200 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-teal-400 focus:border-transparent transition-all duration-300 hover:bg-[#0a2a43]/70"
                  />
                  <button 
                    type="button" 
                    @click="togglePasswordVisibility" 
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-teal-400 transition-colors duration-200 focus:outline-none"
                    aria-label="Toggle password visibility"
                  >
                    <i :class="passwordVisible ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-sm"></i>
                  </button>
                </div>
              </div>

              <!-- Controls -->
              <!-- Submit -->
              <div class="pt-2">
                <button
                  type="submit"
                  :disabled="isLoading"
                  class="w-full py-3 sm:py-3.5 text-sm font-semibold text-white bg-gradient-to-r from-teal-500 to-blue-500 rounded-lg hover:from-teal-600 hover:to-blue-600 focus:outline-none focus:ring-4 focus:ring-teal-300/50 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300 shadow-lg hover:shadow-teal-500/50 transform hover:-translate-y-0.5 active:translate-y-0"
                >
                  <span v-if="!isLoading" class="flex items-center justify-center gap-2">
                    <i class="fas fa-sign-in-alt"></i>
                    Login
                  </span>
                  <span v-else class="flex items-center justify-center gap-2">
                    <i class="fas fa-spinner fa-spin"></i>
                    Logging in...
                  </span>
                </button>
              </div>

              <!-- register / error -->
              <div class="flex items-center justify-center sm:justify-between text-xs pt-4 border-t border-gray-700/50">
                <p class="text-gray-400 text-center sm:text-left">
                  Don't have an account? 
                  <Link 
                    href="/register" 
                    class="text-teal-400 hover:text-teal-300 font-semibold hover:underline transition-colors duration-200 ml-1"
                  >
                    Register now
                  </Link>
                </p>
              </div>

              <p v-if="loginErrorMessage" class="text-center text-red-400 text-xs sm:text-sm mt-3 bg-red-500/10 border border-red-500/20 rounded-lg py-2 px-3">
                <i class="fas fa-exclamation-circle mr-1"></i>{{ loginErrorMessage }}
              </p>
              <p v-if="debugInfo" class="text-center text-xs text-gray-500 mt-2">{{ debugInfo }}</p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { router } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';

export default {
  components: {
    Link,
  },
  data() {
    return {
      email: '',
      password: '',
      rememberMe: false,
      loginErrorMessage: '',
      isLoading: false,
      passwordVisible: false,
      debugInfo: '',
    };
  },
  methods: {
    togglePasswordVisibility() {
      this.passwordVisible = !this.passwordVisible;
    },
    async loginUser() {
      this.isLoading = true;
      this.loginErrorMessage = '';
      this.debugInfo = '';

      try {
        // Use Inertia router instead of axios to handle CSRF tokens automatically
        router.post('/login', {
          email: this.email,
          password: this.password,
          remember: this.rememberMe,
        }, {
          onSuccess: (page) => {
            this.debugInfo = 'Login successful! Redirecting...';
            // Inertia will handle the redirect automatically
          },
          onError: (errors) => {
            console.error('Login errors:', errors);
            if (errors.email) {
              this.loginErrorMessage = errors.email;
            } else if (errors.password) {
              this.loginErrorMessage = errors.password;
            } else {
              this.loginErrorMessage = 'Invalid credentials. Please try again.';
            }
          },
          onFinish: () => {
            this.isLoading = false;
          }
        });
      } catch (error) {
        console.error('Login error:', error);
        this.loginErrorMessage = 'An error occurred during login. Please try again.';
        this.isLoading = false;
      }
    },
  },
};
</script>

<style scoped>
@keyframes fade-in {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes slide-up {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in {
  animation: fade-in 0.6s ease-out;
}

.animate-slide-up {
  animation: slide-up 0.7s ease-out;
}

.delay-700 {
  animation-delay: 700ms;
}

/* Custom scrollbar for better aesthetics */
::-webkit-scrollbar {
  width: 8px;
}

::-webkit-scrollbar-track {
  background: #0a2a43;
}

::-webkit-scrollbar-thumb {
  background: #14b8a6;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: #0d9488;
}
</style>
