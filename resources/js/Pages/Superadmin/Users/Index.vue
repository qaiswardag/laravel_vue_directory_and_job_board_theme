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
import FullScreenSpinner from "@/Components/Loaders/FullScreenSpinner.vue";

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
    PencilIcon,
    PlusIcon,
    TrashIcon,
    ArrowLeftIcon,
    ArrowRightIcon,
} from "@heroicons/vue/24/outline";

const breadcrumbsLinks = [
    {
        label: "Admin Dashboard",
        route: {
            name: "admin.dashboard",
            parameters: null,
        },
    },
    { label: "Users" },
];

const props = defineProps({
    user: {
        required: true,
    },
    users: {
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

const superadminRoles = ref([
    {
        name: "reader",
        description: "Reader users have the ability only to read.",
    },
    {
        name: "editor",
        description:
            "Editor users have the ability to read, create, and update. However, Editor can not create or update Superadmin.",
    },
    {
        name: "admin",
        description: "Administrator users can perform any action.",
    },
]);

const selectedRole = ref(superadminRoles[0]);

watch(selectedRole, (newValue) => {
    superadminUserform.role = newValue.name;
});

const modalShowRemoveSuperadmin = ref(false);
const modalShowCreateUpdateSuperadmin = ref(false);
const modalShowDeleteUser = ref(false);

// modal content
const typeModal = ref("");
const gridColumnModal = ref(Number(1));
const titleModal = ref("");
const descriptionModal = ref("");
const firstButtonModal = ref("");
const secondButtonModal = ref(null);
const thirdButtonModal = ref(null);
// set dynamic modal handle functions
const firstModalButtonFunction = ref(null);
const secondModalButtonFunction = ref(null);
const thirdModalButtonFunction = ref(null);

const superadminUserform = useForm({
    user_id: null,
    role: "",
});

const handleRemoveSuperadmin = (
    userIdForNewSuperadmin,
    userFirstName,
    userLastName
) => {
    modalShowRemoveSuperadmin.value = true;

    // set modal standards
    typeModal.value = "delete";
    gridColumnModal.value = 2;
    titleModal.value = "Remove Superadmin?";
    descriptionModal.value = `Remove ${userFirstName} ${userLastName} as Superadmin.`;
    firstButtonModal.value = "Close";
    secondButtonModal.value = null;
    thirdButtonModal.value = "Remove";

    // handle click
    firstModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowRemoveSuperadmin.value = false;
    };
    // handle click
    secondModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowRemoveSuperadmin.value = false;
    };
    // handle click
    thirdModalButtonFunction.value = function () {
        superadminUserform.user_id = userIdForNewSuperadmin;
        removeSuperadminRole(userIdForNewSuperadmin);
    };
    // end modal
};
const handleUpdateSuperadmin = (
    userIdForNewSuperadmin,
    userFirstName,
    userLastName
) => {
    modalShowCreateUpdateSuperadmin.value = true;

    // set modal standards
    typeModal.value = "success";
    gridColumnModal.value = 2;
    titleModal.value = "Update Superadmin?";
    descriptionModal.value = `Update ${userFirstName} ${userLastName} as Superadmin.`;
    firstButtonModal.value = "Close";
    secondButtonModal.value = null;
    thirdButtonModal.value = "Save";

    // handle click
    firstModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowCreateUpdateSuperadmin.value = false;
    };
    // handle click
    secondModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowCreateUpdateSuperadmin.value = false;
    };
    // handle click
    thirdModalButtonFunction.value = function () {
        superadminUserform.user_id = userIdForNewSuperadmin;
        updateSuperadminRole(userIdForNewSuperadmin);
    };
    // end modal
};
const handleCreateSuperadmin = (
    userIdForNewSuperadmin,
    userFirstName,
    userLastName
) => {
    modalShowCreateUpdateSuperadmin.value = true;

    // set modal standards
    typeModal.value = "success";
    gridColumnModal.value = 2;
    titleModal.value = "Create Superadmin?";
    descriptionModal.value = `Create ${userFirstName} ${userLastName} as Superadmin`;
    firstButtonModal.value = "Close";
    secondButtonModal.value = null;
    thirdButtonModal.value = "Save";

    // handle click
    firstModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowCreateUpdateSuperadmin.value = false;
    };
    // handle click
    secondModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowCreateUpdateSuperadmin.value = false;
    };
    // handle click
    thirdModalButtonFunction.value = function () {
        superadminUserform.user_id = userIdForNewSuperadmin;
        createSuperadminRole(userIdForNewSuperadmin);
    };
    // end modal
};

