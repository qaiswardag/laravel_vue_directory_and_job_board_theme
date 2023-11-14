<script setup>
import { computed, nextTick, onBeforeMount, onMounted, ref, watch } from "vue";
import { Link, router, useForm } from "@inertiajs/vue3";
import ActionMessage from "@/Components/Actions/ActionMessage.vue";
import FormSection from "@/Components/Forms/FormSection.vue";
import InputError from "@/Components/Forms/InputError.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import SubmitButton from "@/Components/Buttons/SubmitButton.vue";
import NotificationsFixedBottom from "@/Components/Modals/NotificationsFixedBottom.vue";
import { useStore } from "vuex";
import countryListAllIsoData from "@/utils/country-list-all-iso-data";
import vatIdList from "@/utils/vat-id-list";
import { usePage } from "@inertiajs/vue3";

import {
    TrashIcon,
    CheckIcon,
    NewspaperIcon,
    XMarkIcon,
    ChevronUpDownIcon,
    PlusIcon,
    InformationCircleIcon,
} from "@heroicons/vue/24/outline";
import {
    Combobox,
    ComboboxInput,
    ComboboxButton,
    ComboboxOptions,
    ComboboxOption,
    TransitionRoot,
} from "@headlessui/vue";

const store = useStore();

const props = defineProps({
    user: {
        required: true,
    },
    intent: {
        required: true,
    },
    publishableKey: {
        required: true,
    },
    products: {
        required: true,
    },
    job: {
        required: true,
    },
});

const getIsLoading = computed(() => {
    return store.getters["user/getIsLoading"];
});

const selectedProduct = ref(null);

const handleSelectProduct = function (product) {
    if (selectedProduct.value?.id === product.id) {
        selectedProduct.value = null;
        form.product_id = null;
        form.price_identifier_stripe = null;
        form.chargeable_amount_integer = null;
    } else {
        selectedProduct.value = product;
        form.product_id = product.id;
        form.price_identifier_stripe = product.priceIdentifierStripe;
        form.chargeable_amount_integer = product.chargeableAmountInteger;
    }
};

const form = useForm({
    next_route_name: "team.jobs.index",
    user_id: props.user.id,
    first_name: props.user.first_name,
    last_name: props.user.last_name,
    name: props.user.first_name + " " + props.user.last_name,
    email: props.user.email,
    product_id: null,
    price_identifier_stripe: null,
    chargeable_amount_integer: null,
    card_id: null,
    country: props.user.country,
    city: props.user.city,
    state: props.user.state,
    line1: props.user.line1,
    line2: props.user.line2,
    postal_code: props.user.postal_code,
    phone: props.user.phone,
    phone_code: props.user.phone_code,

    vat_id: props.user.vat_id,
    tax_id: props.user.tax_id,
    vat_number: props.user.vat_number,
});

const createOrUpdate = async () => {
    form.country = selectedCountry.value?.code;
    form.phone_code = selectedPhoneCode.value?.phone_code;

    form.vat_id = selectedVatId.value?.code;
    form.tax_id = selectedVatId.value?.tax_id;

    form.post(
        route("stripe.single.charge.job.store", [
            usePage().props.currentUserTeam.id,
            props.job.id,
        ]),
        {
            onSuccess: () => {},
            onError: () => {},
            onFinish: () => {},
        }
    );
};

const showErrorNotifications = ref(false);

const notificationsModalButton = function () {
    showErrorNotifications.value = false;
};

// country # start
const selectedCountry = ref(null);
const query = ref("");

const filteredCountries = computed(() =>
    query.value === ""
        ? countryListAllIsoData
        : countryListAllIsoData.filter((country) =>
              country.country?.toLowerCase().includes(query.value.toLowerCase())
          )
);

const handleRemoveInput = function () {
    selectedCountry.value = null;
};
// country # end

// VAT ID # start
const selectedVatId = ref(null);
const queryVatId = ref("");

