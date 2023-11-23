<script setup>
import tailwindColors from "@/utils/builder/tailwaind-colors";
import PageBuilder from "@/composables/PageBuilder";
import EditorAccordion from "@/Components/PageBuilder/EditorMenu/EditorAccordion.vue";
import { useStore } from "vuex";
import { computed, ref, watch } from "vue";
import {
    Listbox,
    ListboxButton,
    ListboxLabel,
    ListboxOption,
    ListboxOptions,
} from "@headlessui/vue";
import {
    CheckIcon,
    ChevronUpDownIcon,
    XMarkIcon,
} from "@heroicons/vue/20/solid";

const store = useStore();

const pageBuilder = new PageBuilder(store);

const textColor = ref(null);

const getTextColor = computed(() => {
    return store.getters["pageBuilderState/getTextColor"];
});

watch(
    getTextColor,
    (newValue) => {
        textColor.value = newValue;
    },
    { immediate: true }
);
</script>

<template>
    <EditorAccordion>
        <template #title>Text Color</template>
        <template #content>
            <label class="myPrimaryInputLabel"> Text color </label>
            <Listbox as="div" v-model="textColor">
                <div class="relative mt-2">
                    <ListboxButton class="myPrimarySelect">
                        <span class="flex items-center gap-2">
                            <div
                                v-if="getTextColor === 'none'"
                                class="w-6 h-6 cursor-default border border-gray-200 rounded-sm"
                            >
                                <XMarkIcon
                                    class="text-myPrimaryErrorColor stroke-1.5"
                                ></XMarkIcon>
                            </div>
                            <div
                                v-if="textColor !== 'none'"
                                class="aspect-square w-6 h-6 border border-gray-100 rounded-sm"
                                :class="`bg-${textColor?.replace('text-', '')}`"
                            ></div>
                            <span class="block truncate">{{ textColor }}</span>
                        </span>
                        <span
                            class="pointer-events-none absolute inset-y-0 right-0 ml-3 flex items-center pr-2"
                        >
                            <ChevronUpDownIcon
                                class="h-5 w-5 text-gray-400"
                                aria-hidden="true"
                            />
                        </span>
                    </ListboxButton>

                    <transition
                        leave-active-class="transition ease-in duration-100"
                        leave-from-class="opacity-100"
                        leave-to-class="opacity-0"
                    >
                        <ListboxOptions
                            class="absolute z-10 mt-1 max-h-56 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                        >
                            <ListboxOption
                                as="template"
                                v-for="color in tailwindColors.textColorVariables"
                                @click="pageBuilder.handleTextColor(textColor)"
                                :key="color"
                                :value="color"
                                v-slot="{ active, textColor }"
                            >
                                <li
                                    :class="[
                                        active
                                            ? 'bg-myPrimaryLinkColor text-white'
                                            : 'text-myPrimaryDarkGrayColor',
                                        'relative cursor-default select-none py-2 pl-3 pr-9',
                                    ]"
                                >
                                    <div
                                        v-if="color === 'none'"
                                        class="flex items-center"
                                    >
                                        <div
                                            class="w-6 h-6 cursor-default border border-gray-200 rounded-sm bg-white"
                                        >
                                            <XMarkIcon
                                                class="text-myPrimaryErrorColor stroke-1.5"
                                            ></XMarkIcon>
                                        </div>
                                        <span class="ml-3">{{ color }}</span>
                                    </div>
                                    <div
                                        v-if="color !== 'none'"
                                        class="flex items-center"
                                    >
                                        <div
                                            class="aspect-square w-6 h-6 border border-gray-100 rounded-sm"
                                            :class="`bg-${color.replace(
                                                'text-',
                                                ''
                                            )}`"
                                        ></div>
                                        <span class="ml-3">{{ color }}</span>
                                    </div>
                                </li>
                            </ListboxOption>
                        </ListboxOptions>
                    </transition>
                </div>
            </Listbox>
        </template>
    </EditorAccordion>
</template>
