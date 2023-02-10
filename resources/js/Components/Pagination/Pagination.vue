<script setup>
defineProps({
    links: {
        required: true,
        type: Array,
    },
});
</script>

<template>
    <div v-if="links && links.length > 3">
        <div
            class="flex flex-wrap items-center justify-center -mb-1 mt-8 py-2 px-2 bg-gray-50 rounded"
        >
            <template v-for="(link, k) in links" :key="k">
                <div
                    v-if="link.url === null"
                    class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-myPrimaryDarkGrayColor border border-myPrimaryLightGrayColor rounded"
                    v-html="link.label"
                />
                <Link
                    v-else
                    class="flex items-center mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-myPrimaryLightGrayColor focus:border-myPrimaryDarkGrayColor focus:text-myPrimaryDarkGrayColor"
                    :class="{
                        'bg-myPrimaryBrandColor text-white': link.active,
                    }"
                    :href="link.url"
                    v-html="link.label"
                ></Link>
            </template>
        </div>
    </div>
</template>