const filteredVatIds = computed(() =>
    queryVatId.value === ""
        ? vatIdList
        : vatIdList.filter((country) =>
              country.country
                  ?.toLowerCase()
                  .includes(queryVatId.value.toLowerCase())
          )
);

const handleRemoveVatIdInput = function () {
    selectedVatId.value = null;
};
// VAT ID # end

// phone code # start
const selectedPhoneCode = ref(null);
const queryPhoneCode = ref("");

const filteredPhoneCodes = computed(() =>
    queryPhoneCode.value === ""
        ? countryListAllIsoData
        : countryListAllIsoData.filter((country) => {
              return (
                  country.phone_code.includes(queryPhoneCode.value) ||
                  country.country
                      ?.toLowerCase()
                      .includes(queryPhoneCode.value.toLowerCase())
              );
          })
);

const handleRemoveInputPhoneCode = function () {
    selectedPhoneCode.value = null;
};
// phone code # end

//  Stripe
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

const stripe = Stripe(props.publishableKey);
const elements = ref(null);
const cardElement = ref(null);

const handleSubmit = () => {
    proccessCard();
};

const cardError = ref(null);

const proccessCard = async function () {
    store.commit("user/setIsLoading", true);
    try {
        const { paymentMethod, error } = await stripe.createPaymentMethod(
            "card",
            cardElement.value,
            {
                billing_details: { name: form.name },
            }
        );

        if (error) {
            cardError.value = error.message;
        } else {
            // The card has been verified successfully...
            cardError.value = null;
            form.card_id = paymentMethod.id;
            await createOrUpdate();
        }
    } catch (err) {
        cardError.value = `Something went wrong with the payment method. ${err.message}`;
    } finally {
        store.commit("user/setIsLoading", false);
    }
};

onMounted(async () => {
    await nextTick();
    elements.value = stripe.elements();
    cardElement.value = elements.value.create("card", elementsStylesCard);
    cardElement.value.mount("#card-element");
    //
    // Stripe end
    if (props.user.country) {
        selectedCountry.value =
            filteredCountries.value.find((country) => {
                return country.code === props.user.country;
            }) || null;
    }

    if (props.user.phone_code) {
        selectedPhoneCode.value =
            filteredPhoneCodes.value.find((country) => {
                return country.phone_code === props.user.phone_code;
            }) || null;
    }

    if (props.user.vat_id) {
        selectedVatId.value =
            filteredVatIds.value.find((country) => {
                return country.code === props.user.vat_id;
            }) || null;
    }
});
</script>

