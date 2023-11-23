<script setup>
import EditorAccordion from "../EditorAccordion.vue";
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

const getRestoredElement = computed(() => {
    return store.getters["pageBuilderState/getRestoredElement"];
});
</script>

<template>
    <EditorAccordion>
        <template #title>Delete or restore</template>
        <template #content>
            <div class="my-2">
                <button
                    v-if="getRestoredElement !== null"
                    @click="pageBuilder.handleRestoreElement"
                    type="button"
                    class="myPrimaryButton gap-2 items-center w-full"
                >
                    <ArrowPathRoundedSquareIcon
                        class="w-4 h-4 text-white stroke-1.5"
                    ></ArrowPathRoundedSquareIcon>
                    Restore Element
                </button>
                <button
                    v-if="getRestoredElement === null"
                    @click="pageBuilder.handleDeleteElement"
                    type="button"
                    class="myPrimaryDeleteButton gap-2 items-center w-full"
                >
                    <TrashIcon
                        class="w-4 h-4 text-white stroke-1.5"
                    ></TrashIcon>
                    Delete Element
                </button>
            </div>
        </template>
    </EditorAccordion>
</template>
