<script setup>
import jobPrices from "@/utils/pricing/job-prices";

const props = defineProps({
    currentUserTeam: {
        required: true,
    },
});
</script>

<template>
    <div v-for="product in jobPrices" :key="product.id">
        <div
            class="flex items-center justify-center gap-4 bg-gray-100 px-4 py-3 rounded-full lg:w-2/3 mx-auto shadow"
        >
            <p class="myPrimaryParagraph font-medium flex items-center gap-2">
                <span>{{ product.priceRaw }} USD</span>
                <span>
                    {{ product.name }}
                </span>
            </p>
        </div>
    </div>
</template>
