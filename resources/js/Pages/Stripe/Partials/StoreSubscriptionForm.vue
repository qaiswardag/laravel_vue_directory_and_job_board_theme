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
import FullScreenSpinner from "@/Components/Loaders/FullScreenSpinner.vue";

const store = useStore();

const props = defineProps({
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

const selectedProduct = ref(null);

const handleSelectProduct = function (product) {
    if (selectedProduct.value?.id === product.id) {
        selectedProduct.value = null;
        formSubscription.product_id = null;
    } else {
        selectedProduct.value = product;
        formSubscription.product_id = product.id;
    }
};

const formSubscription = useForm({
    user_id: props.user.id,
    first_name: props.user.first_name,
    last_name: props.user.last_name,
    email: props.user.email,
    product_id: null,
    payment_method: null,
});

const createOrUpdate = () => {
    formSubscription.payment_method = getPaymentMethod.value?.id;

    console.log(`den er:`, formSubscription.payment_method);
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

const showErrorNotifications = ref(false);

const notificationsModalButton = function () {
    showErrorNotifications.value = false;
};

onBeforeMount(() => {
    if (props.post) {
        formType.value = "update";
    }
});
</script>

<template>
    <FormSection @submitted="handleSubmit">
        <template #title> Subscription Form </template>
        <template #main>
            <div class="myInputsOrganization">
                <p class="my-12">intent: {{ intent }}</p>
                <div class="myPrimaryFormOrganizationHeaderDescriptionSection">
                    <div class="myPrimaryFormOrganizationHeader">
                        Billing Information
                    </div>
                </div>
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
            </div>
        </template>

        <template #sidebar>
            <div class="myInputsOrganization">
                <div class="myPrimaryFormOrganizationHeader">
                    Select subscription type
                </div>
                <div class="myPrimaryFormOrganizationHeaderDescriptionSection">
                    <div>
                        <div class="space-y-4">
                            <div
                                as="template"
                                v-for="product in storeSubscriptionPrices(
                                    $page.props.user
                                )"
                                :key="product.id"
                                :value="product"
                            >
                                <div
                                    class="border border-transparent hover:border-myPrimaryLinkColor border-myPrimaryLinkColor shadow-sm sm:flex sm:justify-between rounded-lg myPrimaryTag bg-myPrimaryLinkColor bg-opacity-5"
                                >
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
                                            <span> Selected</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <InputError
                            :message="formSubscription.errors.product_id"
                        />
                    </div>
                </div>
            </div>

            <div class="myInputsOrganization">
                <div class="myPrimaryFormOrganizationHeader">
                    Payment methods
                </div>
                <SelectPaymentMethod
                    :user="user"
                    :intent="intent"
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
</template>
