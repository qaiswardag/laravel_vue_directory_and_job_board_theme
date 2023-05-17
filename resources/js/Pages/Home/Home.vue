<script setup>
import GuestsLayout from "@/Layouts/GuestsLayout.vue";
import { AdjustmentsHorizontalIcon } from "@heroicons/vue/24/outline";
import FullWidthElement from "@/Components/Layouts/FullWidthElement.vue";
import DefaultElement from "@/Components/Layouts/DefaultElement.vue";
import { Disclosure, DisclosureButton, DisclosurePanel } from "@headlessui/vue";
import { MinusSmallIcon, PlusSmallIcon } from "@heroicons/vue/24/outline";
import { Head } from "@inertiajs/vue3";
import SectionBorder from "@/Components/SectionBorder.vue";

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
            <div class="myPrimaryMainPageHeaderParagraph bg-purple-200">
                <h1 class="myPrimaryMainPageHeaderNotLoggedIn">
                    Turn Emails into Revenue
                </h1>
                <p class="myPrimaryMainPageParagraphNotLoggedIn">
                    Quisquam, laborum. Omnis voluptate vero sed culpa! Eius,
                    hic? Aliquid deserunt blanditiis animi suscipit minima ea
                    sed excepturi.
                </p>
            </div>
        </template>

        <!-- Faq - start -->
        <FullWidthElement :descriptionArea="true">
            <template #title>Frequently asked questions</template>
            <template #description>
                As new users join, they bring innovative ideas, unique skills,
                and diverse experiences that enrich our platform. Connect with
                them, explore their work, and celebrate the fresh energy they
                contribute to our collaborative space.
            </template>
            <template #content>
                <dl class="divide-y divide-myPrimaryMediumGrayColor">
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
                                <span class="myPrimaryParagraph font-semibold"
                                    >{{ faq.question }}
                                </span>
                                <span class="ml-6 flex h-7 items-center">
                                    <PlusSmallIcon
                                        v-if="!open"
                                        class="h-6 w-6"
                                        aria-hidden="true"
                                    />
                                    <MinusSmallIcon
                                        v-else
                                        class="h-6 w-6"
                                        aria-hidden="true"
                                    />
                                </span>
                            </DisclosureButton>
                        </dt>
                        <DisclosurePanel as="dd" class="mt-2 pr-12">
                            <p class="myPrimaryParagraph">
                                {{ faq.answer }}
                            </p>
                        </DisclosurePanel>
                    </Disclosure>
                </dl>
            </template>
        </FullWidthElement>
        <!-- Faq - end -->
        <SectionBorder></SectionBorder>
        <!-- Only Title and Content - start -->
        <DefaultElement :descriptionArea="false">
            <template #title>Only Title & Content</template>
            <template #content>
                <p class="myPrimaryParagraph">
                    Lorem ipsum dolor, sit amet consectetur adipisicing elit.
                    Illum in facilis labore id. Illum animi deleniti sed fuga
                    illo, commodi corporis est amet, ipsa doloremque quisquam
                    molestiae nemo porro cum expedita, voluptatum reiciendis.
                    Impedit vitae beatae laborum accusantium placeat ratione,
                    mollitia libero ipsum quae. Aspernatur laborum voluptas
                    voluptatibus maiores maxime nemo, mollitia nostrum enim,
                    consectetur eligendi debitis totam. A quisquam, iste
                    expedita voluptas molestiae quas debitis corporis quaerat
                    ullam optio vel officia magnam. Facilis eius repellat
                    perferendis placeat mollitia ratione odio illum. At quisquam
                    dolorem tempora debitis ullam corrupti rerum repellat, ipsum
                    laborum assumenda fugit placeat deserunt et voluptatibus
                    iusto?
                </p>
            </template>
        </DefaultElement>
        <!-- Only Title and Content - end -->
        <SectionBorder></SectionBorder>
        <!-- Only Title and Content - start -->
        <FullWidthElement :descriptionArea="false">
            <template #title>Only Title & Content</template>
            <template #content>
                <p class="myPrimaryParagraph">
                    Lorem ipsum dolor, sit amet consectetur adipisicing elit.
                    Illum in facilis labore id. Illum animi deleniti sed fuga
                    illo, commodi corporis est amet, ipsa doloremque quisquam
                    molestiae nemo porro cum expedita, voluptatum reiciendis.
                    Impedit vitae beatae laborum accusantium placeat ratione,
                    mollitia libero ipsum quae. Aspernatur laborum voluptas
                    voluptatibus maiores maxime nemo, mollitia nostrum enim,
                    consectetur eligendi debitis totam. A quisquam, iste
                    expedita voluptas molestiae quas debitis corporis quaerat
                    ullam optio vel officia magnam. Facilis eius repellat
                    perferendis placeat mollitia ratione odio illum. At quisquam
                    dolorem tempora debitis ullam corrupti rerum repellat, ipsum
                    laborum assumenda fugit placeat deserunt et voluptatibus
                    iusto?
                </p>
            </template>
        </FullWidthElement>
        <!-- Only Title and Content - end -->
        <SectionBorder></SectionBorder>
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
                        <h3 class="myPrimaryParagraph font-semibold mt-2">
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
                        <h3 class="myPrimaryParagraph font-semibold mt-2">
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
                        <h3 class="myPrimaryParagraph font-semibold mt-2">
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
                        <h3 class="myPrimaryParagraph font-semibold mt-2">
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
                        <h3 class="myPrimaryParagraph font-semibold mt-2">
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
                        <h3 class="myPrimaryParagraph font-semibold mt-2">
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
                        <h3 class="myPrimaryParagraph font-semibold mt-2">
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
                        <h3 class="myPrimaryParagraph font-semibold mt-2">
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
                        <h3 class="myPrimaryParagraph font-semibold mt-2">
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
                        <h3 class="myPrimaryParagraph font-semibold mt-2">
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
                        <h3 class="myPrimaryParagraph font-semibold mt-2">
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
                        <h3 class="myPrimaryParagraph font-semibold mt-2">
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
                        <h3 class="myPrimaryParagraph font-semibold mt-2">
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
                        <h3 class="myPrimaryParagraph font-semibold mt-2">
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
                        <h3 class="myPrimaryParagraph font-semibold mt-2">
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
        <SectionBorder></SectionBorder>
        <!-- Number 4 - start -->
        <FullWidthElement :descriptionArea="true" class="bg-gray-100">
            <template #title>Number four</template>
            <template #description>
                As new users join, they bring innovative ideas, unique skills,
                and diverse experiences that enrich our platform. Connect with
                them, explore their work, and celebrate the fresh energy they
                contribute to our collaborative space.
            </template>
            <template #content>
                <p class="myPrimaryParagraph">
                    Lorem ipsum, dolor sit amet consectetur adipisicing elit.
                    Corrupti suscipit adipisci mollitia odit dolore voluptate
                    voluptatibus eum libero explicabo, optio nostrum
                    perspiciatis enim tenetur? Nobis inventore minus provident
                    eveniet quidem natus quae possimus quam soluta, vitae,
                    reprehenderit delectus eaque ab iste, voluptas enim debitis
                    veritatis deserunt assumenda nisi.
                </p>
                <br />
                <p class="myPrimaryParagraph">
                    Qui, quidem, expedita itaque harum dolores enim autem
                    recusandae atque cumque accusamus delectus obcaecati
                    corporis in reiciendis cum. Perferendis cupiditate porro at
                    libero asperiores officiis explicabo? Sit dicta id ipsum
                    aliquam quasi. Dolor repudiandae minus laborum recusandae
                    quaerat nam sed debitis harum accusantium necessitatibus
                    neque omnis, ut impedit? Unde dolorum maxime asperiores.
                </p>
            </template>
        </FullWidthElement>
        <!-- Number 4 - end -->
    </GuestsLayout>
</template>
