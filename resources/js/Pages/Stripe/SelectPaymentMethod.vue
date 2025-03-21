<script setup>
import { nextTick, onBeforeMount, onMounted, ref, watch } from "vue";
import { Link, router, useForm } from "@inertiajs/vue3";
import ActionMessage from "@/Components/Actions/ActionMessage.vue";
import FormSection from "@/Components/Forms/FormSection.vue";
import InputError from "@/Components/Forms/InputError.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";
import SecondaryButton from "@/Components/Buttons/SecondaryButton.vue";
import SubmitButton from "@/Components/Buttons/SubmitButton.vue";
import { Switch } from "@headlessui/vue";
import DynamicModal from "@/Components/Modals/DynamicModal.vue";
import SectionBorder from "@/Components/Sections/SectionBorder.vue";
import { useStore } from "vuex";
import ModalPaymentMethodForm from "@/Pages/Stripe/Partials/ModalPaymentMethodForm.vue";
import SmallUniversalSpinner from "@/Components/Loaders/SmallUniversalSpinner.vue";
import { vueFetch } from "@/composables/vueFetch";
import { PlusIcon } from "@heroicons/vue/20/solid";
import { CheckIcon, TrashIcon } from "@heroicons/vue/24/outline";

const store = useStore();

const props = defineProps({
    user: {
        required: true,
    },
    intent: {
        required: true,
    },
    publishableKey: {
        publishableKey: true,
    },
});

// get images
const {
    handleData: handleGetPaymentMethods,
    fetchedData: fetchedPaymentMethods,
    isError: isErrorPaymentMethods,
    error: errorPaymentMethods,
    errors: errorsPaymentMethods,
    isLoading: isLoadingPaymentMethods,
    isSuccess: isSuccessPaymentMethods,
} = vueFetch();

const fetchPaymentMethods = function () {
    handleGetPaymentMethods(
        route("stripe.api.internal.payment.methods.index", {
            user: props.user,
        })
    );
};

const showModalPaymentMethodForm = ref(false);
const firstButtonTextModalPaymentMethodForm = ref(null);
const titleModalPaymentMethodForm = ref(null);
const firstModalPaymentMethodFunctionForm = ref(null);
const secondModalPaymentMethodFunctionForm = ref(null);

const handleCreatePaymentMethod = function () {
    titleModalPaymentMethodForm.value = "Add payment method";
    firstButtonTextModalPaymentMethodForm.value = "Close";
    showModalPaymentMethodForm.value = true;

    firstModalPaymentMethodFunctionForm.value = function () {
        showModalPaymentMethodForm.value = false;
    };

    secondModalPaymentMethodFunctionForm.value = function () {
        fetchPaymentMethods();
    };

    // end modal
};

const modalShowDeletePaymentMethod = ref(false);
const modalShowUpdatePaymentMethod = ref(false);

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

const handleDeletePaymentMethod = function (method) {
    modalShowDeletePaymentMethod.value = true;

    typeModal.value = "delete";
    gridColumnModal.value = 3;
    titleModal.value = `Delete payment method?`;
    descriptionModal.value = `Are you sure you want to delete this payment method?`;
    firstButtonModal.value = "Close";
    secondButtonModal.value = null;
    thirdButtonModal.value = "Delete";

    // handle click
    firstModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowDeletePaymentMethod.value = false;
    };
    // handle click
    thirdModalButtonFunction.value = function () {
        deletePaymentMethod(method);
    };
    // end modal
};

const deletePaymentMethodForm = useForm({
    payment_method_id: null,
});

//
//
//
// form action
const deletePaymentMethod = (method) => {
    deletePaymentMethodForm.payment_method_id = method.id;
    deletePaymentMethodForm.delete(route("stripe.payment.methods.destroy"), {
        preserveScroll: true,
        onSuccess: () => (modalShowDeletePaymentMethod.value = false),
        onError: (err) => {},
        onFinish: (log) => {
            modalShowDeletePaymentMethod.value = false;
            fetchPaymentMethods();
        },
    });
};

