<script setup>
import Modal from "@/Components/Modals/Modal.vue";
import { ref, computed, onMounted } from "vue";
import { TailwindPagination } from "laravel-vue-pagination";
import { useStore } from "vuex";
import SmallUniversalSpinner from "@/Components/Loaders/SmallUniversalSpinner.vue";

import {
    Squares2X2Icon,
    TrashIcon,
    CheckIcon,
    MapPinIcon,
    GlobeAmericasIcon,
    PlusIcon,
    MinusIcon,
    NewspaperIcon,
} from "@heroicons/vue/24/outline";

const props = defineProps({
    team: {
        required: true,
    },
    user: {
        required: true,
    },
    show: {
        type: Boolean,
        default: false,
        required: true,
    },
    title: {
        type: String,
        required: true,
    },
    description: {
        required: true,
    },
    firstButtonText: {
        type: String,
    },
    secondButtonText: {
        type: String,
    },
    existingItems: {
        required: true,
    },
    apiUrlRouteName: {
        type: String,
        required: true,
    },
    vuexActionMethod: {
        type: String,
        required: true,
    },
    vuexGetCurrentItems: {
        type: String,
        required: true,
    },
    vuexGetCurrentAttachedItems: {
        type: String,
        required: true,
    },
    vuexSetCurrentAttachedItems: {
        type: String,
        required: true,
    },
    vuexSetRemoveAttachedItem: {
        type: String,
        required: true,
    },
    vuexSetCurrentAttachedItemsToEmptyArray: {
        type: String,
        required: true,
    },
    displayIcon: {
        default: null,
        Boolean,
        required: true,
    },
    icon: {
        default: null,
        String,
        required: false,
    },
});

// store
const store = useStore();

const frontEndError = ref(null);

const getCurrentItems = computed(() => {
    return store.getters[props.vuexGetCurrentItems];
});

const getCurrentAttachedItems = computed(() => {
    const items = store.getters[props.vuexGetCurrentAttachedItems];

    if (Array.isArray(items)) {
        const filteredItems = items.filter(
            (item) =>
                item &&
                (item.name !== undefined || item.first_name !== undefined)
        );

        return filteredItems.sort((a, b) => {
            if (a.name !== undefined && b.name !== undefined) {
                // Sort by 'name' if both 'name' properties are defined.
                const nameA = a.name;
                const nameB = b.name;
                return nameA.localeCompare(nameB);
            }
            if (a.first_name !== undefined && b.first_name !== undefined) {
                // Sort by 'first_name' if both 'first_name' properties are defined.
                const firstNameA = a.first_name;
                const firstNameB = b.first_name;
                return firstNameA.localeCompare(firstNameB);
            }
            if (a.name !== undefined) {
                // Sort by 'name' if only 'name' is defined for one of the items.
                return -1;
            }
            if (a.first_name !== undefined) {
                // Sort by 'first_name' if only 'first_name' is defined for one of the items.
                return 1;
            }
            // Both items have neither 'name' nor 'first_name' defined.
            return 0;
        });
    }
});

//
const filteredItems = ref([]);
//
const search_query = ref("");
// handle search
const handleSearch = function (page) {
    // dispatch
    store.dispatch(
        props.vuexActionMethod,
        {
            teamId: props.team.id,
            page: page,
            search_query: search_query.value,
            apiUrlRouteName: props.apiUrlRouteName,
        },
        {}
    );
};

// get result for "laravel pagination" package
const getResultsForPage = (page = 1) => {
    handleSearch(page);
};

const emit = defineEmits([
    "firstModalButtonSearchItemsFunction",
    "secondModalButtonSearchItemsFunction",
]);

// first button function
const firstButton = function () {
    emit("firstModalButtonSearchItemsFunction");
};
// second button  function
const secondButton = function () {
    emit("secondModalButtonSearchItemsFunction");
};

const isItemAlreadyAdded = function (item) {
    const isItemExists = getCurrentAttachedItems.value.some(
        (u) => u.id === item.id
    );

    // User with matching ID already exists
    if (isItemExists === true) {
        return true;
    }
    // User with matching ID do not exists
    if (isItemExists === false) {
        return false;
    }
};

