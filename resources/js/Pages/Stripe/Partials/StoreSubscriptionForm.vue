<script setup>
import { computed, nextTick, onBeforeMount, onMounted, ref, watch } from "vue";
import { Link, router, useForm } from "@inertiajs/vue3";
import ActionMessage from "@/Components/Actions/ActionMessage.vue";
import FormSection from "@/Components/Forms/FormSection.vue";
import InputError from "@/Components/Forms/InputError.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import SubmitButton from "@/Components/Buttons/SubmitButton.vue";
import SelectPaymentMethod from "@/Pages/Stripe/SelectPaymentMethod.vue";
import NotificationsFixedBottom from "@/Components/Modals/NotificationsFixedBottom.vue";
import { useStore } from "vuex";
import storeSubscriptionPrices from "@/utils/pricing/store-subscription-prices";
import countryListAllIsoData from "@/utils/country-list-all-iso-data";
import vatIdList from "@/utils/vat-id-list";

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
    post: {
        required: false,
    },
});

const formType = ref("create");

const handleSubmit = () => {
    createOrUpdate();
};

const selectedProduct = ref(null);

const handleSelectProduct = function (product) {
    if (selectedProduct.value?.id === product.id) {
        selectedProduct.value = null;
        formSubscription.product_id = null;
        formSubscription.price_product_identifier_stripe = null;
    } else {
        selectedProduct.value = product;
        formSubscription.product_id = product.id;
        formSubscription.price_product_identifier_stripe =
            product.priceProductIdentifierStripe;
    }
};

