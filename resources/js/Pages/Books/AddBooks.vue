<template>
  <Layout>
    <div class="ml-64 p-6 flex-1 min-h-screen relative">
      <!-- Beautiful animated gradient background -->
      <div class="fixed inset-0 ml-64 -z-10 bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">
        <!-- Animated gradient orbs -->
        <div class="absolute top-0 left-0 w-96 h-96 bg-gradient-to-br from-blue-300/30 to-indigo-400/30 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-gradient-to-br from-purple-300/30 to-pink-400/30 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-gradient-to-br from-indigo-300/20 to-blue-400/20 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
        <!-- Grid overlay for texture -->
        <div class="absolute inset-0 bg-grid-pattern opacity-5"></div>
      </div>

      <div class="max-w-5xl mx-auto relative">
  <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-xl overflow-hidden border border-gray-300">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-8">
            <div class="md:col-span-1 flex flex-col items-center justify-center space-y-4">
              <div class="h-36 w-36 rounded-lg bg-gradient-to-br from-indigo-500 to-blue-500 flex items-center justify-center text-white text-2xl font-bold">📚</div>
              <h2 class="text-lg font-semibold">Add New Book</h2>
              <p class="text-sm text-gray-500 text-center">Quickly add a book to the catalog. Add a cover image and we'll show a preview and generate a QR code after saving.</p>
              <div class="w-full mt-2">
                <div class="text-xs text-gray-500">Status</div>
                <div class="mt-1 inline-block px-3 py-1 rounded-full bg-green-100 text-green-800 text-sm">Available</div>
              </div>
            </div>

            <div class="md:col-span-2">
              <form @submit.prevent="handleAddBook" class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div class="col-span-2 lg:col-span-1">
                  <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                  <div class="mt-1 p-2 bg-white border border-gray-50 rounded-md">
                    <input v-model="form.title" type="text" required class="w-full rounded-md border border-gray-300 shadow-sm p-3 focus:outline-none focus:border-indigo-300 focus:ring-2 focus:ring-indigo-100 transition" />
                  </div>
                  <div v-if="form.errors.title" class="text-red-500 text-xs mt-1">{{ form.errors.title }}</div>
                </div>

                <div class="col-span-2 lg:col-span-1">
                  <label class="block text-sm font-medium text-gray-700 mb-1">Author</label>
                  <div class="mt-1 p-2 bg-white border border-gray-50 rounded-md">
                    <input v-model="form.author" type="text" required class="w-full rounded-md border border-gray-300 shadow-sm p-3 focus:outline-none focus:border-indigo-300 focus:ring-2 focus:ring-indigo-100 transition" />
                  </div>
                  <div v-if="form.errors.author" class="text-red-500 text-xs mt-1">{{ form.errors.author }}</div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Book ID</label>
                  <div class="mt-1 p-2 bg-white border border-gray-50 rounded-md">
                    <input v-model="form.bookId" type="text" required class="w-full rounded-md border border-gray-300 shadow-sm p-3 focus:outline-none focus:border-indigo-300 focus:ring-2 focus:ring-indigo-100 transition" />
                  </div>
                  <div v-if="form.errors.bookId" class="text-red-500 text-xs mt-1">{{ form.errors.bookId }}</div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Publication Date</label>
                  <div class="mt-1 p-2 bg-white border border-gray-50 rounded-md">
                    <input v-model="form.publicationDate" type="date" class="w-full rounded-md border border-gray-300 shadow-sm p-3 focus:outline-none focus:border-indigo-300 focus:ring-2 focus:ring-indigo-100 transition" />
                  </div>
                  <div v-if="form.errors.publicationDate" class="text-red-500 text-xs mt-1">{{ form.errors.publicationDate }}</div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Date Acquired</label>
                  <div class="mt-1 p-2 bg-white border border-gray-50 rounded-md">
                    <input v-model="form.dateAcquired" type="date" class="w-full rounded-md border border-gray-300 shadow-sm p-3 focus:outline-none focus:border-indigo-300 focus:ring-2 focus:ring-indigo-100 transition" />
                  </div>
                  <div v-if="form.errors.dateAcquired" class="text-red-500 text-xs mt-1">{{ form.errors.dateAcquired }}</div>
                </div>

                <div class="col-span-2">
                  <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                  <div class="mt-1 p-2 bg-white border border-gray-50 rounded-md">
                    <textarea v-model="form.description" rows="4" class="w-full rounded-md border border-gray-300 shadow-sm p-3 focus:outline-none focus:border-indigo-300 focus:ring-2 focus:ring-indigo-100 transition"></textarea>
                  </div>
                  <div v-if="form.errors.description" class="text-red-500 text-xs mt-1">{{ form.errors.description }}</div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Program</label>
                  <div class="mt-1 p-2 bg-white border border-gray-50 rounded-md">
                    <select 
                      v-model="form.course" 
                      @change="form.subject_for = ''" 
                      class="w-full rounded-md border border-gray-300 shadow-sm p-3 focus:outline-none focus:border-indigo-300 focus:ring-2 focus:ring-indigo-100 transition"
                      required
                    >
                      <option value="">Select a Program...</option>
                      <option v-for="course in courses" :key="course.id" :value="course.code">
                        {{ course.code }} - {{ course.name }}
                      </option>
                    </select>
                  </div>
                  <div v-if="form.errors.course" class="text-red-500 text-xs mt-1">{{ form.errors.course }}</div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Course</label>
                  <div class="mt-1 p-2 bg-white border border-gray-50 rounded-md">
                    <select 
                      v-model="form.subject_for" 
                      :disabled="!form.course || filteredSubjects.length === 0"
                      class="w-full rounded-md border border-gray-300 shadow-sm p-3 focus:outline-none focus:border-indigo-300 focus:ring-2 focus:ring-indigo-100 transition disabled:bg-gray-100 disabled:cursor-not-allowed"
                    >
                      <option value="">{{ form.course ? (filteredSubjects.length > 0 ? 'Select a Course...' : 'No Course for this program') : 'Select a Program first' }}</option>
                      <option v-for="subject in filteredSubjects" :key="subject.id" :value="subject.name">
                        {{ subject.code }} - {{ subject.name }}
                      </option>
                    </select>
                  </div>
                  <div v-if="form.errors.subject_for" class="text-red-500 text-xs mt-1">{{ form.errors.subject_for }}</div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Amount (Book Cost)</label>
                  <div class="mt-1 p-2 bg-white border border-gray-50 rounded-md">
                    <input v-model="form.amount" type="number" step="0.01" min="0" placeholder="0.00" class="w-full rounded-md border border-gray-300 shadow-sm p-3 focus:outline-none focus:border-indigo-300 focus:ring-2 focus:ring-indigo-100 transition" />
                  </div>
                  <div v-if="form.errors.amount" class="text-red-500 text-xs mt-1">{{ form.errors.amount }}</div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Cover Image</label>
                  <label
                    class="flex flex-col items-center justify-center px-4 py-6 bg-gray-50 border-2 border-dashed rounded-lg cursor-pointer hover:bg-gray-100 transition-colors"
                    :class="dragActive ? 'border-indigo-300 bg-indigo-50' : 'border-gray-200'"
                    @dragover.prevent="handleDragOver"
                    @dragleave.prevent="handleDragLeave"
                    @drop.prevent="handleDrop"
                  >
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/></svg>
                    <span class="mt-2 text-sm text-gray-600">Click to upload or drag and drop</span>
                    <input type="file" class="hidden" @change="previewImage" accept="image/*" />
                  </label>
                  <div v-if="imagePreview" class="mt-3 flex items-center space-x-3">
                    <img :src="imagePreview" class="w-40 h-40 object-cover rounded-lg shadow" />
                    <div class="text-sm text-gray-600">
                      <div class="font-medium">{{ form.image?.name || 'Selected image' }}</div>
                      <div class="text-xs mt-1">{{ form.image ? (Math.round(form.image.size/1024) + ' KB') : '' }}</div>
                      <div class="mt-2">
                        <button type="button" @click="form.image = null; imagePreview = null" class="text-sm text-red-600 hover:underline">Remove</button>
                      </div>
                    </div>
                  </div>
                  <div v-if="form.errors.image" class="text-red-500 text-xs mt-1">{{ form.errors.image }}</div>
                </div>

                <div class="col-span-2 flex items-center justify-between mt-3">
                  <div class="flex space-x-2">
                    <button 
                      type="button" 
                      @click="loadPreviousBook" 
                      :disabled="!hasPreviousBooks"
                      class="px-4 py-2 rounded-lg border text-sm text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-2"
                      title="Load previous book data"
                    >
                      <i class="fas fa-arrow-left"></i>
                      <span>Previous</span>
                    </button>
                    <button 
                      type="button" 
                      @click="form.reset(); imagePreview = null; currentBookIndex = -1" 
                      class="px-4 py-2 rounded-lg border text-sm text-gray-700 hover:bg-gray-50"
                    >
                      Reset
                    </button>
                  </div>
                  <button type="submit" :disabled="form.processing" class="px-6 py-2 rounded-lg bg-gradient-to-r from-indigo-600 to-blue-600 text-white shadow hover:from-indigo-500 hover:to-blue-500">Add Book</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Layout>
