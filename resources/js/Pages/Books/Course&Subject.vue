<template>
  <FrontEndLayout>
    <main class="ml-64 flex-1 overflow-y-auto bg-gradient-to-br from-gray-50 to-blue-50">
      <div class="p-8">
        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
          <!-- Add Course Section -->
          <div class="bg-white rounded-2xl shadow-xl p-8 transform transition-all duration-300 hover:shadow-2xl">
            <div class="flex items-center space-x-3 mb-6">
              <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-book-reader text-white text-xl"></i>
              </div>
              <div>
                <h2 class="text-2xl font-bold text-gray-800">Add New Program</h2>
                <p class="text-sm text-gray-500">Create a new course program</p>
              </div>
            </div>

            <form @submit.prevent="addCourse" class="space-y-6">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                  <i class="fas fa-graduation-cap text-blue-500 mr-2"></i>Program Name
                </label>
                <input 
                  v-model="courseForm.name"
                  type="text" 
                  placeholder="e.g., Bachelor of Science in Computer Engineering"
                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all outline-none"
                  required
                />
              </div>

              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                  <i class="fas fa-tag text-blue-500 mr-2"></i>Program Code
                </label>
                <input 
                  v-model="courseForm.code"
                  type="text" 
                  placeholder="e.g., BSCE"
                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all outline-none"
                  required
                />
              </div>

              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                  <i class="fas fa-align-left text-blue-500 mr-2"></i>Description (Optional)
                </label>
                <textarea 
                  v-model="courseForm.description"
                  rows="3"
                  placeholder="Brief description of the course..."
                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all outline-none resize-none"
                ></textarea>
              </div>

              <button 
                type="submit"
                class="w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white font-bold py-4 rounded-xl hover:from-blue-600 hover:to-blue-700 transform hover:scale-[1.02] transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center space-x-2"
              >
                <i class="fas fa-plus-circle"></i>
                <span>Add Program</span>
              </button>
            </form>
          </div>

          <!-- Add Course Section -->
          <div class="bg-white rounded-2xl shadow-xl p-8 transform transition-all duration-300 hover:shadow-2xl">
            <div class="flex items-center space-x-3 mb-6">
              <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-book text-white text-xl"></i>
              </div>
              <div>
                <h2 class="text-2xl font-bold text-gray-800">Add New Course</h2>
                <p class="text-sm text-gray-500">Add Course to a Program</p>
              </div>
            </div>

            <form @submit.prevent="addCourse" class="space-y-6">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                  <i class="fas fa-graduation-cap text-purple-500 mr-2"></i>Select Program
                </label>
                <select 
                  v-model="courseForm.course_id"
                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all outline-none"
                  required
                >
                  <option value="">Choose a Program...</option>
                  <option v-for="course in courses" :key="course.id" :value="course.id">
                    {{ course.code }} - {{ course.name }}
                  </option>
                </select>
              </div>

              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                  <i class="fas fa-book-open text-purple-500 mr-2"></i>Course Name
                </label>
                <input 
                  v-model="courseForm.name"
                  type="text" 
                  placeholder="e.g., Data Structures and Algorithms"
                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all outline-none"
                  required
                />
              </div>

              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                  <i class="fas fa-hashtag text-purple-500 mr-2"></i>Course Code
                </label>
                <input 
                  v-model="courseForm.code"
                  type="text" 
                  placeholder="e.g., CS101"
                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all outline-none"
                  required
                />
              </div>

              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                  <i class="fas fa-align-left text-purple-500 mr-2"></i>Description (Optional)
                </label>
                <textarea 
                  v-model="courseForm.description"
                  rows="3"
                  placeholder="Brief description of the Course..."
                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all outline-none resize-none"
                ></textarea>
              </div>

              <button 
                type="submit"
                class="w-full bg-gradient-to-r from-purple-500 to-purple-600 text-white font-bold py-4 rounded-xl hover:from-purple-600 hover:to-purple-700 transform hover:scale-[1.02] transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center space-x-2"
              >
                <i class="fas fa-plus-circle"></i>
                <span>Add Course</span>
              </button>
            </form>
          </div>
        </div>

        <!-- Display Programs and Courses -->
        <div class="mt-8 bg-white rounded-2xl shadow-xl p-8">
          <div class="flex items-center space-x-3 mb-6">
            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center">
              <i class="fas fa-list text-white text-xl"></i>
            </div>
            <div>
              <h2 class="text-2xl font-bold text-gray-800">All Program & Course</h2>
              <p class="text-sm text-gray-500">View and manage existing Program and their Course</p>
            </div>
          </div>

          <div class="space-y-6">
            <div v-for="course in courses" :key="course.id" class="border-2 border-gray-200 rounded-xl p-6 hover:border-blue-300 transition-all">
              <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                  <div class="flex items-center space-x-3 mb-2">
                    <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-lg font-semibold text-sm">
                      {{ course.code }}
                    </span>
                    <h3 class="text-xl font-bold text-gray-800">{{ course.name }}</h3>
                  </div>
                  <p v-if="course.description" class="text-gray-600 text-sm">{{ course.description }}</p>
                </div>
                <button 
                  @click="deleteProgram(course.id)"
                  class="text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded-lg transition-all"
                  title="Delete Program"
                >
                  <i class="fas fa-trash"></i>
                </button>
              </div>

              <!-- Courses for this program -->
              <div v-if="course.subjects && course.subjects.length > 0" class="mt-4 pl-4 border-l-4 border-purple-300">
                <h4 class="text-sm font-semibold text-gray-600 mb-3 flex items-center">
                  <i class="fas fa-book text-purple-500 mr-2"></i>
                  Courses ({{ course.subjects.length }})
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                  <div 
                    v-for="subject in course.subjects" 
                    :key="subject.id"
                    class="flex items-center justify-between bg-purple-50 rounded-lg p-3 hover:bg-purple-100 transition-all"
                  >
                    <div class="flex items-center space-x-3">
                      <span class="px-2 py-1 bg-purple-200 text-purple-800 rounded text-xs font-bold">
                        {{ subject.code }}
                      </span>
                      <span class="text-gray-700 font-medium">{{ subject.name }}</span>
                    </div>
                    <button 
                      @click="deleteCourse(subject.id)"
                      class="text-red-500 hover:text-red-700 hover:bg-red-100 p-1 rounded transition-all"
                      title="Delete Course"
                    >
                      <i class="fas fa-times text-sm"></i>
                    </button>
                  </div>
                </div>
              </div>
              <div v-else class="mt-4 text-gray-400 italic text-sm">
                No Course added yet
              </div>
            </div>

            <div v-if="courses.length === 0" class="text-center py-12 text-gray-400">
              <i class="fas fa-inbox text-6xl mb-4"></i>
              <p class="text-lg">No Program added yet. Start by adding a new Program!</p>
            </div>
          </div>
        </div>

        <!-- Toast Notification -->
        <transition name="slide-fade">
          <div v-if="showToast" class="fixed bottom-8 right-8 bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-4 rounded-xl shadow-2xl flex items-center space-x-3 z-50">
            <i class="fas fa-check-circle text-2xl"></i>
            <span class="font-semibold">{{ toastMessage }}</span>
          </div>
        </transition>
      </div>
    </main>
  </FrontEndLayout>
