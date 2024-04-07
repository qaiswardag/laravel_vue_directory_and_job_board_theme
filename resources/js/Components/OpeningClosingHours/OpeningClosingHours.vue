<script setup>
import { computed, onMounted, ref, watch } from "vue";
import { useStore } from "vuex";
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
    openingClockFromDatabaseOrLocalStorage: {
        required: true,
    },
    closingClockFromDatabaseOrLocalStorage: {
        required: true,
    },
});

const store = useStore();

const openingHours = [
    { id: 0, clock: "Closed", value: null },
    { id: 1, clock: "00:00:00", value: "00:00:00" },
    { id: 2, clock: "00:30:00", value: "00:30:00" },
    { id: 3, clock: "01:00:00", value: "01:00:00" },
    { id: 4, clock: "01:30:00", value: "01:30:00" },
    { id: 5, clock: "02:00:00", value: "02:00:00" },
    { id: 6, clock: "02:30:00", value: "02:30:00" },
    { id: 7, clock: "03:00:00", value: "03:00:00" },
    { id: 8, clock: "03:30:00", value: "03:30:00" },
    { id: 9, clock: "04:00:00", value: "04:00:00" },
    { id: 10, clock: "04:30:00", value: "04:30:00" },
    { id: 11, clock: "05:00:00", value: "05:00:00" },
    { id: 12, clock: "05:30:00", value: "05:30:00" },
    { id: 13, clock: "06:00:00", value: "06:00:00" },
    { id: 14, clock: "06:30:00", value: "06:30:00" },
    { id: 15, clock: "07:00:00", value: "07:00:00" },
    { id: 16, clock: "07:30:00", value: "07:30:00" },
    { id: 17, clock: "08:00:00", value: "08:00:00" },
    { id: 18, clock: "08:30:00", value: "08:30:00" },
    { id: 19, clock: "09:00:00", value: "09:00:00" },
    { id: 20, clock: "09:30:00", value: "09:30:00" },
    { id: 21, clock: "10:00:00", value: "10:00:00" },
    { id: 22, clock: "10:30:00", value: "10:30:00" },
    { id: 23, clock: "11:00:00", value: "11:00:00" },
    { id: 24, clock: "11:30:00", value: "11:30:00" },
    { id: 25, clock: "12:00:00", value: "12:00:00" },
    { id: 26, clock: "12:30:00", value: "12:30:00" },
    { id: 27, clock: "13:00:00", value: "13:00:00" },
    { id: 28, clock: "13:30:00", value: "13:30:00" },
    { id: 29, clock: "14:00:00", value: "14:00:00" },
    { id: 30, clock: "14:30:00", value: "14:30:00" },
    { id: 31, clock: "15:00:00", value: "15:00:00" },
    { id: 32, clock: "15:30:00", value: "15:30:00" },
    { id: 33, clock: "16:00:00", value: "16:00:00" },
    { id: 34, clock: "16:30:00", value: "16:30:00" },
    { id: 35, clock: "17:00:00", value: "17:00:00" },
    { id: 36, clock: "17:30:00", value: "17:30:00" },
    { id: 37, clock: "18:00:00", value: "18:00:00" },
    { id: 38, clock: "18:30:00", value: "18:30:00" },
    { id: 39, clock: "19:00:00", value: "19:00:00" },
    { id: 40, clock: "19:30:00", value: "19:30:00" },
    { id: 41, clock: "20:00:00", value: "20:00:00" },
    { id: 42, clock: "20:30:00", value: "20:30:00" },
    { id: 43, clock: "21:00:00", value: "21:00:00" },
    { id: 44, clock: "21:30:00", value: "21:30:00" },
    { id: 45, clock: "22:00:00", value: "22:00:00" },
    { id: 46, clock: "22:30:00", value: "22:30:00" },
    { id: 47, clock: "23:00:00", value: "23:00:00" },
    { id: 48, clock: "23:30:00", value: "23:30:00" },
];
const closingHours = [
    { id: 0, clock: "Closed", value: null },
    { id: 1, clock: "00:00:00", value: "00:00:00" },
    { id: 2, clock: "00:30:00", value: "00:30:00" },
    { id: 3, clock: "01:00:00", value: "01:00:00" },
    { id: 4, clock: "01:30:00", value: "01:30:00" },
    { id: 5, clock: "02:00:00", value: "02:00:00" },
    { id: 6, clock: "02:30:00", value: "02:30:00" },
    { id: 7, clock: "03:00:00", value: "03:00:00" },
    { id: 8, clock: "03:30:00", value: "03:30:00" },
    { id: 9, clock: "04:00:00", value: "04:00:00" },
    { id: 10, clock: "04:30:00", value: "04:30:00" },
    { id: 11, clock: "05:00:00", value: "05:00:00" },
    { id: 12, clock: "05:30:00", value: "05:30:00" },
    { id: 13, clock: "06:00:00", value: "06:00:00" },
    { id: 14, clock: "06:30:00", value: "06:30:00" },
    { id: 15, clock: "07:00:00", value: "07:00:00" },
    { id: 16, clock: "07:30:00", value: "07:30:00" },
    { id: 17, clock: "08:00:00", value: "08:00:00" },
    { id: 18, clock: "08:30:00", value: "08:30:00" },
    { id: 19, clock: "09:00:00", value: "09:00:00" },
    { id: 20, clock: "09:30:00", value: "09:30:00" },
    { id: 21, clock: "10:00:00", value: "10:00:00" },
    { id: 22, clock: "10:30:00", value: "10:30:00" },
    { id: 23, clock: "11:00:00", value: "11:00:00" },
    { id: 24, clock: "11:30:00", value: "11:30:00" },
    { id: 25, clock: "12:00:00", value: "12:00:00" },
    { id: 26, clock: "12:30:00", value: "12:30:00" },
    { id: 27, clock: "13:00:00", value: "13:00:00" },
    { id: 28, clock: "13:30:00", value: "13:30:00" },
    { id: 29, clock: "14:00:00", value: "14:00:00" },
    { id: 30, clock: "14:30:00", value: "14:30:00" },
    { id: 31, clock: "15:00:00", value: "15:00:00" },
    { id: 32, clock: "15:30:00", value: "15:30:00" },
    { id: 33, clock: "16:00:00", value: "16:00:00" },
    { id: 34, clock: "16:30:00", value: "16:30:00" },
    { id: 35, clock: "17:00:00", value: "17:00:00" },
    { id: 36, clock: "17:30:00", value: "17:30:00" },
    { id: 37, clock: "18:00:00", value: "18:00:00" },
    { id: 38, clock: "18:30:00", value: "18:30:00" },
    { id: 39, clock: "19:00:00", value: "19:00:00" },
    { id: 40, clock: "19:30:00", value: "19:30:00" },
    { id: 41, clock: "20:00:00", value: "20:00:00" },
    { id: 42, clock: "20:30:00", value: "20:30:00" },
    { id: 43, clock: "21:00:00", value: "21:00:00" },
    { id: 44, clock: "21:30:00", value: "21:30:00" },
    { id: 45, clock: "22:00:00", value: "22:00:00" },
    { id: 46, clock: "22:30:00", value: "22:30:00" },
    { id: 47, clock: "23:00:00", value: "23:00:00" },
    { id: 48, clock: "23:30:00", value: "23:30:00" },
];

