<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";
import Pagination from "@/Components/Pagination/Pagination.vue";
import CardHeadings from "@/Components/Actions/CardHeadings.vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";
import SubmitButton from "@/Components/Buttons/SubmitButton.vue";
import { onMounted, ref } from "vue";
import Breadcrumbs from "@/Components/Breadcrumbs/Breadcrumbs.vue";
import { parseISO, format } from "date-fns";
import ThumbnailSmallImageSlider from "@/Components/ImageSliders/ThumbnailSmallImageSlider.vue";
import UserTag from "@/Components/Users/UserTag.vue";
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";
import { useStore } from "vuex";
import SmallUniversalSpinner from "@/Components/Loaders/SmallUniversalSpinner.vue";
import { vueFetch } from "use-lightweight-fetch";

import {
    ArrowLeftIcon,
    ArrowRightIcon,
    CheckIcon,
    ChevronDownIcon,
    DocumentDuplicateIcon,
    EllipsisVerticalIcon,
    PlusCircleIcon,
    PlusIcon,
    MapPinIcon,
    TagIcon,
    TrashIcon,
    Squares2X2Icon,
    UserIcon,
    PencilIcon,
} from "@heroicons/vue/20/solid";

const store = useStore();

// get images
const {
    handleData: handleGetPayments,
    fetchedData: fetchedPayments,
    isError: isErrorPayments,
    error: errorPayments,
    errors: errorsPayments,
    isLoading: isLoadingPayments,
    isSuccess: isSuccessPayments,
} = vueFetch();

const getPayments = async function () {
    handleGetPayments(route("stripe.api.internal.payment.index"));
};

const scrolTableContainer = ref("scrolTableContainer");

const handleLeft = function () {
    if (scrolTableContainer.value) {
        scrolTableContainer.value.scrollBy({
            left: -800,
            behavior: "smooth",
        });
    }
};
const handleRight = function () {
    if (scrolTableContainer.value) {
        scrolTableContainer.value.scrollBy({
            left: +800,
            behavior: "smooth",
        });
    }
};

onMounted(() => {
    getPayments();
});

const breadcrumbsLinks = [
    {
        label: "Your Profile",
        route: {
            name: "profile.show",
            parameters: null,
        },
    },
    {
        label: "Payments and invoices",
    },
];
</script>

