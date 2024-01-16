<script setup>
import { computed, ref, watch } from "vue";
// dropdown start
import {
    Listbox,
    ListboxButton,
    ListboxOption,
    ListboxOptions,
} from "@headlessui/vue";
import { CheckIcon, ChevronUpDownIcon } from "@heroicons/vue/24/outline";

const props = defineProps({
    modelValue: {
        required: true,
    },
    categories: {
        required: true,
    },
    currentSelect: {
        required: false,
    },
    placeholderButton: {
        required: true,
    },
    placeholderInput: {
        required: true,
    },
});

const input = ref(null);

const emit = defineEmits([
    "update:modelValue",
    "selectedUpdated",
    "firstButtonClick",
]);

const currentSelect = computed(() => {
    return props.currentSelect;
});

const selected = ref(null);

watch(
    currentSelect,
    (newValue) => {
        selected.value = newValue;
    },
    { immediate: true }
);

// first button function
const firstButtonClick = function () {
    emit("firstButtonClick");
};
const selectedUpdated = function (chosen) {
    emit("selectedUpdated", chosen);
};

// dropdown end
</script>
<template>
    <div class="myPrimarySection">
        <div class="mysearchBarWithOptions">
            <div class="relative w-full">
                <div
                    class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none"
                >
                    <span class="material-symbols-outlined"> search </span>
                </div>
                <input
                    :value="modelValue"
                    @input="$emit('update:modelValue', $event.target.value)"
                    type="search"
                    id="search_query"
                    class="myPrimarySearchInput"
                    autocomplete="off"
                    :placeholder="placeholderInput"
                />
            </div>
            <Listbox as="div" v-model="selected">
                <div class="relative md:min-w-[9rem] min-w-[7rem] h-full">
                    <ListboxButton
                        class="myPrimarySelect bg-myPrimaryLightGrayColor border-none shadow-none focus:bg-myPrimaryLightGrayColor rounded-none"
                    >
                        <span class="block truncate">{{ selected }}</span>
                        <span
                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2"
                        >
                            <span class="material-symbols-outlined">
                                arrow_drop_down
                            </span>
                        </span>
                    </ListboxButton>

                    <transition
                        leave-active-class="transition ease-in duration-100"
                        leave-from-class="opacity-100"
                        leave-to-class="opacity-0"
                    >
                        <ListboxOptions
                            class="absolute z-20 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-2 ring-myPrimaryBrandColor ring-opacity-5 focus:outline-none sm:text-sm"
                        >
                            <ListboxOption
                                as="template"
                                v-for="category in categories"
                                @click="selectedUpdated(category)"
                                :key="category"
                                :value="category"
                                v-slot="{ active, selected }"
                            >
                                <li
                                    :class="[
                                        active
                                            ? 'text-white bg-myPrimaryBrandColor'
                                            : 'text-myPrimaryDarkGrayColor',
                                        'relative cursor-default select-none py-2 pl-8 pr-4',
                                    ]"
                                >
                                    <span
                                        :class="[
                                            selected
                                                ? 'font-normal'
                                                : 'font-normal',
                                            'block truncate',
                                        ]"
                                        >{{ category }}</span
                                    >

                                    <span
                                        v-if="selected"
                                        :class="[
                                            active
                                                ? 'text-white'
                                                : 'text-myPrimaryBrandColor',
                                            'absolute inset-y-0 left-0 flex items-center pl-1.5',
                                        ]"
                                    >
                                        <span
                                            class="myMediumIcon material-symbols-outlined"
                                        >
                                            check
                                        </span>
                                    </span>
                                </li>
                            </ListboxOption>
                        </ListboxOptions>
                    </transition>
                </div>
            </Listbox>
            <button
                @click="firstButtonClick"
                type="button"
                class="myPrimarySearchButton"
            >
                {{ placeholderButton }}
            </button>
        </div>
    </div>
</template>