const selectedOpeningHours = ref(null);
const selectedClosingHours = ref(null);

// opening hours
watch(selectedOpeningHours, (newValue) => {
    if (props.resourceId === "store") {
        if (props.weekday === "monday") {
            store.commit("fashionStore/setMondayOpeningTime", newValue.value);
        }
        if (props.weekday === "tuesday") {
            store.commit("fashionStore/setTuesdayOpeningTime", newValue.value);
        }
        if (props.weekday === "wednesday") {
            store.commit(
                "fashionStore/setWednesdayOpeningTime",
                newValue.value
            );
        }
        if (props.weekday === "thursday") {
            store.commit("fashionStore/setThursdayOpeningTime", newValue.value);
        }
        if (props.weekday === "friday") {
            store.commit("fashionStore/setFridayOpeningTime", newValue.value);
        }
        if (props.weekday === "saturday") {
            store.commit("fashionStore/setSaturdayOpeningTime", newValue.value);
        }
        if (props.weekday === "sunday") {
            store.commit("fashionStore/setSundayOpeningTime", newValue.value);
        }
    }

    if (props.resourceId === "team") {
        if (props.weekday === "monday") {
            store.commit("team/setMondayOpeningTime", newValue.value);
        }
        if (props.weekday === "tuesday") {
            store.commit("team/setTuesdayOpeningTime", newValue.value);
        }
        if (props.weekday === "wednesday") {
            store.commit("team/setWednesdayOpeningTime", newValue.value);
        }
        if (props.weekday === "thursday") {
            store.commit("team/setThursdayOpeningTime", newValue.value);
        }
        if (props.weekday === "friday") {
            store.commit("team/setFridayOpeningTime", newValue.value);
        }
        if (props.weekday === "saturday") {
            store.commit("team/setSaturdayOpeningTime", newValue.value);
        }
        if (props.weekday === "sunday") {
            store.commit("team/setSundayOpeningTime", newValue.value);
        }
    }
});

