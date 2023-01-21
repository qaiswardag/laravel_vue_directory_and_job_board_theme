<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link } from "@inertiajs/vue3";

defineProps({
    fun: {
        type: Boolean,
    },
});
</script>

<template>
    <AppLayout title="Testing">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Home
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8 px-3">
                <p class="myPrimaryParagraph">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit.
                    Blanditiis omnis nisi laborum ipsa, a soluta atque modi ex,
                    earum commodi facere dicta iusto dignissimos labore quae
                    impedit similique. Repudiandae ex, laudantium illum cumque
                    sunt aut consequuntur eius ad reiciendis delectus et
                    maiores? Et delectus temporibus, debitis officiis dicta
                    placeat fuga animi quidem pariatur praesentium dolores
                    possimus illo quam id ducimus rerum, quibusdam consequuntur.
                    Quam, vero maiores atque doloremque consectetur quisquam.
                    Sunt fuga veniam quae hic? Sit animi inventore, aut
                    perspiciatis laboriosam excepturi? Error, repellendus rerum
                    natus adipisci fugiat, labore officiis id ullam repudiandae
                    similique, pariatur molestiae obcaecati odio eveniet unde.
                </p>
            </div>
        </div>
    </AppLayout>
</template>
