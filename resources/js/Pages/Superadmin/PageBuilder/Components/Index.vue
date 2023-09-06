<script setup>
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import Pagination from "@/Components/Pagination/Pagination.vue";
import SubmitButton from "@/Components/Buttons/SubmitButton.vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";
import FormSection from "@/Components/Forms/FormSection.vue";
import SearchBarWithOptions from "@/Components/SearchBars/SearchBarWithOptions.vue";
import { onMounted, ref, watch } from "vue";
import Breadcrumbs from "@/Components/Breadcrumbs/Breadcrumbs.vue";
import InputError from "@/Components/Forms/InputError.vue";
import { parseISO, format } from "date-fns";
import CardHeadings from "@/Components/Actions/CardHeadings.vue";

import {
    RadioGroup,
    RadioGroupDescription,
    RadioGroupLabel,
    RadioGroupOption,
} from "@headlessui/vue";
import {
    ArrowPathRoundedSquareIcon,
    ArrowsRightLeftIcon,
    MinusIcon,
    PlusIcon,
} from "@heroicons/vue/24/outline";

const breadcrumbsLinks = [
    {
        label: "Admin Dashboard",
        route: {
            name: "admin.dashboard",
            parameters: null,
        },
    },
    { label: "Components" },
];

const props = defineProps({
    currentUserTeam: {
        required: true,
    },
    components: {
        required: true,
    },
    results: {
        required: false,
    },
    oldInput: {
        search_query: {
            required: false,
        },
        selected_category: {
            required: false,
        },
    },
});

const handleEdit = function () {
    console.log("handle edit");
};
const handleDelete = function () {
    console.log("handle edit");
};

// handle search
const handleSearch = function () {
    search();
};

const searchCategories = ref(["id", "title"]);

const searchForm = useForm({
    search_query: "",
    selected_category: searchCategories.value[1],
});

const selectedUpdated = function (newSelect) {
    searchForm.selected_category = newSelect;
};

const search = () => {
    searchForm.get(route("admin.components"), {
        preserveScroll: true,
        onSuccess: () => {},
        onError: (err) => {},
        onFinish: () => {},
    });
};

onMounted(() => {
    if (props.oldInput?.search_query) {
        searchForm.search_query = props.oldInput.search_query;
    }
    if (props.oldInput?.selected_category) {
        searchForm.selected_category = props.oldInput.selected_category;
    }
});

const routesArray = [
    {
        label: "Admin Dashboard ",
        route: {
            name: "admin.dashboard",
        },
    },
    {
        label: "Create Compoenent",
        route: {
            name: "admin.components.component.create",
            parameters: [props.currentUserTeam.reference_id],
        },
    },
];
</script>

<template>
    <LoggedInLayout>
        <Head title="Manage Components" />
        <template #header>
            <h2 class="myPrimaryMainPageHeader">List of Components</h2>
        </template>
        <template #breadcrumbs>
            <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
        </template>

        <template #description></template>
        <CardHeadings :routesArray="routesArray">
            <template #title
                >Components
                {{ $page.props.user && $page.props.user.current_team.name }}
            </template>
            <template #subTitle
                >Lorem ipsum dolor sit amet consectetur adipisicing elit quam
                corrupti consectetur.
            </template>
            <template #buttons>
                <Link
                    class="myPrimaryButton"
                    type="button"
                    :href="
                        route(
                            'admin.components.component.create',
                            $page.props.user.current_team_id
                        )
                    "
                >
                    Create Store
                </Link>
            </template>
        </CardHeadings>
        <form @submit.prevent="handleSearch">
            <!-- search bar component - start -->
            <SearchBarWithOptions
                v-model="searchForm.search_query"
                @selectedUpdated="selectedUpdated"
                :currentSelect="searchForm.selected_category"
                @firstButtonClick="handleSearch"
                :categories="searchCategories"
                placeholderButton="Search"
                placeholderInput="Search.."
            ></SearchBarWithOptions>
            <!-- search bar component - ens -->
        </form>

        <div class="myTableContainer">
            <div class="myTableSubContainer">
                <table class="myPrimaryTable">
                    <caption class="myPrimaryTableCaption">
                        <p class="myPrimaryParagraph">Result {{ results }}</p>
                    </caption>
                    <thead class="myPrimaryTableTHead">
                        <tr class="myPrimaryTableTr">
                            <th scope="col" class="myPrimaryTableTh">Image</th>
                            <th scope="col" class="myPrimaryTableTh">ID</th>
                            <th scope="col" class="myPrimaryTableTh">Title</th>
                            <th scope="col" class="myPrimaryTableTh">Edit</th>
                            <th scope="col" class="myPrimaryTableTh">Delete</th>
                        </tr>
                    </thead>
                    <tbody class="myPrimaryTableTBody">
                        <TransitionGroup name="table">
                            <tr
                                class="myPrimaryTableTBodyTr"
                                v-for="component in components.data"
                                :key="component.id"
                            >
                                <td class="myPrimaryTableTBodyTd">
                                    <div class="flex items-center gap-2">
                                        <div
                                            v-if="component.cover_image_medium"
                                            class="h-12 w-12 flex-shrink-0"
                                        >
                                            <img
                                                class="object-cover w-12 h-12 rounded-full"
                                                :src="`/storage/uploads/${component.cover_image_medium}`"
                                                :alt="component.title"
                                            />
                                        </div>
                                        <div
                                            v-if="!component.cover_image_medium"
                                            class="h-12 w-12 flex-shrink-0"
                                        >
                                            <img
                                                class="object-cover w-12 h-12 rounded-full"
                                                src="/app-images/builder/components/default_component_image.jpg"
                                                alt="Component"
                                            />
                                        </div>
                                    </div>
                                </td>

                                <td class="myPrimaryTableTBodyTd">
                                    {{ component.id }}
                                </td>
                                <td class="myPrimaryTableTBodyTd">
                                    {{ component.title }}
                                </td>

                                <td class="myPrimaryTableTBodyTd">
                                    {{
                                        format(
                                            parseISO(component.created_at),
                                            "dd/MM/yyyy"
                                        )
                                    }}
                                </td>

                                <td class="myPrimaryTableTBodyTd">
                                    {{
                                        format(
                                            parseISO(component.updated_at),
                                            "dd/MM/yyyy"
                                        )
                                    }}
                                </td>

                                <td class="myPrimaryTableTBodyTd">
                                    <button
                                        @click="handleEdit(component.id)"
                                        type="button"
                                        class="myPrimaryButtonNoBackground"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.5"
                                            stroke="currentColor"
                                            class="w-5 h-5"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"
                                            />
                                        </svg>
                                    </button>
                                </td>

                                <td class="myPrimaryTableTBodyTd">
                                    <SubmitButton
                                        :ButtonStyleDelete="true"
                                        :TableStyle="true"
                                        :disabled="false"
                                        @firstButtonClick="
                                            handleDelete(component.id)
                                        "
                                        buttonText=""
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.5"
                                            stroke="currentColor"
                                            class="w-5 h-5"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"
                                            />
                                        </svg>
                                    </SubmitButton>
                                </td>
                            </tr>
                        </TransitionGroup>
                    </tbody>
                </table>
            </div>
        </div>
        <Pagination :links="components.links"></Pagination>
    </LoggedInLayout>
</template>
