<script setup>
import Modal from "@/Components/Modals/Modal.vue";
import { ref, computed, onMounted, onBeforeMount, nextTick, watch } from "vue";
import {
    TrashIcon,
    CheckIcon,
    NewspaperIcon,
    XMarkIcon,
    ChevronUpDownIcon,
    PlusIcon,
} from "@heroicons/vue/24/outline";
import Spinner from "@/Components/PageBuilder/Loaders/Spinner.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import { useStore } from "vuex";
import { Link, router, useForm } from "@inertiajs/vue3";
import InputError from "@/Components/Forms/InputError.vue";
import countryListAllIsoData from "@/utils/country-list-all-iso-data";
import SubmitButton from "@/Components/Buttons/SubmitButton.vue";

import {
    Combobox,
    ComboboxInput,
    ComboboxButton,
    ComboboxOptions,
    ComboboxOption,
    TransitionRoot,
} from "@headlessui/vue";

const store = useStore();

const elementsStylesCard = {
    style: {
        base: {
            color: "#000",
            fontWeight: "500",
            fontFamily: "Jost",
            fontSize: "16px",
            fontSmoothing: "antialiased",
            ":-webkit-autofill": {
                color: "#fce883",
            },
            "::placeholder": {
                color: "#aaa",
            },
        },
        invalid: {
            iconColor: "#0000",
            color: "myPrimaryErrorColor",
        },
    },
};

const props = defineProps({
    title: {
        required: true,
    },
    firstButtonTextModalPaymentMethodForm: {
        required: true,
    },
    show: {
        type: Boolean,
        default: false,
        required: true,
    },
    publishableKey: {
        required: true,
    },

    // props.intent.client_secret
    // The client_secret is intended for the app or service handling the payment processing
    // rather than for the end-user. It's a confidential key used for secure communication
    // between your application and Stripe's servers.
    user: {
        required: true,
    },
    intent: {
        required: true,
    },
});

const form = useForm({
    user_id: props.user.id,
    name: props.user.first_name + " " + props.user.last_name,
    email: props.user.email,
    country: null,
    city: null,
    postal_code: null,
});

const emit = defineEmits([
    "firstModalPaymentMethodFunctionForm",
    "secondModalPaymentMethodFunctionForm",
]);

const responseStripeCreateSubscription = ref(null);

const stripe = Stripe(props.publishableKey);

const elements = ref(null);
const cardElement = ref(null);

//
// first button function
const firstButton = function () {
    emit("firstModalPaymentMethodFunctionForm");
};
const secondButton = function () {
    validateBillingDetails();
};

const validateBillingDetails = function () {
    form.country = selectedCountry.value?.code;

    form.post(route("stripe.payment.methods.store"), {
        preserveScroll: true,
        onSuccess: () => {
            console.log(`success`);
            createOrUpdatePayment();
        },
        onError: () => {},
        onFinish: () => {},
    });
};

const createOrUpdatePayment = async function () {
    try {
        responseStripeCreateSubscription.value = await stripe.confirmCardSetup(
            props.intent.client_secret,
            {
                payment_method: {
                    card: cardElement.value,
                    billing_details: {
                        name: form.name,
                        email: form.email,
                        address: {
                            country: form.country,
                            city: form.city,
                            postal_code: form.postal_code,
                        },
                    },
                },
            }
        );
        console.log(`data er:`, responseStripeCreateSubscription.value);

        if (responseStripeCreateSubscription.value?.setupIntent?.status) {
            firstButton();
            emit("secondModalPaymentMethodFunctionForm");
        }
    } catch (err) {
        console.error(
            `Encountered an error while trying to create a subscription.`,
            err
        );
    }
};

const query = ref("");

const filteredCountries = computed(() =>
    query.value === ""
        ? countryListAllIsoData
        : countryListAllIsoData.filter((country) =>
              country.name?.toLowerCase().includes(query.value.toLowerCase())
          )
);

const selectedCountry = ref(null);

const handleRemoveInput = function () {
    selectedCountry.value = null;
};
//
//
onMounted(async () => {
    await nextTick();
    elements.value = stripe.elements({
        clientSecret: props.intent.client_secret,
    });
    cardElement.value = elements.value.create("card", elementsStylesCard);
    cardElement.value.mount("#card-element");
});
</script>