const formSubscription = useForm({
    user_id: props.user.id,
    first_name: props.user.first_name,
    last_name: props.user.last_name,
    email: props.user.email,
    product_id: null,
    price_product_identifier_stripe: null,
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

const createOrUpdate = () => {
    if (formType.value === "create") {
        formSubscription.country = selectedCountry.value?.code;
        formSubscription.phone_code = selectedPhoneCode.value?.phone_code;

        formSubscription.vat_id = selectedVatId.value?.code;
        formSubscription.tax_id = selectedVatId.value?.tax_id;

        formSubscription.post(route("stripe.stores.store.subscription"), {
            preserveScroll: true,
            onSuccess: () => {},
            onError: () => {},
            onFinish: () => {},
        });
    }

    if (formType.value === "update") {
        formSubscription.country = selectedCountry.value?.code;
        formSubscription.phone_code = selectedPhoneCode.value?.phone_code;

        formSubscription.vat_id = selectedVatId.value?.code;
        formSubscription.tax_id = selectedVatId.value?.tax_id;

        formSubscription.post(
            route("stripe.stores.update.subscription", props.post.id),
            {
                preserveScroll: true,
                onSuccess: () => {},
                onError: () => {},
                onFinish: () => {},
            }
        );
    }
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

onBeforeMount(() => {
    if (props.post) {
        formType.value = "update";

        const product = storeSubscriptionPrices().find((product) => {
            return (
                product.priceProductIdentifierStripe === props.post.stripe_price
            );
        });

        handleSelectProduct(product);
    }
});

onMounted(() => {
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
        <template #title> Subscription Form </template>
        <template #main>
            <p class="my-12">
                Form type er:
                <strong>
                    {{ formType }}
                </strong>
            </p>
            <p class="my-12">Post er: {{ post }}</p>
            <p class="my-12">form for submit er: {{ formSubscription }}</p>
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
                            v-model="formSubscription.line1"
                            type="text"
                            id="line1"
                            name="line1"
                            placeholder="Street address.."
                            autocomplete="off"
                        />
                        <InputError :message="formSubscription.errors.line1" />
                    </div>

                    <div class="myInputGroup">
                        <InputLabel
                            for="line2"
                            value="Apt, suite, building etc. — optional"
                        />
                        <TextInput
                            v-model="formSubscription.line2"
                            type="text"
                            id="line2"
                            name="line2"
                            placeholder="Apt, suite, building.."
                            autocomplete="off"
                        />
                        <InputError :message="formSubscription.errors.line2" />
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
                                                    'text-gray-900': !active,
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
                                                                loading="lazy"
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
                        <InputError
                            :message="formSubscription.errors.country"
                        />
                    </div>
                    <div class="myInputGroup">
                        <InputLabel for="city" value="City" />
                        <TextInput
                            id="city"
                            v-model="formSubscription.city"
                            placeholder="City.."
                            type="text"
                        />
                        <InputError :message="formSubscription.errors.city" />
                    </div>
                </div>

                <div class="md:flex items-center justify-center myPrimaryGap">
                    <div class="myInputGroup">
                        <InputLabel
                            for="state"
                            value="Province or region — optional"
                        />
                        <TextInput
                            v-model="formSubscription.state"
                            type="text"
                            id="state"
                            name="state"
                            placeholder="Province or region.."
                            autocomplete="off"
                        />
                        <InputError :message="formSubscription.errors.state" />
                    </div>

                    <!-- postal code and phone # start -->
                    <div class="myInputGroup">
                        <InputLabel
                            for="postal_code"
                            value="Postal code — optional"
                        />
                        <TextInput
                            v-model="formSubscription.postal_code"
                            type="text"
                            id="postal_code"
                            name="postal_code"
                            placeholder="Postal code.."
                            autocomplete="off"
                        />
                        <InputError
                            :message="formSubscription.errors.postal_code"
                        />
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
                                                    'text-gray-900': !active,
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
                                                                loading="lazy"
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
                        <InputError
                            :message="formSubscription.errors.phone_code"
                        />
                    </div>
                    <!-- phone code end -->
                    <div class="myInputGroup">
                        <InputLabel for="phone" value="Phone" />
                        <TextInput
                            v-model="formSubscription.phone"
                            type="text"
                            id="phone"
                            name="phone"
                            placeholder="Phone.."
                            autocomplete="off"
                        />
                        <InputError :message="formSubscription.errors.phone" />
                    </div>
                </div>
            </div>
            <!-- postal code and phone # end -->

            <div class="myInputsOrganization">
                <!-- Vat ID and vat number # start -->
                <div class="myPrimaryFormOrganizationHeaderDescriptionSection">
                    <div class="myPrimaryFormOrganizationHeader">
                        Company vat — optional
                    </div>
                </div>

                <div class="md:flex items-center justify-center myPrimaryGap">
                    <div class="myInputGroup">
                        <!-- Headless UI select # start -->
                        <InputLabel for="vat_id123" value="Vat id — optional" />
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
                                                    'text-gray-900': !active,
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
                                                                loading="lazy"
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
                        <InputError :message="formSubscription.errors.vat_id" />
                    </div>

                    <div class="myInputGroup">
                        <InputLabel
                            for="vat_number"
                            value="Vat number — optional"
                        />
                        <TextInput
                            v-model="formSubscription.vat_number"
                            type="text"
                            id="vat_number"
                            name="vat_number"
                            placeholder="Vat number.."
                            autocomplete="off"
                        />
                        <InputError
                            :message="formSubscription.errors.vat_number"
                        />
                    </div>
                </div>
                <!-- Vat ID and vat number # end -->
            </div>
        </template>

        <template #sidebar>
            <div class="myInputsOrganization">
                <div class="myPrimaryFormOrganizationHeader">
                    Select subscription type
                </div>

                <div>
                    <div class="space-y-4">
                        <div
                            as="template"
                            v-for="product in storeSubscriptionPrices(
                                $page.props.user
                            )"
                            :key="product.id"
                        >
                            <div
                                class="flex flex-col gap-2 border border-gray-200 hover:border-myPrimaryLinkColor shadow-sm sm:flex sm:justify-between rounded-lg myPrimaryTag"
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

                                                <p
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
                                                </p>
                                                <p
                                                    class="block text-[10px] leading-6 text-gray-600 italic mt-1"
                                                >
                                                    {{ product.billed }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div
                                        class="flex gap-2 justify-between items-start border-t border-gray-400 pt-2"
                                    >
                                        <div></div>
                                        <div>
                                            <button
                                                class="myPrimaryTag transition bg-white mt-0"
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
                    <InputError :message="formSubscription.errors.product_id" />
                </div>
            </div>

            <div class="myInputsOrganization">
                <div class="myPrimaryFormOrganizationHeader">
                    Payment methods
                </div>
                <SelectPaymentMethod
                    :user="user"
                    :intent="intent"
                    :publishableKey="publishableKey"
                ></SelectPaymentMethod>
                <InputError
                    :message="formSubscription.errors.payment_method_id"
                />
            </div>
        </template>
        <template #actions>
            <SubmitButton
                :disabled="formSubscription.processing"
                buttonText="Subscribe"
            >
            </SubmitButton>
            <div
                class="flex justify-end mt-4"
                v-if="Object.values(formSubscription.errors).length !== 0"
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
                        {{ Object.values(formSubscription.errors).length }}
                        {{
                            Object.values(formSubscription.errors).length === 1
                                ? "error"
                                : "errors"
                        }}
                    </p>
                </div>
            </div>

            <ActionMessage
                :on="formSubscription.recentlySuccessful"
                type="success"
            >
                Successfully submitted.
            </ActionMessage>
            <NotificationsFixedBottom
                :listOfMessages="Object.values(formSubscription.errors)"
                :show="showErrorNotifications"
                @notificationsModalButton="notificationsModalButton"
            >
                <div class="flex items-center justify-start gap-2">
                    <p class="myPrimaryParagraphError">
                        {{ Object.values(formSubscription.errors).length }}
                        {{
                            Object.values(formSubscription.errors).length === 1
                                ? "error"
                                : "errors"
                        }}
                    </p>
                </div>
            </NotificationsFixedBottom>
        </template>
    </FormSection>
</template>
