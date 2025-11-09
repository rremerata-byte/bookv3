<template>

  <!-- Header Section -->
  <header class="bg-[#081c2d] text-white py-3 px-4 md:px-6 shadow-sm mb-4 flex flex-col md:flex-row items-center justify-between">
      <!-- Logo Section -->

      <div class="flex items-center space-x-4 justify-center md:justify-start w-full md:w-auto">
        <img src="/img/logo2.png" alt="BOOK CLOUD Logo" class="max-w-[150px] h-auto" />
      </div>

      <!-- Search Bar Section (Centered) -->
      <div class="w-full md:flex-1 md:mx-6 mt-3 md:mt-0">
        <input 
          id="search"
          name="search"
          type="text" 
          v-model="searchQuery" 
          placeholder="Search by title, author, or course..."
          autocomplete="off"
          class="w-full p-2 text-sm border border-gray-300 rounded-lg text-black focus:outline-none focus:ring-2 focus:ring-blue-400 transition-all duration-200"
        />
      </div>

      <!-- Profile Section -->
      <div class="flex items-center space-x-4 w-full md:w-auto justify-end mt-3 md:mt-0">
        <!-- Profile Button -->
        <button 
          ref="profileBtn"
          @click="toggleDropdown" 
          :aria-expanded="showDropdown"
          class="w-10 h-10 rounded-full bg-[#00509e] flex items-center justify-center text-white text-sm font-semibold shadow-md transition-all hover:scale-105 relative"
        >
          <span class="sr-only">Open profile menu</span>
          <span class="flex items-center space-x-1">
            <span>{{ userInitial }}</span>
            <svg :class="{'transform rotate-180': showDropdown}" class="w-3 h-3 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
          </span>
        </button>

        <!-- User Info: show only name on very small screens; ID/phone/course move to dropdown -->
        <div class="flex flex-col items-center sm:items-end space-y-0.5 text-center sm:text-right">
          <p class="text-sm font-semibold truncate max-w-[180px]">{{ user.fullname }}</p>
          <div class="hidden sm:block">
            <p class="text-xs text-gray-400 truncate max-w-[180px]">{{ user.studentid }}</p>
            <p class="text-xs text-gray-400 truncate max-w-[180px]">{{ user.course || 'No course' }}</p>
            <p class="text-xs text-gray-400 truncate max-w-[180px]">{{ user.phone_number || 'No phone number' }}</p>
          </div>
        </div>

        <!-- Dropdown Menu -->
        <div v-if="showDropdown" ref="dropdown" class="absolute top-12 right-0 w-56 bg-white rounded-lg shadow-md py-2 z-50">
          <div class="px-4 pb-2 border-b">
            <p class="text-sm font-semibold text-gray-800 truncate">{{ user.fullname }}</p>
            <div class="flex items-center justify-between mt-1">
              <p class="text-xs text-gray-500 truncate">ID: {{ user.studentid }}</p>
              <button @click="copyStudentId" type="button" class="text-xs text-blue-600 hover:underline ml-2" title="Copy student ID">Copy</button>
            </div>
            <div class="mt-1">
              <p class="text-xs text-gray-500 truncate">Course: {{ user.course || 'No course' }}</p>
            </div>
            <div class="flex items-center justify-between mt-1">
              <p class="text-xs text-gray-500 truncate">{{ user.phone_number || 'No phone number' }}</p>
              <button @click="toggleProfileDetails" type="button" class="text-xs text-gray-500 hover:text-gray-700 ml-2" v-if="false">Details</button>
            </div>
          </div>

          <!-- Collapsible Details: can be toggled to reduce height (default collapsed) -->
          <div v-show="showProfileDetails" class="px-4 py-2 border-b text-sm text-gray-700">
            <p class="truncate">Full name: {{ user.fullname }}</p>
            <p class="truncate">Student ID: {{ user.studentid }}</p>
            <p class="truncate">Phone: {{ user.phone_number || 'No phone number' }}</p>
          </div>

          <button 
            @click="openProfileModal"
            class="block w-full text-left px-4 py-2 text-gray-800 hover:bg-gray-100 transition-all duration-200"
          >
            <i class="fas fa-user-circle mr-2 text-lg"></i>
            Edit Profile
          </button>
          <Link 
            :href="route('logout')" 
            method="post" 
            as="button"
            class="block w-full text-left px-4 py-2 text-gray-800 hover:bg-gray-100 transition-all duration-200"
          >
            <i class="fas fa-sign-out-alt mr-2 text-lg"></i>
            Logout
          </Link>
        </div>
      </div>
    </header>

    <!-- Hamburger Button and Notification Icon (Below Navbar) -->
    <div class="flex items-center mb-4 space-x-3">
      <!-- Hamburger Button -->
      <button @click="toggleSidebar" type="button" class="ml-2 mt-2 p-2 rounded-full bg-[#081c2d] text-white shadow hover:bg-[#00509e] focus:outline-none transition-all duration-200">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
      </button>

      <!-- Notification Icon Button -->
      <button 
        @click="openNotificationModal" 
        type="button"
        class="mt-2 p-2 rounded-full bg-[#081c2d] text-white shadow hover:bg-[#00509e] focus:outline-none transition-all duration-200 relative"
      >
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>
        <!-- Unread Badge -->
        <span 
          v-if="unreadNotificationsCount > 0" 
          class="absolute -top-1 -right-1 px-2 py-0.5 bg-red-500 text-white text-xs font-bold rounded-full min-w-[20px] text-center animate-pulse"
        >
          {{ unreadNotificationsCount }}
        </span>
      </button>

      <!-- Glowing Border Button - User Course (Toggle ON/OFF) -->
      <button 
        @click="filterByCourse"
        type="button"
        :class="[
          'mt-2 px-4 py-2 rounded-lg font-semibold shadow-lg hover:shadow-xl focus:outline-none transition-all duration-300 relative overflow-hidden',
          isCourseFilterActive 
            ? 'bg-gradient-to-r from-green-500 to-emerald-600 text-white glow-button-active' 
            : 'bg-gradient-to-r from-blue-500 to-purple-600 text-white glow-button'
        ]"
        :title="isCourseFilterActive ? 'Click to show all books' : 'Click to filter by ' + (user.course || 'your course')"
      >
        <span class="relative z-10 flex items-center space-x-2">
          <svg v-if="!isCourseFilterActive" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
          </svg>
          <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          <span>{{ isCourseFilterActive ? user.course + ' (ON)' : user.course || 'No Course' }}</span>
        </span>
      </button>
    </div>

    <!-- Sidebar -->
    <transition name="slide">
      <div v-if="showSidebar" class="fixed inset-0 z-50 flex">
        <div class="bg-[#081c2d] w-64 h-full shadow-lg p-6 flex flex-col">
          <div class="flex items-center justify-between mb-8">
            <button @click="toggleSidebar" type="button" class="text-white focus:outline-none">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>

          <nav class="flex flex-col space-y-4">

            <div class="mb-4">
              <span class="text-gray-300 uppercase text-xs tracking-wider">Notifications</span>
              <button @click="openNotificationModal" type="button" class="flex items-center mt-2 text-white hover:text-blue-400 focus:outline-none relative">
                <i class="fas fa-bell mr-2"></i> 
                <span>Notification</span>
                <span 
                  v-if="unreadNotificationsCount > 0" 
                  class="ml-2 px-2 py-0.5 bg-red-500 text-white text-xs font-bold rounded-full animate-pulse"
                >
                  {{ unreadNotificationsCount }}
                </span>
              </button>
            </div>
              <div class="mb-4">
                <span class="text-gray-300 uppercase text-xs tracking-wider">Scanner</span>
                <button @click="openScannerModal" type="button" class="flex items-center mt-2 text-white hover:text-blue-400 focus:outline-none">
                  <i class="fas fa-qrcode mr-2"></i> Scan Code
                </button>
              </div>
            <div class="mb-4">
              <span class="text-gray-300 uppercase text-xs tracking-wider">History</span>
              <button @click="openTransactionModal" type="button" class="flex items-center mt-2 text-white hover:text-blue-400 focus:outline-none">
                <i class="fas fa-history mr-2"></i> Transaction History
              </button>
            </div>
          </nav>
        </div>
        <div class="flex-1" @click="toggleSidebar"></div>
      </div>
    </transition>

    <!-- Notification Modal -->
    <div v-if="isNotificationModalOpen" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 p-2 sm:p-4" @click.self="closeNotificationModal">
      <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 w-full max-w-full sm:max-w-md md:max-w-lg animate-fade-in relative max-h-[85vh] sm:max-h-[70vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-semibold text-gray-800">Notifications</h3>
          <button 
            v-if="notifications && notifications.length > 0"
            @click="markAllAsRead"
            type="button"
            class="text-xs text-blue-600 hover:text-blue-800 font-medium"
          >
            Mark all as read
          </button>
        </div>
        <div class="text-gray-600">
          <div v-if="notifications && notifications.length === 0" class="text-center py-8">
            <i class="fas fa-bell-slash text-4xl text-gray-300 mb-3"></i>
            <p class="text-gray-500">No notifications yet.</p>
          </div>
          <div v-else>
            <ul class="space-y-2">
              <li 
                v-for="note in notifications" 
                :key="note.id" 
                :class="[
                  'p-3 rounded-lg relative group transition-all duration-200',
                  note.read ? 'bg-gray-50' : 'bg-blue-50 border border-blue-200'
                ]"
              >
                <!-- Unread indicator dot -->
                <div 
                  v-if="!note.read" 
                  class="absolute top-3 left-3 w-2 h-2 bg-blue-500 rounded-full animate-pulse"
                ></div>
                
                <div :class="note.read ? 'ml-0' : 'ml-4'">
                  <div class="flex items-start justify-between">
                    <div class="flex-1">
                      <p class="text-sm text-gray-800 font-medium">
                        {{ buildNotificationText(note, 'short') }}
                      </p>
                      <p class="text-xs text-gray-500 mt-1">
                        {{ buildNotificationText(note, 'long') }}
                      </p>
                      <p v-if="note.created_at" class="text-xs text-gray-400 mt-1 flex items-center">
                        <i class="fas fa-clock mr-1"></i>
                        {{ formatDateTime(note.created_at) }}
                      </p>
                    </div>
                    
                    <!-- Action buttons -->
                    <div class="flex items-center space-x-2 ml-2 opacity-0 group-hover:opacity-100 transition-opacity">
                      <button 
                        @click.stop="toggleReadStatus(note)"
                        type="button"
                        :title="note.read ? 'Mark as unread' : 'Mark as read'"
                        class="p-1.5 rounded-full hover:bg-gray-200 transition-colors"
                      >
                        <i 
                          :class="[
                            'text-sm',
                            note.read ? 'fas fa-envelope text-gray-600' : 'fas fa-envelope-open text-blue-600'
                          ]"
                        ></i>
                      </button>
                      <button 
                        @click.stop="deleteNotification(note.id)"
                        type="button"
                        title="Delete notification"
                        class="p-1.5 rounded-full hover:bg-red-100 transition-colors"
                      >
                        <i class="fas fa-trash text-sm text-red-600"></i>
                      </button>
                    </div>
                  </div>
                  
                  <!-- Read status badge -->
                  <div v-if="note.read" class="mt-2">
                    <span class="text-xs text-gray-400 flex items-center">
                      <i class="fas fa-check-double mr-1"></i>
                      Read
                    </span>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>
        <button @click="closeNotificationModal" type="button" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700">
          <i class="fas fa-times"></i>
        </button>
      </div>
    </div>

    <!-- Transaction History Modal -->
    <div v-if="isTransactionModalOpen" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 p-2 sm:p-4" @click.self="closeTransactionModal">
      <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 w-full max-w-full sm:max-w-md md:max-w-lg animate-fade-in relative max-h-[85vh] sm:max-h-[80vh] overflow-y-auto">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Transaction History</h3>
        <div v-if="myRequests.length === 0" class="text-gray-600">No transaction history yet.</div>
        <div v-else>
          <table class="w-full border-collapse text-sm">
            <thead>
              <tr>
                  <th class="py-2 px-2 text-left">Book</th>
                  <th class="py-2 px-2 text-left">Type</th>
                  <th class="py-2 px-2 text-left">Start</th>
                  <th class="py-2 px-2 text-left">Status</th>
                </tr>
            </thead>
            <tbody>
              <tr v-for="req in myRequests" :key="req.id" class="odd:bg-gray-50 even:bg-white">
                <td class="py-2 px-2">{{ req.book_title }}</td>
                <td class="py-2 px-2 capitalize">{{ req.request_type }}</td>
                <td class="py-2 px-2">{{ formatDate(req.request_date) }}</td>
                <td class="py-2 px-2">
                  <span :class="{
                    'text-yellow-600': req.status === 'pending',
                    'text-green-600': req.status === 'approved',
                    'text-red-600': req.status === 'rejected'
                  }">
                    {{ req.status }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <button @click="closeTransactionModal" type="button" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700">
          <i class="fas fa-times"></i>
        </button>
      </div>
    </div>

    <!-- Borrowed/Reserve Book Modal -->
    <div v-if="isBorrowedModalOpen" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 p-2 sm:p-4" @click.self="closeBorrowedModal">
      <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 w-full max-w-full sm:max-w-md md:max-w-lg animate-fade-in relative">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Borrowed/Reserve Book</h3>
        <div class="text-gray-600">No borrowed or reserved books yet.</div>
        <button @click="closeBorrowedModal" type="button" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700">
          <i class="fas fa-times"></i>
        </button>
      </div>
    </div>



  <!-- Notification Modal -->
  


    <!-- Book Cards -->
    <main class="container mx-auto p-6 md:p-10">
      <h1 class="text-2xl font-semibold mb-6">Available Books</h1>

      <!-- Book Age Legend with Filter and Sort -->
      <div class="mb-6 bg-white p-4 rounded-lg shadow-md border border-gray-200">
        <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
          <i class="fas fa-filter mr-2 text-blue-500"></i>
          📚 Book Age Color Guide:
        </h3>
        <div class="flex flex-wrap gap-3 text-sm">
          <!-- Sort Dropdown -->
          <div class="flex items-center gap-2 w-full sm:w-auto">
            <label class="text-sm font-medium text-gray-700 whitespace-nowrap">
              <i class="fas fa-sort mr-1"></i>
              Sort By:
            </label>
            <select 
              v-model="sortBy" 
              class="flex-1 sm:flex-none px-3 sm:px-4 py-2 sm:py-3 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-700 text-sm sm:min-w-[200px]"
            >
              <option value="title-asc">Title (A-Z)</option>
              <option value="title-desc">Title (Z-A)</option>
              <option value="author-asc">Author (A-Z)</option>
              <option value="author-desc">Author (Z-A)</option>
              <option value="course-asc">Course (A-Z)</option>
              <option value="course-desc">Course (Z-A)</option>
              <option value="availability">Availability</option>
              <option value="age-newest">Book Age (Newest)</option>
              <option value="age-oldest">Book Age (Oldest)</option>
            </select>
          </div>

          <!-- Age Filter Buttons -->
          <button 
            type="button" 
            @click="toggleAgeFilter('new')"
            :class="[
              'flex items-center space-x-2 px-3 py-2 rounded-lg transition-all',
              ageFilter === 'new' ? 'bg-green-100 ring-2 ring-green-500' : 'hover:bg-gray-100'
            ]"
          >
            <div class="w-6 h-6 border-4 border-green-500 rounded flex-shrink-0"></div>
            <span class="text-gray-700">0-2 years (New)</span>
          </button>

          <button 
            type="button" 
            @click="toggleAgeFilter('recent')"
            :class="[
              'flex items-center space-x-2 px-3 py-2 rounded-lg transition-all',
              ageFilter === 'recent' ? 'bg-blue-100 ring-2 ring-blue-500' : 'hover:bg-gray-100'
            ]"
          >
            <div class="w-6 h-6 border-4 border-blue-500 rounded flex-shrink-0"></div>
            <span class="text-gray-700">3-4 years</span>
          </button>

          <button 
            type="button" 
            @click="toggleAgeFilter('moderate')"
            :class="[
              'flex items-center space-x-2 px-3 py-2 rounded-lg transition-all',
              ageFilter === 'moderate' ? 'bg-yellow-100 ring-2 ring-yellow-500' : 'hover:bg-gray-100'
            ]"
          >
            <div class="w-6 h-6 border-4 border-yellow-500 rounded flex-shrink-0"></div>
            <span class="text-gray-700">5-6 years</span>
          </button>

          <button 
            type="button" 
            @click="toggleAgeFilter('older')"
            :class="[
              'flex items-center space-x-2 px-3 py-2 rounded-lg transition-all',
              ageFilter === 'older' ? 'bg-orange-100 ring-2 ring-orange-500' : 'hover:bg-gray-100'
            ]"
          >
            <div class="w-6 h-6 border-4 border-orange-500 rounded flex-shrink-0"></div>
            <span class="text-gray-700">7-9 years</span>
          </button>

          <button 
            type="button" 
            @click="toggleAgeFilter('old')"
            :class="[
              'flex items-center space-x-2 px-3 py-2 rounded-lg transition-all',
              ageFilter === 'old' ? 'bg-red-100 ring-2 ring-red-500' : 'hover:bg-gray-100'
            ]"
          >
            <div class="w-6 h-6 border-4 border-red-500 rounded flex-shrink-0"></div>
            <span class="text-gray-700">10+ years (Old)</span>
          </button>

          <!-- Clear Filter Button -->
          <button 
            v-if="ageFilter"
            type="button" 
            @click="clearAgeFilter"
            class="flex items-center space-x-2 px-3 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 transition-all"
          >
            <i class="fas fa-times"></i>
            <span class="text-gray-700 font-semibold">Clear Filter</span>
          </button>
        </div>
      </div>

  <!-- Force 3 columns across most viewports; switch to 2 columns for very small phones (<=360px) -->
  <div class="grid grid-cols-3 gap-4 books-grid">
      <div 
        v-for="book in filteredBooks" 
        :key="book.id" 
        @click="openModal(book)"
        :class="[
          'bg-white shadow-md rounded-lg overflow-hidden cursor-pointer hover:scale-105 transition transform border-2 md:border-4',
          getBookAgeBorderClass(book)
        ]">
        <div class="w-full h-56 overflow-hidden bg-gray-100 book-cover">
          <img 
            :src="book.image_url || '/img/default-book.jpg'" 
            alt="Book Cover"
            loading="lazy"
            class="w-full h-full object-cover block"
          />
        </div>
        <div class="p-4">
          <h6 :title="book.title" class="text-xl font-extrabold text-gray-900 leading-tight mb-1 line-clamp-2">{{ book.title }}</h6>
          <button
            @click.stop="viewQrCode(book.id, book.title)"
            type="button"
            class="mt-2 inline-flex items-center px-3 py-1 bg-gray-100 text-sm text-gray-700 rounded-md hover:bg-gray-200 focus:outline-none"
          >
            <i class="fas fa-qrcode mr-2"></i>
            View QR Code
          </button>
          <p class="text-sm text-gray-600 mt-2">{{ book.description }}</p>
          <p class="text-sm text-gray-700 mt-1"><span class="font-semibold">Subject:</span> <span class="font-semibold">{{ book.subject || 'N/A' }}</span></p>
          <p class="text-sm text-gray-700 mt-1"><span class="font-semibold">Course:</span> <span class="font-semibold">{{ book.course || 'N/A' }}</span></p>
          <p class="text-sm text-gray-700 mt-1"><span class="font-semibold">Author:</span> <span class="font-semibold">{{ book.author }}</span></p>
          <span 
            :class="{
              'text-green-600 font-medium': book.availability === 'Available',
              'text-red-600 font-medium': book.availability === 'Borrowed',
              'text-yellow-600 font-medium': book.availability === 'Reserved'
            }"
            class="block mt-2">
            {{ book.availability }}
          </span>
        </div>
      </div>
    </div>
    </main>
    <!-- Modal for Book Request -->
    <div 
      v-if="isModalOpen" 
      class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 p-2 sm:p-4"
      @click.self="closeModal">
  <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 w-full max-w-full sm:max-w-md md:max-w-lg animate-fade-in relative max-h-[90vh] overflow-y-auto">
  <h3 class="text-lg font-semibold text-gray-800 mb-4">Request Book <span v-if="selectedBook" class="text-sm text-gray-500 font-normal">- {{ selectedBook.title }}</span></h3>
        <form @submit.prevent="submitRequest" class="space-y-4">
          <!-- Request Type Selection -->
          <div>
            <label for="requestType" class="block text-sm font-medium text-gray-700">Request Type:</label>
            <select 
              id="requestType"
              name="requestType"
              v-model="requestType" 
              class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
              required
            >
              <option value="">Select a request type</option>
              <option value="borrow">Borrow</option>
              <option value="reserve">Reserve</option>
            </select>
          </div>

          <!-- Start Date -->
          <div>
            <label for="startDate" class="block text-sm font-medium text-gray-700">Start Date:</label>
            <input 
              id="startDate"
              name="startDate"
              type="date" 
              v-model="startDate"
              @change="onStartDateChange"
              autocomplete="off"
              class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
              :min="new Date().toISOString().split('T')[0]"
              required
            />
          </div>

          <!-- Return Date -->
          <div>
            <label for="returnDate" class="block text-sm font-medium text-gray-700">
              {{ requestType === 'borrow' ? 'Return Date' : 'Until Date' }}:
            </label>
            <input 
              id="returnDate"
              name="returnDate"
              type="date" 
              v-model="returnDate"
              autocomplete="off"
              class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
              :min="returnDateMin"
              required
            />
          </div>

          <div class="flex justify-end space-x-3">
            <button 
              type="button"
              @click="closeModal"
              class="px-4 py-2 text-black-600 hover:bg-black-100 rounded-lg"
            >
              Cancel
            </button>
            <button 
              type="submit"
              class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed"
              :disabled="!requestType || !startDate || !returnDate"
            >
              Submit Request
            </button>
          </div>
        </form>
      </div>
    </div>
    <!-- Profile Edit Modal -->
    <div 
      v-if="isProfileModalOpen" 
      class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 p-2 sm:p-4"
      @click.self="closeProfileModal"
    >
  <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 w-full max-w-full sm:max-w-md md:max-w-lg animate-fade-in max-h-[90vh] overflow-y-auto">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Edit Profile</h3>
        <form @submit.prevent="updateProfile" class="space-y-4">
          <div>
            <label for="profileFullname" class="block text-sm font-medium text-gray-700">Full Name:</label>
            <input 
              id="profileFullname"
              name="fullname"
              type="text" 
              v-model="profileForm.fullname"
              autocomplete="name"
              class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
              required
            />
          </div>

          <div>
            <label for="profileEmail" class="block text-sm font-medium text-gray-700">Email:</label>
            <input 
              id="profileEmail"
              name="email"
              type="email" 
              v-model="profileForm.email"
              autocomplete="email"
              class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
              required
            />
          </div>

          <div>
            <label for="profilePhone" class="block text-sm font-medium text-gray-700">Phone Number:</label>
            <input 
              id="profilePhone"
              name="phone_number"
              type="tel" 
              v-model="profileForm.phone_number"
              autocomplete="tel"
              class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
            />
          </div>

          <div class="flex justify-end space-x-3">
            <button 
              type="button"
              @click="closeProfileModal"
              class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg"
            >
              Cancel
            </button>
            <button 
              type="submit"
              class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600"
            >
              Save Changes
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- QR Modal -->
    <div v-if="showQrModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 p-2 sm:p-4" @click.self="showQrModal = false">
      <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 w-full max-w-full sm:max-w-md md:max-w-lg animate-fade-in relative max-h-[85vh] sm:max-h-[80vh] overflow-y-auto">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">QR Code - {{ qrModalBookTitle }}</h3>
        
        <!-- QR Code Display with Loading State -->
        <div v-if="qrModalSvg && qrModalSvg.length > 0" class="flex justify-center mb-4" v-html="qrModalSvg"></div>
        <div v-else class="flex justify-center items-center h-48 mb-4">
          <div class="text-center">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500 mb-2"></div>
            <p class="text-gray-500 text-sm">Loading QR code...</p>
          </div>
        </div>
        
        <div class="flex justify-end space-x-3">
          <button @click="downloadQr" type="button" :disabled="!qrModalSvg" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 disabled:opacity-50 disabled:cursor-not-allowed">Download PNG</button>
          <button @click="showQrModal = false" type="button" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">Close</button>
        </div>
      </div>
    </div>

    <!-- Scanner Modal -->
    <div v-if="showScannerModal" class="fixed inset-0 bg-black bg-opacity-70 flex justify-center items-center z-50 p-4 overflow-y-auto">
      <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl shadow-2xl w-full max-w-5xl mx-auto my-8 overflow-hidden animate-fade-in">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-teal-600 px-6 py-4 flex items-center justify-between sticky top-0 z-10">
          <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
              <i class="fas fa-qrcode text-white text-lg"></i>
            </div>
            <div>
              <h3 class="text-xl font-bold text-white">QR Code Scanner</h3>
              <p class="text-xs text-blue-100">Scan or upload a QR code to find books</p>
            </div>
          </div>
          <button 
            @click="closeScannerModal" 
            type="button" 
            class="w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 backdrop-blur-sm flex items-center justify-center transition-all duration-200 group"
            aria-label="Close scanner"
          >
            <i class="fas fa-times text-white group-hover:scale-110 transition-transform"></i>
          </button>
        </div>

        <div class="p-6 max-h-[calc(100vh-12rem)] overflow-y-auto">
          <div class="flex flex-col lg:flex-row gap-6">
            <!-- Left: Camera View -->
            <div class="lg:flex-1">
              <div class="relative rounded-xl overflow-hidden shadow-lg bg-black">
                <video 
                  id="scannerVideo"
                  ref="scanVideo" 
                  playsinline 
                  muted 
                  :class="['w-full h-72 md:h-96 object-cover', scannerActive ? 'ring-4 ring-green-400' : '']"
                  aria-label="QR Code Scanner Camera"
                ></video>
                
                <!-- Scanning Overlay -->
                <div v-if="scannerActive" class="absolute inset-0 pointer-events-none">
                  <!-- Corner brackets -->
                  <div class="absolute top-4 left-4 w-12 h-12 border-t-4 border-l-4 border-green-400 rounded-tl-lg"></div>
                  <div class="absolute top-4 right-4 w-12 h-12 border-t-4 border-r-4 border-green-400 rounded-tr-lg"></div>
                  <div class="absolute bottom-4 left-4 w-12 h-12 border-b-4 border-l-4 border-green-400 rounded-bl-lg"></div>
                  <div class="absolute bottom-4 right-4 w-12 h-12 border-b-4 border-r-4 border-green-400 rounded-br-lg"></div>
                  
                  <!-- Scanning line animation -->
                  <div class="absolute inset-x-8 top-1/2 h-0.5 bg-gradient-to-r from-transparent via-green-400 to-transparent animate-scan"></div>
                </div>
                
                <!-- Status Badge -->
                <div class="absolute top-4 left-1/2 transform -translate-x-1/2">
                  <div v-if="scannerActive" class="px-4 py-2 bg-green-500 text-white rounded-full shadow-lg flex items-center space-x-2 animate-pulse">
                    <div class="w-2 h-2 bg-white rounded-full animate-ping"></div>
                    <span class="text-sm font-semibold">📷 Scanning Active</span>
                  </div>
                  <div v-else class="px-4 py-2 bg-gray-700 text-white rounded-full shadow-lg">
                    <span class="text-sm font-semibold">Camera Ready</span>
                  </div>
                </div>
                
                <!-- Hidden canvas for processing -->
                <canvas 
                  id="scannerCanvas"
                  ref="scanCanvas" 
                  class="hidden"
                  aria-hidden="true"
                ></canvas>
              </div>
              
              <!-- Camera Controls -->
              <div class="mt-4 flex items-center justify-between bg-white rounded-xl p-4 shadow-md">
                <div class="flex items-center space-x-3">
                  <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-camera text-blue-600"></i>
                  </div>
                  <div>
                    <p class="text-sm font-semibold text-gray-800">Camera Mode</p>
                    <p class="text-xs text-gray-500">{{ facingMode === 'environment' ? 'Back Camera' : 'Front Camera' }}</p>
                  </div>
                </div>
                <button 
                  @click="toggleFacing" 
                  type="button" 
                  class="px-4 py-2 bg-gradient-to-r from-blue-500 to-teal-500 text-white rounded-lg hover:from-blue-600 hover:to-teal-600 transition-all duration-200 shadow-md hover:shadow-lg flex items-center space-x-2 font-semibold"
                >
                  <i class="fas fa-sync-alt"></i>
                  <span>Flip Camera</span>
                </button>
              </div>
            </div>

            <!-- Right: Upload & Info Panel -->
            <div class="lg:w-96 space-y-4">
              <!-- Upload Section -->
              <div class="bg-gradient-to-br from-blue-50 to-teal-50 rounded-xl p-5 border-2 border-dashed border-blue-300 shadow-md">
                <div class="flex items-center space-x-3 mb-4">
                  <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                    <i class="fas fa-cloud-upload-alt text-white"></i>
                  </div>
                  <div>
                    <h4 class="text-sm font-bold text-gray-800">Upload QR Code</h4>
                    <p class="text-xs text-gray-600">Or choose an image file</p>
                  </div>
                </div>
                
                <input 
                  id="qrUploadInput"
                  name="qrUpload"
                  type="file" 
                  ref="qrFileInput"
                  @change="previewUploadedQr" 
                  accept="image/*"
                  autocomplete="off"
                  aria-label="Upload QR Code Image"
                  class="hidden"
                />
                
                <label 
                  for="qrUploadInput"
                  class="block w-full py-3 px-4 bg-gradient-to-r from-blue-500 to-teal-500 text-white text-center rounded-lg cursor-pointer hover:from-blue-600 hover:to-teal-600 transition-all duration-200 shadow-md hover:shadow-lg font-semibold"
                >
                  <i class="fas fa-folder-open mr-2"></i>
                  Choose Image File
                </label>
                
                <!-- Preview uploaded image -->
                <div v-if="uploadedQrPreview" class="mt-4 animate-fade-in">
                  <div class="relative rounded-lg overflow-hidden bg-white p-2 shadow-md">
                    <img :src="uploadedQrPreview" class="w-full h-40 object-contain rounded" alt="QR Preview" />
                  </div>
                  <button 
                    @click="scanUploadedQr"
                    type="button"
                    class="w-full mt-3 py-3 px-4 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-lg hover:from-green-600 hover:to-emerald-600 transition-all duration-200 shadow-md hover:shadow-lg font-semibold flex items-center justify-center space-x-2"
                  >
                    <i class="fas fa-search"></i>
                    <span>Scan This QR Code</span>
                  </button>
                </div>
                
                <p class="text-xs text-gray-500 mt-3 flex items-start space-x-2">
                  <i class="fas fa-info-circle mt-0.5"></i>
                  <span>Upload a screenshot or photo of a QR code from your device</span>
                </p>
              </div>

              <!-- Last Scanned Info -->
              <div class="bg-white rounded-xl p-5 shadow-md border border-gray-200">
                <div class="flex items-center space-x-3 mb-3">
                  <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-history text-purple-600"></i>
                  </div>
                  <h4 class="text-sm font-bold text-gray-800">Last Scanned</h4>
                </div>
                <div class="bg-gray-50 rounded-lg p-3">
                  <p v-if="scannedCode" class="text-sm text-gray-800 font-mono break-all">{{ scannedCode }}</p>
                  <p v-else class="text-sm text-gray-400 italic">No code scanned yet</p>
                </div>
              </div>

              <!-- Instructions -->
              <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl p-5 border border-amber-200 shadow-md">
                <div class="flex items-start space-x-3">
                  <div class="w-8 h-8 bg-amber-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                    <i class="fas fa-lightbulb text-white text-sm"></i>
                  </div>
                  <div>
                    <h4 class="text-sm font-bold text-gray-800 mb-2">How to Scan</h4>
                    <ul class="text-xs text-gray-600 space-y-1.5">
                      <li class="flex items-start space-x-2">
                        <i class="fas fa-check text-green-500 mt-0.5"></i>
                        <span>Point camera at QR code</span>
                      </li>
                      <li class="flex items-start space-x-2">
                        <i class="fas fa-check text-green-500 mt-0.5"></i>
                        <span>Hold steady for auto-scan</span>
                      </li>
                      <li class="flex items-start space-x-2">
                        <i class="fas fa-check text-green-500 mt-0.5"></i>
                        <span>Or upload an image file</span>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>

              <!-- Done Button -->
              <button 
                @click="closeScannerModal" 
                type="button"
                class="w-full px-4 py-3 bg-gradient-to-r from-gray-600 to-gray-700 text-white rounded-lg hover:from-gray-700 hover:to-gray-800 transition-all duration-200 shadow-md hover:shadow-lg font-semibold flex items-center justify-center space-x-2"
              >
                <i class="fas fa-check-circle"></i>
                <span>Done Scanning</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Toast -->
    <div v-if="showToast" class="fixed bottom-6 right-6 sm:right-6 sm:left-auto left-1/2 transform sm:translate-x-0 -translate-x-1/2 bg-black text-white px-4 py-2 rounded shadow">
      {{ toastMessage }}
    </div>

