<script setup>
import Modal from "@/Components/Modals/Modal.vue";
import { ref, computed, onMounted, onBeforeMount, nextTick } from "vue";
import {
    TrashIcon,
    CheckIcon,
    NewspaperIcon,
    XMarkIcon,
} from "@heroicons/vue/24/outline";
import Spinner from "@/Components/PageBuilder/Loaders/Spinner.vue";
import InputLabel from "@/Components/Forms/InputLabel.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import { useStore } from "vuex";
import { Link, router, useForm } from "@inertiajs/vue3";
import InputError from "@/Components/Forms/InputError.vue";

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

    // props.intent.client_secret
    // Exactly, the client_secret is intended for the app or service handling the payment processing
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
    country: "",
    city: "",
    postal_code: "",
});

const emit = defineEmits([
    "firstModalPaymentMethodFunctionForm",
    "secondModalPaymentMethodFunctionForm",
]);

const responseStripeCreateSubscription = ref(null);

const publishableKey =
    "pk_test_51DBlO9EuESfVmAWo1jIDgROLHxxxCIP7gQhETpMgPvi6qftjbE11vtIMa1EDBbBQww23KmnzueGYsRBfV6BsX3pD00rMtwtsBJ";

const stripe = Stripe(publishableKey);

const elements = ref(null);
const cardElement = ref(null);
const appearance = ref(null);

//
// first button function
const firstButton = function () {
    emit("firstModalPaymentMethodFunctionForm");
};
const secondButton = function () {
    createOrUpdatePayment();
};

const createOrUpdatePayment = async function () {
    console.log(`skal kooooom he`);
    form.post(route("stripe.payment.methods.store"), {
        preserveScroll: true,
        onSuccess: () => {
            console.log(`success`);
        },
        onError: () => {},
        onFinish: () => {},
    });

    //
    //
    //
    //
    //
    //
    //
    //
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
                <div class="myInputsOrganization">
                    <div class="myInputGroup">
                        <p class="my-4">4000002080000001</p>
                        <p class="my-4">12/34</p>
                        <p class="my-4">567</p>
                        <!-- Email -->
                        <div class="myInputGroup">
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
                        <InputLabel for="email" value="Email" />
                        <TextInput
                            id="email"
                            v-model="form.email"
                            type="email"
                            class="block w-full mt-1"
                        />
                        <InputError :message="form.errors.email" />
                    </div>

                    <div class="myInputGroup myPrimaryInput py-4 px-4">
                        <div class="pt-6 pb-4" id="card-element"></div>
                        <div
                            class="myPrimaryInputError mt-2 mb-0 py-0 self-start"
                            v-if="responseStripeCreateSubscription?.error"
                        >
                            {{ responseStripeCreateSubscription.error.message }}
                        </div>
                    </div>

                    <div class="myInputGroup">
                        <InputLabel for="country" value="Country" />
                        <InputError :message="form.errors.country" />
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
                <button
                    class="myPrimaryButton bg-myPrimaryLinkColor focus-visible:ring-myPrimaryLinkColor focus:ring-myPrimaryLinkColor hover:bg-myPrimaryLinkColor"
                    type="button"
                    @click="secondButton"
                >
                    Submit
                </button>
            </div>
        </div>
    </Modal>
</template>
