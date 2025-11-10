<template>
  <Layout>
    <div class="ml-64 p-8 flex-1 bg-gradient-to-br from-gray-50 to-gray-200 min-h-screen">
      <main class="p-6 bg-white shadow rounded-lg">
        <!-- Search Bar and Sort -->
        <div class="flex flex-col md:flex-row gap-4 mb-6">
          <!-- Search Bar -->
          <div class="flex items-center bg-gray-100 p-4 rounded-lg shadow-inner flex-1">
            <img src="/img/icon.png" class="w-6 h-6 mr-3 opacity-70" alt="Search Icon" />
            <input
              type="text"
              v-model="searchQuery"
              placeholder="Search by title, author, or course..."
              class="w-full p-3 bg-transparent text-gray-700 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded"
            />
          </div>
          
          <!-- Sort Dropdown -->
          
        </div>

        <!-- Add tabs for different views -->
        <div class="mb-6 flex space-x-4">
          <button 
            v-for="tab in tabs" 
            :key="tab.value"
            @click="activeTab = tab.value"
            :class="[
              'px-4 py-2 rounded-lg',
              activeTab === tab.value ? 'bg-blue-500 text-white' : 'bg-gray-200'
            ]"
          >
            {{ tab.label }}
            <span v-if="tab.value !== 'history'" class="ml-2 bg-white text-blue-500 rounded-full px-2 py-1 text-xs">
              {{ getTabCount(tab.value) }}
            </span>
          </button>
        </div>

        <!-- Color Legend Filter (only show in books tab) -->
        <div v-if="activeTab === 'books'" class="mb-6 bg-white p-4 rounded-lg shadow-md border border-gray-200">
          <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
            <i class="fas fa-filter mr-2 text-blue-500"></i>
            📚 Book Age Color Guide:
          </h3>
          <div class="flex flex-wrap gap-3 text-sm">
            <div v-if="activeTab === 'books'" class="flex items-center gap-2">
            <label class="text-sm font-medium text-gray-700 whitespace-nowrap">
              <i class="fas fa-sort mr-1"></i> Sort By:
            </label>
            <select 
              v-model="sortBy"
              class="px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-700 text-sm min-w-[200px]"
            >
              <option value="title-asc">Title (A-Z)</option>
              <option value="title-desc">Title (Z-A)</option>
              <option value="author-asc">Author (A-Z)</option>
              <option value="author-desc">Author (Z-A)</option>
              <option value="course-asc">Course (A-Z)</option>
              <option value="course-desc">Course (Z-A)</option>
              <option value="amount-asc">Amount (Low to High)</option>
              <option value="amount-desc">Amount (High to Low)</option>
              <option value="date-newest">Date Acquired (Newest)</option>
              <option value="date-oldest">Date Acquired (Oldest)</option>
              <option value="age-newest">Book Age (Newest)</option>
              <option value="age-oldest">Book Age (Oldest)</option>
            </select>
          </div>
            <button
              @click="filterByAge('green')"
              type="button"
              :class="[
                'flex items-center space-x-2 px-3 py-2 rounded-lg transition-all',
                selectedAgeFilter === 'green' ? 'bg-green-100 ring-2 ring-green-500' : 'hover:bg-gray-100'
              ]"
            >
              <div class="w-6 h-6 border-4 border-green-500 rounded flex-shrink-0"></div>
              <span class="text-gray-700">0-2 years (New)</span>
            </button>
            <button
              @click="filterByAge('blue')"
              type="button"
              :class="[
                'flex items-center space-x-2 px-3 py-2 rounded-lg transition-all',
                selectedAgeFilter === 'blue' ? 'bg-blue-100 ring-2 ring-blue-500' : 'hover:bg-gray-100'
              ]"
            >
              <div class="w-6 h-6 border-4 border-blue-500 rounded flex-shrink-0"></div>
              <span class="text-gray-700">3-4 years</span>
            </button>
            <button
              @click="filterByAge('yellow')"
              type="button"
              :class="[
                'flex items-center space-x-2 px-3 py-2 rounded-lg transition-all',
                selectedAgeFilter === 'yellow' ? 'bg-yellow-100 ring-2 ring-yellow-500' : 'hover:bg-gray-100'
              ]"
            >
              <div class="w-6 h-6 border-4 border-yellow-500 rounded flex-shrink-0"></div>
              <span class="text-gray-700">5-6 years</span>
            </button>
            <button
              @click="filterByAge('orange')"
              type="button"
              :class="[
                'flex items-center space-x-2 px-3 py-2 rounded-lg transition-all',
                selectedAgeFilter === 'orange' ? 'bg-orange-100 ring-2 ring-orange-500' : 'hover:bg-gray-100'
              ]"
            >
              <div class="w-6 h-6 border-4 border-orange-500 rounded flex-shrink-0"></div>
              <span class="text-gray-700">7-9 years</span>
            </button>
            <button
              @click="filterByAge('red')"
              type="button"
              :class="[
                'flex items-center space-x-2 px-3 py-2 rounded-lg transition-all',
                selectedAgeFilter === 'red' ? 'bg-red-100 ring-2 ring-red-500' : 'hover:bg-gray-100'
              ]"
            >
              <div class="w-6 h-6 border-4 border-red-500 rounded flex-shrink-0"></div>
              <span class="text-gray-700">10+ years (Old)</span>
            </button>
            <button
              v-if="selectedAgeFilter"
              @click="clearAgeFilter"
              type="button"
              class="flex items-center space-x-2 px-3 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 transition-all"
            >
              <i class="fas fa-times"></i>
              <span class="text-gray-700 font-semibold">Clear Filter</span>
            </button>
            
          </div>
        </div>

        <!-- Books Table with enhanced status display -->
        <div v-if="activeTab === 'books'" class="overflow-x-auto shadow-md rounded-lg">
        <table class="min-w-full bg-white">
          <thead class="bg-gradient-to-r from-blue-500 to-blue-600 text-white text-sm uppercase tracking-wider">
            <tr>
              <th class="px-6 py-3 text-left font-semibold whitespace-nowrap">Cover</th>
              <th class="px-6 py-3 text-left font-semibold whitespace-nowrap">Title</th>
              <th class="px-6 py-3 text-left font-semibold whitespace-nowrap">Author</th>
              <th class="px-6 py-3 text-left font-semibold whitespace-nowrap">Course</th>
              <th class="px-6 py-3 text-left font-semibold whitespace-nowrap">Subject For</th>
              <th class="px-6 py-3 text-left font-semibold whitespace-nowrap">Amount</th>
              <th class="px-6 py-3 text-left font-semibold whitespace-nowrap">Date Acquired</th>
              <th class="px-6 py-3 text-left font-semibold whitespace-nowrap">Book ID</th>
              <th class="px-6 py-3 text-left font-semibold whitespace-nowrap">Availability</th>
              <th class="px-6 py-3 text-center font-semibold whitespace-nowrap">QR Code</th>
              <th class="px-6 py-3 text-center font-semibold whitespace-nowrap">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <tr v-for="book in filteredBooks" :key="book.id" :style="{ borderLeft: '4px solid ' + getBookAgeBorderColorHex(book.publicationDate) }" class="hover:bg-gray-50 transition-colors">
              <!-- Book Cover -->
              <td class="px-6 py-4">
                <img 
                :src="book.image_url || 'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22100%22 height=%22120%22%3E%3Crect width=%22100%22 height=%22120%22 fill=%22%23e5e7eb%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 font-family=%22Arial%22 font-size=%2214%22 fill=%22%236b7280%22%3ENo Image%3C/text%3E%3C/svg%3E'" 
                alt="Book Cover" 
                  class="w-16 h-20 object-cover rounded shadow-sm"
                />
              </td>
              <!-- Book Title -->
              <td class="px-6 py-4">
                <span class="text-gray-900 font-medium">{{ book.title }}</span>
              </td>
              <!-- Book Author -->
              <td class="px-6 py-4 text-gray-700">{{ book.author }}</td>
              <!-- Course -->
              <td class="px-6 py-4">
                <span class="text-gray-700">{{ book.course || 'N/A' }}</span>
              </td>
              <!-- Subject For -->
              <td class="px-6 py-4">
                <span class="text-gray-700">{{ book.subject_for || 'N/A' }}</span>
              </td>
              <!-- Amount -->
              <td class="px-6 py-4">
                <span class="text-gray-900 font-semibold">{{ book.amount ? '₱' + Number(book.amount).toFixed(2) : 'N/A' }}</span>
              </td>
              <!-- Date Acquired -->
              <td class="px-6 py-4">
                <span class="text-gray-700">{{ book.dateAcquired ? formatDate(book.dateAcquired) : 'N/A' }}</span>
              </td>
              <!-- Book ID -->
              <td class="px-6 py-4 text-gray-700">{{ book.bookId }}</td>
              <!-- Availability -->
              <td class="px-6 py-4">
                <div class="flex flex-col space-y-1">
                  <span :class="getStatusClass(book.availability)">
                    {{ book.availability }}
                  </span>
                  <span v-if="book.availability === 'Borrowed' && book.current_borrower" class="text-sm text-gray-600">
                    Borrowed by: {{ book.current_borrower.fullname }}
                    <br>
                    Due: {{ formatDate(book.current_borrower.due_date) }}
                  </span>
                  <span v-if="book.availability === 'Reserved' && book.current_reserver" class="text-sm text-gray-600">
                    Reserved by: {{ book.current_reserver.fullname }}
                    <br>
                    Until: {{ formatDate(book.current_reserver.until_date) }}
                  </span>
                </div>
              </td>
              <!-- QR Code Button -->
              <td class="px-6 py-4 text-center">
                <button
                  @click="viewQrCode(book.id, book.title)"
                  class="px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition-colors shadow-sm"
                  title="View QR Code"
                >
                  <i class="fas fa-qrcode mr-1"></i> QR
                </button>
              </td>
              <!-- Actions -->
              <td class="px-6 py-4">
                <div class="flex justify-center space-x-2">
                <button
                  @click="updateBook(book)"
                  class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors shadow-sm"
                >
                  <i class="fas fa-edit mr-1"></i> Edit
                </button>
                <button
                  @click="deleteBook(book.id)"
                  class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors shadow-sm"
                >
                  <i class="fas fa-trash mr-1"></i> Delete
                </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
        </div>

        <div v-if="activeTab === 'borrowed'" class="overflow-x-auto shadow-md rounded-lg">
        <table class="min-w-full bg-white">
          <thead class="bg-gradient-to-r from-red-500 to-red-600 text-white text-sm uppercase tracking-wider">
            <tr>
              <th class="px-6 py-4 text-left font-semibold">Book Title</th>
              <th class="px-6 py-4 text-left font-semibold">Borrower Name</th>
              <th class="px-6 py-4 text-left font-semibold">Phone Number</th>
              <th class="px-6 py-4 text-left font-semibold">Due Date</th>
              <th class="px-6 py-4 text-center font-semibold">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <tr v-for="book in borrowedBooks" :key="book.id" class="hover:bg-gray-50 transition-colors">
              <td class="px-6 py-4">
                <span class="font-medium text-gray-900">{{ book.title }}</span>
              </td>
              <td class="px-6 py-4 text-gray-700">{{ book.current_borrower?.fullname || 'N/A' }}</td>
              <td class="px-6 py-4 text-gray-700">
                <div class="flex items-center">
                  <i class="fas fa-phone text-gray-400 mr-2 text-sm"></i>
                  {{ book.current_borrower?.phone_number || 'N/A' }}
                </div>
              </td>
              <td class="px-6 py-4">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-red-100 text-red-800">
                  <i class="fas fa-calendar-alt mr-2"></i>
                  {{ formatDate(book.current_borrower?.due_date) }}
                </span>
              </td>
              <td class="px-6 py-4 text-center">
                <button 
                  @click="returnBook(book.id)" 
                  class="inline-flex items-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-all shadow-sm hover:shadow-md"
                >
                  <i class="fas fa-undo mr-2"></i>
                  Return Book
                </button>
              </td>
            </tr>
            <tr v-if="borrowedBooks.length === 0">
              <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                <i class="fas fa-inbox text-4xl mb-2"></i>
                <p>No borrowed books at the moment</p>
              </td>
            </tr>
          </tbody>
        </table>
        </div>

        <!-- Reserved Books Table -->
        <div v-if="activeTab === 'reserved'" class="overflow-x-auto shadow-md rounded-lg">
        <table class="min-w-full bg-white">
          <thead class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white text-sm uppercase tracking-wider">
            <tr>
              <th class="px-6 py-4 text-left font-semibold">Book Title</th>
              <th class="px-6 py-4 text-left font-semibold">Reserved By</th>
              <th class="px-6 py-4 text-left font-semibold">Phone Number</th>
              <th class="px-6 py-4 text-left font-semibold">Until Date</th>
              <th class="px-6 py-4 text-center font-semibold">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <tr v-for="book in reservedBooks" :key="book.id" class="hover:bg-gray-50 transition-colors">
              <td class="px-6 py-4">
                <span class="font-medium text-gray-900">{{ book.title }}</span>
              </td>
              <td class="px-6 py-4 text-gray-700">{{ book.current_reserver?.fullname || 'N/A' }}</td>
              <td class="px-6 py-4 text-gray-700">
                <div class="flex items-center">
                  <i class="fas fa-phone text-gray-400 mr-2 text-sm"></i>
                  {{ book.current_reserver?.phone_number || 'N/A' }}
                </div>
              </td>
              <td class="px-6 py-4">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-yellow-100 text-yellow-800">
                  <i class="fas fa-calendar-check mr-2"></i>
                  {{ formatDate(book.current_reserver?.until_date) }}
                </span>
              </td>
              <td class="px-6 py-4 text-center">
                <button 
                  @click="returnReservation(book.id)" 
                  class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-all shadow-sm hover:shadow-md"
                >
                  <i class="fas fa-undo mr-2"></i>
                  Return
                </button>
              </td>
            </tr>
            <tr v-if="reservedBooks.length === 0">
              <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                <i class="fas fa-inbox text-4xl mb-2"></i>
                <p>No reserved books at the moment</p>
              </td>
            </tr>
          </tbody>
        </table>
        </div>

        <!-- Requests Table -->
        <template v-if="activeTab === 'requests'">
          <!-- Pending Requests -->
          <div class="mb-8">
            <h3 class="text-lg font-semibold mb-4">Pending Requests ({{ pendingRequests.length }})</h3>
            <table class="min-w-full bg-white border">
              <thead class="bg-blue-50">
                <tr>
                  <th class="px-4 py-2 text-left">Book</th>
                  <th class="px-4 py-2 text-left">Requester</th>
                  <th class="px-4 py-2 text-left">Type</th>
                  <th class="px-4 py-2 text-left">Start Date</th>
                  <th class="px-4 py-2 text-left">Return Date</th>
                  <th class="px-4 py-2 text-left">Status</th>
                  <th class="px-4 py-2 text-center">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="request in pendingRequests" :key="request.id" class="border-t">
                  <td class="px-4 py-2">{{ request.book.title }}</td>
                  <td class="px-4 py-2">{{ request.user.fullname }}</td>
                  <td class="px-4 py-2 capitalize">{{ request.request_type }}</td>
                  <td class="px-4 py-2">{{ formatDate(request.request_date) }}</td>
                  <td class="px-4 py-2">{{ formatDate(request.return_date) }}</td>
                  <td class="px-4 py-2">
                    <span class="text-yellow-600">{{ request.status }}</span>
                  </td>
                  <td class="px-4 py-2 text-center">
                    <div class="flex justify-center space-x-2">
                      <button 
                        @click="approveRequest(request.id)" 
                        class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition"
                      >
                        Approve
                      </button>
                      <button 
                        @click="rejectRequest(request.id)" 
                        class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition"
                      >
                        Reject
                      </button>
                    </div>
                  </td>
                </tr>
                <tr v-if="pendingRequests.length === 0">
                  <td colspan="7" class="px-4 py-4 text-center text-gray-500">No pending requests</td>
                </tr>
              </tbody>
            </table>
          </div>
        </template>

        <!-- History Tab -->
        <template v-if="activeTab === 'history'">
          <div>
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
              <h3 class="text-lg font-semibold">
                <i class="fas fa-history text-indigo-600 mr-2"></i>
                History 
                <span class="text-sm text-gray-500 font-normal">({{ historyRequests.length }} total)</span>
              </h3>
              
              <!-- Download Buttons -->
              <div class="flex gap-2">
                <button 
                  @click="downloadReport"
                  class="px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all shadow-md hover:shadow-lg flex items-center gap-2 text-sm font-semibold"
                >
                  <i class="fas fa-file-csv"></i>
                  Download CSV
                </button>
                <button 
                  @click="downloadAsPNG"
                  class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all shadow-md hover:shadow-lg flex items-center gap-2 text-sm font-semibold"
                >
                  <i class="fas fa-image"></i>
                  Download PNG
                </button>
              </div>
            </div>

            <!-- Search and Filter Row -->
            <div class="space-y-3 mb-4">
              <!-- First Row: Status Filters -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <select 
                  v-model="historyStatusFilter"
                  class="px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400 text-sm shadow-sm"
                >
                  <option value="all">📋 All Request Status</option>
                  <option value="approved">✅ Approved</option>
                  <option value="rejected">❌ Rejected</option>
                </select>

                <select 
                  v-model="historyReturnFilter"
                  class="px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400 text-sm shadow-sm"
                >
                  <option value="all">📦 All Return Status</option>
                  <option value="returned">✔️ Returned</option>
                  <option value="not-returned">⏳ Not Returned Yet</option>
                </select>
              </div>

              <!-- Second Row: Date Range -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div class="relative">
                  <input 
                    v-model="historyStartDate"
                    type="date" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400 text-sm shadow-sm"
                  />
                  <label class="absolute -top-2.5 left-3 bg-white px-2 text-xs font-medium text-indigo-600">
                    📅 Start Date
                  </label>
                </div>
                <div class="relative">
                  <input 
                    v-model="historyEndDate"
                    type="date" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400 text-sm shadow-sm"
                  />
                  <label class="absolute -top-2.5 left-3 bg-white px-2 text-xs font-medium text-indigo-600">
                    📅 End Date
                  </label>
                </div>
              </div>
            </div>

            <!-- Clear Filters Button -->
            <div class="mb-4">
              <button 
                @click="clearHistoryFilters"
                class="px-3 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors text-sm font-medium flex items-center gap-2"
              >
                <i class="fas fa-times-circle"></i>
                Clear Filters
              </button>
            </div>

            <!-- History Report Container for PNG Export -->
            <div id="history-report-container" class="bg-white p-6 rounded-lg">
             

            <div class="overflow-x-auto shadow-md rounded-lg">
              <table class="min-w-full bg-white border border-gray-200">
                <thead class="bg-gradient-to-r from-indigo-50 to-purple-50">
                  <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                      <i class="fas fa-book mr-1"></i>Book
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                      <i class="fas fa-user mr-1"></i>Requester
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                      <i class="fas fa-tag mr-1"></i>Type
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                      <i class="fas fa-calendar mr-1"></i>Request Date
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                      <i class="fas fa-check-circle mr-1"></i>Request Status
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                      <i class="fas fa-undo mr-1"></i>Return Status
                    </th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                  <tr v-for="request in filteredHistoryRequests" :key="request.id" class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3">
                      <div class="flex items-center">
                        <i class="fas fa-book-open text-indigo-500 mr-2"></i>
                        <span class="font-medium text-gray-900">{{ request.book.title }}</span>
                      </div>
                    </td>
                    <td class="px-4 py-3">
                      <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white font-semibold text-sm mr-2">
                          {{ request.user.fullname.charAt(0).toUpperCase() }}
                        </div>
                        <span class="text-gray-800">{{ request.user.fullname }}</span>
                      </div>
                    </td>
                    <td class="px-4 py-3">
                      <span :class="{
                        'bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-semibold inline-flex items-center': request.request_type === 'borrow',
                        'bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-xs font-semibold inline-flex items-center': request.request_type === 'reserve'
                      }">
                        <i :class="request.request_type === 'borrow' ? 'fas fa-book mr-1' : 'fas fa-bookmark mr-1'"></i>
                        {{ request.request_type }}
                      </span>
                    </td>
                    <td class="px-4 py-3 text-gray-600 text-sm">
                      <i class="far fa-calendar-alt mr-1 text-gray-400"></i>
                      {{ formatDate(request.request_date) }}
                    </td>
                    <td class="px-4 py-3">
                      <span :class="{
                        'bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold inline-flex items-center': request.status === 'approved' || request.status === 'returned',
                        'bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-semibold inline-flex items-center': request.status === 'rejected'
                      }">
                        <i :class="{
                          'fas fa-check-circle mr-1': request.status === 'approved' || request.status === 'returned',
                          'fas fa-times-circle mr-1': request.status === 'rejected'
                        }"></i>
                        {{ request.status === 'returned' ? 'Approved' : (request.status.charAt(0).toUpperCase() + request.status.slice(1)) }}
                      </span>
                    </td>
                    <td class="px-4 py-3">
                      <span v-if="request.status !== 'rejected'" :class="{
                        'bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-semibold inline-flex items-center': request.status === 'returned',
                        'bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-semibold inline-flex items-center': request.status === 'approved'
                      }">
                        <i :class="{
                          'fas fa-check-double mr-1': request.status === 'returned',
                          'fas fa-clock mr-1': request.status === 'approved'
                        }"></i>
                        {{ request.status === 'returned' ? 'Returned' : 'Not Returned Yet' }}
                      </span>
                      <span v-else class="text-gray-400 text-xs italic">N/A</span>
                    </td>
                  </tr>
                  <tr v-if="filteredHistoryRequests.length === 0">
                    <td colspan="6" class="px-4 py-8 text-center">
                      <i class="fas fa-inbox text-gray-300 text-4xl mb-2"></i>
                      <p class="text-gray-500 font-medium">
                        {{ searchQuery || historyStatusFilter !== 'all' ? 'No matching history found' : 'No history yet' }}
                      </p>
                      <p class="text-gray-400 text-sm mt-1">
                        {{ searchQuery || historyStatusFilter !== 'all' ? 'Try adjusting your search or filter' : 'Completed requests will appear here' }}
                      </p>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Summary Stats -->
            <div v-if="historyRequests.length > 0" class="mt-4">
              <!-- Date Info -->
              <div v-if="historyStartDate || historyEndDate" class="mb-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-sm font-medium text-blue-800">
                  <i class="fas fa-calendar-day mr-2"></i>
                  <span v-if="historyStartDate && historyEndDate">
                    Showing results from {{ formatDisplayDate(historyStartDate) }} to {{ formatDisplayDate(historyEndDate) }}
                  </span>
                  <span v-else-if="historyStartDate">
                    Showing results from {{ formatDisplayDate(historyStartDate) }} onwards
                  </span>
                  <span v-else-if="historyEndDate">
                    Showing results up to {{ formatDisplayDate(historyEndDate) }}
                  </span>
                  <span class="ml-2 text-blue-600 font-semibold">({{ filteredHistoryRequests.length }} records)</span>
                </p>
              </div>

              <!-- Stats Grid -->
              <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-7 gap-3">
                <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                  <div class="flex items-center justify-between">
                    <div>
                      <p class="text-xs text-green-600 font-semibold uppercase">Approved</p>
                      <p class="text-2xl font-bold text-green-700">{{ approvedCount }}</p>
                    </div>
                    <i class="fas fa-check-circle text-green-400 text-2xl"></i>
                  </div>
                </div>
                <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                  <div class="flex items-center justify-between">
                    <div>
                      <p class="text-xs text-red-600 font-semibold uppercase">Rejected</p>
                      <p class="text-2xl font-bold text-red-700">{{ rejectedCount }}</p>
                    </div>
                    <i class="fas fa-times-circle text-red-400 text-2xl"></i>
                  </div>
                </div>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                  <div class="flex items-center justify-between">
                    <div>
                      <p class="text-xs text-blue-600 font-semibold uppercase">Returned</p>
                      <p class="text-2xl font-bold text-blue-700">{{ returnedCount }}</p>
                    </div>
                    <i class="fas fa-check-double text-blue-400 text-2xl"></i>
                  </div>
                </div>
                <div class="bg-orange-50 border border-orange-200 rounded-lg p-3">
                  <div class="flex items-center justify-between">
                    <div>
                      <p class="text-xs text-orange-600 font-semibold uppercase">Not Returned</p>
                      <p class="text-2xl font-bold text-orange-700">{{ notReturnedCount }}</p>
                    </div>
                    <i class="fas fa-clock text-orange-400 text-2xl"></i>
                  </div>
                </div>
                <div class="bg-purple-50 border border-purple-200 rounded-lg p-3">
                  <div class="flex items-center justify-between">
                    <div>
                      <p class="text-xs text-purple-600 font-semibold uppercase">Borrowed</p>
                      <p class="text-2xl font-bold text-purple-700">{{ borrowedRequestsCount }}</p>
                    </div>
                    <i class="fas fa-book text-purple-400 text-2xl"></i>
                  </div>
                </div>
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                  <div class="flex items-center justify-between">
                    <div>
                      <p class="text-xs text-yellow-600 font-semibold uppercase">Reserved</p>
                      <p class="text-2xl font-bold text-yellow-700">{{ reservedRequestsCount }}</p>
                    </div>
                    <i class="fas fa-bookmark text-yellow-400 text-2xl"></i>
                  </div>
                </div>
                <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-3">
                  <div class="flex items-center justify-between">
                    <div>
                      <p class="text-xs text-indigo-600 font-semibold uppercase">Total</p>
                      <p class="text-2xl font-bold text-indigo-700">{{ filteredHistoryRequests.length }}</p>
                    </div>
                    <i class="fas fa-list text-indigo-400 text-2xl"></i>
                  </div>
                </div>
              </div>
            </div>
            </div> <!-- Close history-report-container -->
          </div> <!-- Close history section -->
        </template>

        <!-- Modal -->
        <div v-if="showModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50 p-4" @click.self="closeModal">
          <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md max-h-[90vh] overflow-y-auto">
            <h3 class="text-xl font-semibold text-gray-700 mb-4 sticky top-0 bg-white pb-2 border-b">Edit Book Details</h3>
            
            <!-- Title -->
            <div class="mb-4">
              <label for="editTitle" class="block text-sm font-medium text-gray-600">Title:</label>
              <input
                type="text"
                v-model="editBookData.title"
                id="editTitle"
                class="mt-1 p-2 border border-gray-300 rounded w-full focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <!-- Author -->
            <div class="mb-4">
              <label for="editAuthor" class="block text-sm font-medium text-gray-600">Author:</label>
              <input
                type="text"
                v-model="editBookData.author"
                id="editAuthor"
                class="mt-1 p-2 border border-gray-300 rounded w-full focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <!-- Course -->
            <div class="mb-4">
              <label for="editCourse" class="block text-sm font-medium text-gray-600">Course:</label>
              <input
                type="text"
                v-model="editBookData.course"
                id="editCourse"
                placeholder="e.g., Computer Science, Mathematics, Literature"
                class="mt-1 p-2 border border-gray-300 rounded w-full focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <!-- Subject For -->
            <div class="mb-4">
              <label for="editSubjectFor" class="block text-sm font-medium text-gray-600">Subject For:</label>
              <input
                type="text"
                v-model="editBookData.subject_for"
                id="editSubjectFor"
                placeholder="e.g., Mathematics, Programming"
                class="mt-1 p-2 border border-gray-300 rounded w-full focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <!-- Amount -->
            <div class="mb-4">
              <label for="editAmount" class="block text-sm font-medium text-gray-600">Amount (Cost):</label>
              <input
                type="number"
                step="0.01"
                min="0"
                v-model="editBookData.amount"
                id="editAmount"
                placeholder="0.00"
                class="mt-1 p-2 border border-gray-300 rounded w-full focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <!-- Date Acquired -->
            <div class="mb-4">
              <label for="editDateAcquired" class="block text-sm font-medium text-gray-600">Date Acquired:</label>
              <input
                type="date"
                v-model="editBookData.dateAcquired"
                id="editDateAcquired"
                class="mt-1 p-2 border border-gray-300 rounded w-full focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <!-- Availability -->
            <div class="mb-4">
              <label for="availabilitySelect" class="block text-sm font-medium text-gray-600">Availability:</label>
              <select
                v-model="editBookData.availability"
                id="availabilitySelect"
                class="mt-1 p-2 border border-gray-300 rounded w-full focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="Available">Available</option>
                <option value="Borrowed">Borrowed</option>
                <option value="Reserved">Reserved</option>
              </select>
            </div>

            <!-- Image Upload -->
            <!-- In the Modal section, add this before the image upload input -->
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-600">Current Cover:</label>
              <img 
                :src="editBookData.image_url || 'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22100%22 height=%22120%22%3E%3Crect width=%22100%22 height=%22120%22 fill=%22%23e5e7eb%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22 font-family=%22Arial%22 font-size=%2214%22 fill=%22%236b7280%22%3ENo Image%3C/text%3E%3C/svg%3E'" 
                alt="Current Book Cover" 
                class="h-20 object-cover rounded mt-2"
              />
            </div>

            <!-- Update the image upload section -->
            <div class="mb-4">
              <label for="imageUpload" class="block text-sm font-medium text-gray-600">
                Update Book Cover:
              </label>
              <input
                type="file"
                id="imageUpload"
                @change="handleImageUpload"
                accept="image/jpeg,image/png,image/jpg"
                class="mt-1 p-2 border border-gray-300 rounded w-full focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <!-- Buttons -->
            <div class="flex justify-between mt-6 space-x-4 sticky bottom-0 bg-white pt-4 border-t">
              <button
                @click="saveBook"
                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition w-full"
              >
                Save
              </button>
              <button
                @click="closeModal"
                class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition w-full"
              >
                Cancel
              </button>
            </div>
          </div>
        </div>

        <!-- QR Code Modal -->
        <div v-if="showQrModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50" @click.self="closeQrModal">
          <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
            <h3 class="text-xl font-semibold text-gray-700 mb-4">QR Code - {{ qrModalBookTitle }}</h3>
            
            <!-- QR Code Display -->
            <div v-if="qrCodeSvg && qrCodeSvg.length > 0" v-html="qrCodeSvg" class="flex justify-center mb-4"></div>
            <div v-else class="flex justify-center items-center h-48">
              <div class="text-center">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-gray-900 mb-2"></div>
                <p class="text-gray-500">Loading QR code...</p>
              </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-between mt-6 space-x-4">
              <button
                @click="downloadQrCode"
                :disabled="!qrCodeSvg"
                class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition w-full disabled:opacity-50 disabled:cursor-not-allowed"
              >
                Download
              </button>
              <button
                @click="closeQrModal"
                class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition w-full"
              >
                Close
              </button>
            </div>
          </div>
        </div>

      </main>
    </div>
  </Layout>
