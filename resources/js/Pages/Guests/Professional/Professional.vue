<script setup>
import GuestsLayout from "@/Layouts/GuestsLayout.vue";
import FullWidthElement from "@/Components/Layouts/FullWidthElement.vue";

import { ref } from "vue";
import { RadioGroup, RadioGroupLabel, RadioGroupOption } from "@headlessui/vue";
import { CheckIcon } from "@heroicons/vue/20/solid";
import { usePage } from "@inertiajs/vue3";
import storeProducts from "@/utils/pricing/store-subscriptions";
</script>

<template>
    <GuestsLayout>
        <Head title="Professional" />
        <template #header>
            <div class="myPrimaryMainPageHeaderParagraph">
                <h1 class="myPrimaryMainPageHeaderNotLoggedIn">Professional</h1>
                <p class="myPrimaryMainPageParagraphNotLoggedIn">
                    myself.ae brings fashion to where the consumers are
                </p>
                <p class="myPrimaryparagraph text-center">
                    Join Fashion's most comprehensive marketing platform in the
                    U.A.E. Showcase stores to local consumers.
                </p>
            </div>
        </template>

        <FullWidthElement :descriptionArea="true" class="bg-red-50">
            <template #title>Pricing plans for all sizes</template>
            <template #description>
                What if there was a digital solution that would bring online
                shoppers back to real stores?
                <br />
                Choose an affordable plan that’s packed with the best features
                for engaging your audience, creating customer loyalty, and
                driving sales. Select the ideal subscription based on number of
                stores.
            </template>
            <template #content>
                <div
                    class="isolate mx-auto mt-10 grid max-w-md grid-cols-1 gap-8 md:max-w-2xl md:grid-cols-2 lg:max-w-4xl xl:mx-0 xl:max-w-none xl:grid-cols-4"
                >
                    <div
                        v-for="product in storeProducts($page.props.user)"
                        :key="product.id"
                        :class="[
                            product.mostPopular
                                ? 'border-2 border-myPrimaryLinkColor'
                                : 'border-2 border-gray-200',
                            'rounded-3xl p-8 hover:border-2 hover:border-myPrimaryLinkColor relative',
                        ]"
                    >
                        <p
                            v-if="product.title"
                            class="myPrimaryParagraph text-xs italic absolute top-1 left-0 right-0 text-center"
                        >
                            {{ product.title }}
                        </p>
                        <h3
                            :id="product.id"
                            :class="[
                                product.mostPopular
                                    ? 'text-myPrimaryLinkColor'
                                    : 'text-gray-900',
                                'text-lg font-semibold leading-8',
                            ]"
                        >
                            {{ product.name }}
                        </h3>
                        <p class="mt-4 text-sm leading-6 text-gray-600">
                            {{ product.description }}
                        </p>
                        <p class="mt-6 flex items-baseline gap-x-1">
                            <span
                                class="text-4xl font-bold tracking-tight text-gray-900"
                                >{{ product.price }}</span
                            >
                            <span
                                class="text-sm font-semibold leading-6 text-gray-600"
                                >{{ product.frequencies }}</span
                            >
                        </p>

                        <Link
                            v-if="$page.props?.user?.current_team?.id"
                            :class="[
                                product.mostPopular
                                    ? 'bg-myPrimaryLinkColor text-white shadow-sm hover:bg-myPrimaryLinkColor'
                                    : 'text-myPrimaryLinkColor ring-1 ring-inset ring-myPrimaryLinkColor hover:ring-myPrimaryLinkColor',
                                'mt-6 block rounded-md py-2 px-3 text-center text-sm font-semibold leading-6 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-myPrimaryLinkColor',
                            ]"
                            :href="
                                route(
                                    product.route.name,
                                    product.route.parameters
                                )
                            "
                            >{{ product.name }}
                        </Link>
                        <Link
                            v-if="!$page.props?.user?.current_team?.id"
                            :class="[
                                product.mostPopular
                                    ? 'bg-myPrimaryLinkColor text-white shadow-sm hover:bg-myPrimaryLinkColor'
                                    : 'text-myPrimaryLinkColor ring-1 ring-inset ring-myPrimaryLinkColor hover:ring-myPrimaryLinkColor',
                                'mt-6 block rounded-md py-2 px-3 text-center text-sm font-semibold leading-6 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-myPrimaryLinkColor',
                            ]"
                            href="register"
                            >Sign up
                        </Link>
                        <ul
                            role="list"
                            class="mt-8 space-y-3 text-sm leading-6 text-gray-600"
                        >
                            <li
                                v-for="feature in product.features"
                                :key="feature"
                                class="flex gap-x-3"
                            >
                                <CheckIcon
                                    class="h-6 w-5 flex-none text-myPrimaryLinkColor"
                                    aria-hidden="true"
                                />
                                {{ feature }}
                            </li>
                        </ul>
                    </div>
                </div>
            </template>
        </FullWidthElement>
    </GuestsLayout>
</template>