//
//
//
//
//
//
const handleSetDefaultPaymentMethod = function (method) {
    //
    modalShowUpdatePaymentMethod.value = true;

    typeModal.value = "success";
    gridColumnModal.value = 3;
    titleModal.value = `Set default payment method`;
    descriptionModal.value = `Are you sure you want to set this payment method as default?`;
    firstButtonModal.value = "Close";
    secondButtonModal.value = null;
    thirdButtonModal.value = "Set as default";

    // handle click
    firstModalButtonFunction.value = function () {
        // handle show modal for unique content
        modalShowUpdatePaymentMethod.value = false;
    };

    // handle click
    thirdModalButtonFunction.value = function () {
        updateDefaultPaymentMethod(method);
    };
    // end modal
};
//
//
//
//
const updatePaymentMethodForm = useForm({
    payment_method_id: null,
});

const updateDefaultPaymentMethod = function (method) {
    updatePaymentMethodForm.payment_method_id = method.id;

    updatePaymentMethodForm.post(route("stripe.payment.methods.update"), {
        preserveScroll: true,
        onSuccess: () => {},
        onError: () => {},
        onFinish: () => {
            modalShowUpdatePaymentMethod.value = false;
            fetchPaymentMethods();
        },
    });
};

onMounted(() => {
    fetchPaymentMethods();
});
</script>

