<script setup>
import GuestsLayout from "@/Layouts/GuestsLayout.vue";
import { AdjustmentsHorizontalIcon } from "@heroicons/vue/24/outline";
import FullWidthElement from "@/Components/Layouts/FullWidthElement.vue";
import DefaultElement from "@/Components/Layouts/DefaultElement.vue";
import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";
import { MinusSmallIcon, PlusSmallIcon } from "@heroicons/vue/24/outline";
import { Head } from "@inertiajs/vue3";
import SectionBorder from "@/Components/Sections/SectionBorder.vue";

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
                    Dashboard Empowered
                    <br />
                    with Advanced features
                </h1>
                <p class="myPrimaryMainPageParagraphNotLoggedIn">
                    Experience the epitome of elegance with our Exquisite Admin
                    & Dashboard. Exquisite Admin & Dashboard Empowered with
                    Advanced Team Management and Media Library
                </p>
            </div>
        </template>

        <FullWidthElement :descriptionArea="true" class="bg-gray-50">
            <template #title>All-in-one platform</template>
            <template #description>
                Experience the epitome of elegance with our Exquisite Admin &
                Dashboard. Empowered with advanced team management capabilities
                and a state-of-the-art media library, it offers unrivaled
                control and efficiency. Seamlessly manage and organize your
                media assets.
                <br />
                Take your administrative prowess to new heights with this
                powerful solution designed to streamline your operations and
                enhance productivity. Discover a world of sophistication and
                convenience with our Exquisite Admin & Dashboard.
            </template>
            <template #content>
                <div
                    class="grid grid-cols-1 lg:gap-x-28 gap-x-8 lg:gap-y-20 gap-y-16 lg:grid-cols-12"
                >
                    <img
                        class="w-full object-cover col-span-8"
                        src="https://media-www.sqspcdn.com/images/pages/homepage/jun-2022/how-to/create-a-website-1500w.jpg"
                        alt=""
                    />
                    <div class="lg:pt-4 col-span-4">
                        <h2 class="myFourthHeader">Deploy faster</h2>
                        <p class="mySecondaryHeader">A better workflow</p>
                        <p class="mt-6 myPrimaryParagraph">
                            Lorem ipsum, dolor sit amet consectetur adipisicing
                            elit. Maiores impedit perferendis suscipit eaque,
                            iste dolor cupiditate blanditiis ratione.
                        </p>
                        <dl
                            class="mt-10 max-w-xl space-y-8 myPrimaryParagraph lg:max-w-none"
                        >
                            <!-- space -->
                            <dd class="block mt-4">
                                Lorem ipsum, dolor sit amet consectetur
                                adipisicing elit. Maiores impedit perferendis
                                suscipit eaque, iste dolor cupiditate blanditiis
                                ratione.
                            </dd>

                            <!-- space -->
                            <dd class="block mt-4">
                                Anim aute id magna aliqua ad ad non deserunt
                                sunt. Qui irure qui lorem cupidatat commodo.
                            </dd>

                            <!-- space -->
                            <dd class="block mt-4">
                                Ac tincidunt sapien vehicula erat auctor
                                pellentesque rhoncus. Et magna sit morbi
                                lobortis.
                            </dd>
                        </dl>
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
                                Dashboard
                            </Link>
                        </div>
                    </div>
                </div>
            </template>
        </FullWidthElement>

        <!-- People - start -->
        <DefaultElement :descriptionArea="true">
            <template #title>Latest Users</template>
            <template #description>
                As new users join, they bring innovative ideas, unique skills,
                and diverse experiences that enrich our platform. Connect with
                them, explore their work, and celebrate the fresh energy they
                contribute to our collaborative space.
            </template>
            <template #content>
                <ul
                    role="list"
                    class="myPrimarySection mx-auto grid max-w-2xl grid-cols-2 gap-x-8 gap-y-16 text-center sm:grid-cols-3 md:grid-cols-4 lg:mx-0 lg:max-w-none lg:grid-cols-5 xl:grid-cols-6"
                >
                    <li>
                        <img
                            class="mx-auto h-24 w-24 rounded-full"
                            src="https://images.unsplash.com/photo-1519244703995-f4e0f30006d5?ixlib=rb-=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=8&amp;w=1024&amp;h=1024&amp;q=80"
                            alt=""
                        />
                        <h3 class="myPrimaryParagraph font-normal mt-2">
                            Michael Foster
                        </h3>
                        <p class="myPrimaryParagraph mt-2">Co-Founder / CTO</p>
                    </li>
                    <li>
                        <img
                            class="mx-auto h-24 w-24 rounded-full"
                            src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?ixlib=rb-=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=8&amp;w=1024&amp;h=1024&amp;q=80"
                            alt=""
                        />
                        <h3 class="myPrimaryParagraph font-normal mt-2">
                            Dries Vincent
                        </h3>
                        <p class="myPrimaryParagraph mt-2">
                            Business Relations
                        </p>
                    </li>
                    <li>
                        <img
                            class="mx-auto h-24 w-24 rounded-full"
                            src="https://images.unsplash.com/photo-1517841905240-472988babdf9?ixlib=rb-=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=8&amp;w=1024&amp;h=1024&amp;q=80"
                            alt=""
                        />
                        <h3 class="myPrimaryParagraph font-normal mt-2">
                            Lindsay Walton
                        </h3>
                        <p class="myPrimaryParagraph mt-2">
                            Front-end Developer
                        </p>
                    </li>
                    <li>
                        <img
                            class="mx-auto h-24 w-24 rounded-full"
                            src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=8&amp;w=1024&amp;h=1024&amp;q=80"
                            alt=""
                        />
                        <h3 class="myPrimaryParagraph font-normal mt-2">
                            Courtney Henry
                        </h3>
                        <p class="myPrimaryParagraph mt-2">Designer</p>
                    </li>
                    <li>
                        <img
                            class="mx-auto h-24 w-24 rounded-full"
                            src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=8&amp;w=1024&amp;h=1024&amp;q=80"
                            alt=""
                        />
                        <h3 class="myPrimaryParagraph font-normal mt-2">
                            Tom Cook
                        </h3>
                        <p class="myPrimaryParagraph mt-2">
                            Director of Product
                        </p>
                    </li>
                    <li>
                        <img
                            class="mx-auto h-24 w-24 rounded-full"
                            src="https://images.unsplash.com/photo-1517365830460-955ce3ccd263?ixlib=rb-=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=8&amp;w=1024&amp;h=1024&amp;q=80"
                            alt=""
                        />
                        <h3 class="myPrimaryParagraph font-normal mt-2">
                            Whitney Francis
                        </h3>
                        <p class="myPrimaryParagraph mt-2">Copywriter</p>
                    </li>
                    <li>
                        <img
                            class="mx-auto h-24 w-24 rounded-full"
                            src="https://images.unsplash.com/photo-1519345182560-3f2917c472ef?ixlib=rb-=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=8&amp;w=1024&amp;h=1024&amp;q=80"
                            alt=""
                        />
                        <h3 class="myPrimaryParagraph font-normal mt-2">
                            Leonard Krasner
                        </h3>
                        <p class="myPrimaryParagraph mt-2">Senior Designer</p>
                    </li>
                    <li>
                        <img
                            class="mx-auto h-24 w-24 rounded-full"
                            src="https://images.unsplash.com/photo-1463453091185-61582044d556?ixlib=rb-=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=8&amp;w=1024&amp;h=1024&amp;q=80"
                            alt=""
                        />
                        <h3 class="myPrimaryParagraph font-normal mt-2">
                            Floyd Miles
                        </h3>
                        <p class="myPrimaryParagraph mt-2">
                            Principal Designer
                        </p>
                    </li>
                    <li>
                        <img
                            class="mx-auto h-24 w-24 rounded-full"
                            src="https://images.unsplash.com/photo-1502685104226-ee32379fefbe?ixlib=rb-=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=8&amp;w=1024&amp;h=1024&amp;q=80"
                            alt=""
                        />
                        <h3 class="myPrimaryParagraph font-normal mt-2">
                            Emily Selman
                        </h3>
                        <p class="myPrimaryParagraph mt-2">
                            VP, User Experience
                        </p>
                    </li>
                    <li>
                        <img
                            class="mx-auto h-24 w-24 rounded-full"
                            src="https://images.unsplash.com/photo-1500917293891-ef795e70e1f6?ixlib=rb-=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=8&amp;w=1024&amp;h=1024&amp;q=80"
                            alt=""
                        />
                        <h3 class="myPrimaryParagraph font-normal mt-2">
                            Kristin Watson
                        </h3>
                        <p class="myPrimaryParagraph mt-2">
                            VP, Human Resources
                        </p>
                    </li>
                    <li>
                        <img
                            class="mx-auto h-24 w-24 rounded-full"
                            src="https://images.unsplash.com/photo-1505840717430-882ce147ef2d?ixlib=rb-=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=8&amp;w=1024&amp;h=1024&amp;q=80"
                            alt=""
                        />
                        <h3 class="myPrimaryParagraph font-normal mt-2">
                            Emma Dorsey
                        </h3>
                        <p class="myPrimaryParagraph mt-2">Senior Developer</p>
                    </li>
                    <li>
                        <img
                            class="mx-auto h-24 w-24 rounded-full"
                            src="https://images.unsplash.com/photo-1509783236416-c9ad59bae472?ixlib=rb-=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=8&amp;w=1024&amp;h=1024&amp;q=80"
                            alt=""
                        />
                        <h3 class="myPrimaryParagraph font-normal mt-2">
                            Alicia Bell
                        </h3>
                        <p class="myPrimaryParagraph mt-2">Junior Copywriter</p>
                    </li>
                    <li>
                        <img
                            class="mx-auto h-24 w-24 rounded-full"
                            src="https://images.unsplash.com/photo-1507101105822-7472b28e22ac?ixlib=rb-=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=8&amp;w=1024&amp;h=1024&amp;q=80"
                            alt=""
                        />
                        <h3 class="myPrimaryParagraph font-normal mt-2">
                            Jenny Wilson
                        </h3>
                        <p class="myPrimaryParagraph mt-2">Studio Artist</p>
                    </li>
                    <li>
                        <img
                            class="mx-auto h-24 w-24 rounded-full"
                            src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?ixlib=rb-=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=8&amp;w=1024&amp;h=1024&amp;q=80"
                            alt=""
                        />
                        <h3 class="myPrimaryParagraph font-normal mt-2">
                            Anna Roberts
                        </h3>
                        <p class="myPrimaryParagraph mt-2">Partner, Creative</p>
                    </li>
                    <li>
                        <img
                            class="mx-auto h-24 w-24 rounded-full"
                            src="https://images.unsplash.com/photo-1531427186611-ecfd6d936c79?ixlib=rb-=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=8&amp;w=1024&amp;h=1024&amp;q=80"
                            alt=""
                        />
                        <h3 class="myPrimaryParagraph font-normal mt-2">
                            Benjamin Russel
                        </h3>
                        <p class="myPrimaryParagraph mt-2">
                            Director, Print Operations
                        </p>
                    </li>
                </ul>
            </template>
        </DefaultElement>
        <!-- People - end -->

        <FullWidthElement :descriptionArea="true" class="bg-gray-50">
            <template #title>Frequently asked questions</template>
            <template #description>
                As new users join, they bring innovative ideas, unique skills,
                and diverse experiences that enrich our platform. Connect with
                them, explore their work, and celebrate the fresh energy they
                contribute to our collaborative space.
            </template>
            <template #content>
                <div
                    class="grid grid-cols-1 lg:gap-x-28 gap-x-8 lg:gap-y-20 gap-y-16 lg:grid-cols-12"
                >
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
