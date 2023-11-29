<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import GuestsLayout from "@/Layouts/GuestsLayout.vue";
import FullWidthElement from "@/Components/Layouts/FullWidthElement.vue";

import { ref } from "vue";
import { RadioGroup, RadioGroupLabel, RadioGroupOption } from "@headlessui/vue";
import { CheckIcon } from "@heroicons/vue/20/solid";
import { usePage } from "@inertiajs/vue3";
import storeSubscriptionPrices from "@/utils/pricing/store-subscription-prices";
import PageHeader from "@/Components/Headers/PageHeader.vue";
</script>

<template>
    <MainLayout>
        <GuestsLayout>
            <Head title="Professional" />
            <template #header>
                <PageHeader title="Professional" class="bg-red-50"></PageHeader>
            </template>

            <FullWidthElement :descriptionArea="true" class="bg-red-50">
                <template #title>Pricing plans for all sizes</template>
                <template #description>
                    What if there was a digital solution that would bring online
                    shoppers back to real stores?
                    <br />
                    Choose an affordable plan that’s packed with the best
                    features for engaging your audience, creating customer
                    loyalty, and driving sales. Select the ideal subscription
                    based on number of stores.
                </template>
                <template #content>
                    <div
                        class="isolate mx-auto mt-10 grid grid-cols-1 gap-8 md:grid-cols-3 xl:grid-cols-3"
                    >
                        <div
                            v-for="product in storeSubscriptionPrices"
                            :key="product.id"
                            :class="[
                                product.mostPopular
                                    ? 'border-2 border-myPrimaryLinkColor'
                                    : 'border-2 border-gray-200',
                                'rounded-3xl p-8 hover:border-2 hover:border-myPrimaryLinkColor relative',
                            ]"
                        >
                            <div
                                v-html="product.title"
                                class="text-xs font-bold tracking-tight"
                            ></div>
                            <h3
                                :id="product.id"
                                :class="[
                                    product.mostPopular
                                        ? 'text-myPrimaryLinkColor'
                                        : 'text-myPrimaryDarkGrayColor',
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
                            <p
                                class="block text-sm leading-6 font-medium text-gray-600 italic mt-1"
                            >
                                Total ${{ product.totalPrice }}
                            </p>
                            <p
                                class="block text-[12px] leading-6 text-gray-600 italic mt-1"
                            >
                                {{ product.billed }}
                            </p>

                            <Link
                                v-if="$page.props?.user"
                                class="myPrimaryButton w-full mt-4"
                                :class="[
                                    product.mostPopular
                                        ? 'bg-myPrimaryLinkColor text-white'
                                        : 'bg-transparent border-myPrimaryLinkColor text-myPrimaryDarkGrayColor hover:text-white',
                                ]"
                                :href="
                                    route(
                                        product.route.name,
                                        product.route.parameters
                                    )
                                "
                                >{{ product.title }}
                            </Link>
                            <Link
                                v-if="!$page.props?.user"
                                :class="[
                                    product.mostPopular
                                        ? 'bg-myPrimaryLinkColor text-white shadow-sm hover:bg-myPrimaryLinkColor'
                                        : 'text-myPrimaryLinkColor ring-1 ring-inset ring-myPrimaryLinkColor hover:ring-myPrimaryLinkColor',
                                    'mt-6 block rounded-md py-2 px-3 text-center text-sm font-semibold leading-6 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-myPrimaryLinkColor cursor-pointer',
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
                                    <span
                                        class="myMediumIcon material-symbols-outlined"
                                    >
                                        check
                                    </span>
                                    {{ feature }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </template>
            </FullWidthElement>
        </GuestsLayout>
    </MainLayout>
</template>
