<script setup>
import { computed, ref } from "vue";

const props = defineProps({
    tagsOnLoad: {
        type: String,
    },
});

const error = ref(null);
const vueTag = ref("");
// const tagsEntered = ref([]);
const emit = defineEmits(["firstTagsButton"]);
const regexSpecialCharacters = /[ `!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~]/;

// if (props.tagsOnLoad.length !== 0) {
//     tagsEntered.value = props.tagsOnLoad.split(",");
// }

const tagsEntered = computed(() => {
    if (props.tagsOnLoad.length !== 0) {
        return props.tagsOnLoad.split(",");
    }
    if (props.tagsOnLoad.length === 0) {
        return [];
    }
});

// handle input
const handleInput = function () {
    // error
    error.value = null;
    // spaces are not allowed
    if (vueTag.value.includes(" ")) {
        error.value = "Spaces not allowed.";
        return;
    }
    // check for empty tag and special characters
    if (vueTag.value === "" || regexSpecialCharacters.test(vueTag.value)) {
        error.value = "Tag can not be empty or includes special characters.";
        return;
    }

    // check if tag already have been added
    if (tagsEntered.value.includes(vueTag.value)) {
        error.value = "This tag have already been added.";
        return;
    }
    // add to tags entered & remove all whitespaces
    if (!tagsEntered.value.includes(vueTag.value)) {
        tagsEntered.value.push(vueTag.value.trim());
        emit("firstTagsButton", tagsEntered.value.toString(","));
    }
    // clear vue model for tag
    vueTag.value = "";
};

// delete tag
const deleteTag = function (e) {
    tagsEntered.value = tagsEntered.value.filter((tag) => {
        return tag !== e.target.getAttribute("data-tag");
    });
    emit("firstTagsButton", tagsEntered.value.toString(","));
};
</script>

<template>
    <div>
        <div class="flex gap-2 items-center flex-wrap">
            <TransitionGroup name="tags">
                <template v-for="tag in tagsEntered" :key="tag">
                    <div
                        :data-tag="tag"
                        @click="deleteTag"
                        class="flex flex-wrap justify-center items-center gap-2 bg-myPrimaryBrandColor text-white font-semibold text-sm px-3 py-2 cursor-pointer rounded-2xl hover:bg-red-500"
                    >
                        <p :data-tag="tag">
                            {{ tag }}
                            <span :data-tag="tag">×</span>
                        </p>
                    </div>
                </template>
            </TransitionGroup>
        </div>
    </div>

    <div class="flex mt-3">
        <div class="mt-1 relative flex items-center w-full">
            <input
                v-model="vueTag"
                type="text"
                class="myPrimaryInput"
                placeholder="Enter tags.."
                @keydown.enter.tab.prevent="handleInput"
                autocomplete="off"
            />
            <div class="absolute inset-y-0 right-0 flex py-1.5 pr-1.5">
                <kbd
                    class="inline-flex items-center border border-gray-200 rounded px-2 text-xs font-sans font-medium text-gray-400"
                >
                    ⏎
                </kbd>
            </div>
        </div>
    </div>
    <p class="myPrimaryParagraph text-myErrorColor mpy-2">{{ error }}</p>
</template>