</template>


<script>
  import { Link } from '@inertiajs/vue3';
  import Layout from '../../Layouts/FrontEndLayout.vue';
  import Modal from '../../Components/Modal.vue';
  import html2canvas from 'html2canvas';

export default {
  components: {
        Link,
        Layout,
        Modal
    },
  props: {
    books: Array,
    bookRequests: Array
  },
  data() {
    return {
      activeTab: 'books',
      searchQuery: '',
      sortBy: 'title-asc', // Default sort
      selectedAgeFilter: null, // null, 'green', 'blue', 'yellow', 'orange', 'red'
      historyStatusFilter: 'all', // 'all', 'approved', 'rejected'
      historyReturnFilter: 'all', // 'all', 'returned', 'not-returned'
      historyStartDate: '', // Start date for date range filter
      historyEndDate: '', // End date for date range filter
      showModal: false,
      showQrModal: false,
      qrCodeSvg: null,
      qrModalBookTitle: '',
      qrModalBookId: null,
      editBookData: {
        id: null,
        title: '',
        author: '',
        course: '',
        subject_for: '',
        amount: '',
        dateAcquired: '',
        availability: 'Available',
        availabilityDate: '',
        image_url: null
      },
      tabs: [
        { label: 'All Books', value: 'books' },
        { label: 'Requests', value: 'requests' },
        { label: 'Borrowed', value: 'borrowed' },
        { label: 'Reserved', value: 'reserved' },
        { label: 'History', value: 'history' }
      ],
      requestForm: {
        type: 'borrow',
        request_date: '',
        return_date: '',
        book_id: null
      }
    };
  },
  computed: {
    filteredBooks() {
      let books = this.books.filter(book => {
        return book.title.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
               book.author.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
               (book.course && book.course.toLowerCase().includes(this.searchQuery.toLowerCase()));
      });

      // Apply age filter if selected
      if (this.selectedAgeFilter) {
        books = books.filter(book => {
          const color = this.getBookAgeColorName(book.publicationDate);
          return color === this.selectedAgeFilter;
        });
      }

      // Apply sorting
      const [field, order] = this.sortBy.split('-');
      
      books.sort((a, b) => {
        let aValue, bValue;
        
        switch(field) {
          case 'title':
            aValue = a.title.toLowerCase();
            bValue = b.title.toLowerCase();
            break;
          case 'author':
            aValue = a.author.toLowerCase();
            bValue = b.author.toLowerCase();
            break;
          case 'course':
            aValue = (a.course || '').toLowerCase();
            bValue = (b.course || '').toLowerCase();
            break;
          case 'amount':
            aValue = parseFloat(a.amount) || 0;
            bValue = parseFloat(b.amount) || 0;
            break;
          case 'date':
            aValue = a.dateAcquired ? new Date(a.dateAcquired).getTime() : 0;
            bValue = b.dateAcquired ? new Date(b.dateAcquired).getTime() : 0;
            break;
          case 'age':
            aValue = a.publicationDate ? new Date(a.publicationDate).getTime() : 0;
            bValue = b.publicationDate ? new Date(b.publicationDate).getTime() : 0;
            break;
          default:
            return 0;
        }
        
        if (order === 'asc' || order === 'oldest') {
          return aValue > bValue ? 1 : aValue < bValue ? -1 : 0;
        } else {
          return aValue < bValue ? 1 : aValue > bValue ? -1 : 0;
        }
      });

      return books;
    },
    borrowedBooks() {
      let books = this.books.filter(book => book.availability === 'Borrowed');
      
      // Apply search filter
      if (this.searchQuery) {
        const query = this.searchQuery.toLowerCase();
        books = books.filter(book => {
          return (
            book.title.toLowerCase().includes(query) ||
            (book.current_borrower && book.current_borrower.fullname.toLowerCase().includes(query))
          );
        });
      }
      
      return books;
    },
    reservedBooks() {
      let books = this.books.filter(book => book.availability === 'Reserved');
      
      // Apply search filter
      if (this.searchQuery) {
        const query = this.searchQuery.toLowerCase();
        books = books.filter(book => {
          return (
            book.title.toLowerCase().includes(query) ||
            (book.current_reserver && book.current_reserver.fullname.toLowerCase().includes(query))
          );
        });
      }
      
      return books;
    },
    pendingRequests() {
      let requests = this.bookRequests.filter(request => request.status === 'pending');
      
      // Apply search filter
      if (this.searchQuery) {
        const query = this.searchQuery.toLowerCase();
        requests = requests.filter(request => {
          return (
            request.book.title.toLowerCase().includes(query) ||
            request.user.fullname.toLowerCase().includes(query) ||
            request.request_type.toLowerCase().includes(query)
          );
        });
      }
      
      return requests;
    },

    historyRequests() {
      return this.bookRequests.filter(request => 
        request.status === 'rejected' || 
        request.status === 'approved' || 
        request.status === 'returned'
      );
    },
    
    filteredHistoryRequests() {
      let filtered = this.historyRequests;

      // Apply search filter using the main search bar
      if (this.searchQuery) {
        const query = this.searchQuery.toLowerCase();
        filtered = filtered.filter(request => {
          return (
            request.book.title.toLowerCase().includes(query) ||
            request.user.fullname.toLowerCase().includes(query) ||
            request.request_type.toLowerCase().includes(query)
          );
        });
      }

      // Apply request status filter (approved/rejected only)
      if (this.historyStatusFilter !== 'all') {
        if (this.historyStatusFilter === 'approved') {
          // Show both approved and returned (returned items were approved first)
          filtered = filtered.filter(request => request.status === 'approved' || request.status === 'returned');
        } else if (this.historyStatusFilter === 'rejected') {
          filtered = filtered.filter(request => request.status === 'rejected');
        }
      }

      // Apply return status filter (returned/not-returned only)
      if (this.historyReturnFilter !== 'all') {
        if (this.historyReturnFilter === 'returned') {
          filtered = filtered.filter(request => request.status === 'returned');
        } else if (this.historyReturnFilter === 'not-returned') {
          // Not returned = approved but not yet returned (excludes rejected)
          filtered = filtered.filter(request => request.status === 'approved');
        }
      }

      // Apply single date filter - show only records from that specific day
      if (this.historyStartDate || this.historyEndDate) {
        filtered = filtered.filter(request => {
          const requestDate = new Date(request.request_date);
          requestDate.setHours(0, 0, 0, 0);
          
          // If both dates are set, filter by range
          if (this.historyStartDate && this.historyEndDate) {
            const startDate = new Date(this.historyStartDate);
            startDate.setHours(0, 0, 0, 0);
            const endDate = new Date(this.historyEndDate);
            endDate.setHours(23, 59, 59, 999);
            
            return requestDate >= startDate && requestDate <= endDate;
          }
          
          // If only start date is set
          if (this.historyStartDate) {
            const startDate = new Date(this.historyStartDate);
            startDate.setHours(0, 0, 0, 0);
            return requestDate >= startDate;
          }
          
          // If only end date is set
          if (this.historyEndDate) {
            const endDate = new Date(this.historyEndDate);
            endDate.setHours(23, 59, 59, 999);
            return requestDate <= endDate;
          }
          
          return true;
        });
      }

      return filtered;
    },

    approvedCount() {
      // Count both approved and returned (since returned items were approved)
      return this.filteredHistoryRequests.filter(r => r.status === 'approved' || r.status === 'returned').length;
    },

    rejectedCount() {
      return this.filteredHistoryRequests.filter(r => r.status === 'rejected').length;
    },

    returnedCount() {
      return this.filteredHistoryRequests.filter(r => r.status === 'returned').length;
    },

    notReturnedCount() {
      // Only approved but not yet returned
      return this.filteredHistoryRequests.filter(r => r.status === 'approved').length;
    },

    borrowedRequestsCount() {
      return this.filteredHistoryRequests.filter(r => r.request_type === 'borrow').length;
    },

    reservedRequestsCount() {
      return this.filteredHistoryRequests.filter(r => r.request_type === 'reserve').length;
    }
  },

  methods: {
    getStatusClass(status) {
      return {
        'Available': 'bg-green-100 text-green-800',
        'Borrowed': 'bg-red-100 text-red-800',
        'Reserved': 'bg-yellow-100 text-yellow-800'
      }[status] || 'bg-gray-100 text-gray-800'
    },
    getTabCount(tab) {
      switch(tab) {
        case 'books': 
          return Array.isArray(this.books) ? this.books.length : 0;
        case 'borrowed': 
          return Array.isArray(this.books) ? this.books.filter(b => b.availability === 'Borrowed').length : 0;
        case 'reserved': 
          return Array.isArray(this.books) ? this.books.filter(b => b.availability === 'Reserved').length : 0;
        case 'requests': 
          // Return only pending requests count (do not include history)
          return Array.isArray(this.pendingRequests) ? this.pendingRequests.length : 0;
        case 'history':
          // Return count of all history items (approved, rejected, returned)
          return Array.isArray(this.historyRequests) ? this.historyRequests.length : 0;
        default:
          return 0;
      }
    },
    async approveRequest(requestId) {
        try {
            await this.$inertia.patch(route('book-requests.approve', requestId));
        } catch (error) {
            alert('Error approving request');
        }
    },

    async rejectRequest(requestId) {
        try {
            await this.$inertia.patch(route('book-requests.reject', requestId));
        } catch (error) {
            alert('Error rejecting request');
        }
    },

    returnBook(bookId) {
      this.$inertia.post(route('receipts.return', bookId), {
        action_type: 'return',
        return_date: new Date().toISOString(),
      });
    },
    
    returnReservation(bookId) {
      this.$inertia.post(route('reserve.return', bookId), {
        action_type: 'return',
        return_date: new Date().toISOString(),
      });
    },
    
    cancelReservation(bookId) {
      this.$inertia.delete(route('reserve.destroy', bookId), {
        data: {
          action_type: 'cancellation',
          cancellation_date: new Date().toISOString(),
        }
      });
    },
    
    formatDate(date) {
      if (!date) return '';
      const d = new Date(date);
      const month = String(d.getMonth() + 1).padStart(2, '0');
      const day = String(d.getDate()).padStart(2, '0');
      const year = d.getFullYear();
      return `${month}/${day}/${year}`;
    },
    updateBook(book) {
      this.editBookData = {
        id: book.id,
        title: book.title,
        author: book.author,
        course: book.course || '',
        subject_for: book.subject_for || '',
        amount: book.amount || '',
        dateAcquired: book.dateAcquired || '',
        availability: book.availability || 'Available',
        availabilityDate: book.availabilityDate || '',
        image_url: book.image_url, // Include the current image URL
      };
      this.showModal = true;
    },
    deleteBook(bookId) {
      if (!confirm('Are you sure you want to delete this book? This action cannot be undone.')) {
        return;
      }
      
      this.$inertia.delete(route('books.destroy', bookId), {
        preserveScroll: true,
        onSuccess: () => {
          alert('Book deleted successfully');
        },
        onError: (errors) => {
          console.error('Delete error:', errors);
          alert('Failed to delete book. Please try again.');
        }
      });
    },
    saveBook() {
      const formData = new FormData();
      formData.append('_method', 'PUT'); // Method spoofing for PUT
      formData.append('title', this.editBookData.title);
      formData.append('author', this.editBookData.author);
      formData.append('course', this.editBookData.course);
      formData.append('subject_for', this.editBookData.subject_for || '');
      formData.append('amount', this.editBookData.amount || '');
      formData.append('dateAcquired', this.editBookData.dateAcquired || '');
      formData.append('availability', this.editBookData.availability);
      
      if (this.editBookData.image_url instanceof File) {
        formData.append('image', this.editBookData.image_url); // Note: changed to 'image' to match controller
      }

      this.$inertia.post(route('books.update', this.editBookData.id), formData, {
        preserveScroll: true,
        onSuccess: () => {
          this.closeModal();
        },
      });
    },

    handleImageUpload(event) {
      const file = event.target.files[0];
      if (file) {
        this.editBookData.image_url = file;
      }
    },

    closeModal() {
      this.showModal = false;
      this.editBookData = {
        id: null,
        title: '',
        author: '',
        course: '',
        subject_for: '',
        amount: '',
        dateAcquired: '',
        availability: 'Available',
        availabilityDate: '',
        image_url: null
      };
    },
    showRequestModal(book) {
      this.requestForm.book_id = book.id;
      this.showRequestModal = true;
    },
    closeRequestModal() {
      this.requestForm = {
        type: 'borrow',
        request_date: '',
        return_date: '',
        book_id: null
      };
      this.showRequestModal = false;
    },
    submitRequest() {
      this.$inertia.post(route('book-requests.store'), this.requestForm);
      this.closeRequestModal();
    },
    
    async viewQrCode(bookId, title) {
      this.qrModalBookId = bookId;
      this.qrModalBookTitle = title;
      this.showQrModal = true;
      this.qrCodeSvg = null; // Reset while loading

      try {
        console.log('Fetching QR code for book ID:', bookId);
        
        const response = await fetch(`/books/${bookId}/qrcode`, {
          method: 'GET',
          headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          }
        });

        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers.get('content-type'));

        if (!response.ok) {
          const errorText = await response.text();
          console.error('Server error response:', errorText);
          throw new Error(`Server returned ${response.status}`);
        }

        const data = await response.json();
        console.log('Response data keys:', Object.keys(data));
        
        // Check for error in response
        if (data.error) {
          console.error('QR code error:', data.error);
          alert('Error: ' + data.error);
          this.closeQrModal();
          return;
        }
        
        // Verify we have valid SVG data
        if (!data.qrCodeSvg || typeof data.qrCodeSvg !== 'string' || data.qrCodeSvg.length === 0) {
          console.error('Invalid or empty QR code data:', data);
          alert('No QR code data received from server');
          this.closeQrModal();
          return;
        }
        
        console.log('QR SVG length:', data.qrCodeSvg.length);
        console.log('QR SVG starts with:', data.qrCodeSvg.substring(0, 50));
        
        // Verify SVG content
        const svgContent = data.qrCodeSvg.trim();
        if (!svgContent.startsWith('<?xml') && !svgContent.startsWith('<svg')) {
          console.error('Invalid SVG format. Content starts with:', svgContent.substring(0, 100));
          alert('Invalid SVG format received');
          this.closeQrModal();
          return;
        }
        
        this.qrCodeSvg = data.qrCodeSvg;
        console.log('✅ QR code loaded successfully for:', data.bookTitle || title);
      } catch (error) {
        console.error('Error fetching QR code:', error);
        alert('Failed to load QR code: ' + error.message);
        this.closeQrModal();
      }
    },

    closeQrModal() {
      this.showQrModal = false;
      this.qrCodeSvg = null;
      this.qrModalBookTitle = '';
      this.qrModalBookId = null;
    },

    downloadQrCode() {
      if (!this.qrCodeSvg) return;

      try {
        // Create a temporary container for the SVG
        const container = document.createElement('div');
        container.innerHTML = this.qrCodeSvg;
        const svgElement = container.querySelector('svg');
        
        if (!svgElement) {
          alert('Invalid QR code format');
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
        const svgBlob = new Blob([this.qrCodeSvg], { type: 'image/svg+xml;charset=utf-8' });
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
            const link = document.createElement('a');
            const safeTitle = this.qrModalBookTitle.replace(/[^a-z0-9]/gi, '_').toLowerCase();
            link.href = downloadUrl;
            link.download = `${safeTitle}_qr_code.png`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(downloadUrl);
            URL.revokeObjectURL(url);
          }, 'image/png');
        };

        img.onerror = () => {
          alert('Failed to convert QR code to image');
          URL.revokeObjectURL(url);
        };

        img.src = url;
      } catch (error) {
        console.error('Error downloading QR code:', error);
        alert('Failed to download QR code');
      }
    },

    // Book age color methods
    getBookAgeBorderColorHex(publicationDate) {
      if (!publicationDate) return '#D1D5DB'; // gray-300
      
      const pubDate = new Date(publicationDate);
      const currentDate = new Date();
      const yearsDiff = currentDate.getFullYear() - pubDate.getFullYear();
      
      // Green: 0-2 years
      if (yearsDiff >= 0 && yearsDiff <= 2) return '#10B981'; // green-500
      // Blue: 3-4 years
      if (yearsDiff >= 3 && yearsDiff <= 4) return '#3B82F6'; // blue-500
      // Yellow: 5-6 years
      if (yearsDiff >= 5 && yearsDiff <= 6) return '#EAB308'; // yellow-500
      // Orange: 7-9 years
      if (yearsDiff >= 7 && yearsDiff <= 9) return '#F97316'; // orange-500
      // Red: 10+ years
      if (yearsDiff >= 10) return '#EF4444'; // red-500
      
      return '#D1D5DB'; // gray-300
    },

    getBookAgeColorName(publicationDate) {
      if (!publicationDate) return null;
      
      const pubDate = new Date(publicationDate);
      const currentDate = new Date();
      const yearsDiff = currentDate.getFullYear() - pubDate.getFullYear();
      
      // Green: 0-2 years
      if (yearsDiff >= 0 && yearsDiff <= 2) return 'green';
      // Blue: 3-4 years
      if (yearsDiff >= 3 && yearsDiff <= 4) return 'blue';
      // Yellow: 5-6 years
      if (yearsDiff >= 5 && yearsDiff <= 6) return 'yellow';
      // Orange: 7-9 years
      if (yearsDiff >= 7 && yearsDiff <= 9) return 'orange';
      // Red: 10+ years
      if (yearsDiff >= 10) return 'red';
      
      return null;
    },

    filterByAge(color) {
      if (this.selectedAgeFilter === color) {
        // If clicking the same filter, clear it
        this.selectedAgeFilter = null;
      } else {
        this.selectedAgeFilter = color;
      }
    },

    clearAgeFilter() {
      this.selectedAgeFilter = null;
    },

    clearHistoryFilters() {
      this.historyStatusFilter = 'all';
      this.historyReturnFilter = 'all';
      this.historyStartDate = '';
      this.historyEndDate = '';
    },

    formatDisplayDate(dateString) {
      if (!dateString) return '';
      const date = new Date(dateString);
      const options = { year: 'numeric', month: 'long', day: 'numeric' };
      return date.toLocaleDateString('en-US', options);
    },

    downloadReport() {
      if (this.filteredHistoryRequests.length === 0) {
        alert('No data to download. Please adjust your filters.');
        return;
      }

      // Prepare CSV data
      const headers = ['Book Title', 'Author', 'Requester', 'Email', 'Request Type', 'Request Date', 'Status'];
      const rows = this.filteredHistoryRequests.map(request => [
        request.book.title,
        request.book.author,
        request.user.fullname,
        request.user.email,
        request.request_type.charAt(0).toUpperCase() + request.request_type.slice(1),
        this.formatDate(request.request_date),
        request.status.charAt(0).toUpperCase() + request.status.slice(1)
      ]);

      // Add summary section
      const summaryRows = [
        [],
        ['SUMMARY STATISTICS'],
        ['Total Records', this.filteredHistoryRequests.length],
        ['Approved', this.approvedCount],
        ['Rejected', this.rejectedCount],
        ['Returned', this.returnedCount],
        ['Borrowed Requests', this.borrowedRequestsCount],
        ['Reserved Requests', this.reservedRequestsCount],
        [],
        ['Report Generated', new Date().toLocaleString()],
      ];

      // Add date info if filter is applied
      if (this.historyStartDate || this.historyEndDate) {
        if (this.historyStartDate && this.historyEndDate) {
          summaryRows.splice(1, 0, ['Date Range', `${this.formatDisplayDate(this.historyStartDate)} to ${this.formatDisplayDate(this.historyEndDate)}`]);
        } else if (this.historyStartDate) {
          summaryRows.splice(1, 0, ['Start Date', this.formatDisplayDate(this.historyStartDate)]);
        } else if (this.historyEndDate) {
          summaryRows.splice(1, 0, ['End Date', this.formatDisplayDate(this.historyEndDate)]);
        }
      }

      // Convert to CSV format
      const csvContent = [
        headers.join(','),
        ...rows.map(row => row.map(cell => `"${cell}"`).join(',')),
        ...summaryRows.map(row => row.map(cell => `"${cell}"`).join(','))
      ].join('\n');

      // Create blob and download
      const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
      const link = document.createElement('a');
      const url = URL.createObjectURL(blob);
      
      // Generate filename with date
      let filename = 'book_requests_report';
      if (this.historyStartDate || this.historyEndDate) {
        if (this.historyStartDate && this.historyEndDate) {
          filename += `_${this.historyStartDate.replace(/-/g, '')}_to_${this.historyEndDate.replace(/-/g, '')}`;
        } else if (this.historyStartDate) {
          filename += `_from_${this.historyStartDate.replace(/-/g, '')}`;
        } else if (this.historyEndDate) {
          filename += `_until_${this.historyEndDate.replace(/-/g, '')}`;
        }
      } else {
        filename += `_${new Date().toISOString().split('T')[0].replace(/-/g, '')}`;
      }
      filename += '.csv';

      link.setAttribute('href', url);
      link.setAttribute('download', filename);
      link.style.visibility = 'hidden';
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
      URL.revokeObjectURL(url);
    },

    async downloadAsPNG() {
      if (this.filteredHistoryRequests.length === 0) {
        alert('No data to download. Please adjust your filters.');
        return;
      }

      try {
        const element = document.getElementById('history-report-container');
        if (!element) {
          alert('Report container not found');
          return;
        }

        // Capture the element as canvas
        const canvas = await html2canvas(element, {
          backgroundColor: '#ffffff',
          scale: 2, // Higher quality
          logging: false,
          useCORS: true,
          allowTaint: true
        });

        // Convert canvas to blob
        canvas.toBlob((blob) => {
          const url = URL.createObjectURL(blob);
          const link = document.createElement('a');
          
          // Generate filename
          let filename = 'book_requests_report';
          if (this.historyStartDate || this.historyEndDate) {
            if (this.historyStartDate && this.historyEndDate) {
              filename += `_${this.historyStartDate.replace(/-/g, '')}_to_${this.historyEndDate.replace(/-/g, '')}`;
            } else if (this.historyStartDate) {
              filename += `_from_${this.historyStartDate.replace(/-/g, '')}`;
            } else if (this.historyEndDate) {
              filename += `_until_${this.historyEndDate.replace(/-/g, '')}`;
            }
          } else {
            filename += `_${new Date().toISOString().split('T')[0].replace(/-/g, '')}`;
          }
          filename += '.png';
          
          link.href = url;
          link.download = filename;
          document.body.appendChild(link);
          link.click();
          document.body.removeChild(link);
          URL.revokeObjectURL(url);
        }, 'image/png');
        
      } catch (error) {
        console.error('Error generating PNG:', error);
        alert('Failed to generate PNG. Please try again.');
      }
    }
  }
};
</script>

<style>
@keyframes fade-in {
  from {
    opacity: 0;
    transform: scale(0.95);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}
.animate-fade-in {
  animation: fade-in 0.3s ease-out;
}
</style>
