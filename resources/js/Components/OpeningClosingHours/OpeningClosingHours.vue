<script setup>
import { onMounted, ref } from "vue";
import {
    Listbox,
    ListboxButton,
    ListboxLabel,
    ListboxOption,
    ListboxOptions,
} from "@headlessui/vue";
import { CheckIcon, ChevronUpDownIcon } from "@heroicons/vue/20/solid";

const props = defineProps({
    resourceId: {
        required: true,
    },
    weekday: {
        required: true,
    },
});

const openingHours = [
    { id: 0, clock: "Select", value: null },
    { id: 1, clock: "00:00", value: "00:00" },
    { id: 2, clock: "00:30", value: "00:30" },
    { id: 3, clock: "01:00", value: "01:00" },
    { id: 4, clock: "01:30", value: "01:30" },
    { id: 5, clock: "02:00", value: "02:00" },
    { id: 6, clock: "02:30", value: "02:30" },
    { id: 7, clock: "03:00", value: "03:00" },
    { id: 8, clock: "03:30", value: "03:30" },
    { id: 9, clock: "04:00", value: "04:00" },
    { id: 10, clock: "04:30", value: "04:30" },
    { id: 11, clock: "05:00", value: "05:00" },
    { id: 12, clock: "05:30", value: "05:30" },
    { id: 13, clock: "06:00", value: "06:00" },
    { id: 14, clock: "06:30", value: "06:30" },
    { id: 15, clock: "07:00", value: "07:00" },
    { id: 16, clock: "07:30", value: "07:30" },
    { id: 17, clock: "08:00", value: "08:00" },
    { id: 18, clock: "08:30", value: "08:30" },
    { id: 19, clock: "09:00", value: "09:00" },
    { id: 20, clock: "09:30", value: "09:30" },
    { id: 21, clock: "10:00", value: "10:00" },
    { id: 22, clock: "10:30", value: "10:30" },
    { id: 23, clock: "11:00", value: "11:00" },
    { id: 24, clock: "11:30", value: "11:30" },
    { id: 25, clock: "12:00", value: "12:00" },
    { id: 26, clock: "12:30", value: "12:30" },
    { id: 27, clock: "13:00", value: "13:00" },
    { id: 28, clock: "13:30", value: "13:30" },
    { id: 29, clock: "14:00", value: "14:00" },
    { id: 30, clock: "14:30", value: "14:30" },
    { id: 31, clock: "15:00", value: "15:00" },
    { id: 32, clock: "15:30", value: "15:30" },
    { id: 33, clock: "16:00", value: "16:00" },
    { id: 34, clock: "16:30", value: "16:30" },
    { id: 35, clock: "17:00", value: "17:00" },
    { id: 36, clock: "17:30", value: "17:30" },
    { id: 37, clock: "18:00", value: "18:00" },
    { id: 38, clock: "18:30", value: "18:30" },
    { id: 39, clock: "19:00", value: "19:00" },
    { id: 40, clock: "19:30", value: "19:30" },
    { id: 41, clock: "20:00", value: "20:00" },
    { id: 42, clock: "20:30", value: "20:30" },
    { id: 43, clock: "21:00", value: "21:00" },
    { id: 44, clock: "21:30", value: "21:30" },
    { id: 45, clock: "22:00", value: "22:00" },
    { id: 46, clock: "22:30", value: "22:30" },
    { id: 47, clock: "23:00", value: "23:00" },
    { id: 48, clock: "23:30", value: "23:30" },
];
const closingHours = [
    { id: 0, clock: "Select", value: null },
    { id: 1, clock: "00:00", value: "00:00" },
    { id: 2, clock: "00:30", value: "00:30" },
    { id: 3, clock: "01:00", value: "01:00" },
    { id: 4, clock: "01:30", value: "01:30" },
    { id: 5, clock: "02:00", value: "02:00" },
    { id: 6, clock: "02:30", value: "02:30" },
    { id: 7, clock: "03:00", value: "03:00" },
    { id: 8, clock: "03:30", value: "03:30" },
    { id: 9, clock: "04:00", value: "04:00" },
    { id: 10, clock: "04:30", value: "04:30" },
    { id: 11, clock: "05:00", value: "05:00" },
    { id: 12, clock: "05:30", value: "05:30" },
    { id: 13, clock: "06:00", value: "06:00" },
    { id: 14, clock: "06:30", value: "06:30" },
    { id: 15, clock: "07:00", value: "07:00" },
    { id: 16, clock: "07:30", value: "07:30" },
    { id: 17, clock: "08:00", value: "08:00" },
    { id: 18, clock: "08:30", value: "08:30" },
    { id: 19, clock: "09:00", value: "09:00" },
    { id: 20, clock: "09:30", value: "09:30" },
    { id: 21, clock: "10:00", value: "10:00" },
    { id: 22, clock: "10:30", value: "10:30" },
    { id: 23, clock: "11:00", value: "11:00" },
    { id: 24, clock: "11:30", value: "11:30" },
    { id: 25, clock: "12:00", value: "12:00" },
    { id: 26, clock: "12:30", value: "12:30" },
    { id: 27, clock: "13:00", value: "13:00" },
    { id: 28, clock: "13:30", value: "13:30" },
    { id: 29, clock: "14:00", value: "14:00" },
    { id: 30, clock: "14:30", value: "14:30" },
    { id: 31, clock: "15:00", value: "15:00" },
    { id: 32, clock: "15:30", value: "15:30" },
    { id: 33, clock: "16:00", value: "16:00" },
    { id: 34, clock: "16:30", value: "16:30" },
    { id: 35, clock: "17:00", value: "17:00" },
    { id: 36, clock: "17:30", value: "17:30" },
    { id: 37, clock: "18:00", value: "18:00" },
    { id: 38, clock: "18:30", value: "18:30" },
    { id: 39, clock: "19:00", value: "19:00" },
    { id: 40, clock: "19:30", value: "19:30" },
    { id: 41, clock: "20:00", value: "20:00" },
    { id: 42, clock: "20:30", value: "20:30" },
    { id: 43, clock: "21:00", value: "21:00" },
    { id: 44, clock: "21:30", value: "21:30" },
    { id: 45, clock: "22:00", value: "22:00" },
    { id: 46, clock: "22:30", value: "22:30" },
    { id: 47, clock: "23:00", value: "23:00" },
    { id: 48, clock: "23:30", value: "23:30" },
];

