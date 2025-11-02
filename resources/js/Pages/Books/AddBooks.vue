<template>
  <Layout>
    <div class="ml-64 p-6 flex-1 bg-cover bg-center bg-fixed min-h-screen" style="background-image: url('/img/background.jpg')">
      <div class="max-w-5xl mx-auto">
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

                <div class="col-span-2">
                  <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                  <div class="mt-1 p-2 bg-white border border-gray-50 rounded-md">
                    <textarea v-model="form.description" rows="4" class="w-full rounded-md border border-gray-300 shadow-sm p-3 focus:outline-none focus:border-indigo-300 focus:ring-2 focus:ring-indigo-100 transition"></textarea>
                  </div>
                  <div v-if="form.errors.description" class="text-red-500 text-xs mt-1">{{ form.errors.description }}</div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Course</label>
                  <div class="mt-1 p-2 bg-white border border-gray-50 rounded-md">
                    <input v-model="form.course" type="text" placeholder="e.g., Computer Science" class="w-full rounded-md border border-gray-300 shadow-sm p-3 focus:outline-none focus:border-indigo-300 focus:ring-2 focus:ring-indigo-100 transition" />
                  </div>
                  <div v-if="form.errors.course" class="text-red-500 text-xs mt-1">{{ form.errors.course }}</div>
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
                  <button type="button" @click="form.reset(); imagePreview = null" class="px-4 py-2 rounded-lg border text-sm text-gray-700 hover:bg-gray-50">Reset</button>
                  <button type="submit" :disabled="form.processing" class="px-6 py-2 rounded-lg bg-gradient-to-r from-indigo-600 to-blue-600 text-white shadow hover:from-indigo-500 hover:to-blue-500">Add Book</button>
                </div>
              </form>
            </div>
          </div>

          <!-- QR Code Modal -->
          <div v-if="showQrModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-xl shadow-2xl p-6 max-w-sm w-full relative">
              <button @click="closeQrModal" class="absolute top-3 right-3 text-gray-500 hover:text-gray-800">&times;</button>
              <h3 class="text-lg font-semibold mb-4 text-center">Book QR Code</h3>
              <div v-html="typeof qrCodeSvg === 'string' ? qrCodeSvg : ''" class="flex justify-center"></div>
              <div class="mt-4 flex justify-center">
                <button @click="closeQrModal" class="bg-gradient-to-r from-indigo-600 to-blue-600 text-white px-4 py-2 rounded hover:from-indigo-500 hover:to-blue-500">Close</button>
              </div>
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
  data() {
    return {
      imagePreview: null,
      showQrModal: false,
      qrCodeSvg: null
      , dragActive: false
    }
  },
  setup() {
    const form = useForm({
      title: '',
      author: '',
      bookId: '',
      publicationDate: '',
      description: '',
      course: '',
      availability: 'Available',
      image: null
    });

    return { form };
  },
  methods: {
    handleAddBook() {
      this.form.post(route('books.store'), {
        onSuccess: (page) => {
          this.form.reset();
          this.imagePreview = null;
          // Show QR code modal if available in page.props
          if (page.props.qrCodeSvg) {
            this.qrCodeSvg = page.props.qrCodeSvg;
            this.showQrModal = true;
          }
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
    }
  }
};
</script>
