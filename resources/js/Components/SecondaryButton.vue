<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <button
        :type="type"
        class="inline-flex items-center rounded-xl border border-sunshine-400 bg-gradient-to-r from-cream-50 to-white px-6 py-3 text-sm font-semibold uppercase tracking-widest text-sunshine-800 shadow-md transition duration-300 ease-in-out hover:bg-gradient-to-r hover:from-sunshine-100 hover:to-sunshine-200 focus:outline-none focus:ring-2 focus:ring-sunshine-500 focus:ring-offset-2 disabled:opacity-25 hover:scale-105 hover:shadow-lg"
    >
        <slot />
    </button>
</template>
