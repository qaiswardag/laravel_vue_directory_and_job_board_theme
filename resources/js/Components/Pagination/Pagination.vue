<script setup>
defineProps({
    links: {
        required: true,
        type: Array,
    },
});
</script>

<template>
    <div class="myPrimarySection" v-if="links && links.length > 3">
        <div
            class="flex gap-y-2 space-x-2 flex-wrap items-center justify-center -mb-1 py-2 rounded"
        >
            <template v-for="(link, k) in links" :key="k">
                <div
                    v-if="link.url === null"
                    class="relative inline-flex items-center border px-4 py-2 text-sm font-medium focus:z-20 border-none bg-myPrimaryLightGrayColor shadow-sm text-myPrimaryDarkGrayColor rounded-full"
                    v-html="link.label"
                />
                <Link
                    v-else
                    class="relative inline-flex items-center border px-4 py-2 text-sm font-medium focus:z-20 border-none bg-myPrimaryLightGrayColor shadow-sm text-myPrimaryDarkGrayColor rounded-full"
                    :class="{
                        'bg-myPrimaryLinkColor hover:bg-myPrimaryLinkColor text-white':
                            link.active,
                        'hover:bg-gray-300': link.active === false,
                    }"
                    :href="link.url"
                >
                    {{ link.label }}
                </Link>
            </template>
        </div>
    </div>
</template>
