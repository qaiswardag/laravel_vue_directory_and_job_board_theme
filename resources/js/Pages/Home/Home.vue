<script setup>
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
            "Cutting-Edge Technologies and Frameworks. Developed with the Latest Stack, Laravel, Vue 3, Inertia, and Tailwind CSS.",
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
    <GuestsLayout>
        <Head title="Home" />
        <template #header>
            <div class="myPrimaryMainPageHeaderParagraph">
                <h1 class="myPrimaryMainPageHeaderNotLoggedIn">
                    Laravel & Vue app empowered
                    <br />
                    with advanced features
                </h1>
                <p class="myPrimaryMainPageParagraphNotLoggedIn">
                    Discover the ultimate minimalist administration panel with
                    robust and multiple security layers and advanced features
                    like Team Management, Blog and Media Library.
                </p>
                <div class="flex justify-center">
                    <div
                        v-if="$page.props.user === null"
                        class="mt-8 flex gap-2 items-center"
                    >
                        <Link :href="route('login')" class="myPrimaryButton">
                            Login
                        </Link>
                        <Link :href="route('register')" class="myPrimaryButton">
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

        <FullWidthElement :descriptionArea="true" class="bg-gray-50">
            <template #title>All-in-one platform</template>
            <template #description>
                <span class="block">
                    Minimalist app empowered with advanced Team Management
                    capabilities and a beautiful Media Library. Offers unrivaled
                    control and efficiency. Take your administrative abilities
                    to new heights with this minimal admin panel designed to
                    streamline your operations and enhance productivity.
                </span>
            </template>
            <template #content>
                <div class="grid grid-cols-1 lg:gap-16 gap-24 lg:grid-cols-3">
                    <div>
                        <h2 class="myTertiaryHeader">Everything you need</h2>
                        <p class="mt-2 myPrimaryParagraph">
                            Complete Solution for Seamless Management and
                            Control. Everything You Need to Get Started Right
                            Out of the Box.
                        </p>
                    </div>
                    <dl
                        class="col-span-2 grid grid-cols-1 gap-x-8 gap-y-10 text-base leading-7 text-gray-600 sm:grid-cols-2 lg:gap-y-16"
                    >
                        <div
                            v-for="feature in features"
                            :key="feature.name"
                            class="relative pl-9"
                        >
                            <dt class="myFourthHeader">
                                <CheckIcon
                                    class="absolute left-0 top-1 h-5 w-5 text-myPrimaryLinkColor"
                                    aria-hidden="true"
                                />
                                {{ feature.name }}
                            </dt>
                            <dd class="mt-2 myPrimaryParagraph">
                                {{ feature.description }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </template>
        </FullWidthElement>

        <FullWidthElement :descriptionArea="true" class="bg-red-50">
            <template #title>Frequently asked questions</template>
            <template #description>
                As new users join, they bring innovative ideas, unique skills,
                and diverse experiences that enrich the platform. Connect with
                them, explore their work, and celebrate the fresh energy they
                contribute to our collaborative space.
            </template>
            <template #content>
                <div class="grid grid-cols-1 lg:gap-16 gap-24 lg:grid-cols-12">
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
                        src="/app-images/blog/4.jpg"
                        alt="image"
                    />
                </div>
            </template>
        </FullWidthElement>
    </GuestsLayout>
</template>
