<script setup>
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
        <div class="flex flex-wrap gap-2 items-center justify-left py-4">
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
                class="py-1.5 px-2 flex justify-center items-center gap-1 myPrimaryTag font-medium"
                :class="[
                    {
                        'bg-myPrimaryLinkColor text-white': listSelected.some(
                            (cat) => cat.id === item.id
                        ),
                    },
                    {
                        '': listSelected.some((cat) => cat.id !== item.id),
                    },
                ]"
                :disabled="listSelected.name === item.name"
            >
                <template v-if="props.icon === 'interests'">
                    <span class="myMediumIcon material-symbols-outlined">
                        interests
                    </span>
                </template>

                <template v-if="props.icon === 'GlobeAmericasIcon'">
                    <span class="myMediumIcon material-symbols-outlined">
                        globe
                    </span>
                </template>

                <template v-if="props.icon === 'MapPinIcon'">
                    <span class="myMediumIcon material-symbols-outlined">
                        location_on
                    </span>
                </template>

                <template v-if="props.icon === 'NewspaperIcon'">
                    <span class="myMediumIcon material-symbols-outlined">
                        sell
                    </span>
                </template>

                {{ item.name }}
            </button>
        </div>
    </template>
</template>
