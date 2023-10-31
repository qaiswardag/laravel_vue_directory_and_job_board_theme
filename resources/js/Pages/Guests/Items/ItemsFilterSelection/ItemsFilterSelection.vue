<script setup>
import {
    GlobeAmericasIcon,
    MapIcon,
    MapPinIcon,
    NewspaperIcon,
    Square2StackIcon,
    Squares2X2Icon,
} from "@heroicons/vue/24/outline";

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
    <!-- Selected list # Start -->
    <div class="border border-gray-200 rounded py-4 h-24 min-h-full">
        <p class="text-xs">Selected {{ nameOfList }}</p>
        <div class="overflow-x-scroll flex gap-2">
            <template
                v-for="singleSelection in listSelected"
                :key="singleSelection.id"
            >
                <button
                    @click="
                        handleRemoveItem({
                            name: singleSelection.name,
                            id: singleSelection.id,
                        })
                    "
                    class="myPrimaryTag"
                >
                    <div class="flex justify-center items-center gap-1">
                        <template v-if="props.icon === 'Squares2X2Icon'">
                            <Squares2X2Icon
                                class="mySmallIcon py-0 m-0"
                            ></Squares2X2Icon>
                        </template>

                        <span class="block w-max">
                            {{ singleSelection.name }}
                        </span>
                    </div>
                </button>
            </template>
        </div>
    </div>
    <!-- Selected list # End -->

    <!-- List # Start -->
    <div class="my-12 myPrimaryDarkGrayColor">
        <p class="text-xs">{{ nameOfList }}</p>
        <div
            class="flex gap-2 flex-wrap py-4 border-t border-b border-gray-200"
        >
            <template v-for="category in list" :key="category.id">
                <button
                    v-if="!listSelected.some((cat) => cat.id === category.id)"
                    @click="
                        handleSelectItem({
                            name: category.name,
                            id: category.id,
                        })
                    "
                    class="myPrimaryTag"
                    :class="[
                        {
                            'bg-myPrimaryLinkColor text-white':
                                listSelected.name === category.name,
                        },
                        {
                            'bg-myPrimaryLinkColor text-white':
                                listSelected.name === category.name,
                        },
                    ]"
                    :disabled="listSelected.name === category.name"
                >
                    <div class="flex justify-center items-center gap-1">
                        <template v-if="props.icon === 'Squares2X2Icon'">
                            <Squares2X2Icon
                                class="mySmallIcon py-0 m-0"
                            ></Squares2X2Icon>
                        </template>
                        {{ category.name }}
                    </div>
                </button>
            </template>
        </div>
    </div>
    <!-- List # End -->
</template>
