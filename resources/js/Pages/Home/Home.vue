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

const features = [
    {
        name: "Technologies and Frameworks",
        description:
            "Cutting-Edge Technologies and Frameworks. Developed with the Latest Stack, Laravel, Vue 3, Vuex, Composition API, Inertia, and Tailwind CSS.",
    },
    {
        name: "Invite Team Members",
        description:
            "Unleash the power of Teamwork. Invite and engage Team Members with ease.",
    },
    {
        name: "Teamwork & Collaboration",
        description:
            "Unleash the power of Teamwork. Strengthen Teamwork and achieve collective success through collaboration.",
    },
    {
        name: "Media Library",
        description:
            "Seamlessly share images within Your Team via the Media Library. Enhancing collaboration and efficiency.",
    },
    {
        name: "Single Page Application",
        description:
            "Fast, as most resources are only loaded once. Only data is transmitted back and forth.",
    },
    {
        name: "Superadmin Management",
        description:
            "Superadmin Management to effectively Create, Update and Delete. Manage Teams and Users with Custom Superadmin Roles and Privileges.",
    },
    {
        name: "Multiple Post Authors",
        description:
            "Multiple Post Authors empowers Users within a Team to Contribute and enrich each Post with a variety of viewpoints.",
    },
    {
        name: "Authorization",
        description: "Authorization Policies for Superadmins, Teams and Users.",
    },
    {
        name: "Minimal and Intuitive Design",
        description:
            "Beautiful, elegant and intuitive design. Enhance user engagement with amazing visual experience.",
    },
    {
        name: "Set Brand and Link Colors one place",
        description:
            "Set Brand and Link Colors once and apply them across the entire Platform with ease.",
    },
    {
        name: "Mobile-First Approach",
        description: "Developed with Mobile-First approach.",
    },
];
const faqs = [
    {
        question: "How does the team collaboration feature work?",
        answer: "The app allows team members to share Images, add multiple Post Authors, manage Team Members and determine Users abilities.",
    },
    {
        question: "What is the purpose of the media library?",
        answer: "The media library simplifies storing, organizing, and managing various media assets, making it easy to share images within Your Team to use them across the platform to enhancing collaboration.",
    },
    {
        question: "Can I manage the blog easily?",
        answer: "Absolutely! The dashboard includes a comprehensive blog management system, enabling you to create, edit, and publish blog posts with ease, enhancing your content management capabilities.",
    },
    {
        question:
            "Is it possible to customize the dashboard to match my branding?",
        answer: "Yes, our Laravel dashboard with Vue offers extensive customization options, allowing you to personalize the user interface, colors, and branding elements to reflect your unique style and identity.",
    },
    {
        question: "How secure is the app?",
        answer: "We prioritize data security and employ industry-standard measures to safeguard your information. The dashboard comes with robust and multiple security layers, secure authentication, and strict access controls.",
    },
    {
        question:
            "Can I integrate third-party tools or services with the dashboard?",
        answer: "Absolutely! Our App is build using Laravel and Vue. Both frameworks provides seamless integration capabilities, allowing you to connect and utilize various third-party tools and services to enhance functionality and streamline your workflow.",
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
                <FullWidthElement :descriptionArea="true" class="bg-red-50">
                    <template #title>Edit or add designs</template>
                    <template #description>
                        Add generated cutouts to your image, with
                        representation. Specify clothing, position, camera angle
                        and more to occupy your image perfectly.
                    </template>
                    <template #content>
                        <div class="w-full relative">
                            <img
                                class="top-0 left-0 w-full object-cover rounded-lg"
                                src="https://assets-global.website-files.com/64e38a3a25593ef3c6e7968a/6502f8c0d2d28543f52f901e_prod-hero-ds.webp"
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
                    <template #title>How it works </template>
                    <template #description>
                        Let users and teams elevate their administrative
                        abilities to new heights. Designed to streamline
                        operations and enhance productivity.
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
                                    src="https://images.unsplash.com/photo-1543728069-a3f97c5a2f32?auto=format&fit=crop&q=80&w=3269&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                                    alt="/"
                                />
                                <div
                                    class="bg-black/10 absolute top-0 left-0 w-full h-full rounded-lg"
                                ></div>
                                <div class="px-2 pt-8 absolute top-0 w-full">
                                    <p class="text-white mySecondaryHeader">
                                        Populate the image
                                    </p>
                                    <p class="myPrimaryParagrapg text-white">
                                        Control every aspect of your assets with
                                        generative editing, flexible setting and
                                        useful filters.
                                    </p>
                                </div>
                            </div>
                            <div
                                class="w-full lg:h-[30rem] h-[22rem] relative col-span-1 flex flex-col bg-red-100 text-center shadow rounded-lg"
                            >
                                <img
                                    class="top-0 left-0 w-full object-cover lg:h-[30rem] h-[22rem] rounded-lg"
                                    src="https://assets-global.website-files.com/64e38a3a25593ef3c6e7968a/64e8fb8601b9e5823dfede12_wys-1.webp"
                                    alt="/"
                                />
                                <div
                                    class="bg-black/10 absolute top-0 left-0 w-full h-full rounded-lg"
                                ></div>
                                <div class="px-2 pt-8 absolute top-0 w-full">
                                    <p class="text-white mySecondaryHeader">
                                        Architecture & Design
                                    </p>
                                    <p class="myPrimaryParagrapg text-white">
                                        No special hardware or technical skills
                                        required—only the web app, your idea and
                                        a sketch.
                                    </p>
                                </div>
                            </div>
                            <div
                                class="w-full lg:h-[30rem] h-[22rem] relative col-span-1 flex flex-col bg-red-100 text-center shadow rounded-lg"
                            >
                                <img
                                    class="top-0 left-0 w-full object-cover lg:h-[30rem] h-[22rem] rounded-lg"
                                    src="https://assets-global.website-files.com/64e38a3a25593ef3c6e7968a/64e8fba35806093b06741fe8_wys-2.webp"
                                    alt="/"
                                />
                                <div
                                    class="bg-black/10 absolute top-0 left-0 w-full h-full rounded-lg"
                                ></div>
                                <div class="px-2 pt-8 absolute top-0 w-full">
                                    <p class="text-white mySecondaryHeader">
                                        Easy to use
                                    </p>
                                    <p class="myPrimaryParagrapg text-white">
                                        Eliminate render time. Skip browsing
                                        though countless asset libraries.
                                        Iterate in an instant.
                                    </p>
                                </div>
                            </div>
                            <div
                                class="w-full lg:h-[30rem] h-[22rem] relative col-span-1 flex flex-col bg-red-100 text-center shadow rounded-lg"
                            >
                                <img
                                    class="top-0 left-0 w-full object-cover lg:h-[30rem] h-[22rem] rounded-lg"
                                    src="https://assets-global.website-files.com/64e38a3a25593ef3c6e7968a/64e8fccaba3cd3a0f6992383_wys-3.webp"
                                    alt="/"
                                />
                                <div
                                    class="bg-black/10 absolute top-0 left-0 w-full h-full rounded-lg"
                                ></div>
                                <div class="px-2 pt-8 absolute top-0 w-full">
                                    <p class="text-white mySecondaryHeader">
                                        Professional output
                                    </p>
                                    <p class="myPrimaryParagrapg text-white">
                                        Get high quality assets and images, with
                                        a suite of tools dedicated to architects
                                        and designers.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </template>
                </FullWidthElement>
                <!-- 4 images # end -->

                <!-- FAQ # start -->
                <FullWidthElement :descriptionArea="true" class="bg-red-50">
                    <template #title>Frequently asked questions</template>
                    <template #description>
                        Minimalist Admin Panel empowered with advanced Team
                        Management capabilities, Blog, and a beautiful Media
                        Library. Offers control and efficiency. Let users and
                        teams elevate their administrative abilities to new
                        heights. Designed to streamline operations and enhance
                        productivity.
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
                                src="/app-images/pages/frequently_asked_questions.jpg"
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
