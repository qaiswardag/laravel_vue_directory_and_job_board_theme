<script setup>
import GuestsLayout from "@/Layouts/GuestsLayout.vue";
import { AdjustmentsHorizontalIcon } from "@heroicons/vue/24/outline";
import FullWidthElement from "@/Components/Layouts/FullWidthElement.vue";
import DefaultElement from "@/Components/Layouts/DefaultElement.vue";
import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";
import { MinusSmallIcon, PlusSmallIcon } from "@heroicons/vue/24/outline";
import { Head } from "@inertiajs/vue3";
import SectionBorder from "@/Components/Sections/SectionBorder.vue";
import FooterSectionBorder from "@/Components/Sections/FooterSectionBorder.vue";
import EmptySectionBorder from "../../Components/Sections/EmptySectionBorder.vue";

const faqs = [
    {
        question: "What's the best thing about Switzerland?",
        answer: "I don't know, but the flag is a big plus. Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas cupiditate laboriosam fugiat. I don't know, but the flag is a big plus. Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas cupiditate laboriosam fugiat. I don't know, but the flag is a big plus. Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas cupiditate laboriosam fugiat.",
    },
    {
        question: "What's the best thing about Switzerland?",
        answer: "I don't know, but the flag is a big plus. Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas cupiditate laboriosam fugiat. I don't know, but the flag is a big plus. Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas cupiditate laboriosam fugiat. I don't know, but the flag is a big plus. Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas cupiditate laboriosam fugiat.",
    },
    {
        question: "What's the best thing about Switzerland?",
        answer: "I don't know, but the flag is a big plus. Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas cupiditate laboriosam fugiat. I don't know, but the flag is a big plus. Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas cupiditate laboriosam fugiat. I don't know, but the flag is a big plus. Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas cupiditate laboriosam fugiat.",
    },
    {
        question: "What's the best thing about Switzerland?",
        answer: "I don't know, but the flag is a big plus. Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas cupiditate laboriosam fugiat. I don't know, but the flag is a big plus. Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas cupiditate laboriosam fugiat. I don't know, but the flag is a big plus. Lorem ipsum dolor sit amet consectetur adipisicing elit. Quas cupiditate laboriosam fugiat.",
    },
];
</script>

<template>
    <GuestsLayout>
        <Head title="Home" />
        <template #header>
            <div class="myPrimaryMainPageHeaderParagraph">
                <h1 class="myPrimaryMainPageHeaderNotLoggedIn">
                    Laravel app empowered
                    <br />
                    with advanced features
                </h1>
                <p class="myPrimaryMainPageParagraphNotLoggedIn">
                    Discover the ultimate minimalist app with robust and
                    multiple security layers and advanced features like team
                    Management and Media Library.
                </p>
            </div>
        </template>

        <FullWidthElement :descriptionArea="true" class="bg-gray-50">
            <template #title>All-in-one platform</template>
            <template #description>
                Minimalist app empowered with advanced team management
                capabilities and a state-of-the-art media library, it offers
                unrivaled control and efficiency. Seamlessly manage and organize
                your media assets. Take your administrative prowess to new
                heights with this powerful solution designed to streamline your
                operations and enhance productivity.
            </template>
            <template #content>
                <div class="grid grid-cols-1 lg:gap-16 lg:grid-cols-12">
                    <img
                        class="w-full object-cover col-span-8"
                        src="https://media-www.sqspcdn.com/images/pages/homepage/jun-2022/how-to/create-a-website-1500w.jpg"
                        alt=""
                    />
                    <div class="lg:pt-4 col-span-4">
                        <p class="mySecondaryHeader">A better workflow</p>
                        <p class="myPrimaryParagraph">
                            Beautiful and minimal designed web application built
                            with Laravel, Vue, Vuex, and Inertia, offering a
                            seamless user experience, robust performance, and
                            advanced functionalities.
                        </p>
                        <div
                            v-if="$page.props.user === null"
                            class="mt-8 flex gap-2 items-center"
                        >
                            <Link
                                :href="route('login')"
                                class="myPrimaryButton"
                            >
                                Login
                            </Link>
                            <Link
                                :href="route('register')"
                                class="myPrimaryButton"
                            >
                                Sign up
                            </Link>
                        </div>
                        <div
                            v-if="$page.props.user !== null"
                            class="mt-8 flex gap-2 items-center"
                        >
                            <Link
                                :href="route('dashboard')"
                                class="myPrimaryButton"
                            >
                                Go to dashboard
                            </Link>
                        </div>
                    </div>
                </div>
            </template>
        </FullWidthElement>

        <FullWidthElement :descriptionArea="true" class="bg-red-50">
            <template #title>Frequently asked questions</template>
            <template #description>
                As new users join, they bring innovative ideas, unique skills,
                and diverse experiences that enrich our platform. Connect with
                them, explore their work, and celebrate the fresh energy they
                contribute to our collaborative space.
            </template>
            <template #content>
                <div class="grid grid-cols-1 lg:gap-16 lg:grid-cols-12">
                    <dl
                        class="divide-y divide-myPrimaryMediumGrayColor lg:col-span-4"
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
                                    <span class="myPrimaryParagraph font-medium"
                                        >{{ faq.question }}
                                    </span>
                                    <span class="ml-6 flex h-7 items-center">
                                        <PlusSmallIcon
                                            v-if="!open"
                                            class="h-5 w-5"
                                            aria-hidden="true"
                                        />
                                        <MinusSmallIcon
                                            v-else
                                            class="h-5 w-5"
                                            aria-hidden="true"
                                        />
                                    </span>
                                </DisclosureButton>
                            </dt>
                            <DisclosurePanel as="dd" class="mt-2 pr-12 pb-16">
                                <p class="myPrimaryParagraph">
                                    {{ faq.answer }}
                                </p>
                            </DisclosurePanel>
                        </Disclosure>
                    </dl>
                    <img
                        class="w-full object-cover lg:col-span-8"
                        src="https://media-www.sqspcdn.com/images/pages/homepage/jun-2022/showcase/meiwen-see-1500w.webp"
                        alt="image"
                    />
                </div>
            </template>
        </FullWidthElement>
    </GuestsLayout>
</template>
