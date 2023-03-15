<template>
    <header class="bg-white border-b border-gray-200">
        <nav
            class="mx-auto flex max-w-7xl items-center justify-between p-6 lg:px-8"
            aria-label="Global"
        >
            <div class="flex lg:flex-1">
                <a href="#" class="-m-1.5 p-1.5">
                    <span class="sr-only">Your Company</span>
                    <img
                        class="h-8 w-auto"
                        src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600"
                        alt=""
                    />
                </a>
            </div>
            <div class="flex lg:hidden">
                <button
                    type="button"
                    class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700"
                    @click="mobileMenuOpen = true"
                >
                    <span class="sr-only">Open main menu</span>
                    <Bars3Icon class="h-6 w-6" aria-hidden="true" />
                </button>
            </div>
            <div class="hidden lg:flex lg:gap-x-12">
                <template v-if="$page.props.user !== null">
                    <Link
                        :href="route('dashboard')"
                        :class="{
                            'text-myPrimaryBrandColor':
                                route().current('dashboard'),
                            'text-sm font-semibold leading-6 text-myPrimaryDarkGrayColor': true,
                        }"
                        class="myPrimaryParagraph"
                    >
                        Dashboard
                    </Link>
                </template>
                <Link
                    :href="route('users')"
                    :class="{
                        'text-myPrimaryBrandColor': route().current('users'),
                        'text-sm font-semibold leading-6 text-myPrimaryDarkGrayColor': true,
                    }"
                    class="myPrimaryParagraph"
                >
                    Users
                </Link>
                <Link
                    :href="route('posts')"
                    :class="{
                        'text-myPrimaryBrandColor': route().current('posts'),
                        'text-sm font-semibold leading-6 text-myPrimaryDarkGrayColor': true,
                    }"
                    class="myPrimaryParagraph"
                >
                    Posts
                </Link>
            </div>

            <template v-if="$page.props.user === null">
                <div
                    class="hidden lg:flex lg:flex-1 lg:justify-end myPrimaryGap"
                >
                    <Link
                        :href="route('login')"
                        :class="{
                            'text-myPrimaryBrandColor':
                                route().current('login'),
                            'text-sm font-semibold leading-6 text-myPrimaryDarkGrayColor': true,
                        }"
                        class="myPrimaryParagraph"
                    >
                        Login
                    </Link>
                    <Link
                        :href="route('register')"
                        :class="{
                            'text-myPrimaryBrandColor':
                                route().current('register'),
                            'text-sm font-semibold leading-6 text-myPrimaryDarkGrayColor': true,
                        }"
                        class="myPrimaryParagraph"
                    >
                        Register
                    </Link>
                </div>
            </template>
        </nav>
        <Dialog
            as="div"
            class="lg:hidden"
            @close="mobileMenuOpen = false"
            :open="mobileMenuOpen"
        >
            <div class="fixed inset-0 z-10" />
            <DialogPanel
                class="fixed inset-y-0 right-0 z-10 w-full overflow-y-auto bg-white px-6 py-6 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10"
            >
                <div class="flex items-center justify-between">
                    <a href="#" class="-m-1.5 p-1.5">
                        <span class="sr-only">Your Company</span>
                        <img
                            class="h-8 w-auto"
                            src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600"
                            alt=""
                        />
                    </a>
                    <button
                        type="button"
                        class="-m-2.5 rounded-md p-2.5 text-gray-700"
                        @click="mobileMenuOpen = false"
                    >
                        <span class="sr-only">Close menu</span>
                        <XMarkIcon class="h-6 w-6" aria-hidden="true" />
                    </button>
                </div>
                <div class="mt-6 flow-root">
                    <div class="-my-6 divide-y divide-gray-500/10">
                        <div class="space-y-2 py-6">
                            <Link
                                :href="route('dashboard')"
                                :class="{
                                    'myPrimaryLink myPrimaryParagraph':
                                        route().current('dashboard'),
                                }"
                                class="myPrimaryParagraph"
                            >
                                Dashboard
                            </Link>
                        </div>
                        <div class="py-6">
                            <a
                                href="#"
                                class="-mx-3 block rounded-lg py-2.5 px-3 text-base font-semibold leading-7 text-gray-900 hover:bg-gray-50"
                                >Log in</a
                            >
                        </div>
                    </div>
                </div>
            </DialogPanel>
        </Dialog>
    </header>
</template>

<script setup>
import { ref } from "vue";
import { Dialog, DialogPanel } from "@headlessui/vue";
import { Bars3Icon, XMarkIcon } from "@heroicons/vue/24/outline";

const mobileMenuOpen = ref(false);
</script>
