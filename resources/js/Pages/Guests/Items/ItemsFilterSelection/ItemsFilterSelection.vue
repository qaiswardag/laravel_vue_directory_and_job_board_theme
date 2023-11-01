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
    <div class="bg-purple-100 p-4 my-12">
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

                            <template v-if="props.icon === 'GlobeAmericasIcon'">
                                <GlobeAmericasIcon
                                    class="mySmallIcon py-0 m-0"
                                ></GlobeAmericasIcon>
                            </template>

                            <template v-if="props.icon === 'MapPinIcon'">
                                <MapPinIcon
                                    class="mySmallIcon py-0 m-0"
                                ></MapPinIcon>
                            </template>

                            <template v-if="props.icon === 'NewspaperIcon'">
                                <NewspaperIcon
                                    class="mySmallIcon py-0 m-0"
                                ></NewspaperIcon>
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
                <template v-for="item in list" :key="item.id">
                    <button
                        v-if="!listSelected.some((cat) => cat.id === item.id)"
                        @click="
                            handleSelectItem({
                                name: item.name,
                                id: item.id,
                            })
                        "
                        class="myPrimaryTag"
                        :class="[
                            {
                                'bg-myPrimaryLinkColor text-white':
                                    listSelected.name === item.name,
                            },
                            {
                                'bg-myPrimaryLinkColor text-white':
                                    listSelected.name === item.name,
                            },
                        ]"
                        :disabled="listSelected.name === item.name"
                    >
                        <div class="flex justify-center items-center gap-1">
                            <template v-if="props.icon === 'Squares2X2Icon'">
                                <Squares2X2Icon
                                    class="mySmallIcon py-0 m-0"
                                ></Squares2X2Icon>
                            </template>

                            <template v-if="props.icon === 'GlobeAmericasIcon'">
                                <GlobeAmericasIcon
                                    class="mySmallIcon py-0 m-0"
                                ></GlobeAmericasIcon>
                            </template>

                            <template v-if="props.icon === 'MapPinIcon'">
                                <MapPinIcon
                                    class="mySmallIcon py-0 m-0"
                                ></MapPinIcon>
                            </template>

                            <template v-if="props.icon === 'NewspaperIcon'">
                                <NewspaperIcon
                                    class="mySmallIcon py-0 m-0"
                                ></NewspaperIcon>
                            </template>
                            {{ item.name }}
                        </div>
                    </button>
                </template>
            </div>
        </div>
        <!-- List # End -->
    </div>
</template>
