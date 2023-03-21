<script setup>
import Modal from "@/Components/Modals/Modal.vue";
import { ref, computed, onMounted } from "vue";
import { TailwindPagination } from "laravel-vue-pagination";
import { useStore } from "vuex";

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
});

// store
const store = useStore();

const getCurrentUsers = computed(() => {
    return store.getters["users/getCurrentUsers"];
});
//
const search_query = ref("");
// handle search
const handleSearch = function (page) {
    // dispatch
    store.dispatch("users/loadUsers", {
        teamId: props.team.id,
        page: page,
        search_query: search_query.value,
    });
};

// get result for "laravel pagination" package
const getResultsForPage = (page = 1) => {
    console.log("get result for page ran.");
    handleSearch(page);
};

const emit = defineEmits([
    "firstModalButtonSearchAuthorFunction",
    "secondModalButtonSearchAuthorFunction",
]);

// first button function
const firstButton = function () {
    emit("firstModalButtonSearchAuthorFunction");
};
// second button  function
const secondButton = function () {
    emit("secondModalButtonSearchAuthorFunction");
};

// onMounted(() => {
//     handleSearch(1);
// });
</script>

<template>
    <Modal :show="show" @close="firstButton" maxWidth="5xl">
        <div
            class="w-full relative inline-block align-bottom text-left overflow-hidden transform transition-all sm:align-middle px-4 py-4"
        >
            <p>er: {{ getCurrentUsers }}</p>
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
                        stroke-width="2"
                        stroke="currentColor"
                        aria-hidden="true"
                        class="h-6 w-6 text-myPrimaryDarkGrayColor self-center cursor-pointer"
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
            <div class="flex-1 flex items-stretch overflow-hidden mt-2">
                <main class="flex-1 overflow-y-auto relativ">
                    <div class="py-4 max-w-7xl mx-auto px-4 sm:pr-6 lg:pr-8">
                        <form class="mb-4" @submit.prevent="handleSearch(1)">
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
                                                stroke-width="2"
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
                                    class="myPrimaryButton"
                                >
                                    Search
                                </button>
                            </div>
                        </form>

                        <!-- loading - start -->
                        <div
                            v-if="
                                getCurrentUsers &&
                                getCurrentUsers.isLoading === false &&
                                getCurrentUsers.isError
                            "
                            class="myPrimaryParagraphError"
                        >
                            {{ getCurrentUsers.isError }}
                        </div>

                        <div
                            v-if="
                                getCurrentUsers &&
                                getCurrentUsers.fetchedData &&
                                getCurrentUsers.fetchedData.users &&
                                getCurrentUsers.fetchedData.total_results !== 0
                            "
                            class="scale-90 text-center bottom-0 w-full my-2 border-b border-gray-200 py-2"
                        >
                            <TailwindPagination
                                :active-classes="[
                                    'bg-black',
                                    'text-white',
                                    'border-black',
                                ]"
                                :limit="2"
                                :class="[
                                    'flex',
                                    'justify-center',
                                    'items-center',
                                    'rounded-full',
                                ]"
                                :data="getCurrentUsers.fetchedData.users"
                                @pagination-change-page="getResultsForPage"
                            >
                            </TailwindPagination>
                        </div>

                        <div
                            v-if="
                                getCurrentUsers &&
                                getCurrentUsers.isLoading === true
                            "
                        >
                            <div
                                class="flex items-center justify-center min-h-[20rem] mb-12"
                            >
                                <div
                                    class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                                    role="status"
                                >
                                    <span
                                        class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]"
                                        >Loading...</span
                                    >
                                </div>
                            </div>
                        </div>
                        <!-- loading - end -->
                        <template
                            v-if="
                                getCurrentUsers &&
                                getCurrentUsers.isLoading === false &&
                                getCurrentUsers.fetchedData &&
                                getCurrentUsers.fetchedData.users &&
                                getCurrentUsers.fetchedData.users.data &&
                                getCurrentUsers.fetchedData.total_results !== 0
                            "
                        >
                            <div
                                class="flex flex-col myPrimaryGap min-h-[20rem]"
                            >
                                <div
                                    v-for="user in getCurrentUsers.fetchedData
                                        .users.data"
                                    :key="user.id"
                                    class="rounded-md bg-myPrimaryLightGrayColor hover:bg-opacity-70 py-4 pl-4 pr-2 cursor-pointer"
                                >
                                    <div
                                        class="flex items-center gap-2 mt-2 bg-red-200 py-2 px-2 rounded my-4"
                                    >
                                        <div
                                            v-if="
                                                user && user.profile_photo_path
                                            "
                                        >
                                            <div
                                                class="h-12 w-12 flex-shrink-0"
                                            >
                                                <img
                                                    class="object-cover w-12 h-12 rounded-full"
                                                    :src="
                                                        user.profile_photo_url
                                                    "
                                                    :alt="
                                                        user.first_name +
                                                        user.last_name
                                                    "
                                                />
                                            </div>
                                        </div>

                                        <div
                                            v-if="
                                                user &&
                                                user.profile_photo_path === null
                                            "
                                            class="h-12 w-12 rounded-full bg-myPrimaryBrandColor flex justify-center items-center text-xs font-semibold text-white"
                                        >
                                            {{
                                                user.first_name
                                                    .charAt(0)
                                                    .toUpperCase()
                                            }}
                                            {{
                                                user.last_name
                                                    .charAt(0)
                                                    .toUpperCase()
                                            }}
                                        </div>
                                        <span
                                            class="flex flex-col items-left gap-1 myPrimaryParagraph text-xs"
                                        >
                                            <span>
                                                {{ user.first_name }}
                                                {{ user.last_name }}
                                            </span>
                                            <span>
                                                {{ user.email }}
                                            </span>
                                            <span> Editor </span>
                                        </span>
                                    </div>

                                    <p
                                        class="myPrimaryParagraph text-xs font-semibold"
                                    >
                                        Name: {{ user.first_name }}
                                        {{ user.last_name }}
                                    </p>
                                    <p class="myPrimaryParagraph text-xs">
                                        Email: {{ user.email }}
                                    </p>
                                    <p class="myPrimaryParagraph text-xs">
                                        Role: {{ user }}
                                    </p>
                                </div>
                            </div>
                        </template>
                    </div>
                </main>
                <aside
                    aria-label="sidebar"
                    class="w-72 bg-white pl-2 pr-2 border-l border-gray-200"
                >
                    <div class="pb-16 space-y-6">
                        <p class="myPrimaryParagraph">No user selected</p>
                    </div>
                    <!--v-if-->
                </aside>
            </div>
        </div>
        <!-- content end -->

        <div class="flex flex-col px-6 py-4 bg-gray-100 text-right">
            <div
                class="sm:grid sm:gap-3 sm:grid-cols-2 grid gap-4 sm:grid-flow-row-dense md:w-full"
            >
                <button
                    v-if="firstButtonText"
                    ref="firstButtonRef"
                    class="shadow myPrimaryButton bg-myPrimaryMediumGrayColor text-sm w-full hover:bg-gray-500 text-white hover:text-white focus:ring-gray-500"
                    type="button"
                    @click="firstButton"
                >
                    {{ firstButtonText }}
                </button>

                <div v-if="secondButtonText">
                    <div>
                        <button
                            class="flex items-center gap-2 myPrimaryButton text-sm w-full"
                            type="button"
                            @click="secondButton"
                        >
                            {{ secondButtonText }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </Modal>
</template>

<style scope>
/*
  Enter and leave animations can use different
  durations and timing functions.
*/
.slide-fade-enter-active {
    transition: all 0.3s ease-out;
}

.slide-fade-leave-active {
    transition: all 0.8s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter-from,
.slide-fade-leave-to {
    transform: translateX(20px);
    opacity: 0;
}

.bounce-enter-active {
    animation: bounce-in 0.5s;
}
.bounce-leave-active {
    animation: bounce-in 0.5s reverse;
}
@keyframes bounce-in {
    0% {
        transform: scale(0);
    }
    50% {
        transform: scale(1.25);
    }
    100% {
        transform: scale(1);
    }
}
</style>