</template>

<script setup>
import { ref } from 'vue';
import FrontEndLayout from '@/Layouts/FrontEndLayout.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
  courses: {
    type: Array,
    default: () => []
  }
});

const courseForm = ref({
  course_id: '',
  name: '',
  code: '',
  description: ''
});

const showToast = ref(false);
const toastMessage = ref('');

const addCourse = () => {
  const form = useForm(courseForm.value);
  
  form.post('/course-subject/add-subject', {
    onSuccess: () => {
      courseForm.value = { course_id: '', name: '', code: '', description: '' };
      showToastMessage('Course added successfully!');
    },
    onError: (errors) => {
      console.error(errors);
      showToastMessage('Failed to add course!');
    }
  });
};

const deleteProgram = (courseId) => {
  if (confirm('Are you sure you want to delete this program and all its courses?')) {
    useForm({}).delete(`/course-subject/delete-course/${courseId}`, {
      onSuccess: () => {
        showToastMessage('Program deleted successfully!');
      }
    });
  }
};

const deleteCourse = (courseId) => {
  if (confirm('Are you sure you want to delete this course?')) {
    useForm({}).delete(`/course-subject/delete-subject/${courseId}`, {
      onSuccess: () => {
        showToastMessage('Course deleted successfully!');
      }
    });
  }
};

const showToastMessage = (message) => {
  toastMessage.value = message;
  showToast.value = true;
  setTimeout(() => {
    showToast.value = false;
  }, 3000);
};
</script>

<style scoped>
.slide-fade-enter-active {
  transition: all 0.3s ease-out;
}

.slide-fade-leave-active {
  transition: all 0.2s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter-from,
.slide-fade-leave-to {
  transform: translateX(20px);
  opacity: 0;
}
</style>
