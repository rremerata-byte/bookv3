<template>
    <div class="flex h-screen">
        <!-- Sidebar -->
        <nav class="bg-gradient-to-b from-forest-600 to-forest-700 w-64 p-4 fixed h-full shadow-xl">
            <Link href="/" class="block text-center mb-6">
                <img src="/img/snsu.png" alt="BOOK CLOUD Logo" class="mx-auto w-full max-w-[200px] drop-shadow-lg" />
            </Link>


            <div class="menu-content">
                <ul class="space-y-2">
                    <li>
                        <Link 
                            href="/dashboard" 
                            class="flex items-center space-x-3 text-white py-3 px-4 rounded-xl transition-all duration-300 hover:bg-gradient-to-r hover:from-sunshine-500 hover:to-sunshine-600 hover:shadow-lg hover:transform hover:scale-105"
                            :class="{ 'bg-gradient-to-r from-sunshine-500 to-sunshine-600 shadow-lg': $page.url === '/dashboard' }"
                        >
                            <i class="fas fa-chart-line text-lg"></i>
                            <span class="font-medium">Dashboard</span>
                        </Link>
                    </li>
                    <li>
                        <Link 
                            href="/available-books" 
                            class="flex items-center space-x-3 text-white py-3 px-4 rounded-xl transition-all duration-300 hover:bg-gradient-to-r hover:from-sunshine-500 hover:to-sunshine-600 hover:shadow-lg hover:transform hover:scale-105"
                            :class="{ 'bg-gradient-to-r from-citrus-500 to-citrus-600 shadow-lg': $page.url === '/available-books' }"
                        >
                            <i class="fas fa-book text-lg"></i>
                            <span class="font-medium">Books Manager</span>
                        </Link>
                    </li>
                    <li>
                        <Link 
                            href="/add-book" 
                            class="flex items-center space-x-3 text-white py-3 px-4 rounded-xl transition-all duration-300 hover:bg-gradient-to-r hover:from-sunshine-500 hover:to-sunshine-600 hover:shadow-lg hover:transform hover:scale-105"
                            :class="{ 'bg-gradient-to-r from-citrus-500 to-citrus-600 shadow-lg': $page.url === '/add-book' }"
                        >
                            <i class="fas fa-plus-circle text-lg"></i>
                            <span class="font-medium">Add Books</span>
                        </Link>
                    </li>
                    <li>
                        <Link 
                            href="/student-list" 
                            class="flex items-center space-x-3 text-white py-3 px-4 rounded-xl transition-all duration-300 hover:bg-gradient-to-r hover:from-sunshine-500 hover:to-sunshine-600 hover:shadow-lg hover:transform hover:scale-105"
                            :class="{ 'bg-gradient-to-r from-citrus-500 to-citrus-600 shadow-lg': $page.url === '/student-list' }"
                        >
                            <i class="fas fa-users text-lg"></i>
                            <span class="font-medium">User List</span>
                        </Link>
                    </li>
                    <li>
                        <Link 
                            href="/course-subject" 
                            class="flex items-center space-x-3 text-white py-3 px-4 rounded-xl transition-all duration-300 hover:bg-gradient-to-r hover:from-sunshine-500 hover:to-sunshine-600 hover:shadow-lg hover:transform hover:scale-105"
                            :class="{ 'bg-gradient-to-r from-citrus-500 to-citrus-600 shadow-lg': $page.url === '/course-subject' }"
                        >
                            <i class="fas fa-graduation-cap text-lg"></i>
                            <span class="font-medium">Program & Course</span>
                        </Link>
                    </li>
                </ul>
            </div>
            <hr class="mt-6">
            <div class="mt-6 text-white">
                <div class="flex items-center space-x-4 relative">
                    <!-- Profile Button -->
                    <button 
                        @click="toggleDropdown" 
                        class="w-12 h-12 rounded-full bg-gradient-to-r from-sunshine-500 to-citrus-500 flex items-center justify-center text-white text-lg font-semibold shadow-lg transition-all hover:scale-105 hover:shadow-xl"
                    >
                        {{ userInitial }}
                    </button>
                    
                    <!-- User Info -->
                    <div class="flex-grow">
                        <p class="text-lg font-semibold">{{ user.fullname }}</p>
                        <p class="text-sm text-gray-300">{{ user.studentid }}</p>
                    </div>

                    <!-- Dropdown Menu -->
                    <div v-if="showDropdown" class="absolute top-14 right-0 w-48 bg-gradient-to-b from-cream-50 to-white rounded-xl shadow-xl py-2 z-50 border border-sunshine-200">
                        <Link 
                            href="/profile" 
                            class="block px-4 py-3 text-gray-800 hover:bg-gradient-to-r hover:from-sunshine-100 hover:to-citrus-100 rounded-lg mx-2 transition-all duration-200 font-medium"
                        >
                            <i class="fas fa-user-circle mr-2 text-lg text-citrus-600"></i>
                            Profile
                        </Link>
                        <Link 
                            href="/logout" 
                            method="post" 
                            as="button"
                            class="block w-full text-left px-4 py-3 text-gray-800 hover:bg-gradient-to-r hover:from-sunshine-100 hover:to-citrus-100 rounded-lg mx-2 transition-all duration-200 font-medium"
                        >
                            <i class="fas fa-sign-out-alt mr-2 text-lg text-citrus-600"></i>
                            Logout
                        </Link>
                    </div>
                </div>
            </div>

        </nav>

        <!---->
        <slot></slot>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const showDropdown = ref(false);
const user = computed(() => usePage().props.auth.user);

const userInitial = computed(() => {
    return user.value.fullname.charAt(0).toUpperCase();
});

const toggleDropdown = (e) => {
    e.stopPropagation();
    showDropdown.value = !showDropdown.value;
};


const closeDropdown = (e) => {
    if (!e.target.closest('.dropdown') && !e.target.closest('button')) {
        showDropdown.value = false;
    }
};


onMounted(() => {
    document.addEventListener('click', closeDropdown);
});

onUnmounted(() => {
    document.removeEventListener('click', closeDropdown);
});
</script>

<style scoped>
.dropdown {
    display: block; /* Ensure the dropdown is visible when toggled */
    opacity: 1;
    z-index: 9999; /* Make sure it's on top */

}

.menu-content ul li a:hover {
    background: linear-gradient(135deg, #eab308, #f97316);
}

.menu-content ul li a.active {
    background: linear-gradient(135deg, #facc15, #fb923c);
    box-shadow: 0 4px 12px rgba(251, 146, 60, 0.4);
}
</style>
