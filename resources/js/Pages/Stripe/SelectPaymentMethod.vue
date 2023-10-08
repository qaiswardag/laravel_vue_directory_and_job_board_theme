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

import { vueFetch } from "use-lightweight-fetch";
import { PlusIcon } from "@heroicons/vue/20/solid";

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

const selectedMethod = ref(null);

const handleSelectPaymentMethod = function (method) {
    if (selectedMethod.value?.id === method.id) {
        selectedMethod.value = null;
        store.commit("user/setPaymentMethod", null);
    } else {
        selectedMethod.value = method;
        store.commit("user/setPaymentMethod", selectedMethod.value);
    }
};

const showModalPaymentMethodForm = ref(false);
const firstButtonTextModalPaymentMethodForm = ref(null);
const titleModalPaymentMethodForm = ref(null);
const firstModalPaymentMethodFunctionForm = ref(null);
const secondModalPaymentMethodFunctionForm = ref(null);

const handleCreatePaymentMethod = function () {
    titleModalPaymentMethodForm.value = "This is title for card form";
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

onMounted(() => {
    fetchPaymentMethods();
});
</script>

<template>
    <template v-if="isLoadingPaymentMethods && !isErrorPaymentMethods">
        <div class="flex flex-col w-full">
            <SmallUniversalSpinner
                class="flex justify-center items-center"
            ></SmallUniversalSpinner>
        </div>
    </template>
    <div>
        <p
            v-if="errorPaymentMethods && !isLoadingPaymentMethods"
            class="myPrimaryInputError mt-2 mb-0 py-0 self-start"
        >
            {{ errorPaymentMethods }}
        </p>

        <div v-if="!isLoadingPaymentMethods">
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

            <p
                class="py-4"
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

            <div>
                <div
                    v-if="
                        fetchedPaymentMethods &&
                        fetchedPaymentMethods.paymentMethods &&
                        Array.isArray(fetchedPaymentMethods.paymentMethods) &&
                        fetchedPaymentMethods.paymentMethods.length !== 0
                    "
                    class="p-2 border border-myPrimaryLightGrayColor"
                >
                    <div
                        class="min-h-[4rem] max-h-[18rem] flex flex-col w-full overflow-y-scroll divide-y divide-gray-200 pr-2"
                    >
                        <div
                            as="template"
                            v-for="paymentMethod in fetchedPaymentMethods.paymentMethods"
                            :key="paymentMethod.id"
                        >
                            <div
                                class="border border-transparent hover:border-myPrimaryLinkColor border-myPrimaryLinkColor shadow-sm sm:flex sm:justify-between rounded-lg myPrimaryTag bg-myPrimaryLinkColor bg-opacity-5"
                            >
                                <div class="flex items-center">
                                    <span class="flex flex-col text-sm">
                                        <div
                                            as="span"
                                            class="font-medium text-gray-900 mb-4"
                                        >
                                            {{
                                                paymentMethod.card?.brand?.toUpperCase()
                                            }}
                                        </div>
                                        <div
                                            as="span"
                                            class="text-gray-500 flex flex-col gap-2"
                                        >
                                            <p>
                                                **** **** ****
                                                {{ paymentMethod.card?.last4 }}
                                            </p>
                                            <p class="block sm:inline">
                                                Name:
                                                {{
                                                    paymentMethod
                                                        .billing_details?.name
                                                }}
                                            </p>
                                            <p class="block sm:inline">
                                                Country:
                                                {{
                                                    paymentMethod.card?.country
                                                }}
                                            </p>
                                        </div>
                                    </span>
                                </div>

                                <div>
                                    <button
                                        class="myPrimaryTag transition bg-white mt-0"
                                        v-if="
                                            selectedMethod?.id !==
                                            paymentMethod.id
                                        "
                                        type="button"
                                        @click="
                                            handleSelectPaymentMethod(
                                                paymentMethod
                                            )
                                        "
                                    >
                                        <span> Select </span>
                                    </button>
                                    <button
                                        class="myPrimaryTag transition bg-myPrimaryLinkColor text-white mt-0"
                                        v-if="
                                            selectedMethod?.id ===
                                            paymentMethod.id
                                        "
                                        type="button"
                                        @click="
                                            handleSelectPaymentMethod(
                                                paymentMethod
                                            )
                                        "
                                    >
                                        <span> Selected</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="flex items-center justify-between border-t border-gray-200 pt-2 mt-1"
                >
                    <p
                        @click="handleCreatePaymentMethod"
                        class="myPrimaryParagraph text-xs italic cursor-pointer mb-2"
                    >
                        New Payment Method?
                    </p>
                    <button
                        type="button"
                        class="h-10 w-10 cursor-pointer rounded-full flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                        @click="handleCreatePaymentMethod"
                    >
                        <PlusIcon
                            class="shrink-0 w-4 h-4 m-2 stroke-2"
                        ></PlusIcon>
                    </button>
                </div>
            </div>
        </div>
    </div>
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