<template>
    <MainLayout>
        <LoggedInLayout>
            <Head title="Payments and invoices" />

            <template #header> Payments and invoices </template>
            <template #breadcrumbs>
                <Breadcrumbs :links="breadcrumbsLinks"></Breadcrumbs>
            </template>

            <div>
                <h1 class="myTertiaryHeader">Payments and invoices</h1>
            </div>

            <!-- error # start -->
            <template
                v-if="
                    !isLoadingPayments && isErrorPayments && !isSuccessPayments
                "
            >
                <p class="myPrimaryParagraphError">
                    {{ errorPayments }}
                </p>
            </template>
            <!-- error # end -->

            <!-- Loading # start -->
            <template v-if="isLoadingPayments">
                <SmallUniversalSpinner
                    width="w-8"
                    height="h-8"
                    border="border-4"
                ></SmallUniversalSpinner>
            </template>
            <!-- Loading # end -->

            <template
                v-if="
                    (fetchedPayments &&
                        Array.isArray(fetchedPayments.payments) &&
                        fetchedPayments.payments.length === 0) ||
                    (fetchedPayments &&
                        fetchedPayments.payments &&
                        fetchedPayments.payments.invoices &&
                        Array.isArray(fetchedPayments.payments.invoices) &&
                        fetchedPayments.payments.invoices.length === 0)
                "
            >
                <p class="myPrimaryParagraph">
                    Looks like there are no payments and invoices!
                </p>
            </template>

            <template v-if="!isLoadingPayments && !isErrorPayments">
                <div
                    v-if="
                        fetchedPayments &&
                        fetchedPayments.payments &&
                        fetchedPayments.payments.invoices &&
                        Array.isArray(fetchedPayments.payments.invoices) &&
                        fetchedPayments.payments.invoices.length >= 1
                    "
                >
                    <div class="myTableContainerPlusScrollButton">
                        <div class="myScrollButtonContainer">
                            <button
                                @click="handleLeft"
                                class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                            >
                                <ArrowLeftIcon
                                    class="mySmallIcon"
                                ></ArrowLeftIcon>
                            </button>
                            <button
                                @click="handleRight"
                                class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                            >
                                <ArrowRightIcon
                                    class="mySmallIcon"
                                ></ArrowRightIcon>
                            </button>
                        </div>
                        <div ref="scrolTableContainer" class="myTableContainer">
                            <div class="myTableSubContainer">
                                <table
                                    class="myPrimaryTable"
                                    aria-describedby="index"
                                >
                                    <thead class="myPrimaryTableTHead">
                                        <tr class="myPrimaryTableTr">
                                            <th
                                                scope="col"
                                                class="myPrimaryTableTh"
                                            >
                                                ID
                                            </th>
                                            <th
                                                scope="col"
                                                class="myPrimaryTableTh"
                                            >
                                                Downlaod PDF
                                            </th>
                                            <th
                                                scope="col"
                                                class="myPrimaryTableTh"
                                            >
                                                customer_name
                                            </th>
                                            <th
                                                scope="col"
                                                class="myPrimaryTableTh"
                                            >
                                                customer_email
                                            </th>
                                            <th
                                                scope="col"
                                                class="myPrimaryTableTh"
                                            >
                                                subtotal
                                            </th>
                                            <th
                                                scope="col"
                                                class="myPrimaryTableTh"
                                            >
                                                subtotal_excluding_tax
                                            </th>
                                            <th
                                                scope="col"
                                                class="myPrimaryTableTh"
                                            >
                                                tax
                                            </th>
                                            <th
                                                scope="col"
                                                class="myPrimaryTableTh"
                                            >
                                                account_tax_ids
                                            </th>
                                            <th
                                                scope="col"
                                                class="myPrimaryTableTh"
                                            >
                                                account_country & name
                                            </th>
                                            <th
                                                scope="col"
                                                class="myPrimaryTableTh"
                                            >
                                                attempt_count
                                            </th>
                                            <th
                                                scope="col"
                                                class="myPrimaryTableTh"
                                            >
                                                collection_method
                                            </th>
                                            <th
                                                scope="col"
                                                class="myPrimaryTableTh"
                                            >
                                                billing_reason
                                            </th>
                                            <th
                                                scope="col"
                                                class="myPrimaryTableTh"
                                            >
                                                currency
                                            </th>
                                            <th
                                                scope="col"
                                                class="myPrimaryTableTh"
                                            >
                                                automatic_tax
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="myPrimaryTableTBody">
                                        <TransitionGroup name="table">
                                            <tr
                                                class="myPrimaryTableTBodyTr"
                                                v-for="payment in fetchedPayments
                                                    .payments.invoices"
                                                :key="payment.id"
                                            >
                                                <td
                                                    class="myPrimaryTableTBodyTd"
                                                >
                                                    {{ payment.id }}
                                                </td>
                                                <td
                                                    class="myPrimaryTableTBodyTd"
                                                >
                                                    <a
                                                        :href="
                                                            payment.invoice_pdf
                                                        "
                                                        target="_blank"
                                                        class="myPrimaryLink"
                                                        >Invoice PDF</a
                                                    >
                                                </td>
                                                <td
                                                    class="myPrimaryTableTBodyTd"
                                                >
                                                    {{ payment.customer_name }}
                                                </td>
                                                <td
                                                    class="myPrimaryTableTBodyTd"
                                                >
                                                    {{ payment.customer_email }}
                                                </td>
                                                <td
                                                    class="myPrimaryTableTBodyTd"
                                                >
                                                    $
                                                    {{ payment.subtotal / 100 }}
                                                </td>
                                                <td
                                                    class="myPrimaryTableTBodyTd"
                                                >
                                                    $
                                                    {{
                                                        payment.subtotal_excluding_tax /
                                                        100
                                                    }}
                                                </td>
                                                <td
                                                    class="myPrimaryTableTBodyTd"
                                                >
                                                    $ {{ payment.tax / 100 }}
                                                </td>

                                                <td
                                                    class="myPrimaryTableTBodyTd"
                                                >
                                                    {{
                                                        payment.account_tax_ids
                                                    }}
                                                </td>
                                                <td
                                                    class="myPrimaryTableTBodyTd"
                                                >
                                                    Country:
                                                    {{
                                                        payment.account_country
                                                    }}
                                                    — Name:
                                                    {{ payment.account_name }}
                                                </td>

                                                <td
                                                    class="myPrimaryTableTBodyTd"
                                                >
                                                    {{ payment.attempt_count }}
                                                </td>

                                                <td
                                                    class="myPrimaryTableTBodyTd"
                                                >
                                                    {{
                                                        payment.collection_method
                                                    }}
                                                </td>

                                                <td
                                                    class="myPrimaryTableTBodyTd"
                                                >
                                                    {{ payment.billing_reason }}
                                                </td>
                                                <td
                                                    class="myPrimaryTableTBodyTd"
                                                >
                                                    {{ payment.currency }}
                                                </td>
                                                <td
                                                    class="myPrimaryTableTBodyTd"
                                                >
                                                    Enabled:
                                                    {{
                                                        payment.automatic_tax
                                                            .enabled
                                                    }}
                                                    — Status:
                                                    {{
                                                        payment.automatic_tax
                                                            .status
                                                    }}
                                                </td>
                                            </tr>
                                        </TransitionGroup>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </LoggedInLayout>
    </MainLayout>
</template>
