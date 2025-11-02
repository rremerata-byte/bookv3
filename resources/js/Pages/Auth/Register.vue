<template>
  <Link href="/" class="absolute top-3 sm:top-5 left-3 sm:left-5 z-50 transition-transform hover:scale-105">
    <img src="/img/logo2.png" alt="BOOK CLOUD Logo" class="w-[150px] sm:w-[180px] md:w-[220px] h-auto drop-shadow-2xl">
  </Link>
  
  <div class="flex items-center justify-center min-h-screen bg-cover bg-center relative px-4 py-20 sm:py-24 overflow-hidden" style="background-image: url('/img/sign.jpg');">
    <!-- Enhanced animated gradient overlay -->
    <div class="absolute inset-0 bg-gradient-to-br from-[#081c2d]/85 via-[#0a2540]/90 to-[#061424]/95"></div>
    
    <!-- Decorative animated elements -->
    <div class="absolute top-10 right-20 w-64 h-64 bg-purple-500/10 rounded-full blur-3xl animate-pulse"></div>
    <div class="absolute bottom-10 left-20 w-80 h-80 bg-teal-500/10 rounded-full blur-3xl animate-pulse delay-1000"></div>
    
    <div class="relative bg-[#081c2d]/95 backdrop-blur-xl rounded-2xl shadow-2xl p-5 sm:p-6 md:p-8 max-w-lg w-full my-6 border border-gray-700/50 hover:border-teal-500/30 transition-all duration-300 animate-scale-in">
      <!-- Glass effect overlay -->
      <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent rounded-2xl pointer-events-none"></div>
      
      <div class="relative">
        <div class="text-center mb-6 sm:mb-8">
          <h2 class="text-2xl sm:text-3xl font-extrabold text-white mb-2 bg-gradient-to-r from-white via-teal-100 to-purple-100 bg-clip-text text-transparent">
            Create Account
          </h2>
          <div class="h-1 w-20 bg-gradient-to-r from-teal-400 via-blue-500 to-purple-500 rounded-full mx-auto mb-3"></div>
          <p class="text-sm sm:text-base text-gray-300">Getting started is easy</p>
        </div>
        
        <form id="registrationForm" @submit.prevent="registerUser" class="space-y-3 sm:space-y-4">
          <!-- Full Name -->
          <div class="group">
            <input 
              id="fullname"
              name="fullname"
              type="text" 
              v-model="fullname" 
              placeholder="Full Name" 
              autocomplete="name"
              required 
              class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-600 rounded-lg text-sm bg-[#2E3B4E]/50 text-gray-300 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-300 hover:bg-[#2E3B4E]/70"
            >
          </div>
          
          <!-- Email -->
          <div class="group">
            <input 
              id="email"
              name="email"
              type="email" 
              v-model="email" 
              placeholder="Email Address" 
              autocomplete="email"
              required 
              class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-600 rounded-lg text-sm bg-[#2E3B4E]/50 text-gray-300 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-300 hover:bg-[#2E3B4E]/70"
            >
          </div>
          
          <!-- Student ID -->
          <div class="group">
            <input 
              id="studentid"
              name="studentid"
              type="text" 
              v-model="studentid" 
              placeholder="Student ID" 
              autocomplete="off"
              required 
              class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-600 rounded-lg text-sm bg-[#2E3B4E]/50 text-gray-300 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-300 hover:bg-[#2E3B4E]/70"
            >
          </div>
          
          <!-- Course & Section -->
          <div class="group">
            <input 
              id="courseSection"
              name="courseSection"
              type="text" 
              v-model="courseSection" 
              placeholder="Course & Section" 
              autocomplete="off"
              required 
              class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-600 rounded-lg text-sm bg-[#2E3B4E]/50 text-gray-300 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-300 hover:bg-[#2E3B4E]/70"
            >
          </div>
          
          <!-- Gender -->
          <div class="group">
            <select 
              id="gender"
              name="gender"
              v-model="gender" 
              autocomplete="sex"
              required 
              class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-600 rounded-lg text-sm bg-[#2E3B4E]/50 text-gray-400 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-300 hover:bg-[#2E3B4E]/70 cursor-pointer"
            >
              <option value="">Select Gender</option>
              <option value="Male" class="bg-[#2E3B4E] text-gray-300">Male</option>
              <option value="Female" class="bg-[#2E3B4E] text-gray-300">Female</option>
              <option value="Other" class="bg-[#2E3B4E] text-gray-300">Other</option>
            </select>
          </div>
          
          <!-- Password -->
          <div class="relative group">
            <input 
              id="password"
              name="password"
              :type="passwordVisible ? 'text' : 'password'" 
              v-model="password" 
              placeholder="Password" 
              autocomplete="new-password"
              required 
              class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-600 rounded-lg text-sm bg-[#2E3B4E]/50 text-gray-300 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent pr-12 transition-all duration-300 hover:bg-[#2E3B4E]/70"
            >
            <button
              type="button"
              class="absolute right-3 sm:right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-teal-400 transition-colors duration-200 focus:outline-none" 
              @click="togglePasswordVisibility('password')"
              aria-label="Toggle password visibility"
            >
              <i :class="passwordVisible ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-sm"></i>
            </button>
          </div>
          
          <!-- Confirm Password -->
          <div class="relative group">
            <input 
              id="confirmPassword"
              name="confirmPassword"
              :type="confirmPasswordVisible ? 'text' : 'password'" 
              v-model="confirmPassword" 
              placeholder="Confirm Password" 
              autocomplete="new-password"
              required 
              class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-600 rounded-lg text-sm bg-[#2E3B4E]/50 text-gray-300 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent pr-12 transition-all duration-300 hover:bg-[#2E3B4E]/70"
            >
            <button
              type="button"
              class="absolute right-3 sm:right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-teal-400 transition-colors duration-200 focus:outline-none" 
              @click="togglePasswordVisibility('confirmPassword')"
              aria-label="Toggle confirm password visibility"
            >
              <i :class="confirmPasswordVisible ? 'fas fa-eye-slash' : 'fas fa-eye'" class="text-sm"></i>
            </button>
          </div>
          
          <!-- Phone Number -->
          <div class="group">
            <input 
              id="phone_number"
              name="phone_number"
              type="tel" 
              v-model="phone_number" 
              placeholder="Phone Number (Optional)" 
              autocomplete="tel"
              class="w-full px-3 sm:px-4 py-2.5 sm:py-3 border border-gray-600 rounded-lg text-sm bg-[#2E3B4E]/50 text-gray-300 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-300 hover:bg-[#2E3B4E]/70"
            >
          </div>
          
          <!-- Submit Button -->
          <div class="pt-2">
            <button 
              :disabled="isLoading" 
              type="submit" 
              class="w-full py-3 sm:py-3.5 text-sm font-bold text-white bg-gradient-to-r from-teal-500 via-blue-500 to-purple-500 rounded-lg hover:from-teal-600 hover:via-blue-600 hover:to-purple-600 focus:outline-none focus:ring-4 focus:ring-teal-300/50 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300 shadow-lg hover:shadow-teal-500/50 transform hover:-translate-y-0.5 active:translate-y-0"
            >
              <span v-if="!isLoading" class="flex items-center justify-center gap-2">
                <i class="fas fa-user-plus"></i>
                Create Account
              </span>
              <span v-else class="flex items-center justify-center gap-2">
                <i class="fas fa-spinner fa-spin"></i>
                Registering...
              </span>
            </button>
          </div>
          
          <!-- Login Link -->
          <div class="text-center pt-4 border-t border-gray-700/50">
            <p class="text-xs sm:text-sm text-gray-400">
              Already have an account? 
              <Link 
                href="/login" 
                class="text-teal-400 hover:text-teal-300 font-semibold hover:underline transition-colors duration-200 ml-1"
              >
                Login here
              </Link>
            </p>
          </div>
          
          <!-- Error Message -->
          <p v-if="registrationMessage" class="text-center text-red-400 text-xs sm:text-sm mt-3 bg-red-500/10 border border-red-500/20 rounded-lg py-2 px-3">
            <i class="fas fa-exclamation-circle mr-1"></i>{{ registrationMessage }}
          </p>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { Link } from '@inertiajs/vue3';

export default {
  components: {
    Link,
  },
  data() {
    return {
      fullname: '',
      email: '',
      studentid: '',
      courseSection: '',
      gender: '',
      password: '',
      confirmPassword: '',
      phone_number: '',
      registrationMessage: '',
      passwordVisible: false,
      confirmPasswordVisible: false,
      isLoading: false,
    };
  },
  methods: {
    togglePasswordVisibility(field) {
      if (field === 'password') {
        this.passwordVisible = !this.passwordVisible;
      } else if (field === 'confirmPassword') {
        this.confirmPasswordVisible = !this.confirmPasswordVisible;
      }
    },
    async registerUser() {
      if (this.password !== this.confirmPassword) {
        this.registrationMessage = 'Passwords do not match!';
        return;
      }
      const userData = {
        fullname: this.fullname,
        email: this.email,
        studentid: this.studentid,
        courseSection: this.courseSection,
        gender: this.gender,
        password: this.password,
        password_confirmation: this.confirmPassword,
        phone_number: this.phone_number,
      };
      this.isLoading = true;
      try {
        const response = await axios.post('/api/register', userData);
        if (response.data.success) {
          window.location.href = response.data.redirect || '/student/home';
        }
      } catch (error) {
        if (error.response && error.response.data.error) {
          this.registrationMessage = Object.values(error.response.data.error).join(', ');
        } else {
          this.registrationMessage = 'An error occurred. Please try again.';
        }
      } finally {
        this.isLoading = false;
      }
    },
  },
};
</script>

<style scoped>
@keyframes scale-in {
  from {
    opacity: 0;
    transform: scale(0.95);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}

.animate-scale-in {
  animation: scale-in 0.5s ease-out;
}

.delay-1000 {
  animation-delay: 1000ms;
}

/* Custom scrollbar */
::-webkit-scrollbar {
  width: 8px;
}

::-webkit-scrollbar-track {
  background: #0a2a43;
}

::-webkit-scrollbar-thumb {
  background: linear-gradient(180deg, #14b8a6, #3b82f6);
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(180deg, #0d9488, #2563eb);
}

/* Smooth focus transitions */
input:focus,
select:focus {
  transform: translateY(-1px);
}

/* Hover effects for form groups */
.group:hover input,
.group:hover select {
  border-color: #14b8a6;
}
</style>
