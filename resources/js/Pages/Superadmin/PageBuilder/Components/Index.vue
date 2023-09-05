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
    components: {
        required: true,
    },
});

// handle search
const handleSearch = function () {
    search();
};

const searchCategories = ref(["id", "name"]);

const searchForm = useForm({
    search_query: "",
    selected_category: searchCategories.value[1],
});

const selectedUpdated = function (newSelect) {
    searchForm.selected_category = newSelect;
};

const search = () => {
    searchForm.get(route("admin.users"), {
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
        <form @submit.prevent="handleSearch">
            <!-- search bar componenet - start -->
            <SearchBarWithOptions
                v-model="searchForm.search_query"
                @selectedUpdated="selectedUpdated"
                :currentSelect="searchForm.selected_category"
                @firstButtonClick="handleSearch"
                :categories="searchCategories"
                placeholderButton="Search"
                placeholderInput="Search.."
            ></SearchBarWithOptions>
            <!-- search bar componenet - ens -->
        </form>

        <p>fetched components: {{ JSON.stringify(components) }}</p>
        <br />
        <br />
        <br />
        <br />
        <br />
        List of components
        <!-- <Pagination :links="users.links"></Pagination> -->
    </LoggedInLayout>
</template>
