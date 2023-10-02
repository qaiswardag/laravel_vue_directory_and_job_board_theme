<script setup>
import { computed, nextTick, onBeforeMount, onMounted, ref, watch } from "vue";
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
import SelectPaymentMethod from "./SelectPaymentMethod.vue";
import NotificationsFixedBottom from "@/Components/Modals/NotificationsFixedBottom.vue";
import { useStore } from "vuex";
import {
    Listbox,
    ListboxButton,
    ListboxLabel,
    ListboxOption,
    ListboxOptions,
} from "@headlessui/vue";
import { CheckIcon, ChevronUpDownIcon } from "@heroicons/vue/20/solid";

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
    intent: {
        required: true,
    },
});

const formType = ref("create");

const handleSubmit = () => {
    createOrUpdate();
};

const getPaymentMethod = computed(() => {
    return store.getters["user/getPaymentMethod"];
});

const products = [
    { id: null, name: "Select" },
    { id: "single_store", name: "Single Store" },
    { id: "three_stores", name: "Three Stores" },
    { id: "ten_stores", name: "Ten Stores" },
    { id: "two_hundred_stores", name: "Two hundred Stores" },
];

const selectedProduct = ref(products[0]);

watch(selectedProduct, (newSelectedProduct) => {
    formSubscription.product_id = newSelectedProduct.id;
});

const formSubscription = useForm({
    team: props.team,
    user_id: props.user.id,
    //
    //
    first_name: props.user.first_name,
    last_name: props.user.last_name,
    email: props.user.email,
    product_id: null,
    payment_method: null,
});

const createOrUpdate = () => {
    formSubscription.payment_method = getPaymentMethod.value?.id;

    if (formType.value === "create") {
        console.log(`came here..`);
        formSubscription.post(route("team.stores.store.subscription"), {
            preserveScroll: true,
            onSuccess: () => {},
            onError: () => {},
            onFinish: () => {},
        });
    }
};

const showModalCardForm = ref(false);
const firstButtonTextCardForm = ref(null);
const titleCardForm = ref(null);
const firstCardFormFunction = ref(null);

const handleCardForm = function () {
    titleCardForm.value = "This is title for card form";
    firstButtonTextCardForm.value = "Close";
    showModalCardForm.value = true;

    firstCardFormFunction.value = function () {
        // handle show modal for unique content
        showModalCardForm.value = false;
    };

    // end modal
};

const showErrorNotifications = ref(false);

const notificationsModalButton = function () {
    showErrorNotifications.value = false;
};

onBeforeMount(() => {
    if (props.post) {
        formType.value = "update";
    }
});

onMounted(() => {});
</script>

