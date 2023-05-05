<script setup>
import GuestsLayout from "@/Layouts/GuestsLayout.vue";
import { AdjustmentsHorizontalIcon } from "@heroicons/vue/24/outline";

defineProps({
    test: {
        type: Boolean,
    },
});
</script>

<template>
    <GuestsLayout title="Home">
        <template #header>
            <h2 class="myPrimaryMainPageHeader">Home</h2>
        </template>
        <div class="flex flex-col gap-8 items-center pt-8">
            <p>SVG icons:</p>
            <p class="mt-2">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="w-12 h-12"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"
                    />
                </svg>
            </p>
            <p>
                <AdjustmentsHorizontalIcon
                    class="h-12 w-12 mt-12 text-myPrimaryDarkGrayColor hover:text-myPrimaryDarkGrayColor"
                />
            </p>
        </div>
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
    </GuestsLayout>
</template>