</template>

<script>
  import { Link, usePage, router } from '@inertiajs/vue3';
  import jsQR from 'jsqr';

  export default {
    components: {
      Link
    },
    props: {
      books: {
        type: Array,
        required: true
      },
      myRequests: {
        type: Array,
        required: false,
        default: () => []
      }
      ,
      notifications: {
        type: Array,
        required: false,
        default: () => []
      }
    },
    data() {
      return {
        searchQuery: '',
        sortBy: 'title-asc',
        ageFilter: null, // null, 'new', 'recent', 'moderate', 'older', 'old'
        showQrModal: false,
        qrModalSvg: '',
        qrModalBookId: null,
        qrModalBookTitle: '',
        showToast: false,
        toastMessage: '',
        isModalOpen: false,
        isProfileModalOpen: false,
  isNotificationModalOpen: false, // Only open when user clicks notification
        isTransactionModalOpen: false,
        isBorrowedModalOpen: false,
        showDropdown: false,
        showProfileDetails: false,
        // Scanner state
        showScannerModal: false,
        scannerActive: false,
        scanStream: null,
        scanIntervalId: null,
        scannedCode: null,
        facingMode: 'environment',
        uploadedQrPreview: null,
        uploadedQrFile: null,
        showSidebar: false,
        selectedBook: null,
        requestType: '',
        startDate: '',
        returnDate: '',
        isCourseFilterActive: false, // Toggle state for course filter
        profileForm: {
          fullname: '',
          email: '',
          phone_number: ''
        }
      }
    },

    mounted() {
      // Close dropdown when clicking outside or pressing Escape
      document.addEventListener('click', this.handleDocumentClick);
      document.addEventListener('keydown', this.handleKeydown);
    },
    beforeUnmount() {
      document.removeEventListener('click', this.handleDocumentClick);
      document.removeEventListener('keydown', this.handleKeydown);
      // ensure scanner is stopped
      this.stopScanner();
    },
    computed: {
      user() {
        return usePage().props.auth.user;
      },
      userInitial() {
        return this.user.fullname.charAt(0);
      },
      filteredBooks() {
        // Step 1: Filter by course if toggle is active
        let filtered = this.books;
        
        if (this.isCourseFilterActive && this.user.course) {
          // When toggle is ON, only show books for user's course
          filtered = filtered.filter(book => 
            book.course && book.course.toLowerCase().includes(this.user.course.toLowerCase())
          );
        }

        // Step 2: Apply search query within the filtered set
        if (this.searchQuery) {
          filtered = filtered.filter(book => 
            book.title.toLowerCase().includes(this.searchQuery.toLowerCase()) || 
            book.author.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
            (book.course && book.course.toLowerCase().includes(this.searchQuery.toLowerCase())) ||
            (book.subject && book.subject.toLowerCase().includes(this.searchQuery.toLowerCase()))
          );
        }

        // Apply age filter
        if (this.ageFilter) {
          filtered = filtered.filter(book => {
            const age = this.getBookAge(book);
            switch (this.ageFilter) {
              case 'new':
                return age <= 2;
              case 'recent':
                return age >= 3 && age <= 4;
              case 'moderate':
                return age >= 5 && age <= 6;
              case 'older':
                return age >= 7 && age <= 9;
              case 'old':
                return age >= 10;
              default:
                return true;
            }
          });
        }

        // Apply sorting
        const sorted = [...filtered];
        switch (this.sortBy) {
          case 'title-asc':
            sorted.sort((a, b) => a.title.localeCompare(b.title));
            break;
          case 'title-desc':
            sorted.sort((a, b) => b.title.localeCompare(a.title));
            break;
          case 'author-asc':
            sorted.sort((a, b) => a.author.localeCompare(b.author));
            break;
          case 'author-desc':
            sorted.sort((a, b) => b.author.localeCompare(a.author));
            break;
          case 'course-asc':
            sorted.sort((a, b) => (a.course || '').localeCompare(b.course || ''));
            break;
          case 'course-desc':
            sorted.sort((a, b) => (b.course || '').localeCompare(a.course || ''));
            break;
          case 'availability':
            // Sort by availability: Available -> Reserved -> Borrowed
            const order = { 'Available': 1, 'Reserved': 2, 'Borrowed': 3 };
            sorted.sort((a, b) => (order[a.availability] || 999) - (order[b.availability] || 999));
            break;
          case 'age-newest':
            sorted.sort((a, b) => this.getBookAge(a) - this.getBookAge(b));
            break;
          case 'age-oldest':
            sorted.sort((a, b) => this.getBookAge(b) - this.getBookAge(a));
            break;
        }
        return sorted;
      },
      unreadNotificationsCount() {
        if (!this.notifications || !Array.isArray(this.notifications)) return 0;
        return this.notifications.filter(n => !n.read).length;
      },
      // Minimum allowed return date depending on request type
      returnDateMin() {
        if (!this.startDate) return null;
        // parse startDate as local date parts to avoid timezone shifts
        const parts = this.startDate.split('-');
        if (parts.length !== 3) return this.startDate;
        const y = parseInt(parts[0], 10);
        const m = parseInt(parts[1], 10) - 1;
        const d = parseInt(parts[2], 10);
        if (this.requestType === 'reserve') {
          const next = new Date(y, m, d + 1);
          const yyyy = next.getFullYear();
          const mm = String(next.getMonth() + 1).padStart(2, '0');
          const dd = String(next.getDate()).padStart(2, '0');
          return `${yyyy}-${mm}-${dd}`;
        }
        // borrow: min is the same day
        return this.startDate;
      }
    },
    watch: {
      requestType(newType) {
        if (newType === 'borrow' && this.startDate) {
          this.setReturnDateForBorrow();
        } else if (newType === 'reserve' && this.startDate) {
          this.setReturnDateForReserve();
        }
      },
      startDate(newDate) {
        if (this.requestType === 'borrow' && newDate) {
          this.setReturnDateForBorrow();
        } else if (this.requestType === 'reserve' && newDate) {
          this.setReturnDateForReserve();
        }
      }
    },
    methods: {
      toggleDropdown() {
        this.showDropdown = !this.showDropdown;
        if (!this.showDropdown) this.showProfileDetails = false;
      },
      toggleProfileDetails() {
        this.showProfileDetails = !this.showProfileDetails;
      },
      handleDocumentClick(e) {
        // If dropdown is open and click is outside dropdown and profile button, close it
        try {
          const dd = this.$refs.dropdown;
          const btn = this.$refs.profileBtn;
          if (this.showDropdown && dd && btn && !dd.contains(e.target) && !btn.contains(e.target)) {
            this.showDropdown = false;
            this.showProfileDetails = false;
          }
        } catch (err) {
          // ignore
        }
      },
      handleKeydown(e) {
        if (e.key === 'Escape' && this.showDropdown) {
          this.showDropdown = false;
          this.showProfileDetails = false;
        }
      },
      /***** Scanner methods *****/
      async openScannerModal() {
        this.showScannerModal = true;
        // small delay to let modal render
        this.$nextTick(() => this.startScanner());
      },
      async closeScannerModal() {
        this.showScannerModal = false;
        this.uploadedQrPreview = null;
        this.uploadedQrFile = null;
        await this.stopScanner();
      },
      async loadJsQR() {
        if (window.jsQR) return window.jsQR;
        return new Promise((resolve, reject) => {
          const s = document.createElement('script');
          s.src = 'https://unpkg.com/jsqr/dist/jsQR.js';
          s.onload = () => resolve(window.jsQR);
          s.onerror = reject;
          document.head.appendChild(s);
        });
      },
      async startScanner() {
        if (this.scannerActive) return;
        try {
          const jsQR = await this.loadJsQR();
          const constraints = { video: { facingMode: this.facingMode, width: { ideal: 1280 }, height: { ideal: 720 } } };
          const stream = await navigator.mediaDevices.getUserMedia(constraints);
          this.scanStream = stream;
          const video = this.$refs.scanVideo;
          video.srcObject = stream;
          await video.play();
          this.scannerActive = true;

          const canvas = this.$refs.scanCanvas;
          const ctx = canvas.getContext('2d');

          this.scanIntervalId = setInterval(() => {
            if (video.readyState !== video.HAVE_ENOUGH_DATA) return;
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            try {
              const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
              const code = jsQR(imageData.data, imageData.width, imageData.height);
              if (code && code.data) {
                this.scannedCode = code.data;
                console.log('QR Code Scanned:', this.scannedCode);
                
                // Find the book by bookId from the scanned QR code (trim to remove whitespace)
                const scannedBookId = String(this.scannedCode).trim();
                console.log('Looking for book with ID:', scannedBookId);
                console.log('Available books:', this.books.map(b => ({ id: b.id, bookId: b.bookId, title: b.title })));
                
                const scannedBook = this.books.find(book => String(book.bookId).trim() === scannedBookId);
                
                if (scannedBook) {
                  // Book found! Open the book request modal
                  console.log('Book found!', scannedBook);
                  this.showToast = true;
                  this.toastMessage = `Book found: ${scannedBook.title}`;
                  setTimeout(() => (this.showToast = false), 2500);
                  
                  // Close scanner and open book modal
                  this.closeScannerModal();
                  
                  // Open the book request modal with the scanned book
                  setTimeout(() => {
                    this.openModal(scannedBook);
                  }, 300);
                } else {
                  // Book not found, just set search query
                  console.log('Book NOT found for ID:', scannedBookId);
                  this.searchQuery = this.scannedCode;
                  this.showToast = true;
                  this.toastMessage = 'Code scanned but book not found. Scanned: ' + scannedBookId;
                  setTimeout(() => (this.showToast = false), 4000);
                  this.closeScannerModal();
                }
              }
            } catch (err) {
              console.error('Scanner error:', err);
              // ignore occasional errors
            }
          }, 250);
        } catch (err) {
          console.error('Scanner start failed', err);
          this.showToast = true;
          this.toastMessage = 'Unable to access camera. Please allow camera permission.';
          setTimeout(() => (this.showToast = false), 3000);
          this.showScannerModal = false;
        }
      },
      async toggleFacing() {
        // flip between environment and user
        this.facingMode = this.facingMode === 'environment' ? 'user' : 'environment';
        // restart scanner with new facing mode
        if (this.scannerActive) {
          await this.stopScanner();
          // small delay to allow camera to stop
          setTimeout(() => this.startScanner(), 300);
        }
      },
      async stopScanner() {
        try {
          if (this.scanIntervalId) {
            clearInterval(this.scanIntervalId);
            this.scanIntervalId = null;
          }
          if (this.scanStream) {
            this.scanStream.getTracks().forEach(t => t.stop());
            this.scanStream = null;
          }
          this.scannerActive = false;
        } catch (err) {
          // ignore
        }
      },
      previewUploadedQr(event) {
        const file = event.target.files[0];
        if (!file) return;

        // Store the file for later scanning
        this.uploadedQrFile = file;

        // Create preview
        const reader = new FileReader();
        reader.onload = (e) => {
          this.uploadedQrPreview = e.target.result;
        };
        reader.readAsDataURL(file);
      },
      async scanUploadedQr() {
        if (!this.uploadedQrFile) {
          this.showToast = true;
          this.toastMessage = 'Please select an image first';
          setTimeout(() => (this.showToast = false), 2000);
          return;
        }

        try {
          // Create an image element
          const img = new Image();
          img.onload = () => {
            // Create a canvas to read the image
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            canvas.width = img.width;
            canvas.height = img.height;
            ctx.drawImage(img, 0, 0);

            // Try to decode QR code from the image
            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            const code = jsQR(imageData.data, imageData.width, imageData.height);

            if (code && code.data) {
              this.scannedCode = code.data;
              console.log('QR Code from uploaded image:', this.scannedCode);

              // Find the book by bookId
              const scannedBookId = String(this.scannedCode).trim();
              console.log('Looking for book with ID:', scannedBookId);
              
              const scannedBook = this.books.find(book => String(book.bookId).trim() === scannedBookId);

              if (scannedBook) {
                // Book found! Open the book request modal
                console.log('Book found!', scannedBook);
                this.showToast = true;
                this.toastMessage = `Book found: ${scannedBook.title}`;
                setTimeout(() => (this.showToast = false), 2500);

                // Close scanner and open book modal
                this.closeScannerModal();
                
                // Reset upload state
                this.uploadedQrPreview = null;
                this.uploadedQrFile = null;

                // Open the book request modal with the scanned book
                setTimeout(() => {
                  this.openModal(scannedBook);
                }, 300);
              } else {
                // Book not found
                console.log('Book NOT found for ID:', scannedBookId);
                this.showToast = true;
                this.toastMessage = 'Book not found. Scanned ID: ' + scannedBookId;
                setTimeout(() => (this.showToast = false), 4000);
              }
            } else {
              // No QR code detected in the image
              this.showToast = true;
              this.toastMessage = 'No QR code detected in the image. Please try another image.';
              setTimeout(() => (this.showToast = false), 3000);
            }

            // Reset file input
            if (this.$refs.qrFileInput) {
              this.$refs.qrFileInput.value = '';
            }
          };

          img.src = this.uploadedQrPreview;
        } catch (error) {
          console.error('Error processing QR image:', error);
          this.showToast = true;
          this.toastMessage = 'Error processing image. Please try again.';
          setTimeout(() => (this.showToast = false), 3000);
        }
      },
      async copyStudentId() {
        const id = this.user && this.user.studentid ? String(this.user.studentid) : '';
        if (!id) return;
        try {
          if (navigator.clipboard && navigator.clipboard.writeText) {
            await navigator.clipboard.writeText(id);
          } else {
            const ta = document.createElement('textarea');
            ta.value = id;
            document.body.appendChild(ta);
            ta.select();
            document.execCommand('copy');
            ta.remove();
          }
          this.showToast = true;
          this.toastMessage = 'Student ID copied to clipboard.';
          setTimeout(() => (this.showToast = false), 2000);
        } catch (err) {
          console.error(err);
        }
      },
      toggleSidebar() {
        this.showSidebar = !this.showSidebar;
      },
      filterByCourse() {
        if (!this.user.course) {
          this.showToast = true;
          this.toastMessage = 'No course assigned to your account';
          setTimeout(() => (this.showToast = false), 2500);
          return;
        }

        // Toggle the filter state
        this.isCourseFilterActive = !this.isCourseFilterActive;

        if (this.isCourseFilterActive) {
          // Turn ON: Filter by user's course only
          this.showToast = true;
          this.toastMessage = `Showing books for ${this.user.course} only`;
          setTimeout(() => (this.showToast = false), 2500);
        } else {
          // Turn OFF: Show all books
          this.showToast = true;
          this.toastMessage = 'Showing all books';
          setTimeout(() => (this.showToast = false), 2500);
        }

        // Scroll to the books section
        const booksSection = document.querySelector('main');
        if (booksSection) {
          booksSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
      },
      openNotificationModal() {
        this.isNotificationModalOpen = true;
        this.showSidebar = false;
      },
      closeNotificationModal() {
        this.isNotificationModalOpen = false;
      },
      openTransactionModal() {
        this.isTransactionModalOpen = true;
        this.showSidebar = false;
      },
      closeTransactionModal() {
        this.isTransactionModalOpen = false;
      },
      openBorrowedModal() {
        this.isBorrowedModalOpen = true;
        this.showSidebar = false;
      },
      closeBorrowedModal() {
        this.isBorrowedModalOpen = false;
      },
      openModal(book) {
        this.selectedBook = book;
        // Reset form when opening modal
        this.requestType = '';
        this.startDate = '';
        this.returnDate = '';
        this.isModalOpen = true;
        console.log('Modal opened for book:', book.title);
      },
      onStartDateChange() {
        if (!this.startDate) return;
        if (this.requestType === 'borrow') this.setReturnDateForBorrow();
        else if (this.requestType === 'reserve') this.setReturnDateForReserve();
      },
      closeModal() {
        this.isModalOpen = false;
        this.selectedBook = null;
        // Clear form fields
        this.requestType = '';
        this.startDate = '';
        this.returnDate = '';
      },
      submitRequest() {
    if (this.requestType === 'reserve') {
    router.post(route('student.reserve-book'), {
      book_id: this.selectedBook.id,
      reservation_date: this.startDate,
      return_date: this.returnDate
    }, {
      onSuccess: () => {
        this.closeModal();
        this.showToast = true;
        this.toastMessage = 'Reservation submitted successfully!';
        setTimeout(() => (this.showToast = false), 3000);
      },
      onError: (errors) => {
        console.error(errors);
        const msg = (errors && errors[Object.keys(errors)[0]] && errors[Object.keys(errors)[0]][0]) || 'Failed to submit reservation';
        this.showToast = true;
        this.toastMessage = msg;
        setTimeout(() => (this.showToast = false), 4000);
      }
    });
  } else if (this.requestType === 'borrow') {
    router.post(route('book-requests.store'), {
      book_id: this.selectedBook.id,
      request_type: 'borrow',
      request_date: this.startDate,
      return_date: this.returnDate
    }, {
      onSuccess: () => {
        this.closeModal();
        this.showToast = true;
        this.toastMessage = 'Borrow request submitted successfully!';
        setTimeout(() => (this.showToast = false), 3000);
      },
      onError: (errors) => {
        console.error(errors);
        const msg = (errors && errors[Object.keys(errors)[0]] && errors[Object.keys(errors)[0]][0]) || 'Failed to submit request';
        this.showToast = true;
        this.toastMessage = msg;
        setTimeout(() => (this.showToast = false), 4000);
      }
    });
  }
      },
      openProfileModal() {
        this.profileForm.fullname = this.user.fullname;
        this.profileForm.email = this.user.email;
        this.profileForm.phone_number = this.user.phone_number;
        this.isProfileModalOpen = true;
      },
      closeProfileModal() {
        this.isProfileModalOpen = false;
      },
      updateProfile() {
        router.put(route('profile.update.basic'), this.profileForm, {
          onSuccess: () => {
            this.closeProfileModal();
            // You can add a success notification here if you want
          },
          onError: (errors) => {
            // Handle errors if needed
            console.log(errors);
          }
        });
      },
      async viewQrCode(bookId, title) {
        try {
          console.log('Fetching QR code for book ID:', bookId);
          const res = await fetch(`/books/${bookId}/qrcode`, {
            headers: { 
              'Accept': 'application/json',
              'X-Requested-With': 'XMLHttpRequest'
            }
          });
          
          console.log('Response status:', res.status);
          
          if (!res.ok) {
            const errorData = await res.json().catch(() => ({}));
            console.error('Server error:', errorData);
            throw new Error(errorData.error || 'Failed to fetch QR code');
          }
          
          const data = await res.json();
          console.log('QR code data received:', {
            hasQrCodeSvg: !!data.qrCodeSvg,
            svgLength: data.qrCodeSvg?.length,
            svgPreview: data.qrCodeSvg?.substring(0, 100),
            bookId: data.bookId,
            bookTitle: data.bookTitle
          });
          
          // Check for error in response
          if (data.error) {
            console.error('QR code error:', data.error);
            throw new Error(data.error);
          }
          
          // Verify we have valid SVG data
          if (!data.qrCodeSvg || typeof data.qrCodeSvg !== 'string') {
            console.error('Invalid QR code data:', data);
            throw new Error('Invalid QR code data received');
          }
          
          // Verify SVG content
          const svgContent = data.qrCodeSvg.trim();
          if (!svgContent.startsWith('<?xml') && !svgContent.startsWith('<svg')) {
            console.error('Invalid SVG format. Content starts with:', svgContent.substring(0, 50));
            throw new Error('Invalid SVG format received');
          }
          
          this.qrModalSvg = data.qrCodeSvg;
          this.qrModalBookId = bookId;
          this.qrModalBookTitle = title;
          this.showQrModal = true;
          console.log('QR modal opened successfully');
        } catch (e) {
          console.error('viewQrCode error:', e);
          this.showToast = true;
          this.toastMessage = 'Unable to load QR code: ' + e.message;
          setTimeout(() => (this.showToast = false), 3000);
        }
      },
      buildNotificationText(note, variant = 'short') {
        // If server already provided a message, prefer it (short for preview)
        if (note.message && variant === 'short') return note.message;

        const payload = (note.payload && typeof note.payload === 'object') ? note.payload : (note.data && typeof note.data === 'object' ? note.data : {});
        const book = payload.book_title || payload.book || '';
        const userName = payload.user_name || (this.user && this.user.fullname) || 'You';
        const reqType = payload.request_type || payload.requestType || payload.type || 'request';
        const reqDate = payload.request_date || payload.requestDate || payload.start_date || payload.requested_at;
        const startDate = payload.start_date || payload.startDate || payload.request_date || reqDate;
        const dueDate = payload.due_date || payload.return_date || payload.dueDate;
        const untilDate = payload.until_date || payload.untilDate || payload.available_until;
        const daysLeft = payload.days_left || payload.daysLeft;
        const reason = payload.reason || payload.note || '';
        const fine = payload.fine_amount || payload.fineAmount || payload.fine || '';

        const short = (text) => text;
        const long = (text) => text;

        switch ((note.type || '').toLowerCase()) {
          case 'request_submitted':
          case 'request_submitted_notification':
          case 'request_submitted':
            return variant === 'short'
              ? short(`Request received — ${book} (${reqType}) is pending review.`)
              : long(`${userName}, your ${reqType} request for "${book}" starting ${this.formatDate(reqDate)} is received and is pending approval.`);
          case 'request_approved':
            return variant === 'short'
              ? short(`Request approved: ${book} — pick-up/borrow starts ${this.formatDate(startDate)}.`)
              : long(`Good news, ${userName}! Your ${reqType} request for "${book}" has been approved. Start date: ${this.formatDate(startDate)}. Due: ${this.formatDate(dueDate)}.`);
          case 'request_rejected':
            return variant === 'short'
              ? short(`Request declined: ${book}.`)
              : long(`We're sorry, ${userName}. Your ${reqType} request for "${book}" (start: ${this.formatDate(reqDate)}) was declined. Reason: ${reason}. Contact admin for details.`);
          case 'reservation_ready':
          case 'reservation_ready_notification':
            return variant === 'short'
              ? short(`Reservation ready: ${book} is available.`)
              : long(`Hi ${userName}, the book you reserved — "${book}" — is now available for pickup. Please collect it by ${this.formatDate(untilDate)}.`);
          case 'due_soon':
            return variant === 'short'
              ? short(`Due in ${daysLeft || 3} days: ${book}`)
              : long(`Reminder: "${book}" is due on ${this.formatDate(dueDate)} (in ${daysLeft || 3} days). Please return or extend to avoid fines.`);
          case 'due_tomorrow':
            return variant === 'short'
              ? short(`Due tomorrow: ${book}`)
              : long(`Heads up — "${book}" is due tomorrow (${this.formatDate(dueDate)}). Return or contact the library to extend your loan.`);
          case 'overdue':
            return variant === 'short'
              ? short(`Overdue: ${book}`)
              : long(`Overdue notice: "${book}" was due on ${this.formatDate(dueDate)}. Please return it immediately to avoid fines. Current fine: ${fine}.`);
          case 'book_returned':
            return variant === 'short'
              ? short(`Returned: ${book}`)
              : long(`Your borrowed book "${book}" was marked returned on ${this.formatDate(dueDate || payload.returned_at)}.`);
          default:
            // fallback to server message or a generic rendering of payload
            if (note.message) return note.message;
            if (payload && Object.keys(payload).length) return JSON.stringify(payload);
            return '';
        }
      },
      formatDateTime(date) {
        if (!date) return '';
        try {
          const d = new Date(date);
          return d.toLocaleString('en-US', { year: 'numeric', month: 'short', day: 'numeric', hour: 'numeric', minute: '2-digit' });
        } catch (e) {
          return date;
        }
      },
      downloadQr() {
        if (!this.qrModalSvg) return;

        try {
          // Create a temporary container for the SVG
          const container = document.createElement('div');
          container.innerHTML = this.qrModalSvg;
          const svgElement = container.querySelector('svg');
          
          if (!svgElement) {
            this.showToast = true;
            this.toastMessage = 'Invalid QR code format';
            setTimeout(() => (this.showToast = false), 3000);
            return;
          }

          // Get SVG dimensions
          const svgWidth = svgElement.width.baseVal.value || 200;
          const svgHeight = svgElement.height.baseVal.value || 200;

          // Create canvas
          const canvas = document.createElement('canvas');
          canvas.width = svgWidth;
          canvas.height = svgHeight;
          const ctx = canvas.getContext('2d');

          // Create image from SVG
          const svgBlob = new Blob([this.qrModalSvg], { type: 'image/svg+xml;charset=utf-8' });
          const url = URL.createObjectURL(svgBlob);
          const img = new Image();

          img.onload = () => {
            // Draw white background
            ctx.fillStyle = 'white';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            
            // Draw SVG on canvas
            ctx.drawImage(img, 0, 0);

            // Convert canvas to PNG and download
            canvas.toBlob((blob) => {
              const downloadUrl = URL.createObjectURL(blob);
              const a = document.createElement('a');
              const safeTitle = (this.qrModalBookTitle || 'book').replace(/[^a-z0-9-_]/gi, '_').toLowerCase();
              a.href = downloadUrl;
              a.download = `${safeTitle}-${this.qrModalBookId || 'qr'}.png`;
              document.body.appendChild(a);
              a.click();
              a.remove();
              URL.revokeObjectURL(downloadUrl);
              URL.revokeObjectURL(url);
              
              this.showToast = true;
              this.toastMessage = 'QR code downloaded as PNG.';
              setTimeout(() => (this.showToast = false), 3000);
            }, 'image/png');
          };

          img.onerror = () => {
            this.showToast = true;
            this.toastMessage = 'Failed to convert QR code to image';
            setTimeout(() => (this.showToast = false), 3000);
            URL.revokeObjectURL(url);
          };

          img.src = url;
        } catch (error) {
          console.error('Error downloading QR code:', error);
          this.showToast = true;
          this.toastMessage = 'Failed to download QR code';
          setTimeout(() => (this.showToast = false), 3000);
        }
      },
      setReturnDateForBorrow() {
        if (this.startDate) {
          // parse as local date parts (avoid Date("YYYY-MM-DD") timezone issues)
          const parts = this.startDate.split('-');
          if (parts.length === 3) {
            const y = parseInt(parts[0], 10);
            const m = parseInt(parts[1], 10) - 1;
            const d = parseInt(parts[2], 10);
            const start = new Date(y, m, d);
            start.setDate(start.getDate() + 7);
            const yyyy = start.getFullYear();
            const mm = String(start.getMonth() + 1).padStart(2, '0');
            const dd = String(start.getDate()).padStart(2, '0');
            this.returnDate = `${yyyy}-${mm}-${dd}`;
          }
        }
      },
      setReturnDateForReserve() {
        if (this.startDate) {
          const parts = this.startDate.split('-');
          if (parts.length === 3) {
            const y = parseInt(parts[0], 10);
            const m = parseInt(parts[1], 10) - 1;
            const d = parseInt(parts[2], 10);
            const next = new Date(y, m, d + 1);
            const yyyy = next.getFullYear();
            const mm = String(next.getMonth() + 1).padStart(2, '0');
            const dd = String(next.getDate()).padStart(2, '0');
            this.returnDate = `${yyyy}-${mm}-${dd}`;
          }
        }
      },
      formatDate(date) {
        if (!date) return '';
        return new Date(date).toLocaleDateString('en-US', {
          year: 'numeric',
          month: 'short',
          day: 'numeric',
        });
      },
      toggleReadStatus(notification) {
        // Toggle read/unread status
        router.post(route('notifications.toggle-read', notification.id), {}, {
          preserveScroll: true,
          preserveState: true,
          onSuccess: () => {
            // Inertia will automatically update the data
          },
          onError: (errors) => {
            console.error('Failed to toggle notification status:', errors);
            this.showToast = true;
            this.toastMessage = 'Failed to update notification';
            setTimeout(() => (this.showToast = false), 3000);
          }
        });
      },
      markAllAsRead() {
        router.post(route('notifications.mark-all-read'), {}, {
          preserveScroll: true,
          preserveState: true,
          onSuccess: () => {
            this.showToast = true;
            this.toastMessage = 'All notifications marked as read';
            setTimeout(() => (this.showToast = false), 2000);
          },
          onError: (errors) => {
            console.error('Failed to mark all as read:', errors);
            this.showToast = true;
            this.toastMessage = 'Failed to mark notifications as read';
            setTimeout(() => (this.showToast = false), 3000);
          }
        });
      },
      deleteNotification(notificationId) {
        if (!confirm('Are you sure you want to delete this notification?')) {
          return;
        }
        
        router.delete(route('notifications.destroy', notificationId), {
          preserveScroll: true,
          preserveState: true,
          onSuccess: () => {
            this.showToast = true;
            this.toastMessage = 'Notification deleted';
            setTimeout(() => (this.showToast = false), 2000);
          },
          onError: (errors) => {
            console.error('Failed to delete notification:', errors);
            this.showToast = true;
            this.toastMessage = 'Failed to delete notification';
            setTimeout(() => (this.showToast = false), 3000);
          }
        });
      },
      getBookAgeBorderClass(book) {
        if (!book.publicationDate) {
          return 'border-gray-400 hover:border-gray-500'; // Default if no publication date
        }

        const ageInYears = this.getBookAge(book);

        // Color coding based on book age
        if (ageInYears <= 2) {
          return 'border-green-500 hover:border-green-600'; // 0-2 years: Green (New)
        } else if (ageInYears <= 4) {
          return 'border-blue-500 hover:border-blue-600'; // 3-4 years: Blue (Recent)
        } else if (ageInYears <= 6) {
          return 'border-yellow-500 hover:border-yellow-600'; // 5-6 years: Yellow (Moderate)
        } else if (ageInYears <= 9) {
          return 'border-orange-500 hover:border-orange-600'; // 7-9 years: Orange (Older)
        } else {
          return 'border-red-500 hover:border-red-600'; // 10+ years: Red (Old)
        }
      },
      getBookAge(book) {
        if (!book.publicationDate) return 999; // Return high number for books without publication date

        const pubDate = new Date(book.publicationDate);
        const currentDate = new Date();
        const ageInYears = currentDate.getFullYear() - pubDate.getFullYear();

        // Adjust if birthday hasn't occurred this year
        const adjustedAge = (currentDate.getMonth() < pubDate.getMonth() || 
                            (currentDate.getMonth() === pubDate.getMonth() && currentDate.getDate() < pubDate.getDate())) 
                            ? ageInYears - 1 : ageInYears;

        return adjustedAge;
      },
      toggleAgeFilter(filter) {
        // Toggle filter: if same filter is clicked, turn it off; otherwise, set new filter
        this.ageFilter = this.ageFilter === filter ? null : filter;
      },
      clearAgeFilter() {
        this.ageFilter = null;
      }
    }
  }
</script>

<style scoped>
  /* Custom Tailwind CSS */
  .animate-fade-in {
    animation: fadeIn 0.3s ease-out;
  }

  @keyframes fadeIn {
    0% { opacity: 0; }
    100% { opacity: 1; }
  }

  /* Scanning line animation */
  @keyframes scan {
    0%, 100% { 
      transform: translateY(-100%);
      opacity: 0;
    }
    50% { 
      opacity: 1;
    }
    100% {
      transform: translateY(100%);
    }
  }

  .animate-scan {
    animation: scan 2s ease-in-out infinite;
  }

  /* Custom scrollbar styling */
  ::-webkit-scrollbar {
    width: 8px;
    height: 8px;
  }

  ::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 10px;
  }

  ::-webkit-scrollbar-thumb {
    background: linear-gradient(180deg, #3b82f6, #14b8a6);
    border-radius: 10px;
  }

  ::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(180deg, #2563eb, #0d9488);
  }

  /* Firefox scrollbar */
  * {
    scrollbar-width: thin;
    scrollbar-color: #3b82f6 #f1f5f9;
  }

  /* Make the books grid adapt for very small phones */
  @media (max-width: 360px) {
    .books-grid {
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 0.5rem;
    }
    /* Slightly reduce cover height on tiny screens to avoid overly tall cards */
    .book-cover {
      height: 12rem; /* 192px (h-48) */
    }
  }

  /* Two-line clamp with ellipsis for long titles */
  .line-clamp-2 {
    overflow: hidden;
    display: -webkit-box;
    /* non-standard fallback for modern browsers / linters */
    line-clamp: 2;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    line-height: 1.15em; /* keep consistent spacing */
  }

  /* Glowing border button animation */
  .glow-button {
    position: relative;
    animation: glow-pulse 2s ease-in-out infinite;
  }

  .glow-button::before {
    content: '';
    position: absolute;
    inset: -2px;
    background: linear-gradient(45deg, #3b82f6, #8b5cf6, #ec4899, #3b82f6);
    background-size: 300% 300%;
    border-radius: 0.5rem;
    z-index: -1;
    opacity: 0.7;
    filter: blur(8px);
    animation: glow-rotate 3s linear infinite;
  }

  /* Active state with green glow */
  .glow-button-active {
    position: relative;
    animation: glow-pulse-active 2s ease-in-out infinite;
  }

  .glow-button-active::before {
    content: '';
    position: absolute;
    inset: -2px;
    background: linear-gradient(45deg, #10b981, #059669, #34d399, #10b981);
    background-size: 300% 300%;
    border-radius: 0.5rem;
    z-index: -1;
    opacity: 0.7;
    filter: blur(8px);
    animation: glow-rotate 3s linear infinite;
  }

  @keyframes glow-pulse {
    0%, 100% {
      box-shadow: 0 0 20px rgba(59, 130, 246, 0.5), 0 0 40px rgba(139, 92, 246, 0.3);
    }
    50% {
      box-shadow: 0 0 30px rgba(59, 130, 246, 0.8), 0 0 60px rgba(139, 92, 246, 0.5);
    }
  }

  @keyframes glow-pulse-active {
    0%, 100% {
      box-shadow: 0 0 20px rgba(16, 185, 129, 0.5), 0 0 40px rgba(5, 150, 105, 0.3);
    }
    50% {
      box-shadow: 0 0 30px rgba(16, 185, 129, 0.8), 0 0 60px rgba(5, 150, 105, 0.5);
    }
  }

  @keyframes glow-rotate {
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
</style>