const handleAttachItem = function (item) {
    const isItemExists = getCurrentAttachedItems.value.some(
        (u) => u.id === item.id
    );

    // Item with matching ID already exists
    if (isItemExists === true) {
        frontEndError.value = {
            id: item.id,
            error: "Item aldready added.",
        };
    }
    // Item with matching ID do not exists
    if (isItemExists === false) {
        frontEndError.value = null;
        store.commit(props.vuexSetCurrentAttachedItems, item);
    }
};
const handleRemoveAttachedItem = function (itemId) {
    // filter the array to exclude user with matching ID
    filteredItems.value = getCurrentAttachedItems.value.filter(
        (item) => item.id !== itemId
    );
    store.commit(props.vuexSetRemoveAttachedItem, filteredItems.value);
};

onMounted(() => {
    store.commit(props.vuexSetCurrentAttachedItemsToEmptyArray, []);

    if (
        (props.existingItems !== null || props.existingItems !== undefined) &&
        Array.isArray(props.existingItems)
    ) {
        props.existingItems.forEach((item) => {
            store.commit(props.vuexSetCurrentAttachedItems, item);
        });
    }

    handleSearch(1);
});
</script>

<template>
    <Modal
        :show="show"
        @close="firstButton"
        maxWidth="4xl"
        minHeight="min-h-[50rem]"
        maxHeight="max-h-[50rem]"
    >
        <div
            class="mb-24 px-4 w-full relative inline-block align-bottom text-left overflow-hidden transform transition-all sm:align-middle"
        >
            <div
                class="flex items-center border-b border-gray-200 pb-2 mb-2 justify-between"
            >
                <h3 class="tertiaryHeader my-0 py-0">
                    {{ title }}
                </h3>
                <div @click="firstButton" class="flex-end">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        aria-hidden="true"
                        class="myMediumIcon cursor-pointer"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M6 18L18 6M6 6l12 12"
                        ></path>
                    </svg>
                </div>
            </div>

            <!-- content start -->
            <div
                class="h-full flex md:flex-row flex-col myPrimaryGap mt-2 p-2 overflow-y-scroll"
            >
                <main class="overflow-y-auto relativ w-full">
                    <div>
                        <template
                            v-if="getCurrentItems && !getCurrentItems.isError"
                        >
                            <form @submit.prevent="handleSearch(1)">
                                <div class="mysearchBarWithOptions">
                                    <div class="relative w-full">
                                        <div
                                            class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none"
                                        >
                                            <svg
                                                aria-hidden="true"
                                                class="w-5 h-5 text-gray-500 dark:text-gray-400"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="1.5"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                                ></path>
                                            </svg>
                                        </div>
                                        <input
                                            type="search"
                                            id="search_query"
                                            v-model="search_query"
                                            class="myPrimarySearchInput"
                                            autocomplete="off"
                                            placeholder="Search..."
                                        />
                                    </div>

                                    <button
                                        @click="handleSearch(1)"
                                        type="button"
                                        class="myPrimarySearchButton"
                                    >
                                        Search
                                    </button>
                                </div>
                            </form>
                        </template>

                        <!-- Results # start -->
                        <template
                            v-if="
                                getCurrentItems &&
                                !getCurrentItems.isError &&
                                getCurrentItems.fetchedData &&
                                getCurrentItems.fetchedData.items &&
                                getCurrentItems.fetchedData.items.data &&
                                getCurrentItems.fetchedData.total_results !== 0
                            "
                        >
                            <div class="flex justify-start items-center">
                                <div class="myPrimaryTag">
                                    Total
                                    {{ getCurrentItems.fetchedData.count }}
                                    {{
                                        getCurrentItems.fetchedData.count
                                            .length === 1
                                            ? "item"
                                            : "items"
                                    }}
                                </div>
                            </div>
                        </template>
                        <!-- Results # end -->

                        <!-- Pagination # start -->
                        <template
                            v-if="
                                getCurrentItems &&
                                getCurrentItems.fetchedData &&
                                getCurrentItems.fetchedData.items &&
                                !getCurrentItems.isError &&
                                getCurrentItems.fetchedData.total_results !== 0
                            "
                        >
                            <div
                                class="flex items-center justify-around border-t border-gray-200 bg-white py-3 mt-4 gap-2 flex-wrap-reverse"
                            >
                                <TailwindPagination
                                    :limit="1"
                                    :keepLength="true"
                                    :class="[
                                        'space-x-1',
                                        'shadow-none',
                                        'tailwind-pagination-package',
                                    ]"
                                    :active-classes="[
                                        'bg-myPrimaryLinkColor',
                                        'text-white',
                                        'rounded-full',
                                    ]"
                                    :item-classes="[
                                        'p-0',
                                        'm-0',
                                        'border-none',
                                        'bg-myPrimaryLightGrayColor',
                                        'shadow-sm',
                                        'text-myPrimaryDarkGrayColor',
                                        'rounded-full',
                                    ]"
                                    :data="getCurrentItems.fetchedData.items"
                                    @pagination-change-page="getResultsForPage"
                                >
                                    <template #prev-nav>
                                        <span> Prev </span>
                                    </template>
                                    <template #next-nav>
                                        <span>Next</span>
                                    </template>
                                </TailwindPagination>
                            </div>
                        </template>
                        <!-- Pagination # end -->

                        <!-- Errors # start -->
                        <template
                            v-if="
                                getCurrentItems &&
                                !getCurrentItems.isLoading &&
                                getCurrentItems.isError
                            "
                        >
                            <p class="myPrimaryParagraphError">
                                {{ getCurrentItems.error }}
                            </p>
                        </template>
                        <!-- Errors # end -->

                        <!-- Loading # start -->
                        <template
                            v-if="getCurrentItems && getCurrentItems.isLoading"
                        >
                            <SmallUniversalSpinner
                                class="h-40"
                                width="w-6"
                                height="h-6"
                                border="border-4"
                            ></SmallUniversalSpinner>
                        </template>
                        <!-- Loading # end -->

                        <!-- Actual data # start -->
                        <template
                            v-if="
                                getCurrentItems &&
                                !getCurrentItems.isLoading &&
                                !getCurrentItems.isError
                            "
                        >
                            <div
                                v-if="
                                    getCurrentItems.isSuccess &&
                                    getCurrentItems.fetchedData &&
                                    getCurrentItems.fetchedData.items &&
                                    getCurrentItems.fetchedData.items.data
                                "
                                class="h-full md:max-h-[26.3rem] max-h-[13rem] overflow-y-scroll p-2"
                            >
                                <div
                                    v-if="
                                        getCurrentItems.fetchedData.items.data
                                            .length === 0
                                    "
                                    class="flex justify-between items-center my-2 myPrimaryTag w-max gap-4"
                                >
                                    <p class="myPrimaryParagraph">
                                        It looks like there are no items..
                                    </p>
                                </div>
                                <div
                                    v-if="
                                        getCurrentItems.fetchedData.items.data
                                            .length !== 0
                                    "
                                    class="rounded divide-y divide-gray-200 flex flex-col w-full gap-2 px-2 p-4 border border-myPrimaryLightGrayColor"
                                >
                                    <div
                                        v-for="item in getCurrentItems
                                            .fetchedData.items.data"
                                        :key="item.id"
                                    >
                                        <div
                                            v-if="
                                                getCurrentItems.fetchedData
                                                    .items.data !== 0
                                            "
                                            class="flex justify-between items-center my-2 myPrimaryTag w-max gap-4"
                                        >
                                            <div
                                                class="flex items-center gap-4 my-2"
                                            >
                                                <!-- start photo -->
                                                <div
                                                    class="flex-shrink-0"
                                                    v-if="
                                                        item &&
                                                        item.profile_photo_path
                                                    "
                                                >
                                                    <img
                                                        class="object-cover h-12 w-12 rounded-full"
                                                        :src="`/storage/${item.profile_photo_path}`"
                                                        alt="Image"
                                                    />
                                                </div>

                                                <div
                                                    v-if="
                                                        item &&
                                                        item.first_name &&
                                                        item.last_name &&
                                                        !item.profile_photo_path
                                                    "
                                                    class="flex-shrink-0 myPrimaryParagraph h-12 w-12 gap-0.5 rounded-full bg-myPrimaryBrandColor flex justify-center items-center text-xs font-normal text-white"
                                                >
                                                    <span>
                                                        {{
                                                            item?.first_name
                                                                .charAt(0)
                                                                .toUpperCase()
                                                        }}
                                                    </span>
                                                    <span>
                                                        {{
                                                            item?.last_name
                                                                .charAt(0)
                                                                .toUpperCase()
                                                        }}
                                                    </span>
                                                </div>

                                                <!-- end photo -->
                                                <div
                                                    v-if="
                                                        item.first_name &&
                                                        item.last_name
                                                    "
                                                >
                                                    <p
                                                        class="flex flex-col items-left gap-0.5 myPrimaryParagraph text-xs"
                                                    >
                                                        <span
                                                            class="font-medium"
                                                        >
                                                            {{
                                                                item.first_name
                                                            }}
                                                            {{ item.last_name }}
                                                        </span>
                                                        <span>
                                                            Email:
                                                            {{ item.email }}
                                                        </span>
                                                        <span>
                                                            Role:
                                                            {{ item.role }}
                                                        </span>
                                                    </p>
                                                </div>

                                                <div
                                                    class="flex flex-col items-left gap-0.5 myPrimaryParagraph text-xs"
                                                >
                                                    <div
                                                        @click="
                                                            handleAttachItem(
                                                                item
                                                            )
                                                        "
                                                        class="flex items-center gap-4 my-2"
                                                        v-if="item.name"
                                                    >
                                                        <button
                                                            type="button"
                                                            class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                                                        >
                                                            <div
                                                                v-if="
                                                                    props.icon ===
                                                                    'interests'
                                                                "
                                                            >
                                                                <span
                                                                    class="myMediumIcon material-symbols-outlined"
                                                                >
                                                                    interests
                                                                </span>
                                                            </div>
                                                            <div
                                                                v-if="
                                                                    props.icon ===
                                                                    'CheckIcon'
                                                                "
                                                            >
                                                                <span
                                                                    class="myMediumIcon material-symbols-outlined"
                                                                >
                                                                    check
                                                                </span>
                                                            </div>
                                                            <div
                                                                v-if="
                                                                    props.icon ===
                                                                    'MapPinIcon'
                                                                "
                                                            >
                                                                <span
                                                                    class="myMediumIcon material-symbols-outlined"
                                                                >
                                                                    location_on
                                                                </span>
                                                            </div>

                                                            <div
                                                                v-if="
                                                                    props.icon ===
                                                                    'GlobeAmericasIcon'
                                                                "
                                                            >
                                                                <span
                                                                    class="myMediumIcon material-symbols-outlined"
                                                                >
                                                                    globe
                                                                </span>
                                                            </div>
                                                            <div
                                                                v-if="
                                                                    props.icon ===
                                                                    'NewspaperIcon'
                                                                "
                                                            >
                                                                <span
                                                                    class="myMediumIcon material-symbols-outlined"
                                                                >
                                                                    sell
                                                                </span>
                                                            </div>
                                                        </button>
                                                        <span
                                                            class="font-medium"
                                                        >
                                                            {{ item.name }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <button
                                                @click="handleAttachItem(item)"
                                                v-if="
                                                    isItemAlreadyAdded(item) ===
                                                    false
                                                "
                                                class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-myPrimaryLinkColor text-white"
                                            >
                                                <span
                                                    class="myMediumIcon material-symbols-outlined"
                                                >
                                                    add
                                                </span>
                                            </button>
                                            <button
                                                @click="
                                                    handleRemoveAttachedItem(
                                                        item.id
                                                    )
                                                "
                                                v-if="
                                                    isItemAlreadyAdded(item) ===
                                                    true
                                                "
                                                class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-myPrimaryErrorColor text-white"
                                            >
                                                <span
                                                    class="myMediumIcon material-symbols-outlined"
                                                >
                                                    remove
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <!-- Actual data # end -->
                    </div>
                </main>
                <aside
                    v-if="getCurrentItems && !getCurrentItems.isError"
                    aria-label="sidebar"
                    class="h-full md:max-h-[38.5rem] max-h-[12rem] md:w-3/5 w-full pl-2 border border-gray-200 overflow-y-scroll"
                >
                    <div
                        v-if="
                            Array.isArray(getCurrentAttachedItems) &&
                            getCurrentAttachedItems.length === 0
                        "
                        class="pb-6 space-y-6"
                    >
                        <p class="myPrimaryParagraph text-xs p-2">
                            No items added
                        </p>
                    </div>

                    <div
                        v-if="
                            Array.isArray(getCurrentAttachedItems) &&
                            getCurrentAttachedItems.length !== 0
                        "
                        class="flex flex-col w-full divide-y divide-gray-200 p-2"
                    >
                        <div class="flex justify-start items-center mb-4">
                            <div class="myPrimaryTag">
                                Added
                                {{ getCurrentAttachedItems.length }}
                                {{
                                    getCurrentAttachedItems.length === 1
                                        ? "item"
                                        : "items"
                                }}
                            </div>
                        </div>
                        <div
                            v-for="item in getCurrentAttachedItems"
                            :key="item.id"
                            class="px-2 hover:bg-white"
                        >
                            <div
                                class="flex justify-between items-center my-2 myPrimaryTag w-max gap-4"
                            >
                                <div class="flex items-center gap-2">
                                    <!-- start photo -->
                                    <div
                                        class="flex-shrink-0"
                                        v-if="item && item.profile_photo_path"
                                    >
                                        <img
                                            class="object-cover h-12 w-12 rounded-full"
                                            :src="`/storage/${item.profile_photo_path}`"
                                            alt="Image"
                                        />
                                    </div>

                                    <div
                                        v-if="
                                            item &&
                                            item.first_name &&
                                            item.last_name &&
                                            !item.profile_photo_path
                                        "
                                        class="flex-shrink-0 myPrimaryParagraph h-12 w-12 gap-0.5 rounded-full bg-myPrimaryBrandColor flex justify-center items-center text-xs font-normal text-white"
                                    >
                                        <span>
                                            {{
                                                item?.first_name
                                                    .charAt(0)
                                                    .toUpperCase()
                                            }}
                                        </span>
                                        <span>
                                            {{
                                                item?.last_name
                                                    .charAt(0)
                                                    .toUpperCase()
                                            }}
                                        </span>
                                    </div>

                                    <!-- end photo -->

                                    <div
                                        class="flex flex-col items-left gap-0.5 myPrimaryParagraph text-xs"
                                    >
                                        <div
                                            class="flex items-center gap-4 my-2"
                                            v-if="item.name"
                                        >
                                            <button
                                                type="button"
                                                class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                                            >
                                                <div
                                                    v-if="
                                                        props.icon ===
                                                        'interests'
                                                    "
                                                >
                                                    <span
                                                        class="myMediumIcon material-symbols-outlined"
                                                    >
                                                        interests
                                                    </span>
                                                </div>
                                                <div
                                                    v-if="
                                                        props.icon ===
                                                        'CheckIcon'
                                                    "
                                                >
                                                    <span
                                                        class="myMediumIcon material-symbols-outlined"
                                                    >
                                                        check
                                                    </span>
                                                </div>
                                                <div
                                                    v-if="
                                                        props.icon ===
                                                        'MapPinIcon'
                                                    "
                                                >
                                                    <span
                                                        class="myMediumIcon material-symbols-outlined"
                                                    >
                                                        location_on
                                                    </span>
                                                </div>
                                                <div
                                                    v-if="
                                                        props.icon ===
                                                        'GlobeAmericasIcon'
                                                    "
                                                >
                                                    <span
                                                        class="myMediumIcon material-symbols-outlined"
                                                    >
                                                        globe
                                                    </span>
                                                </div>
                                                <div
                                                    v-if="
                                                        props.icon ===
                                                        'NewspaperIcon'
                                                    "
                                                >
                                                    <span
                                                        class="myMediumIcon material-symbols-outlined"
                                                    >
                                                        sell
                                                    </span>
                                                </div>
                                            </button>
                                            <span class="font-medium">
                                                {{ item.name }}
                                            </span>
                                        </div>
                                        <div
                                            class="flex flex-col items-left gap-0.5 myPrimaryParagraph text-xs"
                                            v-if="
                                                item.first_name &&
                                                item.last_name
                                            "
                                        >
                                            <span class="font-medium">
                                                {{ item.first_name }}
                                                {{ item.last_name }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <button
                                    type="button"
                                    @click="handleRemoveAttachedItem(item.id)"
                                    class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryErrorColor hover:text-white"
                                >
                                    <span
                                        class="myMediumIcon material-symbols-outlined"
                                    >
                                        delete
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <!--v-if-->
                </aside>
            </div>
        </div>
        <!-- content end -->

        <div
            class="bg-red-50 px-2 py-4 absolute bottom-0 left-0 right-0 flex sm:justify-end justify-center"
        >
            <div class="sm:w-3/6 w-full px-2 my-2 flex gap-2 justify-end">
                <button
                    v-if="firstButtonText"
                    ref="firstButtonRef"
                    class="mySecondaryButton"
                    type="button"
                    @click="firstButton"
                >
                    {{ firstButtonText }}
                </button>
                <button
                    v-if="secondButtonText"
                    class="myPrimaryButton"
                    type="button"
                    @click="secondButton"
                >
                    {{ secondButtonText }}
                </button>
            </div>
        </div>
    </Modal>
</template>
