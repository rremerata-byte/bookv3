<template>
  <Layout>
  <div class="ml-64 p-8 flex-1 bg-cover bg-center bg-fixed min-h-screen" style="background-image: url('/img/background.jpg')">
      <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
          <button @click="openCreateModal" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
            Add New User
          </button>
        </div>

        <div class="overflow-x-auto">
          <div class="shadow-sm rounded-lg overflow-hidden bg-white">
            <div class="flex items-center justify-between px-4 py-3 bg-gradient-to-r from-white to-gray-50">
              <div class="flex items-center space-x-3">
                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7 7 0 1116.65 16.65z" />
                </svg>
                <input v-model="query" type="text" placeholder="Search students by name, email or ID..." class="w-64 px-3 py-2 rounded-md border text-sm focus:ring-0 focus:border-indigo-300" />
                <div class="text-sm text-gray-500">Showing <span class="font-semibold text-gray-700">{{ filteredStudents.length }}</span></div>
              </div>
              <div class="text-sm text-gray-500">Total: <span class="font-medium text-gray-700">{{ students.length }}</span></div>
            </div>

            <table class="min-w-full divide-y divide-gray-200 bg-white">
              <thead class="bg-gray-100">
                <tr>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student ID</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course & Section</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                  <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="student in filteredStudents" :key="student.id" class="hover:bg-gray-50 transition-colors">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="h-10 w-10 flex items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 text-white font-semibold mr-3">
                        {{ initials(student.fullname) }}
                      </div>
                      <div>
                        <div class="text-sm font-medium text-gray-900">{{ student.fullname }}</div>
                        <div class="text-xs text-gray-500">ID: {{ student.studentid }}</div>
                      </div>
                    </div>
                  </td>

                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ student.email }}</div>
                  </td>

                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ student.studentid }}</td>

                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ student.courseSection }}</td>

                  <td class="px-6 py-4 whitespace-nowrap">
                    <span
                      class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                      :class="student.gender === 'Male' ? 'bg-blue-100 text-blue-800' : student.gender === 'Female' ? 'bg-pink-100 text-pink-800' : 'bg-gray-100 text-gray-800'"
                    >
                      {{ student.gender || 'N/A' }}
                    </span>
                  </td>

                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ student.phone_number || 'N/A' }}</td>

                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <button @click="editStudent(student)" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                    <button @click="confirmDelete(student)" class="text-red-600 hover:text-red-800">Delete</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <Modal :show="showModal" @close="closeModal">
      <div class="p-6">
        <h3 class="text-lg font-medium mb-4">{{ isEditing ? 'Edit Student' : 'Add New Student' }}</h3>
        <form @submit.prevent="submitForm">
          <div class="space-y-4">
            <input v-model="form.fullname" type="text" placeholder="Full Name" class="w-full p-2 border rounded">
            <input v-model="form.email" type="email" placeholder="Email" class="w-full p-2 border rounded">
            <input v-model="form.studentid" type="text" placeholder="Student ID" class="w-full p-2 border rounded">
            <input v-model="form.courseSection" type="text" placeholder="Course & Section" class="w-full p-2 border rounded">
            <select v-model="form.gender" class="w-full p-2 border rounded">
              <option value="">Select Gender</option>
              <option value="Male">Male</option>
              <option value="Female">Female</option>
              <option value="Other">Other</option>
            </select>
            <input 
              v-model="form.phone_number" 
              type="tel" 
              placeholder="Phone Number" 
              class="w-full p-2 border rounded"
            >
            <div v-if="!isEditing">
              <input v-model="form.password" type="password" placeholder="Password" class="w-full p-2 border rounded">
              <input v-model="form.password_confirmation" type="password" placeholder="Confirm Password" class="w-full p-2 border rounded mt-2">
            </div>
            <div v-if="isEditing" class="space-y-4 border-t pt-4 mt-4">
              <h4 class="font-medium">Change Password (optional)</h4>
              <input 
                v-model="form.current_password" 
                type="password" 
                placeholder="Current Password" 
                class="w-full p-2 border rounded"
              >
              <input 
                v-model="form.new_password" 
                type="password" 
                placeholder="New Password" 
                class="w-full p-2 border rounded"
              >
              <input 
                v-model="form.new_password_confirmation" 
                type="password" 
                placeholder="Confirm New Password" 
                class="w-full p-2 border rounded"
              >
            </div>
            <div v-if="form.errors.current_password" class="text-red-500 text-sm mt-1">
                {{ form.errors.current_password }}
            </div>
            <div v-if="form.errors.new_password" class="text-red-500 text-sm mt-1">
                {{ form.errors.new_password }}
            </div>
          </div>
          <div class="mt-6 flex justify-end space-x-3">
            <button type="button" @click="closeModal" class="px-4 py-2 border rounded">Cancel</button>
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Save</button>
          </div>
        </form>
      </div>
    </Modal>

    <Modal :show="showDeleteModal" @close="closeDeleteModal">
      <div class="p-6">
        <h3 class="text-lg font-medium mb-4">Confirm Delete</h3>
        <p>Are you sure you want to delete this student?</p>
        <div class="mt-6 flex justify-end space-x-3">
          <button @click="closeDeleteModal" class="px-4 py-2 border rounded">Cancel</button>
          <button @click="deleteStudent" class="px-4 py-2 bg-red-500 text-white rounded">Delete</button>
        </div>
      </div>
    </Modal>
  </Layout>
