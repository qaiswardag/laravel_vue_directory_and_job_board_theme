<script setup>
import { computed, onBeforeMount, onMounted, ref, watch } from "vue";
import { useStore } from "vuex";
import PageBuilder from "@/composables/PageBuilder";
import TipTapInput from "@/Components/TipTap/TipTapInput.vue";
import TextColorEditor from "@/Components/PageBuilder/EditorMenu/Editables/TextColorEditor.vue";

const store = useStore();

const pageBuilder = new PageBuilder(store);

const toggleTipTap = ref(true);

const getShowModalTipTap = computed(() => {
    return store.getters["pageBuilderState/getShowModalTipTap"];
});
</script>
<template>
    <div>
        <div
            class="mt-2 mb-10 blockease-linear duration-200 block px-2 ease-linear"
        >
            <template v-if="pageBuilder.selectedElementIsValidText()">
                <div
                    class="h-16 py-2 pl-4 pr-4 bg-gray-100 rounded-full flex gap-4 flex-shrink-0 justify-start items-center border-gray-100 shadow"
                >
                    <button
                        @click="toggleTipTap = !toggleTipTap"
                        type="button"
                        class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                    >
                        <template v-if="toggleTipTap">
                            <span class="material-symbols-outlined">
                                collapse_content
                            </span>
                        </template>
                        <template v-if="!toggleTipTap">
                            <span class="material-symbols-outlined">
                                expand_content
                            </span>
                        </template>
                    </button>
                </div>
            </template>
            <TipTapInput v-if="!getShowModalTipTap && toggleTipTap">
            </TipTapInput>
        </div>
    </div>
</template>
