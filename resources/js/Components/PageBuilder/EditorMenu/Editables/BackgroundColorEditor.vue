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
import { CheckIcon, ChevronUpDownIcon } from "@heroicons/vue/20/solid";

const store = useStore();

const pageBuilder = new PageBuilder(store);

const backgroundColor = ref(null);

const getBackgroundColor = computed(() => {
    return store.getters["pageBuilderState/getBackgroundColor"];
});

watch(
    getBackgroundColor,
    (newValue) => {
        backgroundColor.value = newValue;
    },
    { immediate: true }
);
</script>

<template>
    <EditorAccordion>
        <template #title>Background Color</template>
        <template #content>
            <label class="myPrimaryInputLabel"> Background color </label>
            <Listbox as="div" v-model="backgroundColor">
                <div class="relative mt-2">
                    <ListboxButton class="myPrimarySelect">
                        <span class="flex items-center">
                            <div
                                v-if="backgroundColor !== 'none'"
                                class="aspect-square w-6 h-6"
                                :class="`bg-${backgroundColor?.replace(
                                    'bg-',
                                    ''
                                )}`"
                            ></div>
                            <span
                                class="block truncate"
                                :class="[
                                    backgroundColor !== 'none' ? 'ml-3' : '',
                                ]"
                                >{{ backgroundColor }}</span
                            >
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
                                v-for="color in tailwindColors.backgroundColorVariables"
                                @click="
                                    pageBuilder.handleBackgroundColor(
                                        backgroundColor
                                    )
                                "
                                :key="color"
                                :value="color"
                                v-slot="{ active, backgroundColor }"
                            >
                                <li
                                    :class="[
                                        active
                                            ? 'bg-myPrimaryLinkColor text-white'
                                            : 'text-gray-900',
                                        'relative cursor-default select-none py-2 pl-3 pr-9',
                                    ]"
                                >
                                    <div class="flex items-center">
                                        <div
                                            class="aspect-square w-6 h-6"
                                            :class="`bg-${color.replace(
                                                'bg-',
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
