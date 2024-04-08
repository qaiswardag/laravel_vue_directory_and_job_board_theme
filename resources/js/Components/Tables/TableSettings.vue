<script setup>
import { ref, watch, onMounted } from "vue";
import { useStore } from "vuex";

import {
    Listbox,
    ListboxButton,
    ListboxLabel,
    ListboxOption,
    ListboxOptions,
} from "@headlessui/vue";

const store = useStore();

const settings = ref({});

const showPostId = ref(false);

const showCategories = ref(false);

const peopleShow = ref(false);

const createdUpdatedDateShow = ref(false);

// post id
watch(showPostId, (newValue) => {
    if (newValue) {
        settings.value.showPostId = true;

        const settingsString = JSON.stringify(settings.value);

        store.commit("user/setUserSettings", settings);

        localStorage.setItem("settings", settingsString);
    }
    if (!newValue) {
        settings.value.showPostId = false;

        const settingsString = JSON.stringify(settings.value);

        store.commit("user/setUserSettings", settings);

        localStorage.setItem("settings", settingsString);
    }
});

// categories
watch(showCategories, (newValue) => {
    if (newValue) {
        settings.value.categoriesShow = true;

        const settingsString = JSON.stringify(settings.value);

        store.commit("user/setUserSettings", settings);

        localStorage.setItem("settings", settingsString);
    }
    if (!newValue) {
        settings.value.categoriesShow = false;

        const settingsString = JSON.stringify(settings.value);

        store.commit("user/setUserSettings", settings);

        localStorage.setItem("settings", settingsString);
    }
});

// people
watch(peopleShow, (newValue) => {
    if (newValue) {
        settings.value.authorsOrPeopleShow = true;

        const settingsString = JSON.stringify(settings.value);

        store.commit("user/setUserSettings", settings);

        localStorage.setItem("settings", settingsString);
    }
    if (!newValue) {
        settings.value.authorsOrPeopleShow = false;

        const settingsString = JSON.stringify(settings.value);

        store.commit("user/setUserSettings", settings);

        localStorage.setItem("settings", settingsString);
    }
});

// dates
watch(createdUpdatedDateShow, (newValue) => {
    if (newValue) {
        settings.value.createdUpdatedDateShow = true;

        const settingsString = JSON.stringify(settings.value);

        store.commit("user/setUserSettings", settings);

        localStorage.setItem("settings", settingsString);
    }
    if (!newValue) {
        settings.value.createdUpdatedDateShow = false;

        const settingsString = JSON.stringify(settings.value);

        store.commit("user/setUserSettings", settings);

        localStorage.setItem("settings", settingsString);
    }
});

onMounted(() => {
    // Check if 'settings' is already set in local storage
    const existingSettingsString = localStorage.getItem("settings");

    if (!existingSettingsString) {
        settings.value = {
            showPostId: false,
            categoriesShow: false,
            authorsOrPeopleShow: false,
            createdUpdatedDateShow: false,
        };

        const settingsString = JSON.stringify(settings.value);

        store.commit("user/setUserSettings", settings);

        localStorage.setItem("settings", settingsString);
    }

    if (existingSettingsString) {
        const tableSettings = JSON.parse(existingSettingsString);

        if (tableSettings) {
            // post id
            if (tableSettings.showPostId) {
                showPostId.value = true;
            }
            if (!tableSettings.showPostId) {
                showPostId.value = false;
            }

            // categories
            if (tableSettings.categoriesShow) {
                showCategories.value = true;
            }
            if (!tableSettings.categoriesShow) {
                showCategories.value = false;
            }

            // people
            if (tableSettings.authorsOrPeopleShow) {
                peopleShow.value = true;
            }
            if (!tableSettings.authorsOrPeopleShow) {
                peopleShow.value = false;
            }

            // dates
            if (tableSettings.createdUpdatedDateShow) {
                createdUpdatedDateShow.value = true;
            }
            if (!tableSettings.createdUpdatedDateShow) {
                createdUpdatedDateShow.value = false;
            }
        }

        store.commit("user/setUserSettings", tableSettings);
    }
});
</script>
<template>
    <div class="border border-myPrimaryMediumGrayColor rounded py-2 px-2 my-2">
        <div
            class="flex items-center justify-start myPrimaryGap overflow-x-scroll w-full px-2 py-4"
        >
            <div class="relative flex items-center">
                <div class="flex h-6 items-center">
                    <input
                        id="post_id"
                        name="post_id"
                        v-model="showPostId"
                        type="checkbox"
                        class="h-5 w-5 rounded border-gray-300 text-myPrimaryBrandColor focus:ring-myPrimaryBrandColor"
                    />
                </div>
                <div class="w-max min-w-0 flex-1 text-xs">
                    <label
                        for="post_id"
                        class="pl-2 select-none font-medium text-gray-900"
                        >ID
                    </label>
                </div>
            </div>

            <div class="relative flex items-center">
                <div class="flex h-6 items-center">
                    <input
                        id="categories"
                        name="categories"
                        v-model="showCategories"
                        type="checkbox"
                        class="h-5 w-5 rounded border-gray-300 text-myPrimaryBrandColor focus:ring-myPrimaryBrandColor"
                    />
                </div>
                <div class="w-max min-w-0 flex-1 text-xs">
                    <label
                        for="categories"
                        class="pl-2 select-none font-medium text-gray-900"
                        >Categories
                    </label>
                </div>
            </div>

            <div class="relative flex items-center">
                <div class="flex h-6 items-center">
                    <input
                        id="dates"
                        name="dates"
                        v-model="createdUpdatedDateShow"
                        type="checkbox"
                        class="h-5 w-5 rounded border-gray-300 text-myPrimaryBrandColor focus:ring-myPrimaryBrandColor"
                    />
                </div>
                <div class="w-max min-w-0 flex-1 text-xs">
                    <label
                        for="dates"
                        class="pl-2 select-none font-medium text-gray-900"
                        >Created and updated
                    </label>
                </div>
            </div>
            <div class="relative flex items-center">
                <div class="flex h-6 items-center">
                    <input
                        id="people"
                        name="people"
                        v-model="peopleShow"
                        type="checkbox"
                        class="h-5 w-5 rounded border-gray-300 text-myPrimaryBrandColor focus:ring-myPrimaryBrandColor"
                    />
                </div>
                <div class="w-max min-w-0 flex-1 text-xs">
                    <label
                        for="people"
                        class="pl-2 select-none font-medium text-gray-900"
                        >People
                    </label>
                </div>
            </div>
        </div>
    </div>
</template>
