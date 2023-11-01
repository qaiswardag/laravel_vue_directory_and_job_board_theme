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
    <template v-if="list">
        <div
            class="flex flex-wrap gap-2 items-center justify-left py-4 border-t-2 border-purple-700"
        >
            <button
                @click="
                    listSelected.some((cat) => cat.id === item.id)
                        ? handleRemoveItem(item)
                        : handleSelectItem({
                              name: item.name,
                              id: item.id,
                          })
                "
                v-for="item in list"
                :key="item.id"
                class="py-1.5 px-2 flex justify-center items-center gap-1 myPrimaryTag hover:bg-myPrimaryLinkColor hover:text-white"
                :class="[
                    {
                        'bg-myPrimaryLinkColor text-white': listSelected.some(
                            (cat) => cat.id === item.id
                        ),
                    },
                    {
                        'hover:bg-myPrimaryLinkColor hover:text-white ':
                            listSelected.some((cat) => cat.id !== item.id),
                    },
                ]"
                :disabled="listSelected.name === item.name"
            >
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
                    <MapPinIcon class="mySmallIcon py-0 m-0"></MapPinIcon>
                </template>

                <template v-if="props.icon === 'NewspaperIcon'">
                    <NewspaperIcon class="mySmallIcon py-0 m-0"></NewspaperIcon>
                </template>

                {{ item.name }}
            </button>
        </div>
    </template>
</template>
