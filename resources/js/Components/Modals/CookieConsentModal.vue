<script setup>
import { ref } from "vue";

const props = defineProps({
    show: {
        type: Boolean,
        required: true,
    },
});

const emit = defineEmits(["handleCookieModal"]);

// Function to set a cookie
function setCookie(cookieName, cookieValue, expirationDays) {
    const cookieDate = new Date();

    cookieDate.setTime(
        cookieDate.getTime() + expirationDays * 24 * 60 * 60 * 1000
    );
    const expires = "expires=" + cookieDate.toUTCString();

    document.cookie = cookieName + "=" + cookieValue + ";" + expires + "/";
}

const handleCookieModal = function () {
    emit("handleCookieModal");

    setCookie("cookieConsent", "userHasConsented", 365);
};
</script>

<template>
    <div
        aria-live="assertive"
        class="pointer-events-none fixed right-0 bottom-0 left-0 flex items-end px-4 py-6 sm:items-start sm:p-6 z-40"
    >
        <div class="flex w-full flex-col items-center space-y-4 sm:items-end">
            <!-- Notification panel, dynamically insert this into the live region when it needs to be displayed -->
            <transition
                enter-active-class="transform ease-out duration-300 transition"
                enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
                leave-active-class="transition ease-in duration-100"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div
                    v-if="show"
                    class="pointer-events-auto w-full max-w-sm rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5"
                >
                    <div class="p-4 border-2 border-myPrimaryDarkGrayColor">
                        <div class="flex items-start">
                            <div class="ml-3 w-0 flex-1">
                                <p
                                    class="myPrimaryParagraph mt-2 mb-2 font-normal"
                                >
                                    <slot></slot>
                                </p>

                                <p class="myPrimaryParagraph text-sm">
                                    This website uses cookies and local storage
                                    for performance and personalization. Only
                                    essential cookies are turned on by default.
                                    Read more at the
                                    <a
                                        :href="route('terms.show')"
                                        target="_blank"
                                    >
                                        Terms of Service
                                    </a>
                                    and
                                    <a
                                        :href="route('policy.show')"
                                        target="_blank"
                                    >
                                        Privacy Policy.
                                    </a>
                                </p>
                                <div
                                    class="mt-4 pt-4 flex items-center justify-end border-t border-gray-200"
                                >
                                    <button
                                        @click="handleCookieModal"
                                        type="button"
                                        class="mySecondaryButton"
                                    >
                                        Allow all cookies
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
    </div>
</template>