</template>

<script>
import { Link } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';
import Layout from '../../Layouts/FrontEndLayout.vue';

export default {
  components: {
    Link,
    Layout
  },
  props: {
    recentBooks: {
      type: Array,
      default: () => []
    },
    courses: {
      type: Array,
      default: () => []
    }
  },
  data() {
    return {
      imagePreview: null,
      showQrModal: false,
      qrCodeSvg: null,
      dragActive: false,
      currentBookIndex: -1 // Track which previous book we're viewing
    }
  },
  setup() {
    const form = useForm({
      title: '',
      author: '',
      bookId: '',
      publicationDate: '',
      dateAcquired: '',
      description: '',
      course: '',
      subject_for: '',
      amount: '',
      availability: 'Available',
      image: null
    });
    

    return { form };
  },
  computed: {
    hasPreviousBooks() {
      return this.recentBooks && this.recentBooks.length > 0;
    },
    // Get subjects for the selected course
    filteredSubjects() {
      if (!this.form.course) return [];
      const selectedCourse = this.courses.find(c => c.code === this.form.course);
      return selectedCourse ? selectedCourse.subjects : [];
    }
  },
  methods: {
    handleAddBook() {
      this.form.post(route('books.store'), {
        onSuccess: (page) => {
          this.form.reset();
          this.imagePreview = null;
          // QR code modal removed - book added successfully without showing QR
          alert('Book added successfully!');
        },
      });
    },
    closeQrModal() {
      this.showQrModal = false;
      this.qrCodeSvg = null;
    },
    previewImage(event) {
      const file = event.target.files[0];
      if (file) {
        this.form.image = file;
        this.imagePreview = URL.createObjectURL(file);
      }
    },

    handleDragOver(e) {
      e.preventDefault();
      this.dragActive = true;
    },
    handleDragLeave(e) {
      e.preventDefault();
      this.dragActive = false;
    },
    handleDrop(e) {
      e.preventDefault();
      this.dragActive = false;
      const file = e.dataTransfer?.files && e.dataTransfer.files[0];
      if (file) {
        this.form.image = file;
        this.imagePreview = URL.createObjectURL(file);
      }
    },

    loadPreviousBook() {
      if (!this.hasPreviousBooks) return;

      // Move to the next previous book (or start from the most recent)
      if (this.currentBookIndex === -1) {
        this.currentBookIndex = 0;
      } else {
        this.currentBookIndex = (this.currentBookIndex + 1) % this.recentBooks.length;
      }

      const book = this.recentBooks[this.currentBookIndex];
      
      // Populate the form with the previous book's data
      this.form.title = book.title || '';
      this.form.author = book.author || '';
      this.form.bookId = book.bookId || ''; // Show the previous bookId
      this.form.publicationDate = book.publicationDate || ''; // Show the previous publication date
      this.form.dateAcquired = book.dateAcquired || ''; // Show the previous date acquired
      this.form.description = book.description || '';
      this.form.course = book.course || '';
      this.form.subject_for = book.subject_for || ''; // Show the previous subject_for
      this.form.amount = book.amount || ''; // Show the previous amount
      
      // Handle the image
      if (book.image_path) {
        // Show the image preview
        this.imagePreview = `/storage/${book.image_path}`;
        
        // Fetch the image and convert it to a File object so it can be uploaded
        fetch(`/storage/${book.image_path}`)
          .then(response => response.blob())
          .then(blob => {
            // Extract filename from path
            const filename = book.image_path.split('/').pop();
            // Create a File object from the blob
            const file = new File([blob], filename, { type: blob.type });
            // Set it in the form
            this.form.image = file;
          })
          .catch(error => {
            console.error('Error loading image:', error);
            this.form.image = null;
          });
      } else {
        this.imagePreview = null;
        this.form.image = null;
      }

      // Show feedback to user
      const bookNumber = this.currentBookIndex + 1;
      const totalBooks = this.recentBooks.length;
      alert(`Loaded book ${bookNumber} of ${totalBooks} recent books: "${book.title}"`);
    }
  }
};
</script>

<style scoped>
/* Grid pattern background */
.bg-grid-pattern {
  background-image: 
    linear-gradient(to right, rgba(99, 102, 241, 0.1) 1px, transparent 1px),
    linear-gradient(to bottom, rgba(99, 102, 241, 0.1) 1px, transparent 1px);
  background-size: 30px 30px;
}

/* Smooth animations for gradient orbs */
@keyframes pulse {
  0%, 100% {
    opacity: 0.3;
    transform: scale(1);
  }
  50% {
    opacity: 0.5;
    transform: scale(1.1);
  }
}
</style>