<template>
    <DynamicModal
        :show="modalShowDeletePaymentMethod"
        :type="typeModal"
        :disabled="deletePaymentMethodForm.processing"
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
        :show="modalShowUpdatePaymentMethod"
        :type="typeModal"
        :disabled="updatePaymentMethodForm.processing"
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

    <template v-if="isLoadingPaymentMethods">
        <SmallUniversalSpinner
            class="h-40"
            width="w-6"
            height="h-6"
            border="border-4"
        ></SmallUniversalSpinner>
    </template>

    <p
        v-if="isErrorPaymentMethods && !isLoadingPaymentMethods"
        class="myPrimaryInputError mt-2 mb-0 py-0 self-start"
    >
        {{ errorPaymentMethods }}
    </p>

    <p
        class="myPrimaryParagraph"
        v-if="
            fetchedPaymentMethods &&
            fetchedPaymentMethods.paymentMethods &&
            Array.isArray(fetchedPaymentMethods.paymentMethods) &&
            fetchedPaymentMethods.paymentMethods.length === 0
        "
    >
        No payment methods are connected to your account.
    </p>

    <template v-if="!isLoadingPaymentMethods && !isErrorPaymentMethods">
        <div class="myPrimaryFormOrganizationHeader">
            <p
                class="pb-4 pt-2"
                v-if="
                    fetchedPaymentMethods &&
                    fetchedPaymentMethods.paymentMethods &&
                    Array.isArray(fetchedPaymentMethods.paymentMethods) &&
                    fetchedPaymentMethods.paymentMethods.length !== 0
                "
            >
                {{ fetchedPaymentMethods.paymentMethods.length }} available
                payment
                {{
                    fetchedPaymentMethods.paymentMethods.length === 1
                        ? "method"
                        : "methods"
                }}
            </p>
        </div>

        <div
            v-if="
                fetchedPaymentMethods &&
                fetchedPaymentMethods.paymentMethods &&
                Array.isArray(fetchedPaymentMethods.paymentMethods) &&
                fetchedPaymentMethods.paymentMethods.length !== 0
            "
            class="flex flex-col gap-6"
        >
            <div
                v-for="paymentMethod in fetchedPaymentMethods.paymentMethods"
                :key="paymentMethod.id"
            >
                <div class="px-2 py-8 rounded-lg border border-gray-200">
                    <div>
                        <div class="flex items-center">
                            <span class="flex flex-col text-sm">
                                <div as="span" class="myQuaternaryHeader">
                                    {{
                                        paymentMethod.card?.brand
                                            ?.charAt(0)
                                            .toUpperCase() +
                                        paymentMethod.card?.brand.slice(1)
                                    }}
                                </div>
                                <div
                                    as="span"
                                    class="myPrimaryParagraph text-xs flex flex-col gap-2"
                                >
                                    <div>
                                        **** **** ****
                                        {{ paymentMethod.card?.last4 }}
                                    </div>
                                    <div class="block sm:inline">
                                        Name:
                                        {{
                                            paymentMethod.billing_details?.name
                                        }}
                                    </div>
                                    <div class="block sm:inline">
                                        Country:
                                        {{ paymentMethod.card?.country }}
                                    </div>
                                </div>
                            </span>
                        </div>
                    </div>
                    <div
                        class="mt-4 flex justify-between items-center gap-4 text-xs font-medium myPrimaryTag w-max min-w-full my-0"
                    >
                        <div>
                            <button
                                v-if="
                                    fetchedPaymentMethods?.defaultPaymentMethodId !==
                                    paymentMethod.id
                                "
                                class="myPrimaryTag transition my-0 hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0 bg-white"
                                type="button"
                                @click="
                                    handleSetDefaultPaymentMethod(paymentMethod)
                                "
                            >
                                <span> Set default </span>
                            </button>
                            <button
                                v-if="
                                    fetchedPaymentMethods?.defaultPaymentMethodId ===
                                    paymentMethod.id
                                "
                                class="myPrimaryTag transition bg-myPrimaryLinkColor text-white my-0"
                                type="button"
                                @click="
                                    handleSetDefaultPaymentMethod(paymentMethod)
                                "
                            >
                                <div
                                    class="flex items-center justify-center gap-2"
                                >
                                    <span> Default</span>
                                    <span
                                        class="myMediumIcon material-symbols-outlined"
                                    >
                                        check
                                    </span>
                                </div>
                            </button>
                        </div>
                        <button
                            class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryErrorColor hover:text-white"
                            type="button"
                            @click="handleDeletePaymentMethod(paymentMethod)"
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
        </div>

        <div
            class="flex items-center justify-between pt-2 mt-8"
            :class="{
                'border-t border-gray-200':
                    fetchedPaymentMethods &&
                    fetchedPaymentMethods.paymentMethods &&
                    Array.isArray(fetchedPaymentMethods.paymentMethods) &&
                    fetchedPaymentMethods.paymentMethods.length !== 0,
            }"
        >
            <p
                @click="handleCreatePaymentMethod"
                class="myPrimaryParagraph text-xs cursor-pointer font-medium py-4"
            >
                Add Payment Method
            </p>
            <button
                type="button"
                class="h-10 w-10 cursor-pointer rounded-full flex items-center border-none justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                @click="handleCreatePaymentMethod"
            >
                <span class="myMediumIcon material-symbols-outlined">
                    add
                </span>
            </button>
        </div>

        <InputError
            v-if="
                deletePaymentMethodForm.errors &&
                deletePaymentMethodForm.errors.payment_method_id
            "
            :message="deletePaymentMethodForm.errors.payment_method_id"
        />
        <InputError
            v-if="
                updatePaymentMethodForm.errors &&
                updatePaymentMethodForm.errors.payment_method_id
            "
            :message="updatePaymentMethodForm.errors.payment_method_id"
        />
    </template>

    <ModalPaymentMethodForm
        v-if="showModalPaymentMethodForm"
        :show="showModalPaymentMethodForm"
        :title="titleModalPaymentMethodForm"
        :user="user"
        :intent="intent"
        :publishableKey="publishableKey"
        :firstButtonTextModalPaymentMethodForm="
            firstButtonTextModalPaymentMethodForm
        "
        @firstModalPaymentMethodFunctionForm="
            firstModalPaymentMethodFunctionForm
        "
        @secondModalPaymentMethodFunctionForm="
            secondModalPaymentMethodFunctionForm
        "
    ></ModalPaymentMethodForm>
</template>