<template>
    <FormSection @submitted="handleSubmit">
        <template #title> Payment for job {{ job.title }} </template>
        <template #main>
            <div class="myInputsOrganization">
                <div class="myPrimaryFormOrganizationHeaderDescriptionSection">
                    <div class="myPrimaryFormOrganizationHeader">
                        Billing Information
                    </div>
                </div>

                <div class="md:flex items-center justify-center myPrimaryGap">
                    <div class="myInputGroup">
                        <InputLabel for="line1" value="Street address" />
                        <TextInput
                            v-model="form.line1"
                            type="text"
                            id="line1"
                            name="line1"
                            placeholder="Street address.."
                            autocomplete="off"
                        />
                        <InputError :message="form.errors.line1" />
                    </div>

                    <div class="myInputGroup">
                        <InputLabel
                            for="line2"
                            value="Apt, suite, building etc. "
                        />
                        <TextInput
                            v-model="form.line2"
                            type="text"
                            id="line2"
                            name="line2"
                            placeholder="Apt, suite, building.."
                            autocomplete="off"
                        />
                        <InputError :message="form.errors.line2" />
                    </div>
                </div>

                <div class="md:flex items-center justify-center myPrimaryGap">
                    <div class="myInputGroup">
                        <!-- Headless UI select # start -->
                        <InputLabel for="country123" value="Country" />
                        <!-- Headless UI select # start -->
                        <Combobox v-model="selectedCountry">
                            <div class="relative mt-1">
                                <div class="relative">
                                    <ComboboxInput
                                        name="country123"
                                        id="country123"
                                        class="myPrimarySelect"
                                        autocomplete="country123"
                                        placeholder="Search.."
                                        :displayValue="
                                            (country) => {
                                                return country?.country;
                                            }
                                        "
                                        @change="query = $event.target.value"
                                    />

                                    <div
                                        class="absolute inset-y-0 right-0 flex items-center pr-2"
                                    >
                                        <div
                                            class="flex items-center justify-center gap-2"
                                        >
                                            <button
                                                @click="handleRemoveInput"
                                                type="button"
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
                                            v-slot="{ selected, active }"
                                        >
                                            <li
                                                class="relative cursor-default select-none py-2 pl-10 pr-4"
                                                :class="{
                                                    'bg-gray-800 text-white':
                                                        active,
                                                    'text-myPrimaryDarkGrayColor':
                                                        !active,
                                                }"
                                            >
                                                <span
                                                    class="block truncate"
                                                    :class="{
                                                        'font-medium': selected,
                                                        'font-normal':
                                                            !selected,
                                                    }"
                                                >
                                                    <div
                                                        class="flex items-center gap-2"
                                                    >
                                                        <div
                                                            class="flex-shrink-0 h-3 w-5"
                                                        >
                                                            <img
                                                                :src="`/app-images/flags/${country.code.toLowerCase()}.svg`"
                                                                class="object-cover object-center h-3 w-5 rounded-full drop-shadow-sm"
                                                                alt="flag"
                                                            />
                                                        </div>
                                                        {{
                                                            country.country
                                                                ? country.country
                                                                : ""
                                                        }}
                                                    </div>
                                                </span>

                                                <span
                                                    v-if="!selected"
                                                    class="absolute inset-y-0 left-0 flex items-center pl-3"
                                                    :class="{
                                                        'text-gray-200': active,
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
                                                        'text-white': active,
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
                        <InputError :message="form.errors.country" />
                    </div>
                    <div class="myInputGroup">
                        <InputLabel for="city" value="City" />
                        <TextInput
                            id="city"
                            v-model="form.city"
                            placeholder="City.."
                            type="text"
                        />
                        <InputError :message="form.errors.city" />
                    </div>
                </div>

                <div class="md:flex items-center justify-center myPrimaryGap">
                    <div class="myInputGroup">
                        <InputLabel for="state" value="Province or region " />
                        <TextInput
                            v-model="form.state"
                            type="text"
                            id="state"
                            name="state"
                            placeholder="Province or region.."
                            autocomplete="off"
                        />
                        <InputError :message="form.errors.state" />
                    </div>

                    <!-- postal code and phone # start -->
                    <div class="myInputGroup">
                        <InputLabel for="postal_code" value="Postal code " />
                        <TextInput
                            v-model="form.postal_code"
                            type="text"
                            id="postal_code"
                            name="postal_code"
                            placeholder="Postal code.."
                            autocomplete="off"
                        />
                        <InputError :message="form.errors.postal_code" />
                    </div>
                </div>

                <div class="md:flex items-center justify-center myPrimaryGap">
                    <!-- phone code start -->
                    <div class="myInputGroup">
                        <!-- Headless UI select # start -->
                        <InputLabel
                            for="phone_code123"
                            value="Phone country code"
                        />
                        <!-- Headless UI select # start -->
                        <Combobox v-model="selectedPhoneCode">
                            <div class="relative mt-1">
                                <div class="relative">
                                    <ComboboxInput
                                        name="phone_code123"
                                        id="phone_code123"
                                        autocomplete="phone_code123"
                                        class="myPrimarySelect"
                                        placeholder="Search.."
                                        :displayValue="
                                            (country) => {
                                                return country?.phone_code
                                                    ? `+ ${country.phone_code} ${country?.country}`
                                                    : '';
                                            }
                                        "
                                        @change="
                                            queryPhoneCode = $event.target.value
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
                                                    handleRemoveInputPhoneCode
                                                "
                                                type="button"
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
                                    @after-leave="queryPhoneCode = ''"
                                >
                                    <ComboboxOptions
                                        class="absolute z-30 mt-1 max-h-36 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                                    >
                                        <div
                                            v-if="
                                                filteredPhoneCodes.length ===
                                                    0 && queryPhoneCode !== ''
                                            "
                                            class="relative cursor-default select-none py-2 px-4 text-gray-700"
                                        >
                                            Nothing found.
                                        </div>

                                        <ComboboxOption
                                            v-for="country in filteredPhoneCodes"
                                            as="template"
                                            :key="country.code"
                                            :value="country"
                                            v-slot="{ selected, active }"
                                        >
                                            <li
                                                class="relative cursor-default select-none py-2 pl-10 pr-4"
                                                :class="{
                                                    'bg-gray-800 text-white':
                                                        active,
                                                    'text-myPrimaryDarkGrayColor':
                                                        !active,
                                                }"
                                            >
                                                <span
                                                    class="block truncate"
                                                    :class="{
                                                        'font-medium': selected,
                                                        'font-normal':
                                                            !selected,
                                                    }"
                                                >
                                                    <div
                                                        class="flex items-center gap-2"
                                                    >
                                                        +
                                                        {{
                                                            country?.phone_code
                                                                ? country.phone_code
                                                                : ""
                                                        }}
                                                        <div
                                                            class="flex-shrink-0 h-3 w-5"
                                                        >
                                                            <img
                                                                :src="`/app-images/flags/${country.code.toLowerCase()}.svg`"
                                                                class="object-cover object-center h-3 w-5 rounded-full drop-shadow-sm"
                                                                alt="flag"
                                                            />
                                                        </div>
                                                    </div>
                                                </span>

                                                <span
                                                    v-if="!selected"
                                                    class="absolute inset-y-0 left-0 flex items-center pl-3"
                                                    :class="{
                                                        'text-gray-200': active,
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
                                                        'text-white': active,
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
                        <InputError :message="form.errors.phone_code" />
                    </div>
                    <!-- phone code end -->
                    <div class="myInputGroup">
                        <InputLabel for="phone" value="Phone" />
                        <TextInput
                            v-model="form.phone"
                            type="text"
                            id="phone"
                            name="phone"
                            placeholder="Phone.."
                            autocomplete="off"
                        />
                        <InputError :message="form.errors.phone" />
                    </div>
                </div>
            </div>
            <!-- postal code and phone # end -->

            <!-- pay with card # start -->
            <div class="myInputsOrganization">
                <!-- Vat ID and vat number # start -->
                <div class="myPrimaryFormOrganizationHeaderDescriptionSection">
                    <div class="myPrimaryFormOrganizationHeader">
                        Pay with card
                    </div>
                </div>

                <div class="myInputGroup">
                    <div>
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

                        <!-- Stripe Elements Placeholder -->
                        <div
                            class="py-8 px-4 mt-8 mb-2 rounded border-2 border-red-200"
                        >
                            <div id="card-element"></div>
                        </div>
                        <template v-if="cardError">
                            <p
                                class="myPrimaryInputError mt-2 mb-0 py-0 self-start"
                            >
                                {{ cardError }}
                            </p>
                        </template>
                        <InputError :message="form.errors.card_id" />
                    </div>
                </div>
            </div>
            <!-- pay with card # end -->

            <!-- Vat ID and vat number # start -->
            <div class="myInputsOrganization">
                <!-- Vat ID and vat number # start -->
                <div class="myPrimaryFormOrganizationHeaderDescriptionSection">
                    <div class="myPrimaryFormOrganizationHeader">
                        Company vat
                    </div>
                </div>

                <div class="md:flex items-center justify-center myPrimaryGap">
                    <div class="myInputGroup">
                        <!-- Headless UI select # start -->
                        <InputLabel for="vat_id123" value="Vat id " />
                        <!-- Headless UI select # start -->
                        <Combobox v-model="selectedVatId">
                            <div class="relative mt-1">
                                <div class="relative">
                                    <ComboboxInput
                                        name="vat_id123"
                                        id="vat_id123"
                                        class="myPrimarySelect"
                                        autocomplete="vat_id123"
                                        placeholder="Search.."
                                        :displayValue="
                                            (country) => {
                                                return country?.country;
                                            }
                                        "
                                        @change="
                                            queryVatId = $event.target.value
                                        "
                                    />

                                    <div
                                        class="absolute inset-y-0 right-0 flex items-center pr-2"
                                    >
                                        <div
                                            class="flex items-center justify-center gap-2"
                                        >
                                            <button
                                                @click="handleRemoveVatIdInput"
                                                type="button"
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
                                    @after-leave="queryVatId = ''"
                                >
                                    <ComboboxOptions
                                        class="absolute z-30 mt-1 max-h-36 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                                    >
                                        <div
                                            v-if="
                                                filteredVatIds.length === 0 &&
                                                queryVatId !== ''
                                            "
                                            class="relative cursor-default select-none py-2 px-4 text-gray-700"
                                        >
                                            Nothing found.
                                        </div>

                                        <ComboboxOption
                                            v-for="country in filteredVatIds"
                                            as="template"
                                            :key="country.id"
                                            :value="country"
                                            v-slot="{ selected, active }"
                                        >
                                            <li
                                                class="relative cursor-default select-none py-2 pl-10 pr-4"
                                                :class="{
                                                    'bg-gray-800 text-white':
                                                        active,
                                                    'text-myPrimaryDarkGrayColor':
                                                        !active,
                                                }"
                                            >
                                                <span
                                                    class="block truncate"
                                                    :class="{
                                                        'font-medium': selected,
                                                        'font-normal':
                                                            !selected,
                                                    }"
                                                >
                                                    <div
                                                        class="flex items-center gap-2"
                                                    >
                                                        <div
                                                            class="flex-shrink-0 h-3 w-5"
                                                        >
                                                            <img
                                                                :src="`/app-images/flags/${country.code.toLowerCase()}.svg`"
                                                                class="object-cover object-center h-3 w-5 rounded-full drop-shadow-sm"
                                                                alt="flag"
                                                            />
                                                        </div>
                                                        {{
                                                            country.country
                                                                ? country.country
                                                                : ""
                                                        }}
                                                    </div>
                                                </span>

                                                <span
                                                    v-if="!selected"
                                                    class="absolute inset-y-0 left-0 flex items-center pl-3"
                                                    :class="{
                                                        'text-gray-200': active,
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
                                                        'text-white': active,
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
                        <InputError :message="form.errors.vat_id" />
                    </div>

                    <div class="myInputGroup">
                        <InputLabel for="vat_number" value="Vat number " />
                        <TextInput
                            v-model="form.vat_number"
                            type="text"
                            id="vat_number"
                            name="vat_number"
                            placeholder="Vat number.."
                            autocomplete="off"
                        />
                        <InputError :message="form.errors.vat_number" />
                    </div>
                </div>
            </div>
            <!-- Vat ID and vat number # end -->
        </template>

        <template #sidebar>
            <div class="myInputsOrganization">
                <div class="myPrimaryFormOrganizationHeader">Product</div>
                <div>
                    <div class="space-y-4">
                        <div
                            as="template"
                            v-for="product in products"
                            :key="product.id"
                        >
                            <div
                                class="flex flex-col gap-2 border border-gray-200 hover:border-myPrimaryLinkColor shadow-sm sm:flex sm:justify-between rounded-lg myPrimaryTag bg-white"
                            >
                                <div>
                                    <div class="flex items-center">
                                        <div class="flex flex-col text-sm">
                                            <div
                                                as="span"
                                                class="font-medium text-gray-900"
                                            >
                                                <h3
                                                    :id="product.id"
                                                    class="text-gray-900 text-lg font-semibold leading-8"
                                                >
                                                    {{ product.name }}
                                                </h3>

                                                <div
                                                    class="mt-2 flex items-baseline gap-x-1"
                                                >
                                                    <span
                                                        class="text-2xl font-bold tracking-tight text-gray-900"
                                                        >{{
                                                            product.price
                                                        }}</span
                                                    >
                                                    <span
                                                        class="text-sm font-semibold leading-6 text-gray-600"
                                                        >{{
                                                            product.frequencies
                                                        }}</span
                                                    >
                                                </div>
                                                <div
                                                    class="block text-[10px] leading-6 text-gray-600 italic mt-1"
                                                >
                                                    {{ product.billed }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div
                                        class="flex gap-2 justify-between items-start border-t border-gray-400 pt-2"
                                    >
                                        <div></div>
                                        <div>
                                            <button
                                                class="myPrimaryTag transition mt-0"
                                                v-if="
                                                    selectedProduct?.id !==
                                                    product.id
                                                "
                                                type="button"
                                                @click="
                                                    handleSelectProduct(product)
                                                "
                                            >
                                                <span> Select </span>
                                            </button>
                                            <button
                                                class="myPrimaryTag transition bg-myPrimaryLinkColor text-white mt-0"
                                                v-if="
                                                    selectedProduct?.id ===
                                                    product.id
                                                "
                                                type="button"
                                                @click="
                                                    handleSelectProduct(product)
                                                "
                                            >
                                                <div
                                                    class="flex items-center justify-center gap-2"
                                                >
                                                    <span> Selected</span>
                                                    <CheckIcon
                                                        class="w-3 h-3 stroke-2"
                                                    ></CheckIcon>
                                                </div>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <InputError :message="form.errors.product_id" />
                </div>

                <div class="my-6 p-4 border rounded">
                    <p class="my-2">Works: 4000002080000001</p>
                    <p class="my-2">
                        Can be added but insufficient funds: 4000008260003178
                    </p>
                    <p class="my-2">
                        Can be added but insufficient funds: Declined:
                        4000000000000002
                    </p>
                </div>
            </div>
        </template>
        <template #actions>
            <SubmitButton
                type="button"
                :disabled="form.processing || getIsLoading"
                buttonText="Complete"
                @firstButtonClick="handleSubmit"
            >
            </SubmitButton>
            <div
                class="flex justify-end mt-4"
                v-if="Object.values(form.errors).length !== 0"
            >
                <div
                    @click="showErrorNotifications = true"
                    class="w-fit py-1 flex items-center gap-2 rounded-md px-2 cursor-pointer italic"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="w-4 h-4 text-myPrimaryErrorColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"
                        />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                        />
                    </svg>
                    <p
                        class="myPrimaryParagraph text-xs text-myPrimaryErrorColor py-0 my-0"
                    >
                        Show
                        {{ Object.values(form.errors).length }}
                        {{
                            Object.values(form.errors).length === 1
                                ? "error"
                                : "errors"
                        }}
                    </p>
                </div>
            </div>

            <ActionMessage :on="form.recentlySuccessful" type="success">
                Successfully submitted.
            </ActionMessage>
            <NotificationsFixedBottom
                :listOfMessages="Object.values(form.errors)"
                :show="showErrorNotifications"
                @notificationsModalButton="notificationsModalButton"
            >
                <div class="flex items-center justify-start gap-2">
                    <p class="myPrimaryParagraphError">
                        {{ Object.values(form.errors).length }}
                        {{
                            Object.values(form.errors).length === 1
                                ? "error"
                                : "errors"
                        }}
                    </p>
                </div>
            </NotificationsFixedBottom>
        </template>
    </FormSection>
</template>