const createSuperadminRole = (userIdForNewSuperadmin) => {
    superadminUserform.post(
        route("admin.users.user.create.superadmin", [userIdForNewSuperadmin]),
        {
            errorBag: "createSuperadmin",
            preserveScroll: true,
            onSuccess: () => {
                modalShowCreateUpdateSuperadmin.value = false;
            },
            onError: (err) => {},
            onFinish: () => {
                //
            },
        }
    );
};

const updateSuperadminRole = (userIdForNewSuperadmin) => {
    superadminUserform.put(
        route("admin.users.user.update.superadmin", [userIdForNewSuperadmin]),
        {
            errorBag: "createSuperadmin",
            preserveScroll: true,
            onSuccess: () => {
                modalShowCreateUpdateSuperadmin.value = false;
            },
            onError: (err) => {},
            onFinish: () => {
                //
            },
        }
    );
};
const removeSuperadminRole = (userIdForNewSuperadmin) => {
    superadminUserform.delete(
        route("admin.users.user.remove.superadmin", [userIdForNewSuperadmin]),
        {
            errorBag: "createSuperadmin",
            preserveScroll: true,
            onSuccess: () => {
                modalShowRemoveSuperadmin.value = false;
            },
            onError: (err) => {},
            onFinish: () => {
                //
            },
        }
    );
};

// handle action
const handleDelete = function (id, user) {
    modalShowDeleteUser.value = true;

    // set modal standards
    typeModal.value = "delete";
    gridColumnModal.value = 2;
    titleModal.value = `Delete User ${user.first_name} ${user.last_name}?`;
    descriptionModal.value = `Are you sure you want to delete user ${user.first_name} ${user.last_name}?`;
    firstButtonModal.value = "Close";
    secondButtonModal.value = null;
    thirdButtonModal.value = "Delete User";

    // handle click
    firstModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowDeleteUser.value = false;
    };
    // handle click
    secondModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowDeleteUser.value = false;
    };
    // handle click
    thirdModalButtonFunction.value = function () {
        deleteUser(id);
    };
    // end modal
};
// form
const deleteUserForm = useForm({});

// form action
const deleteUser = (userId) => {
    deleteUserForm.delete(route("admin.users.user.destroy", userId), {
        preserveScroll: true,
        onSuccess: () => (modalShowDeleteUser.value = false),
        onError: (err) => {},
        onFinish: (log) => {
            modalShowDeleteUser.value = false;
        },
    });
};

const handleEdit = function (userId) {};

// handle search
const handleSearch = function () {
    search();
};

const searchCategories = ref(["id", "name", "email"]);

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

const scrolTableContainer = ref("scrolTableContainer");