// closing hours
watch(selectedClosingHours, (newValue) => {
    if (props.resourceId === "store") {
        if (props.weekday === "monday") {
            store.commit("fashionStore/setMondayClosingTime", newValue.value);
        }
        if (props.weekday === "tuesday") {
            store.commit("fashionStore/setTuesdayClosingTime", newValue.value);
        }
        if (props.weekday === "wednesday") {
            store.commit(
                "fashionStore/setWednesdayClosingTime",
                newValue.value
            );
        }
        if (props.weekday === "thursday") {
            store.commit("fashionStore/setThursdayClosingTime", newValue.value);
        }
        if (props.weekday === "friday") {
            store.commit("fashionStore/setFridayClosingTime", newValue.value);
        }
        if (props.weekday === "saturday") {
            store.commit("fashionStore/setSaturdayClosingTime", newValue.value);
        }
        if (props.weekday === "sunday") {
            store.commit("fashionStore/setSundayClosingTime", newValue.value);
        }
    }

    if (props.resourceId === "team") {
        if (props.weekday === "monday") {
            store.commit("team/setMondayClosingTime", newValue.value);
        }
        if (props.weekday === "tuesday") {
            store.commit("team/setTuesdayClosingTime", newValue.value);
        }
        if (props.weekday === "wednesday") {
            store.commit("team/setWednesdayClosingTime", newValue.value);
        }
        if (props.weekday === "thursday") {
            store.commit("team/setThursdayClosingTime", newValue.value);
        }
        if (props.weekday === "friday") {
            store.commit("team/setFridayClosingTime", newValue.value);
        }
        if (props.weekday === "saturday") {
            store.commit("team/setSaturdayClosingTime", newValue.value);
        }
        if (props.weekday === "sunday") {
            store.commit("team/setSundayClosingTime", newValue.value);
        }
    }
});

//
//
//
//

onMounted(() => {
    selectedOpeningHours.value = openingHours[0];
    selectedClosingHours.value = closingHours[0];

    openingHours.forEach((clock) => {
        if (clock.value === props.openingClockFromDatabaseOrLocalStorage) {
            selectedOpeningHours.value = clock;
        }
    });

    closingHours.forEach((clock) => {
        if (clock.value === props.closingClockFromDatabaseOrLocalStorage) {
            selectedClosingHours.value = clock;
        }
    });
});
</script>

<template>
    <div>
        <div class="pt-2 flex items-center justify-start myPrimaryGap">
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