</template>

<script setup>
import { ref, computed } from 'vue';
import Layout from '@/Layouts/FrontEndLayout.vue';
import Modal from '@/Components/Modal.vue';
import { useForm } from '@inertiajs/vue3';

const { students } = defineProps({
  students: {
    type: Array,
    required: true
  }
});

const showModal = ref(false);
const showDeleteModal = ref(false);
const isEditing = ref(false);
const selectedStudent = ref(null);

const form = useForm({
  fullname: '',
  email: '',
  studentid: '',
  courseSection: '',
  gender: '',
  password: '',
  password_confirmation: '',
  current_password: '',
  new_password: '',
  new_password_confirmation: '',
  phone_number: '',
});

// UI helpers
const query = ref('');
const filteredStudents = computed(() => {
  const q = (query.value || '').trim().toLowerCase();
  if (!q) return students;
  return students.filter((s) => {
    const fullname = (s.fullname || '').toString().toLowerCase();
    const email = (s.email || '').toString().toLowerCase();
    const studentid = (s.studentid || '').toString().toLowerCase();
    return fullname.includes(q) || email.includes(q) || studentid.includes(q);
  });
});

const initials = (name) => {
  if (!name) return '';
  return name
    .split(' ')
    .filter(Boolean)
    .map((n) => n[0].toUpperCase())
    .slice(0, 2)
    .join('');
};

const openCreateModal = () => {
  isEditing.value = false;
  form.reset();
  showModal.value = true;
};

const editStudent = (student) => {
  isEditing.value = true;
  selectedStudent.value = student;
  form.fullname = student.fullname;
  form.email = student.email;
  form.studentid = student.studentid;
  form.courseSection = student.courseSection;
  form.gender = student.gender;
  form.phone_number = student.phone_number;
  showModal.value = true;
};

const submitForm = () => {
  if (isEditing.value) {
    form.put(route('students.update', selectedStudent.value.id), {
      onSuccess: () => closeModal(),
    });
  } else {
    form.post(route('students.store'), {
      onSuccess: () => closeModal(),
    });
  }
};

const confirmDelete = (student) => {
  selectedStudent.value = student;
  showDeleteModal.value = true;
};

const deleteStudent = () => {
  form.delete(route('students.delete', selectedStudent.value.id), {
    onSuccess: () => closeDeleteModal(),
  });
};

const closeModal = () => {
  showModal.value = false;
  form.reset();
  form.current_password = '';
  form.new_password = '';
  form.new_password_confirmation = '';
  selectedStudent.value = null;
};

const closeDeleteModal = () => {
  showDeleteModal.value = false;
  selectedStudent.value = null;
};
</script>