const handleLeft = function () {
    if (scrolTableContainer.value) {
        scrolTableContainer.value.scrollLeft -= 500;
    }
};
const handleRight = function () {
    if (scrolTableContainer.value) {
        scrolTableContainer.value.scrollLeft += 500;
    }
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
        <Head title="Manage Users" />
        <DynamicModal
            :show="modalShowCreateUpdateSuperadmin"
            :type="typeModal"
            :disabled="superadminUserform.processing"
            disabledWhichButton="thirdButton"
            :gridColumnAmount="gridColumnModal"
            :title="titleModal"
            :description="descriptionModal"
            :firstButtonText="firstButtonModal"
            :secondButtonText="secondButtonModal"
            :thirdButtonText="thirdButtonModal"
            @firstModalButtonFunction="firstModalButtonFunction"
            @secondModalButtonFunction="secondModalButtonFunction"
            @thirdModalButtonFunction="thirdModalButtonFunction"
        >
            <header></header>
            <main>
                <RadioGroup v-model="selectedRole">
                    <RadioGroupLabel class="sr-only"
                        >Privacy setting</RadioGroupLabel
                    >
                    <div class="-space-y-px rounded-md bg-white">
                        <RadioGroupOption
                            as="template"
                            v-for="(setting, settingIdx) in superadminRoles"
                            :key="setting.name"
                            :value="setting"
                            v-slot="{ checked, active }"
                        >
                            <div
                                :class="[
                                    settingIdx === 0
                                        ? 'rounded-tl-md rounded-tr-md'
                                        : '',
                                    settingIdx === superadminRoles.length - 1
                                        ? 'rounded-bl-md rounded-br-md'
                                        : '',
                                    checked
                                        ? 'z-10 border-myPrimaryLinkColor bg-myPrimaryLightGrayColor'
                                        : 'border-gray-200',
                                    'relative flex cursor-pointer border p-4 focus:outline-none',
                                ]"
                            >
                                <span
                                    :class="[
                                        checked
                                            ? 'bg-myPrimaryLinkColor border-transparent'
                                            : 'bg-white border-gray-300',
                                        active
                                            ? 'ring-2 ring-offset-2 ring-myPrimaryLinkColor'
                                            : '',
                                        'mt-0.5 h-4 w-4 shrink-0 cursor-pointer rounded-full border flex items-center justify-center',
                                    ]"
                                    aria-hidden="true"
                                >
                                    <span
                                        class="rounded-full bg-white w-1.5 h-1.5"
                                    />
                                </span>
                                <span class="ml-3 flex flex-col">
                                    <RadioGroupLabel
                                        as="span"
                                        :class="[
                                            checked
                                                ? 'text-myPrimaryDarkGrayColor'
                                                : 'text-myPrimaryDarkGrayColor',
                                            'myPrimaryParagraph',
                                        ]"
                                        >{{ setting.name }}</RadioGroupLabel
                                    >
                                    <RadioGroupDescription
                                        as="span"
                                        :class="[
                                            checked
                                                ? 'text-myPrimaryLinkColor'
                                                : 'text-myPrimaryMediumGrayColor',
                                            'block text-sm',
                                        ]"
                                        >{{
                                            setting.description
                                        }}</RadioGroupDescription
                                    >
                                </span>
                            </div>
                        </RadioGroupOption>
                    </div>
                </RadioGroup>
                <InputError :message="superadminUserform.errors.role" />
                <InputError :message="superadminUserform.errors.user_id" />
            </main>
        </DynamicModal>

        <DynamicModal
            :show="modalShowRemoveSuperadmin"
            :type="typeModal"
            :disabled="superadminUserform.processing"
            disabledWhichButton="thirdButton"
            :gridColumnAmount="gridColumnModal"
            :title="titleModal"
            :description="descriptionModal"
            :firstButtonText="firstButtonModal"
            :secondButtonText="secondButtonModal"
            :thirdButtonText="thirdButtonModal"
            @firstModalButtonFunction="firstModalButtonFunction"
            @secondModalButtonFunction="secondModalButtonFunction"
            @thirdModalButtonFunction="thirdModalButtonFunction"
        >
            <header></header>
            <main></main>
        </DynamicModal>
        <DynamicModal
            :show="modalShowDeleteUser"
            :type="typeModal"
            :disabled="deleteUserForm.processing"
            disabledWhichButton="thirdButton"
            :gridColumnAmount="gridColumnModal"
            :title="titleModal"
            :description="descriptionModal"
            :firstButtonText="firstButtonModal"
            :secondButtonText="secondButtonModal"
            :thirdButtonText="thirdButtonModal"
            @firstModalButtonFunction="firstModalButtonFunction"
            @secondModalButtonFunction="secondModalButtonFunction"
            @thirdModalButtonFunction="thirdModalButtonFunction"
        >
            <header></header>
            <main></main>
        </DynamicModal>
        <template #header>
            <h2 class="myPrimaryMainPageHeader">Users</h2>
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

        <!-- table start -->
        <div
            v-if="
                user &&
                users.data &&
                Array.isArray(users.data) &&
                users.data.length >= 1
            "
            class="myTableContainerPlusScrollButton"
        >
            <div class="myScrollButtonContainer">
                <button
                    @click="handleLeft"
                    class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white"
                >
                    <ArrowLeftIcon class="mySmallIcon"></ArrowLeftIcon>
                </button>
                <button
                    @click="handleRight"
                    class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white"
                >
                    <ArrowRightIcon class="mySmallIcon"></ArrowRightIcon>
                </button>
            </div>
            <div ref="scrolTableContainer" class="myTableContainer">
                <div class="myTableSubContainer">
                    <table class="myPrimaryTable" aria-describedby="index">
                        <thead class="myPrimaryTableTHead">
                            <tr class="myPrimaryTableTr">
                                <th scope="col" class="myPrimaryTableTh">
                                    User
                                </th>
                                <th scope="col" class="myPrimaryTableTh">
                                    User ID
                                </th>
                                <th scope="col" class="myPrimaryTableTh">
                                    Name
                                </th>
                                <th scope="col" class="myPrimaryTableTh">
                                    Status
                                </th>
                                <th scope="col" class="myPrimaryTableTh">
                                    Superadmin role
                                </th>
                                <th scope="col" class="myPrimaryTableTh">
                                    Updated Date
                                </th>
                                <th scope="col" class="myPrimaryTableTh">
                                    Created at
                                </th>
                                <th scope="col" class="myPrimaryTableTh">
                                    Edit
                                </th>
                                <th scope="col" class="myPrimaryTableTh">
                                    Delete
                                </th>
                            </tr>
                        </thead>
                        <tbody class="myPrimaryTableTBody">
                            <TransitionGroup name="table">
                                <tr
                                    class="myPrimaryTableTBodyTr"
                                    v-for="user in users.data"
                                    :key="user.id"
                                >
                                    <td class="myPrimaryTableTBodyTd">
                                        <div class="flex items-center gap-2">
                                            <div
                                                v-if="
                                                    user &&
                                                    user.profile_photo_path
                                                "
                                            >
                                                <div
                                                    class="h-12 w-12 flex-shrink-0"
                                                >
                                                    <img
                                                        class="object-cover h-12 w-12 rounded-full"
                                                        :src="`/storage/${user.profile_photo_path}`"
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
                                                    user.profile_photo_path ===
                                                        null
                                                "
                                            >
                                                <div
                                                    class="h-12 w-12 rounded-full bg-myPrimaryBrandColor flex justify-center items-center text-xs font-normal text-white"
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
                                            </div>
                                            <span
                                                class="flex flex-col items-left gap-1 myPrimaryParagraph font-medium"
                                            >
                                                <span>
                                                    {{ user.first_name }}
                                                    {{ user.last_name }}
                                                </span>
                                            </span>
                                        </div>
                                    </td>

                                    <td class="myPrimaryTableTBodyTd">
                                        {{ user.id }}
                                    </td>

                                    <td class="myPrimaryTableTBodyTd">
                                        {{ user.first_name }}
                                        {{ user.last_name }}
                                    </td>

                                    <td class="myPrimaryTableTBodyTd">
                                        <span
                                            class="myPrimaryTag"
                                            :class="
                                                user.public
                                                    ? 'bg-myPrimaryLinkColor text-white'
                                                    : 'bg-myPrimaryErrorColor text-white'
                                            "
                                            >{{
                                                user.public
                                                    ? "Published"
                                                    : "Unpublished"
                                            }}</span
                                        >
                                    </td>

                                    <td class="myPrimaryTableTBodyTd">
                                        <template
                                            v-if="user.superadmin !== null"
                                        >
                                            <div class="flex gap-2">
                                                <button
                                                    @click="
                                                        handleUpdateSuperadmin(
                                                            user.id,
                                                            user.first_name,
                                                            user.last_name
                                                        )
                                                    "
                                                    class="myPrimaryButton flex items-center gap-1 text-xs"
                                                >
                                                    <ArrowPathRoundedSquareIcon
                                                        class="h-4 w-4"
                                                    ></ArrowPathRoundedSquareIcon>
                                                    <p>
                                                        {{
                                                            user.superadmin.role
                                                                .charAt(0)
                                                                .toUpperCase() +
                                                            user.superadmin.role.slice(
                                                                1
                                                            )
                                                        }}
                                                    </p>
                                                </button>
                                                <button
                                                    @click="
                                                        handleRemoveSuperadmin(
                                                            user.id,
                                                            user.first_name,
                                                            user.last_name
                                                        )
                                                    "
                                                    class="myPrimaryDeleteButton flex items-center gap-1 text-xs"
                                                >
                                                    <MinusIcon
                                                        class="w-4 h-4"
                                                    ></MinusIcon>
                                                    Remove
                                                </button>
                                            </div>
                                        </template>
                                        <template
                                            v-if="user.superadmin === null"
                                        >
                                            <button
                                                @click="
                                                    handleCreateSuperadmin(
                                                        user.id,
                                                        user.first_name,
                                                        user.last_name
                                                    )
                                                "
                                                class="mySecondaryButton flex items-center gap-1 text-xs"
                                            >
                                                <PlusIcon
                                                    class="w-4 h-4"
                                                ></PlusIcon>
                                                <p>Add Superadmin</p>
                                            </button>
                                        </template>
                                    </td>
                                    <td class="myPrimaryTableTBodyTd">
                                        {{
                                            format(
                                                parseISO(user.updated_at),
                                                "dd/MM/yyyy HH:mm"
                                            )
                                        }}
                                    </td>
                                    <td class="myPrimaryTableTBodyTd">
                                        {{
                                            format(
                                                parseISO(user.created_at),
                                                "dd/MM/yyyy HH:mm"
                                            )
                                        }}
                                    </td>

                                    <td class="myPrimaryTableTBodyTd">
                                        <button
                                            type="button"
                                            @click="handleEdit(user.id)"
                                            class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white"
                                        >
                                            <PencilIcon
                                                class="shrink-0 w-4 h-4 m-2 stroke-2"
                                            ></PencilIcon>
                                        </button>
                                    </td>
                                    <td class="myPrimaryTableTBodyTd">
                                        <button
                                            type="button"
                                            @click="handleDelete(user.id, user)"
                                            class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryErrorColor hover:text-white"
                                        >
                                            <TrashIcon
                                                class="shrink-0 w-4 h-4 m-2 stroke-2"
                                            ></TrashIcon>
                                        </button>
                                    </td>
                                </tr>
                            </TransitionGroup>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <Pagination :links="users.links"></Pagination>
    </LoggedInLayout>
</template>
