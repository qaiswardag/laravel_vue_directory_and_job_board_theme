<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import GuestsLayout from "@/Layouts/GuestsLayout.vue";
import { AdjustmentsHorizontalIcon } from "@heroicons/vue/24/outline";
import FullWidthElement from "@/Components/Layouts/FullWidthElement.vue";
import DefaultElement from "@/Components/Layouts/DefaultElement.vue";
import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";
import { MinusSmallIcon, PlusSmallIcon } from "@heroicons/vue/24/outline";
import SectionBorder from "@/Components/Sections/SectionBorder.vue";
import FooterSectionBorder from "@/Components/Sections/FooterSectionBorder.vue";
import EmptySectionBorder from "../../Components/Sections/EmptySectionBorder.vue";
import { CheckIcon } from "@heroicons/vue/20/solid";

const faqs = [
    {
        question: "Pricing",
        answer: "For pricing information, visit our professional page.",
    },
    {
        question: "Our services",
        answer: "Our services include job posting, product in-store campaigns, and store posting.",
    },
];
</script>

<template>
    <MainLayout>
        <!-- TODO: Remove maintenance for signed-in user: `$page.props.user` -->
        <template v-if="!$page.props.user">
            <Head title="Home" />
            <div class="relative isolate min-h-screen">
                <img
                    src="/brand-images/pink-lady-high-resolution.jpg"
                    alt=""
                    class="absolute inset-0 -z-10 h-full w-full object-cover object-top"
                />
                <div
                    class="bg-black/20 absolute top-0 left-0 w-full h-full"
                ></div>
                <div
                    class="lg:max-w-3xl flex flex-col gap-2 justify-center items-center mx-auto"
                >
                    <div
                        class="mx-auto max-w-7xl px-6 py-32 text-center sm:py-40 lg:px-8 z-20"
                    >
                        <div>
                            <h1 class="myPrimaryHeader text-white">
                                myself fashion &amp; jobs <br />
                            </h1>
                            <p
                                class="myPrimaryParagraph text-lg text-white drop-shadow font-medium mt-8 leading-6"
                            >
                                Exciting news! We're currently upgrading to a
                                new platform with amazing new features! Get
                                ready for a whole new experience! We'll be back
                                by January 1, 2024! <br />
                                Stay tuned and prepare to be amazed!
                            </p>
                        </div>
                        <!---->
                    </div>
                </div>
            </div>
        </template>
        <!-- TODO: Remove maintenance for signed-in user: `$page.props.user` -->
        <template v-if="$page.props.user">
            <GuestsLayout>
                <Head title="Home" />
                <template #header>
                    <div class="w-full relative h-[32rem]">
                        <img
                            class="top-0 left-0 w-full object-cover h-[32rem]"
                            src="/brand-images/pink-lady-high-resolution.jpg"
                            alt="/"
                        />
                        <div
                            class="bg-black/20 absolute top-0 left-0 w-full h-[32rem]"
                        ></div>
                        <div
                            class="absolute text-2xl top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"
                        >
                            <h1 class="myPrimaryHeader text-white text-center">
                                myself fashion & jobs
                                <br />
                            </h1>

                            <p
                                class="myPrimaryParagraph text-white text-center font-medium"
                            >
                                Discover Fashion in the United Arab Emirates.
                            </p>
                        </div>
                    </div>
                </template>

                <!-- Builder # start -->
                <FullWidthElement
                    :descriptionArea="true"
                    class="bg-red-50 lg:block hidden"
                >
                    <template #title
                        >Customize the design the way you want.</template
                    >
                    <template #description>
                        Design stores, jobs or campaigns your way with our
                        intuitive page builder. Customize layouts, colors, and
                        fonts to achieve the perfect design.
                        <br />
                        myself is like a digital shopping window, focusing on
                        your brand image, authenticity and credibility! Just
                        like on a shopping site customers can search for
                        anything they are looking for.
                    </template>
                    <template #content>
                        <div class="w-full relative">
                            <img
                                class="top-0 left-0 w-full object-cover rounded-lg"
                                src="/app-images/pages/home/edit_or_add_designs.jpg"
                                alt="image"
                            />
                            <div
                                class="bg-black/0 absolute top-0 left-0 w-full h-full rounded-lg"
                            ></div>
                        </div>
                    </template>
                </FullWidthElement>
                <!-- Builder # end -->
                <!-- 4 images # start -->
                <FullWidthElement :descriptionArea="true" class="bg-gray-50">
                    <template #title
                        >Fashion brands in one searchable solution</template
                    >
                    <template #description>
                        myself is the largest and most exclusive fashion
                        directory in the United Arab Emirates, gathering all
                        fashion brands in one searchable solution.
                    </template>
                    <template #content>
                        <div
                            class="grid grid-cols-2 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4"
                        >
                            <div
                                class="w-full lg:h-[30rem] h-[22rem] relative col-span-1 flex flex-col bg-red-100 text-center shadow rounded-lg"
                            >
                                <img
                                    class="top-0 left-0 w-full object-cover lg:h-[30rem] h-[22rem] rounded-lg"
                                    src="/app-images/pages/home/fashion_show.jpg"
                                    alt="image"
                                />
                                <Link :href="route('stores.guest.index')">
                                    <div
                                        class="bg-black/10 absolute top-0 left-0 w-full h-full rounded-lg"
                                    ></div>
                                    <div
                                        class="px-2 pt-8 absolute top-0 w-full"
                                    >
                                        <p class="text-white mySecondaryHeader">
                                            Find stores
                                        </p>
                                        <p
                                            class="myPrimaryParagrapg text-white"
                                        >
                                            Control every aspect of your assets
                                            with generative editing, flexible
                                            setting and useful filters.
                                        </p>
                                    </div>
                                </Link>
                            </div>
                            <div
                                class="w-full lg:h-[30rem] h-[22rem] relative col-span-1 flex flex-col bg-red-100 text-center shadow rounded-lg"
                            >
                                <img
                                    class="top-0 left-0 w-full object-cover lg:h-[30rem] h-[22rem] rounded-lg"
                                    src="/app-images/pages/home/orange_bg.jpg"
                                    alt="/"
                                />
                                <Link :href="route('posts.guest.index')">
                                    <div
                                        class="bg-black/10 absolute top-0 left-0 w-full h-full rounded-lg"
                                    ></div>

                                    <div
                                        class="px-2 pt-8 absolute top-0 w-full"
                                    >
                                        <p class="text-white mySecondaryHeader">
                                            Campaigns
                                        </p>
                                        <p
                                            class="myPrimaryParagrapg text-white"
                                        >
                                            No special hardware or technical
                                            skills required—only the web app,
                                            your idea and a sketch.
                                        </p>
                                    </div>
                                </Link>
                            </div>
                            <div
                                class="w-full lg:h-[30rem] h-[22rem] relative col-span-1 flex flex-col bg-red-100 text-center shadow rounded-lg"
                            >
                                <img
                                    class="top-0 left-0 w-full object-cover lg:h-[30rem] h-[22rem] rounded-lg"
                                    src="/app-images/pages/home/girl_light_bg.jpg"
                                    alt="/"
                                />
                                <Link :href="route('jobs.guest.index')">
                                    <div
                                        class="bg-black/10 absolute top-0 left-0 w-full h-full rounded-lg"
                                    ></div>
                                    <div
                                        class="px-2 pt-8 absolute top-0 w-full"
                                    >
                                        <p class="text-white mySecondaryHeader">
                                            Jobs in UAE
                                        </p>
                                        <p
                                            class="myPrimaryParagrapg text-white"
                                        >
                                            Eliminate render time. Skip browsing
                                            though countless asset libraries.
                                            Iterate in an instant.
                                        </p>
                                    </div>
                                </Link>
                            </div>
                            <div
                                class="w-full lg:h-[30rem] h-[22rem] relative col-span-1 flex flex-col bg-red-100 text-center shadow rounded-lg"
                            >
                                <img
                                    class="top-0 left-0 w-full object-cover lg:h-[30rem] h-[22rem] rounded-lg"
                                    src="/app-images/pages/home/three.jpg"
                                    alt="/"
                                />
                                <Link :href="route('professional.index')">
                                    <div
                                        class="bg-black/10 absolute top-0 left-0 w-full h-full rounded-lg"
                                    ></div>
                                    <div
                                        class="px-2 pt-8 absolute top-0 w-full"
                                    >
                                        <p class="text-white mySecondaryHeader">
                                            Professional
                                        </p>
                                        <p
                                            class="myPrimaryParagrapg text-white"
                                        >
                                            Get high quality assets and images,
                                            with a suite of tools dedicated to
                                            architects and designers.
                                        </p>
                                    </div>
                                </Link>
                            </div>
                        </div>
                    </template>
                </FullWidthElement>
                <!-- 4 images # end -->

                <!-- FAQ # start -->
                <FullWidthElement
                    :descriptionArea="true"
                    class="bg-red-50 lg:block hidden"
                >
                    <template #title>Frequently asked questions</template>
                    <template #description>
                        Shopping has become an experience, and store performance
                        must keep up to meet customer expectations. myself is a
                        powerful branding and marketing tool connection brands
                        and stores with consumers. Our platform enables you to
                        build local brand awareness online.
                    </template>
                    <template #content>
                        <div
                            class="grid grid-cols-1 lg:gap-16 gap-24 lg:grid-cols-12"
                        >
                            <dl
                                class="divide-y divide-myPrimaryMediumGrayColor lg:col-span-6"
                            >
                                <Disclosure
                                    as="div"
                                    v-for="faq in faqs"
                                    :key="faq.question"
                                    v-slot="{ open }"
                                >
                                    <dt>
                                        <DisclosureButton
                                            class="py-6 flex w-full items-center justify-between text-left myPrimaryParagraph"
                                        >
                                            <span
                                                class="myPrimaryParagraph font-medium"
                                                >{{ faq.question }}
                                            </span>
                                            <span
                                                class="ml-6 flex h-7 items-center"
                                            >
                                                <span
                                                    v-if="!open"
                                                    class="myMediumIcon material-symbols-outlined"
                                                >
                                                    add
                                                </span>
                                                <span
                                                    v-else
                                                    class="myMediumIcon material-symbols-outlined"
                                                >
                                                    remove
                                                </span>
                                            </span>
                                        </DisclosureButton>
                                    </dt>
                                    <DisclosurePanel
                                        as="dd"
                                        class="mt-2 pr-12 pb-16"
                                    >
                                        <p class="myPrimaryParagraph">
                                            {{ faq.answer }}
                                        </p>
                                    </DisclosurePanel>
                                </Disclosure>
                            </dl>
                            <img
                                class="w-full object-cover lg:col-span-6 rounded-lg"
                                src="/app-images/pages/home/frequently_asked_questions.jpg"
                                alt="image"
                            />
                        </div>
                    </template>
                </FullWidthElement>
                <!-- FAQ # end -->
            </GuestsLayout>
        </template>
    </MainLayout>
</template>
