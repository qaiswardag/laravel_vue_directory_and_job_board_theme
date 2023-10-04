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

const store = useStore();

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
    intent: {
        required: true,
    },
});

const emit = defineEmits([
    "firstModalPaymentMethodFunctionForm",
    "secondModalPaymentMethodFunctionForm",
]);

const cardHolderName = ref("");
const responseStripe = ref(null);

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
    emit("secondModalPaymentMethodFunctionForm");
};

const createOrUpdatePayment = async function () {
    try {
        responseStripe.value = await stripe.confirmCardSetup(
            props.intent.client_secret,
            {
                payment_method: {
                    card: cardElement.value,
                    billing_details: { name: cardHolderName.value },
                },
            }
        );

        console.log(`data er:`, responseStripe.value);

        // firstButton();
    } catch (err) {
        console.log(`Unable to create:`, err);
        return false;
    }
};
onMounted(async () => {
    await nextTick();
    console.log(`kom her`);
    elements.value = stripe.elements({
        clientSecret: props.intent.client_secret,
    });
    cardElement.value = elements.value.create("card");
    cardElement.value.mount("#card-element");
});
</script>

<template>
    <Modal
        maxWidth="2xl"
        :show="show"
        @close="firstButton"
        minHeight="min-h-[30rem]"
        maxHeight="max-h-[30rem]"
    >
        <Spinner v-if="false"></Spinner>
        <div
            class="px-4 w-full relative inline-block align-bottom text-left overflow-hidden transform transition-all sm:align-middle"
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
                <div class="myInputGroup">
                    <p class="my-4">4000002080000001</p>
                    <p class="my-4">12/34</p>
                    <p class="my-4">567</p>
                    <InputLabel
                        for="card_holder_name"
                        value="Cardholder name"
                    />
                    <TextInput
                        placeholder="Cardholder name.."
                        id="title"
                        v-model="cardHolderName"
                        type="text"
                        class="block w-full mt-1"
                        autocomplete="off"
                    />
                </div>

                <div class="my-12" id="card-element"></div>
                <p
                    v-if="responseStripe?.setupIntent?.status"
                    class="text-myPrimarySuccesColor mt-2 mb-0 py-0 self-start"
                >
                    {{ responseStripe?.setupIntent?.status }}
                </p>
                <p
                    class="myPrimaryInputError mt-2 mb-0 py-0 self-start"
                    v-if="responseStripe?.error"
                >
                    {{ responseStripe.error.message }}
                </p>
            </main>
            <!-- content end -->
        </div>
        <div
            class="bg-gray-100 px-6 py-4 absolute bottom-0 left-0 right-0 flex sm:justify-end justify-center"
        >
            <div class="sm:w-3/6 w-full px-2 my-2 flex gap-2 justify-end">
                <button
                    v-if="firstButtonTextModalPaymentMethodForm"
                    ref="firstButtonRef"
                    class="mySecondaryButton"
                    type="button"
                >
                    {{ firstButtonTextModalPaymentMethodForm }}
                </button>
                <button
                    class="myPrimaryButton bg-myPrimaryLinkColor focus-visible:ring-myPrimaryLinkColor focus:ring-myPrimaryLinkColor hover:bg-myPrimaryLinkColor"
                    type="button"
                    @click="secondButton"
                >
                    Save
                </button>
            </div>
        </div>
    </Modal>
</template>
