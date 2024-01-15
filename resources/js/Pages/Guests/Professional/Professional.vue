<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import GuestsLayout from "@/Layouts/GuestsLayout.vue";
import FullWidthElement from "@/Components/Layouts/FullWidthElement.vue";

import { ref } from "vue";
import { RadioGroup, RadioGroupLabel, RadioGroupOption } from "@headlessui/vue";
import { CheckIcon } from "@heroicons/vue/20/solid";
import { usePage } from "@inertiajs/vue3";
import storeSubscriptionPrices from "@/utils/pricing/store-subscription-prices";
import jobPrices from "@/utils/pricing/job-prices";
import PageHeader from "@/Components/Headers/PageHeader.vue";

const props = defineProps({
    myselfContactInformation: {
        required: true,
    },
});
</script>

<template>
    <Head title="Professional">
        <meta
            head-key="description"
            name="description"
            content="Join Fashion's most comprehensive marketing platform in the UAE. Showcase stores to local consumers, find professionals and post fashion jobs. View Prices!"
        />
    </Head>

    <MainLayout>
        <GuestsLayout>
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
                                    'text-lg font-medium leading-8',
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
                                    class="text-sm font-medium leading-6 text-gray-600"
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
                                    'mt-6 block rounded-md py-2 px-3 text-center text-sm font-medium leading-6 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-myPrimaryLinkColor cursor-pointer',
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
            <FullWidthElement :descriptionArea="true" class="bg-gray-50">
                <template #title>Pricing for jobs</template>
                <template #description>
                    Finding the best fit for the job shouldn’t be a full-time
                    job. myself is like a digital shopping window, focusing on
                    your brand image, authenticity and credibility. Post your
                    open roles on myself and easily target, prioritize, and
                    manage qualified candidates.
                </template>
                <template #content>
                    <div
                        v-for="product in jobPrices"
                        :key="product.id"
                        :class="[
                            product.mostPopular
                                ? 'border-2 border-myPrimaryLinkColor'
                                : 'border-2 border-gray-200',
                            'rounded-3xl p-8 hover:border-2 hover:border-myPrimaryLinkColor relative',
                        ]"
                    >
                        <div class="flex lg:flex-row flex-col w-full">
                            <div class="lg:w-2/4">
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
                                        'text-lg font-medium leading-8',
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
                                </p>
                                <p
                                    class="block text-sm leading-6 font-medium text-gray-600 italic mt-1"
                                >
                                    Total ${{ product.totalPrice }}
                                </p>
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

                            <div class="lg:w-2/4 lg:self-center lg:mt-0 mt-4">
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
                                        'mt-6 block rounded-md py-2 px-3 text-center text-sm font-medium leading-6 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-myPrimaryLinkColor cursor-pointer',
                                    ]"
                                    href="register"
                                    >Sign up
                                </Link>
                            </div>
                        </div>
                    </div>
                </template>
            </FullWidthElement>
            <FullWidthElement :descriptionArea="true" class="bg-red-50">
                <template #title>Contact us</template>
                <template #description
                    >If you have questions or need assistance, please contact
                    us.
                </template>
                <template #content>
                    <template v-if="!myselfContactInformation">
                        <p class="myPrimaryParagraph">
                            We are always ready to assist you and will gladly
                            answer any questions or inquiries you may have in
                            connection with our services! Please note that our
                            Service Assistants are available Monday through
                            Friday between 10.00 am and 08.00 pm.
                        </p>
                        <p class="myPrimaryParagraph font-medium mt-4">
                            To ensure security and prevent spam, please log in
                            or register before contacting us — takes less than
                            10 seconds to sign up.
                        </p>

                        <Link class="font-medium" :href="route('login')">
                            Log in or register now.
                        </Link>
                    </template>

                    <template v-if="myselfContactInformation">
                        <p class="myPrimaryParagraph">
                            We are always ready to assist you and will gladly
                            answer any questions or inquiries you may have in
                            connection with our services! Please note that our
                            Service Assistants are available Monday through
                            Friday between 10.00 am and 08.00 pm.
                        </p>
                        <p
                            v-if="myselfContactInformation"
                            class="myPrimaryParagraph mt-4"
                        >
                            <a
                                :href="`mailto:${myselfContactInformation.email}`"
                            >
                                <span class="text-myPrimaryDarkGrayColor">
                                    Email:
                                </span>
                                <span class="font-medium">
                                    {{ myselfContactInformation.email }}
                                </span>
                            </a>
                        </p>
                    </template>
                </template>
            </FullWidthElement>
        </GuestsLayout>
    </MainLayout>
</template>
