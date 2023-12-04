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
import countryListAllIsoData from "@/utils/country-list-all-iso-data";
import vatIdList from "@/utils/vat-id-list";

import {
    TrashIcon,
    CheckIcon,
    NewspaperIcon,
    XMarkIcon,
    ChevronUpDownIcon,
    MinusIcon,
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
    title: {
        required: true,
    },
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
    products: {
        required: true,
    },
    resource: {
        required: false,
    },
    createPath: {
        required: false,
    },
    updatePath: {
        required: false,
    },
    nextRouteName: {
        required: false,
    },
});

const formType = ref("create");

const handleSubmit = async () => {
    await createOrUpdate();
};

const selectedProduct = ref(null);
const productQuantity = ref(null);
const fullDynamicPrice = ref(0);

const handleSelectProduct = function (product) {
    if (!product.dynamic_product) {
        formCharge.product_quantity = null;
    }

    if (product.dynamic_product) {
        formCharge.product_quantity = productQuantity.value;
    }

    if (selectedProduct.value?.id === product.id) {
        selectedProduct.value = null;
        formCharge.dynamic_product = null;
        formCharge.product_quantity = null;
        formCharge.product_id = null;
        formCharge.price_identifier_stripe = null;
    } else {
        selectedProduct.value = product;
        formCharge.dynamic_product = product.dynamic_product;
        formCharge.product_id = product.id;
        formCharge.price_identifier_stripe = product.priceIdentifierStripe;

        formCharge.minimum_quantity = product.minimum_quantity;
        formCharge.maximum_quantity = product.maximum_quantity;
    }
};

