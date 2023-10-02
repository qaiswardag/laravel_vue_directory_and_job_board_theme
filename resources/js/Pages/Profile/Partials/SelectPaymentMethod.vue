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
import CardForm from "../../Stripe/Partials/CardForm.vue";
import { useStore } from "vuex";

import {
    RadioGroup,
    RadioGroupDescription,
    RadioGroupLabel,
    RadioGroupOption,
} from "@headlessui/vue";
import { vueFetch } from "use-lightweight-fetch";

const store = useStore();

const props = defineProps({
    team: {
        required: true,
    },
    user: {
        required: true,
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

const reFetchPaymentMethods = function () {
    handleGetPaymentMethods(
        route("stripe.payment.methods.index", {
            user: props.user,
            team: props.team,
        }),
        {},
        {
            additionalCallTime: 200,
            abortTimeoutTime: 8000,
        }
    );
};

const selectedMethod = ref(null);

const paymentMethodClick = function (method) {
    if (selectedMethod.value?.id === method.id) {
        selectedMethod.value = null;
        store.commit("user/setPaymentMethod", null);
    } else {
        selectedMethod.value = method;
        store.commit("user/setPaymentMethod", selectedMethod.value);
    }
};

onMounted(() => {
    reFetchPaymentMethods();
});
</script>

<template>
    <div class="py-4 px-4 rounded">
        <p
            v-if="errorPaymentMethods && !isLoadingPaymentMethods"
            class="myPrimaryInputError mt-2 mb-0 py-0 self-start"
        >
            {{ errorPaymentMethods }}
        </p>

        <div v-if="!isLoadingPaymentMethods">
            <RadioGroup>
                <RadioGroupLabel class="sr-only">Server size</RadioGroupLabel>
                <div class="space-y-4">
                    <RadioGroupOption
                        as="template"
                        v-for="plan in fetchedPaymentMethods &&
                        fetchedPaymentMethods.paymentMethods"
                        :key="plan.id"
                        :value="plan"
                        v-slot="{ active, checked }"
                        @click="paymentMethodClick(plan)"
                    >
                        <div
                            :class="[
                                selectedMethod?.id === plan.id
                                    ? 'border-2 border-myPrimaryLinkColor'
                                    : 'border-emerald-50',
                                'cursor-pointer relative border-2 rounded-lg px-6 py-4 shadow-sm sm:flex sm:justify-between',
                            ]"
                        >
                            <span class="flex items-center">
                                <span class="flex flex-col text-sm">
                                    <RadioGroupLabel
                                        as="span"
                                        class="font-medium text-gray-900 mb-4"
                                    >
                                        {{ plan.card?.brand?.toUpperCase() }}
                                    </RadioGroupLabel>
                                    <RadioGroupDescription
                                        as="span"
                                        class="text-gray-500 flex flex-col gap-2"
                                    >
                                        <p>
                                            **** **** ****
                                            {{ plan.card?.last4 }}
                                        </p>
                                        {{ plan.card?.exp_month }} /
                                        {{ plan.card?.exp_year }}
                                        <p class="block sm:inline">
                                            Name:
                                            {{ plan.billing_details?.name }}

                                            / Country:
                                            {{ plan.card?.country }}
                                        </p>
                                    </RadioGroupDescription>
                                </span>
                            </span>
                        </div>
                    </RadioGroupOption>
                </div>
            </RadioGroup>
        </div>

        <div class="my-8">
            <div class="mt-6">
                <SubmitButton
                    @firstButtonClick="reFetchPaymentMethods"
                    :disabled="isLoadingPaymentMethods"
                    buttonText="Get Payment methods"
                >
                </SubmitButton>
            </div>
        </div>
    </div>
</template>
