<script setup>
import { useStore } from "vuex";
import { computed, ref } from "vue";
import {
    ArrowPathRoundedSquareIcon,
    TrashIcon,
} from "@heroicons/vue/24/outline";
import PageBuilder from "@/composables/PageBuilder";

// store
const store = useStore();
const pageBuilder = new PageBuilder(store);

const getElement = computed(() => {
    return store.getters["pageBuilderState/getElement"];
});

const getRestoredElement = computed(() => {
    return store.getters["pageBuilderState/getRestoredElement"];
});

const editText = function () {
    console.log(`you cliked edit text!`);
};
</script>
<template>
    <div class="fixed top-40 left-10 z-20 bg-gray-200 py-4 px-4 rounded shadow">
        <div class="flex gap-2 items-center">
            <button
                @click="editText"
                type="button"
                class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
            >
                <span class="material-symbols-outlined"> edit </span>
            </button>

            <!-- delete & restore element # start -->
            <div>
                <button
                    v-if="getRestoredElement !== null"
                    @click="pageBuilder.handleRestoreElement"
                    type="button"
                    class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                >
                    <span class="material-symbols-outlined"> refresh </span>
                </button>
                <button
                    v-if="getElement && getRestoredElement === null"
                    @click="pageBuilder.handleDeleteElement"
                    type="button"
                    class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryErrorColor hover:text-white focus-visible:ring-0 text-myPrimaryErrorColor"
                >
                    <span class="myMediumIcon material-symbols-outlined">
                        delete
                    </span>
                </button>
            </div>
            <!-- delete & restore element # end -->
        </div>
    </div>
</template>