const formCharge = useForm({
    next_route_name: props.nextRouteName,
    user_id: props.user.id,
    first_name: props.user.first_name,
    last_name: props.user.last_name,
    email: props.user.email,
    dynamic_product: null,
    product_quantity: null,
    minimum_quantity: null,
    maximum_quantity: null,
    product_id: null,
    price_identifier_stripe: null,
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

const changeInProductQuantity = function (product) {
    if (typeof fullDynamicPrice.value !== "number") {
        fullDynamicPrice.value = Number(fullDynamicPrice.value);
        productQuantity.value = 0;
    }

    // full dynamic price
    fullDynamicPrice.value = productQuantity.value * product.priceRaw;
    formCharge.product_quantity = productQuantity.value;
};

const addToProductQuantity = function (product) {
    productQuantity.value = Number(productQuantity.value);
    if (isNaN(productQuantity.value)) {
        productQuantity.value = 0;
    }

    if (typeof productQuantity.value !== "number") {
        productQuantity.value = 0;
    }
    if (
        typeof productQuantity.value === "number" &&
        productQuantity.value < 1
    ) {
        productQuantity.value = 0;
    }
    productQuantity.value++;
    formCharge.product_quantity = productQuantity.value;

    // full dynamic price
    fullDynamicPrice.value = formCharge.product_quantity * product.priceRaw;
};

const removeFromProductQuantity = function (product) {
    productQuantity.value = Number(productQuantity.value);
    if (isNaN(productQuantity.value)) {
        productQuantity.value = 0;
    }

    if (typeof productQuantity.value !== "number") {
        productQuantity.value = 1;
    }
    if (
        typeof productQuantity.value === "number" &&
        productQuantity.value < 1
    ) {
        productQuantity.value = 1;
    }
    productQuantity.value--;
    formCharge.product_quantity = productQuantity.value;

    // full dynamic price
    fullDynamicPrice.value = formCharge.product_quantity * product.priceRaw;
};

const createOrUpdate = async () => {
    if (!formCharge.dynamic_product) {
        formCharge.product_quantity = null;
        formCharge.minimum_quantity = null;
        formCharge.maximum_quantity = null;
    }

    if (formType.value === "create") {
        formCharge.country = selectedCountry.value?.code;
        formCharge.phone_code = selectedPhoneCode.value?.phone_code;

        formCharge.vat_id = selectedVatId.value?.code;
        formCharge.tax_id = selectedVatId.value?.tax_id;

        formCharge.post(
            route(props.createPath, [
                props.resource ? props.resource.id : null,
            ]),
            {
                onSuccess: () => {},
                onError: () => {},
                onFinish: () => {},
            }
        );
    }

    if (formType.value === "update") {
        formCharge.country = selectedCountry.value?.code;
        formCharge.phone_code = selectedPhoneCode.value?.phone_code;

        formCharge.vat_id = selectedVatId.value?.code;
        formCharge.tax_id = selectedVatId.value?.tax_id;

        formCharge.post(route(props.updatePath, props.post.id), {
            onSuccess: () => {},
            onError: () => {},
            onFinish: () => {},
        });
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

        const product = props.products.find((product) => {
            return product.priceIdentifierStripe === props.post.stripe_price;
        });

        handleSelectProduct(product);
        productQuantity.value = props.post.quantity;
        fullDynamicPrice.value = props.post.quantity * product.priceRaw;
        formCharge.product_quantity = props.post.quantity;
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
        <template #title>
            {{ title }} {{ resource ? resource.title : "" }}
        </template>
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
                            v-model="formCharge.line1"
                            type="text"
                            id="line1"
                            name="line1"
                            placeholder="Street address.."
                            autocomplete="line1off123"
                        />
                        <InputError :message="formCharge.errors.line1" />
                    </div>

                    <div class="myInputGroup">
                        <InputLabel
                            for="line2"
                            value="Apt, suite, building etc. "
                        />
                        <TextInput
                            v-model="formCharge.line2"
                            type="text"
                            id="line2"
                            name="line2"
                            placeholder="Apt, suite, building.."
                            autocomplete="line2off123"
                        />
                        <InputError :message="formCharge.errors.line2" />
                    </div>
                </div>

                <div class="md:flex items-center justify-center myPrimaryGap">
                    <div class="myInputGroup">
                        <!-- Headless UI select # start -->
                        <InputLabel for="countryoff123" value="Country" />
                        <!-- Headless UI select # start -->
                        <Combobox v-model="selectedCountry">
                            <div class="relative mt-1">
                                <div class="relative">
                                    <ComboboxInput
                                        name="countryoff123"
                                        id="countryoff123"
                                        class="myPrimarySelect"
                                        autocomplete="countryoff123"
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
                                                <span
                                                    class="material-symbols-outlined"
                                                >
                                                    close
                                                </span>
                                            </button>
                                            <ComboboxButton
                                                class="h-8 w-8 cursor-pointer rounded flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                                            >
                                                <ChevronUpDownIcon
                                                    class="shrink-0 w-4 h-4 m-2 stroke-1.5"
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
                                                    <span
                                                        class="myMediumIcon material-symbols-outlined"
                                                    >
                                                        add
                                                    </span>
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
                                                    <span
                                                        class="myMediumIcon material-symbols-outlined"
                                                    >
                                                        check
                                                    </span>
                                                </span>
                                            </li>
                                        </ComboboxOption>
                                    </ComboboxOptions>
                                </TransitionRoot>
                            </div>
                        </Combobox>
                        <InputError :message="formCharge.errors.country" />
                    </div>
                    <div class="myInputGroup">
                        <InputLabel for="city" value="City" />
                        <TextInput
                            id="city"
                            v-model="formCharge.city"
                            placeholder="City.."
                            autocomplete="cityoff123"
                            type="text"
                        />
                        <InputError :message="formCharge.errors.city" />
                    </div>
                </div>

                <div class="md:flex items-center justify-center myPrimaryGap">
                    <div class="myInputGroup">
                        <InputLabel for="state" value="Province or region " />
                        <TextInput
                            v-model="formCharge.state"
                            type="text"
                            id="state"
                            name="state"
                            placeholder="Province or region.."
                            autocomplete="stateoff123"
                        />
                        <InputError :message="formCharge.errors.state" />
                    </div>

                    <!-- postal code and phone # start -->
                    <div class="myInputGroup">
                        <InputLabel for="postal_code" value="Postal code " />
                        <TextInput
                            v-model="formCharge.postal_code"
                            type="text"
                            id="postal_code"
                            name="postal_code"
                            placeholder="Postal code.."
                            autocomplete="postal_code_off123"
                        />
                        <InputError :message="formCharge.errors.postal_code" />
                    </div>
                </div>

                <div class="md:flex items-center justify-center myPrimaryGap">
                    <!-- phone code start -->
                    <div class="myInputGroup">
                        <!-- Headless UI select # start -->
                        <InputLabel
                            for="phone_codeoff123"
                            value="Phone country code"
                        />
                        <!-- Headless UI select # start -->
                        <Combobox v-model="selectedPhoneCode">
                            <div class="relative mt-1">
                                <div class="relative">
                                    <ComboboxInput
                                        name="phone_codeoff123"
                                        id="phone_codeoff123"
                                        autocomplete="phone_codeoff123"
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
                                                <span
                                                    class="material-symbols-outlined"
                                                >
                                                    close
                                                </span>
                                            </button>
                                            <ComboboxButton
                                                class="h-8 w-8 cursor-pointer rounded flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                                            >
                                                <ChevronUpDownIcon
                                                    class="shrink-0 w-4 h-4 m-2 stroke-1.5"
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
                                                    <span
                                                        class="myMediumIcon material-symbols-outlined"
                                                    >
                                                        add
                                                    </span>
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
                                                    <span
                                                        class="myMediumIcon material-symbols-outlined"
                                                    >
                                                        check
                                                    </span>
                                                </span>
                                            </li>
                                        </ComboboxOption>
                                    </ComboboxOptions>
                                </TransitionRoot>
                            </div>
                        </Combobox>
                        <InputError :message="formCharge.errors.phone_code" />
                    </div>
                    <!-- phone code end -->
                    <div class="myInputGroup">
                        <InputLabel for="phone" value="Phone" />
                        <TextInput
                            v-model="formCharge.phone"
                            type="text"
                            id="phone"
                            name="phone"
                            placeholder="Phone.."
                            autocomplete="phoneoff123"
                        />
                        <InputError :message="formCharge.errors.phone" />
                    </div>
                </div>
            </div>
            <!-- postal code and phone # end -->

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
                        <InputLabel for="vat_idoff123" value="Vat id " />
                        <!-- Headless UI select # start -->
                        <Combobox v-model="selectedVatId">
                            <div class="relative mt-1">
                                <div class="relative">
                                    <ComboboxInput
                                        name="vat_idoff123"
                                        id="vat_idoff123"
                                        class="myPrimarySelect"
                                        autocomplete="vat_idoff123"
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
                                                <span
                                                    class="material-symbols-outlined"
                                                >
                                                    close
                                                </span>
                                            </button>
                                            <ComboboxButton
                                                class="h-8 w-8 cursor-pointer rounded flex items-center justify-center bg-gray-50 aspect-square hover:bg-myPrimaryLinkColor hover:text-white focus-visible:ring-0"
                                            >
                                                <ChevronUpDownIcon
                                                    class="shrink-0 w-4 h-4 m-2 stroke-1.5"
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
                                                    <span
                                                        class="myMediumIcon material-symbols-outlined"
                                                    >
                                                        add
                                                    </span>
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
                                                    <span
                                                        class="myMediumIcon material-symbols-outlined"
                                                    >
                                                        check
                                                    </span>
                                                </span>
                                            </li>
                                        </ComboboxOption>
                                    </ComboboxOptions>
                                </TransitionRoot>
                            </div>
                        </Combobox>
                        <InputError :message="formCharge.errors.vat_id" />
                    </div>

                    <div class="myInputGroup">
                        <InputLabel for="vat_number" value="Vat number " />
                        <TextInput
                            v-model="formCharge.vat_number"
                            type="text"
                            id="vat_number"
                            name="vat_number"
                            placeholder="Vat number.."
                            autocomplete="vat_numberoff123"
                        />
                        <InputError :message="formCharge.errors.vat_number" />
                    </div>
                </div>
                <!-- Vat ID and vat number # end -->
            </div>
        </template>

        <template #sidebar>
            <div class="myInputsOrganization">
                <div class="myPrimaryFormOrganizationHeader">
                    Select product
                    <p class="pb-4 pt-2">Select one of below products</p>
                </div>

                <div class="flex flex-col gap-6">
                    <div v-for="product in products" :key="product.id">
                        <div class="px-2 py-8 rounded border border-gray-200">
                            <div>
                                <div class="flex items-center">
                                    <div class="flex flex-col text-sm">
                                        <div
                                            as="span"
                                            class="font-medium text-gray-900"
                                        >
                                            <p
                                                :id="product.id"
                                                class="myQuaternaryHeader"
                                            >
                                                {{ product.name }}
                                            </p>

                                            <div
                                                class="mt-2 flex items-baseline gap-x-1"
                                            >
                                                <span
                                                    class="text-2xl font-bold tracking-tight text-gray-900"
                                                    >{{ product.price }}</span
                                                >
                                                <span
                                                    class="text-sm font-semibold leading-6 text-gray-600"
                                                    >{{
                                                        product.frequencies
                                                    }}</span
                                                >
                                            </div>
                                            <template
                                                v-if="!product.dynamic_product"
                                            >
                                                <p>
                                                    Total: ${{
                                                        product.totalPrice
                                                    }}
                                                </p>
                                            </template>
                                            <template
                                                v-if="product.dynamic_product"
                                            >
                                                <p>
                                                    Total: ${{
                                                        fullDynamicPrice
                                                    }}
                                                </p>
                                            </template>
                                            <div
                                                class="block text-[10px] leading-6 text-gray-600 italic mt-1"
                                            >
                                                {{ product.billed }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="flex justify-between items-center gap-4 text-xs font-medium myPrimaryTag my-0"
                                >
                                    <div>
                                        <!-- product quantity # start  -->
                                        <div
                                            v-if="
                                                product.dynamic_product &&
                                                selectedProduct?.id ===
                                                    product.id
                                            "
                                        >
                                            <!-- Input Number -->
                                            <div
                                                class="myPrimaryInput p-0 mt-0"
                                            >
                                                <div
                                                    class="w-full flex gap-2 justify-between items-center"
                                                >
                                                    <input
                                                        placeholder="Store quantity.."
                                                        id="product_quantity"
                                                        @input="
                                                            changeInProductQuantity(
                                                                product
                                                            )
                                                        "
                                                        v-model="
                                                            productQuantity
                                                        "
                                                        class="myPrimaryInputNoBorder mt-0"
                                                        autocomplete="off"
                                                    />
                                                    <div
                                                        class="flex items-center"
                                                    >
                                                        <button
                                                            @click="
                                                                removeFromProductQuantity(
                                                                    product
                                                                )
                                                            "
                                                            type="button"
                                                            class="h-10 w-10 cursor-pointer rounded flex items-center justify-center hover:bg-gray-50 aspect-square focus-visible:ring-0"
                                                        >
                                                            <span
                                                                class="myMediumIcon material-symbols-outlined"
                                                            >
                                                                remove
                                                            </span>
                                                        </button>
                                                        <button
                                                            @click="
                                                                addToProductQuantity(
                                                                    product
                                                                )
                                                            "
                                                            type="button"
                                                            class="h-10 w-10 cursor-pointer rounded flex items-center justify-center hover:bg-gray-50 aspect-square focus-visible:ring-0"
                                                        >
                                                            <span
                                                                class="myMediumIcon material-symbols-outlined"
                                                            >
                                                                add
                                                            </span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Input Number -->
                                        </div>
                                        <!-- product quantity # end  -->
                                    </div>

                                    <!-- select product # start -->
                                    <div
                                        class="flex gap-2 justify-end items-end self-start justify-self-end"
                                    >
                                        <div>
                                            <button
                                                class="myPrimaryTag transition mt-0 mb-0 break-keep w-max bg-white"
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
                                                class="myPrimaryTag transition bg-myPrimaryLinkColor text-white mt-0 mb-0 break-keep w-max"
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
                                                    <span
                                                        class="myMediumIcon material-symbols-outlined"
                                                    >
                                                        check
                                                    </span>
                                                </div>
                                            </button>
                                        </div>
                                    </div>
                                    <!-- select product # end -->
                                </div>
                            </div>
                        </div>
                        <div class="min-h-[1.5rem]">
                            <template v-if="selectedProduct?.id === product.id">
                                <InputError
                                    :message="formCharge.errors.product_id"
                                />
                            </template>
                        </div>
                    </div>
                </div>
                <template v-if="!selectedProduct?.id">
                    <InputError :message="formCharge.errors.product_id" />
                </template>
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
                <InputError :message="formCharge.errors.payment_method_id" />
            </div>
        </template>
        <template #actions>
            <SubmitButton :disabled="formCharge.processing" buttonText="Save">
            </SubmitButton>
            <div
                class="flex justify-end mt-4"
                v-if="Object.values(formCharge.errors).length !== 0"
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
                        {{ Object.values(formCharge.errors).length }}
                        {{
                            Object.values(formCharge.errors).length === 1
                                ? "error"
                                : "errors"
                        }}
                    </p>
                </div>
            </div>

            <ActionMessage :on="formCharge.recentlySuccessful" type="success">
                Successfully submitted.
            </ActionMessage>
            <NotificationsFixedBottom
                :listOfMessages="Object.values(formCharge.errors)"
                :show="showErrorNotifications"
                @notificationsModalButton="notificationsModalButton"
            >
                <div class="flex items-center justify-start gap-2">
                    <p class="myPrimaryParagraphError">
                        {{ Object.values(formCharge.errors).length }}
                        {{
                            Object.values(formCharge.errors).length === 1
                                ? "error"
                                : "errors"
                        }}
                    </p>
                </div>
            </NotificationsFixedBottom>
        </template>
    </FormSection>
</template>