const selectedOpeningHours = ref(null);
const selectedClosingHours = ref(null);
onMounted(() => {
    selectedOpeningHours.value = openingHours[0];
    selectedClosingHours.value = closingHours[0];
});
</script>

<template>
    <div>
        <p class="myPrimaryParagraph mb-2">
            <span class="my-2 block">
                resourceId:
                <span class="font-medium">
                    {{ resourceId }}
                </span>
            </span>
            <span class="my-2 block">
                weekday:
                <span class="font-medium">
                    {{ weekday.charAt(0).toUpperCase() + weekday.slice(1) }}
                </span>
            </span>
        </p>

        <div class="flex items-center justify-start myPrimaryGap">
            <!-- Opening hours # start -->
            <div class="w-full">
                <Listbox as="div" v-model="selectedOpeningHours" class="w-full">
                    <ListboxLabel
                        class="block text-sm font-medium leading-6 text-gray-900"
                    >
                        Opening hours</ListboxLabel
                    >
                    <div class="relative mt-2">
                        <ListboxButton class="myPrimarySelect">
                            <span class="block truncate">{{
                                selectedOpeningHours &&
                                selectedOpeningHours.clock
                            }}</span>
                            <span
                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2"
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
                                    v-for="whatTime in openingHours"
                                    :key="whatTime.id"
                                    :value="whatTime"
                                >
                                    <li
                                        :class="[
                                            selectedOpeningHours &&
                                            whatTime &&
                                            selectedOpeningHours.clock ===
                                                whatTime.clock
                                                ? ' text-black'
                                                : 'text-black',
                                            'relative cursor-default select-none py-2 pl-3 pr-9 hover:bg-myPrimaryLinkColor hover:text-white',
                                        ]"
                                    >
                                        <span
                                            :class="[
                                                selectedOpeningHours &&
                                                whatTime &&
                                                selectedOpeningHours.clock ===
                                                    whatTime.clock
                                                    ? 'font-medium'
                                                    : 'font-normal',
                                                'block truncate',
                                            ]"
                                            >{{ whatTime.clock }}</span
                                        >

                                        <span
                                            :class="[
                                                selectedOpeningHours &&
                                                whatTime &&
                                                selectedOpeningHours.clock ===
                                                    whatTime.clock
                                                    ? 'hover:text-white'
                                                    : 'hover:bg-myPrimaryLinkColor text-white',
                                                'absolute inset-y-0 right-0 flex items-center pr-4 ',
                                            ]"
                                        >
                                            <CheckIcon
                                                v-if="
                                                    selectedOpeningHours &&
                                                    whatTime &&
                                                    selectedOpeningHours.clock ===
                                                        whatTime.clock
                                                "
                                                class="h-5 w-5"
                                                aria-hidden="true"
                                            />
                                        </span>
                                    </li>
                                </ListboxOption>
                            </ListboxOptions>
                        </transition>
                    </div>
                </Listbox>
            </div>
            <!-- Opening hours # end -->

            <!-- Closing hours # start -->
            <div class="w-full">
                <Listbox as="div" v-model="selectedClosingHours" class="w-full">
                    <ListboxLabel
                        class="block text-sm font-medium leading-6 text-gray-900"
                    >
                        Closing hours</ListboxLabel
                    >
                    <div class="relative mt-2">
                        <ListboxButton class="myPrimarySelect">
                            <span class="block truncate">{{
                                selectedClosingHours &&
                                selectedClosingHours.clock
                            }}</span>
                            <span
                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2"
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
                                    v-for="whatTime in closingHours"
                                    :key="whatTime.id"
                                    :value="whatTime"
                                >
                                    <li
                                        :class="[
                                            selectedClosingHours &&
                                            whatTime &&
                                            selectedClosingHours.clock ===
                                                whatTime.clock
                                                ? ' text-black'
                                                : 'text-black',
                                            'relative cursor-default select-none py-2 pl-3 pr-9 hover:bg-myPrimaryLinkColor hover:text-white',
                                        ]"
                                    >
                                        <span
                                            :class="[
                                                selectedClosingHours &&
                                                whatTime &&
                                                selectedClosingHours.clock ===
                                                    whatTime.clock
                                                    ? 'font-medium'
                                                    : 'font-normal',
                                                'block truncate',
                                            ]"
                                            >{{ whatTime.clock }}</span
                                        >

                                        <span
                                            :class="[
                                                selectedClosingHours &&
                                                whatTime &&
                                                selectedClosingHours.clock ===
                                                    whatTime.clock
                                                    ? 'hover:text-white'
                                                    : 'hover:bg-myPrimaryLinkColor text-white',
                                                'absolute inset-y-0 right-0 flex items-center pr-4 ',
                                            ]"
                                        >
                                            <CheckIcon
                                                v-if="
                                                    selectedClosingHours &&
                                                    whatTime &&
                                                    selectedClosingHours.clock ===
                                                        whatTime.clock
                                                "
                                                class="h-5 w-5"
                                                aria-hidden="true"
                                            />
                                        </span>
                                    </li>
                                </ListboxOption>
                            </ListboxOptions>
                        </transition>
                    </div>
                </Listbox>
            </div>
            <!-- Closing hours # end -->
        </div>
    </div>
</template>