<template>
    <Modal
        maxWidth="xl"
        :show="show"
        @close="firstButton"
        minHeight="min-h-[30rem]"
        maxHeight="max-h-auto"
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
                    <XMarkIcon
                        class="h-6 w-6 text-myPrimaryDarkGrayColor self-center cursor-pointer"
                    ></XMarkIcon>
                </div>
            </div>

            <!-- content start -->
            <main>
                <div class="mb-4 myPrimaryFormMain w-full">
                    <div class="myInputsOrganization">
                        <div class="myInputGroup">
                            <p class="my-4">
                                <a
                                    href="https://stripe.com/docs/testing"
                                    target="blank"
                                    >Testing Cards</a
                                >
                            </p>
                            <p class="my-4">4000002080000001</p>
                            <p class="my-4">12/34</p>
                            <p class="my-4">567</p>
                            <!-- Email -->
                            <div class="myInputGroup">
                                <div
                                    class="myPrimaryFormOrganizationHeaderDescriptionSection"
                                >
                                    <div
                                        class="myPrimaryFormOrganizationHeader"
                                    >
                                        Information
                                    </div>
                                </div>
                                <InputLabel
                                    for="card_holder_name"
                                    value="Cardholder name"
                                />
                                <TextInput
                                    placeholder="Cardholder name.."
                                    id="card_holder_name"
                                    v-model="form.name"
                                    type="text"
                                    autocomplete="off"
                                    class="block w-full mt-1"
                                />
                                <InputError :message="form.errors.name" />
                            </div>
                        </div>

                        <div class="myInputGroup">
                            <InputLabel for="payment_email" value="Email" />
                            <TextInput
                                id="payment_email"
                                placeholder="Email.."
                                v-model="form.email"
                                type="email"
                                class="block w-full mt-1"
                            />
                            <InputError :message="form.errors.email" />
                        </div>
                    </div>

                    <div class="myInputsOrganization">
                        <div class="myInputGroup">
                            <div
                                class="myPrimaryFormOrganizationHeaderDescriptionSection"
                            >
                                <div class="myPrimaryFormOrganizationHeader">
                                    Card information
                                </div>
                            </div>
                            <div class="pb-6 pt-2" id="card-element"></div>
                            <div
                                class="myPrimaryInputError mt-2 mb-0 py-0 self-start"
                                v-if="responseStripeCreateSubscription?.error"
                            >
                                {{
                                    responseStripeCreateSubscription.error
                                        .message
                                }}
                            </div>
                        </div>
                    </div>

                    <div class="myInputsOrganization">
                        <div class="myInputGroup">
                            <div
                                class="myPrimaryFormOrganizationHeaderDescriptionSection"
                            >
                                <div class="myPrimaryFormOrganizationHeader">
                                    Billing address
                                </div>
                            </div>

                            <InputLabel for="payment_country" value="Country" />
                            <!-- Headless UI select # start -->
                            <div>
                                <Combobox v-model="selectedCountry">
                                    <div class="relative mt-1">
                                        <div class="relative">
                                            <ComboboxInput
                                                class="myPrimarySelect"
                                                autocomplete="off"
                                                placeholder="Search.."
                                                :displayValue="
                                                    (country) => {
                                                        return country?.name;
                                                    }
                                                "
                                                @change="
                                                    query = $event.target.value
                                                "
                                            />

                                            <div
                                                class="absolute inset-y-0 right-0 flex items-center pr-2"
                                            >
                                                <div
                                                    class="flex items-center justify-center gap-2"
                                                >
                                                    <button
                                                        @click="
                                                            handleRemoveInput
                                                        "
                                                        class="h-8 w-8 cursor-pointer rounded flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                                                    >
                                                        <XMarkIcon
                                                            class="shrink-0 w-4 h-4 m-2 stroke-2"
                                                        ></XMarkIcon>
                                                    </button>
                                                    <ComboboxButton
                                                        class="h-8 w-8 cursor-pointer rounded flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                                                    >
                                                        <ChevronUpDownIcon
                                                            class="shrink-0 w-4 h-4 m-2 stroke-2"
                                                            aria-hidden="true"
                                                        />
                                                    </ComboboxButton>
                                                </div>
                                            </div>
                                        </div>
                                        <TransitionRoot
                                            leave="transition ease-in duration-100"
                                            leaveFrom="opacity-100"
                                            leaveTo="opacity-0"
                                            @after-leave="query = ''"
                                        >
                                            <ComboboxOptions
                                                class="absolute z-30 mt-1 max-h-36 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                                            >
                                                <div
                                                    v-if="
                                                        filteredCountries.length ===
                                                            0 && query !== ''
                                                    "
                                                    class="relative cursor-default select-none py-2 px-4 text-gray-700"
                                                >
                                                    Nothing found.
                                                </div>

                                                <ComboboxOption
                                                    v-for="country in filteredCountries"
                                                    as="template"
                                                    :key="country.id"
                                                    :value="country"
                                                    v-slot="{
                                                        selected,
                                                        active,
                                                    }"
                                                >
                                                    <li
                                                        class="relative cursor-default select-none py-2 pl-10 pr-4"
                                                        :class="{
                                                            'bg-gray-800 text-white':
                                                                active,
                                                            'text-gray-900':
                                                                !active,
                                                        }"
                                                    >
                                                        <span
                                                            class="block truncate"
                                                            :class="{
                                                                'font-medium':
                                                                    selected,
                                                                'font-normal':
                                                                    !selected,
                                                            }"
                                                        >
                                                            {{
                                                                country.name
                                                                    ? country.name
                                                                    : "Select"
                                                            }}
                                                        </span>

                                                        <span
                                                            v-if="!selected"
                                                            class="absolute inset-y-0 left-0 flex items-center pl-3"
                                                            :class="{
                                                                'text-gray-200':
                                                                    active,
                                                                'text-gray-200':
                                                                    !active,
                                                            }"
                                                        >
                                                            <PlusIcon
                                                                class="h-3 w-3"
                                                                aria-hidden="true"
                                                            />
                                                        </span>
                                                        <span
                                                            v-if="selected"
                                                            class="absolute inset-y-0 left-0 flex items-center pl-3"
                                                            :class="{
                                                                'text-white':
                                                                    active,
                                                                'text-gray-800':
                                                                    !active,
                                                            }"
                                                        >
                                                            <CheckIcon
                                                                class="h-5 w-5"
                                                                aria-hidden="true"
                                                            />
                                                        </span>
                                                    </li>
                                                </ComboboxOption>
                                            </ComboboxOptions>
                                        </TransitionRoot>
                                    </div>
                                </Combobox>
                            </div>
                            <!-- Headless UI select # end -->
                            <InputError :message="form.errors.country" />
                        </div>
                        <div class="flex items-center justify-center gap-2">
                            <div class="myInputGroup">
                                <InputLabel for="payment_city" value="City" />
                                <TextInput
                                    placeholder="City.."
                                    id="payment_city"
                                    v-model="form.city"
                                    type="text"
                                    autocomplete="off"
                                    class="block w-full mt-1"
                                />
                                <InputError :message="form.errors.city" />
                            </div>
                            <div class="myInputGroup">
                                <InputLabel
                                    for="payment_postal_code"
                                    value="Postal code"
                                />
                                <TextInput
                                    placeholder="Postal code.."
                                    id="payment_postal_code"
                                    v-model="form.postal_code"
                                    type="text"
                                    autocomplete="off"
                                    class="block w-full mt-1"
                                />
                                <InputError
                                    :message="form.errors.postal_code"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <!-- content end -->
        </div>
        <div
            class="bg-red-50 px-2 py-4 absolute bottom-0 left-0 right-0 flex sm:justify-end justify-center"
        >
            <div class="sm:w-3/6 w-full px-2 my-2 flex gap-2 justify-end">
                <button
                    v-if="firstButtonTextModalPaymentMethodForm"
                    ref="firstButtonRef"
                    class="mySecondaryButton"
                    type="button"
                    @click="firstButton"
                >
                    {{ firstButtonTextModalPaymentMethodForm }}
                </button>

                <SubmitButton
                    @firstButtonClick="secondButton"
                    :disabled="form.processing"
                    buttonText="Submit"
                >
                </SubmitButton>
            </div>
        </div>
    </Modal>
</template>

<style scoped>
.my-input {
    padding: 10px;
    border: 1px solid #ccc;
}
</style>
