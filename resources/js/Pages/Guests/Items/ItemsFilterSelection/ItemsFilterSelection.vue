<script setup>
import {
    GlobeAmericasIcon,
    MapIcon,
    MapPinIcon,
    NewspaperIcon,
    Square2StackIcon,
    Squares2X2Icon,
    XMarkIcon,
} from "@heroicons/vue/24/outline";

import { ref, computed } from "vue";
import {
    Combobox,
    ComboboxInput,
    ComboboxButton,
    ComboboxOptions,
    ComboboxOption,
    TransitionRoot,
    Listbox,
    ListboxButton,
    ListboxLabel,
    ListboxOptions,
    ListboxOption,
} from "@headlessui/vue";
import { CheckIcon, ChevronUpDownIcon } from "@heroicons/vue/20/solid";

const people = [
    { id: 1, name: "Wade Cooper" },
    { id: 2, name: "Arlene Mccoy" },
    { id: 3, name: "Devon Webb" },
    { id: 4, name: "Tom Cook" },
    { id: 5, name: "Tanya Fox" },
    { id: 6, name: "Hellen Schmidt" },
];

let selected = ref(people[0]);
let query = ref("");

let filteredPeople = computed(() =>
    query.value === ""
        ? people
        : people.filter((person) =>
              person.name
                  .toLowerCase()
                  .replace(/\s+/g, "")
                  .includes(query.value.toLowerCase().replace(/\s+/g, ""))
          )
);

const emit = defineEmits(["selectItem", "removeItem"]);
const props = defineProps({
    nameOfList: {
        required: true,
    },
    list: {
        required: true,
    },
    listSelected: {
        required: true,
    },
    icon: {
        default: null,
        String,
        required: false,
    },
});

const handleSelectItem = function (selectedItem) {
    emit("selectItem", selectedItem);
};
const handleRemoveItem = function (selectedItem) {
    emit("removeItem", selectedItem);
};
</script>

<template>
    <!-- Selected # start -->
    <div>
        <Listbox>
            <div class="relative">
                <!-- Selected list # Start -->
                <ListboxButton
                    class="border border-gray-300 shadow-sm rounded-md w-full min-h-[3.5rem] h-[3.5rem] pt-2 pb-0 pr-8 pl-2 text-left text-sm"
                >
                    <span
                        v-if="listSelected.length === 0"
                        class="font-normal text-gray-500"
                    >
                        Select {{ nameOfList.toLowerCase() }}..
                    </span>

                    <ul
                        class="overflow-x-scroll flex gap-2 mr-4 pb-1 min-w-full w-full"
                    >
                        <li
                            @click.stop="
                                handleRemoveItem({
                                    name: singleSelection.name,
                                    id: singleSelection.id,
                                })
                            "
                            v-for="singleSelection in listSelected"
                            :key="singleSelection.id"
                            class="bg-gray-200 py-2 px-2 rounded-full hover:bg-myPrimaryErrorColor hover:text-white my-0"
                        >
                            <div class="flex justify-center items-center gap-2">
                                <template v-if="props.icon === 'interests'">
                                    <span class="material-symbols-outlined">
                                        interests
                                    </span>
                                </template>

                                <template
                                    v-if="props.icon === 'GlobeAmericasIcon'"
                                >
                                    <span class="material-symbols-outlined">
                                        globe
                                    </span>
                                </template>

                                <template v-if="props.icon === 'MapPinIcon'">
                                    <span class="material-symbols-outlined">
                                        location_on
                                    </span>
                                </template>

                                <template v-if="props.icon === 'NewspaperIcon'">
                                    <span class="material-symbols-outlined">
                                        sell
                                    </span>
                                </template>
                                <div class="w-max">
                                    {{ singleSelection.name }}
                                </div>
                                <span class="material-symbols-outlined">
                                    close
                                </span>
                            </div>
                        </li>
                    </ul>
                    <span
                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2"
                    >
                        <span class="material-symbols-outlined">
                            unfold_more
                        </span>
                    </span>
                </ListboxButton>
                <!-- Selected list # End -->

                <!-- List of option # start -->
                <transition
                    leave-active-class="transition duration-100 ease-in "
                    leave-from-class="opacity-100"
                    leave-to-class="opacity-0"
                >
                    <ListboxOptions
                        class="absolute mt-1 max-h-60 w-full overflow-auto rounded-md bg-white z-30 py-1 text-base shadow-lg ring-1 ring-black/5 focus:outline-none sm:text-sm"
                    >
                        <ListboxOption
                            v-for="item in list"
                            :key="item.id"
                            :value="item"
                        >
                            <li
                                v-if="
                                    !listSelected.some(
                                        (cat) => cat.id === item.id
                                    )
                                "
                                @click="
                                    handleSelectItem({
                                        name: item.name,
                                        id: item.id,
                                    })
                                "
                                class="cursor-pointer hover:bg-myPrimaryLinkColor hover:text-white text-myPrimaryDarkGrayColor relative select-none py-4 pl-3 pr-9"
                            >
                                <div
                                    class="flex justify-left items-center gap-2"
                                >
                                    <template v-if="props.icon === 'interests'">
                                        <span class="material-symbols-outlined">
                                            interests
                                        </span>
                                    </template>

                                    <template
                                        v-if="
                                            props.icon === 'GlobeAmericasIcon'
                                        "
                                    >
                                        <span class="material-symbols-outlined">
                                            globe
                                        </span>
                                    </template>

                                    <template
                                        v-if="props.icon === 'MapPinIcon'"
                                    >
                                        <span class="material-symbols-outlined">
                                            location_on
                                        </span>
                                    </template>

                                    <template
                                        v-if="props.icon === 'NewspaperIcon'"
                                    >
                                        <span class="material-symbols-outlined">
                                            sell
                                        </span>
                                    </template>
                                    {{ item.name }}
                                </div>
                            </li>
                        </ListboxOption>
                    </ListboxOptions>
                </transition>
                <!-- List of option # start -->
            </div>
        </Listbox>
    </div>
    <!-- Selected # end -->
</template>
