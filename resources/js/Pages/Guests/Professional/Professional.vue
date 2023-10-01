<script setup>
import GuestsLayout from "@/Layouts/GuestsLayout.vue";
import FullWidthElement from "@/Components/Layouts/FullWidthElement.vue";

import { ref } from "vue";
import { RadioGroup, RadioGroupLabel, RadioGroupOption } from "@headlessui/vue";
import { CheckIcon } from "@heroicons/vue/20/solid";

const frequencies = [
    { value: "monthly", label: "Monthly", priceSuffix: "/month" },
    { value: "annually", label: "Annually", priceSuffix: "/year" },
];
const tiers = [
    {
        name: "Hobby",
        id: "tier-hobby",
        href: "#",
        price: { monthly: "$15", annually: "$144" },
        description: "The essentials to provide your best work for clients.",
        features: ["5 products", "Up to 1,000 subscribers", "Basic analytics"],
        mostPopular: false,
    },
    {
        name: "Freelancer",
        id: "tier-freelancer",
        href: "#",
        price: { monthly: "$30", annually: "$288" },
        description: "The essentials to provide your best work for clients.",
        features: [
            "5 products",
            "Up to 1,000 subscribers",
            "Basic analytics",
            "48-hour support response time",
        ],
        mostPopular: false,
    },
    {
        name: "Startup",
        id: "tier-startup",
        href: "#",
        price: { monthly: "$60", annually: "$576" },
        description: "A plan that scales with your rapidly growing business.",
        features: [
            "25 products",
            "Up to 10,000 subscribers",
            "Advanced analytics",
            "24-hour support response time",
            "Marketing automations",
        ],
        mostPopular: true,
    },
    {
        name: "Enterprise",
        id: "tier-enterprise",
        href: "#",
        price: { monthly: "$90", annually: "$864" },
        description: "Dedicated support and infrastructure for your company.",
        features: [
            "Unlimited products",
            "Unlimited subscribers",
            "Advanced analytics",
            "1-hour, dedicated support response time",
            "Marketing automations",
            "Custom reporting tools",
        ],
        mostPopular: false,
    },
];

const frequency = ref(frequencies[0]);
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
                shoppers back to real stores? Choose an affordable plan that’s
                packed with the best features for engaging your audience,
                creating customer loyalty, and driving sales. Select the ideal
                subscription based on number of stores.
            </template>
            <template #content>
                <div class="mt-16 flex justify-center">
                    <RadioGroup
                        v-model="frequency"
                        class="grid grid-cols-2 gap-x-1 rounded-full p-1 text-center text-xs font-semibold leading-5 ring-1 ring-inset ring-gray-200"
                    >
                        <RadioGroupLabel class="sr-only"
                            >Payment frequency</RadioGroupLabel
                        >
                        <RadioGroupOption
                            as="template"
                            v-for="option in frequencies"
                            :key="option.value"
                            :value="option"
                            v-slot="{ checked }"
                        >
                            <div
                                :class="[
                                    checked
                                        ? 'bg-myPrimaryLinkColor text-white'
                                        : 'text-gray-500',
                                    'cursor-pointer rounded-full px-2.5 py-1',
                                ]"
                            >
                                <span>{{ option.label }}</span>
                            </div>
                        </RadioGroupOption>
                    </RadioGroup>
                </div>
                <div
                    class="isolate mx-auto mt-10 grid max-w-md grid-cols-1 gap-8 md:max-w-2xl md:grid-cols-2 lg:max-w-4xl xl:mx-0 xl:max-w-none xl:grid-cols-4"
                >
                    <div
                        v-for="tier in tiers"
                        :key="tier.id"
                        :class="[
                            tier.mostPopular
                                ? 'border-2 border-myPrimaryLinkColor'
                                : 'border-2 border-gray-200',
                            'rounded-3xl p-8 hover:border-2 hover:border-myPrimaryLinkColor',
                        ]"
                    >
                        <h3
                            :id="tier.id"
                            :class="[
                                tier.mostPopular
                                    ? 'text-myPrimaryLinkColor'
                                    : 'text-gray-900',
                                'text-lg font-semibold leading-8',
                            ]"
                        >
                            {{ tier.name }}
                        </h3>
                        <p class="mt-4 text-sm leading-6 text-gray-600">
                            {{ tier.description }}
                        </p>
                        <p class="mt-6 flex items-baseline gap-x-1">
                            <span
                                class="text-4xl font-bold tracking-tight text-gray-900"
                                >{{ tier.price[frequency.value] }}</span
                            >
                            <span
                                class="text-sm font-semibold leading-6 text-gray-600"
                                >{{ frequency.priceSuffix }}</span
                            >
                        </p>
                        <a
                            :href="tier.href"
                            :aria-describedby="tier.id"
                            :class="[
                                tier.mostPopular
                                    ? 'bg-myPrimaryLinkColor text-white shadow-sm hover:bg-myPrimaryLinkColor'
                                    : 'text-myPrimaryLinkColor ring-1 ring-inset ring-myPrimaryLinkColor hover:ring-myPrimaryLinkColor',
                                'mt-6 block rounded-md py-2 px-3 text-center text-sm font-semibold leading-6 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-myPrimaryLinkColor',
                            ]"
                            >Buy plan</a
                        >
                        <ul
                            role="list"
                            class="mt-8 space-y-3 text-sm leading-6 text-gray-600"
                        >
                            <li
                                v-for="feature in tier.features"
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