<template>
    <div class="flex flex-col divide-y-2">
        <div class="py-12 my-12 px-4 flex justify-end">
            <button
                @click="handleCardForm"
                type="button"
                class="myPrimaryButton my-4"
            >
                Add Card
            </button>
        </div>
    </div>

    <FormSection @submitted="handleSubmit">
        <template #title> Payment Form </template>

        <template #description> Payment formSubscription </template>

        <template #main>
            <div class="myInputGroup">
                <InputLabel for="first_name" value="First name" />
                <TextInput
                    id="first_name"
                    v-model="formSubscription.first_name"
                    type="text"
                    autocomplete="first_name"
                />
                <InputError :message="formSubscription.errors.first_name" />
            </div>

            <div class="myInputGroup">
                <InputLabel for="last_name" value="Last name" />
                <TextInput
                    id="last_name"
                    v-model="formSubscription.last_name"
                    type="text"
                    autocomplete="last_name"
                />
                <InputError :message="formSubscription.errors.last_name" />
            </div>

            <!-- Email -->
            <div class="myInputGroup">
                <InputLabel for="email" value="Email" />
                <TextInput
                    id="email"
                    v-model="formSubscription.email"
                    type="email"
                />
                <InputError :message="formSubscription.errors.email" />

                <div
                    v-if="
                        $page.props.jetstream.hasEmailVerification &&
                        user.email_verified_at === null
                    "
                >
                    <p class="text-sm mt-2">
                        Your email address is unverified.

                        <Link
                            :href="route('verification.send')"
                            method="post"
                            as="button"
                            class="underline text-myPrimaryDarkGrayColor hover:text-myPrimaryDarkGrayColor"
                            @click.prevent="sendEmailVerification"
                        >
                            Click here to re-send the verification email.
                        </Link>
                    </p>

                    <div
                        v-show="verificationLinkSent"
                        class="mt-2 font-normal text-sm text-myPrimaryLinkColor"
                    >
                        A new verification link has been sent to your email
                        address.
                    </div>
                </div>
            </div>
        </template>

        <template #sidebar>
            <div class="myInputsOrganization">
                <div class="myPrimaryFormOrganizationHeaderDescriptionSection">
                    <div class="myPrimaryFormOrganizationHeader">
                        Subscription
                    </div>
                    <p class="myPrimaryParagraph">
                        Lorem ipsum dolor sit amet, consectetur adipisicing
                        elit. Cupiditate, ratione.
                    </p>
                    <Listbox as="div" v-model="selectedProduct">
                        <div class="relative mt-2">
                            <ListboxButton class="myPrimarySelect">
                                <span class="block truncate">{{
                                    selectedProduct.name
                                }}</span>
                                <span
                                    class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2"
                                >
                                    <ChevronUpDownIcon
                                        class="h-5 w-5 text-gray-400"
                                        aria-hidden="true"
                                    />
                                </span>
                            </ListboxButton>

                            <transition
                                leave-active-class="transition ease-in duration-100"
                                leave-from-class="opacity-100"
                                leave-to-class="opacity-0"
                            >
                                <ListboxOptions
                                    class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                                >
                                    <ListboxOption
                                        as="template"
                                        v-for="person in products"
                                        :key="person.id"
                                        :value="person"
                                        v-slot="{ active, selectedProduct }"
                                    >
                                        <li
                                            :class="[
                                                active
                                                    ? 'bg-myPrimaryBrandColor text-white'
                                                    : 'text-gray-900',
                                                'relative cursor-default select-none py-2 pl-3 pr-9',
                                            ]"
                                        >
                                            <span
                                                :class="[
                                                    selectedProduct
                                                        ? 'font-semibold'
                                                        : 'font-normal',
                                                    'block truncate',
                                                ]"
                                                >{{ person.name }}</span
                                            >

                                            <span
                                                v-if="selectedProduct"
                                                :class="[
                                                    active
                                                        ? 'text-white'
                                                        : 'text-myPrimaryBrandColor',
                                                    'absolute inset-y-0 right-0 flex items-center pr-4',
                                                ]"
                                            >
                                                <CheckIcon
                                                    class="h-5 w-5"
                                                    aria-hidden="true"
                                                />
                                            </span>
                                        </li>
                                    </ListboxOption>
                                </ListboxOptions>
                            </transition>
                        </div>
                    </Listbox>
                    <InputError :message="formSubscription.errors.product_id" />
                </div>
            </div>
            <div class="myInputsOrganization">
                <div class="myPrimaryFormOrganizationHeader">
                    Select Payment method
                </div>
                <p class="myPrimaryParagraph">Specify post status.</p>
                <SelectPaymentMethod
                    :team="team"
                    :user="user"
                ></SelectPaymentMethod>
                <InputError :message="formSubscription.errors.payment_method" />
            </div>
        </template>
        <template #actions>
            <SubmitButton
                :disabled="formSubscription.processing"
                buttonText="Save"
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
                        errors
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
                        errors
                    </p>
                </div>
            </NotificationsFixedBottom>
        </template>
    </FormSection>
    <CardForm
        v-if="firstCardFormFunction"
        :show="showModalCardForm"
        @firstCardFormFunction="firstCardFormFunction"
        :firstButtonTextCardForm="firstButtonTextCardForm"
        :title="titleCardForm"
        :intent="intent"
    ></CardForm>
</template>
