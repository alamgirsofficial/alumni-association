<script setup>

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'User Profile',
                // href: edit(),
            },
        ],
    },
});
defineProps({
    user: Object,
});
</script>

<template>
    <div>
        <h1>{{ user.full_name }}</h1>
        <p>{{ user.email }}</p>
        <p>{{ user.phone }}</p>
    </div>
</template>