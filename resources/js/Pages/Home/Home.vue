<script setup>
import GuestsLayout from "@/Layouts/GuestsLayout.vue";
import { AdjustmentsHorizontalIcon } from "@heroicons/vue/24/outline";

defineProps({
    test: {
        type: Boolean,
    },
});
</script>

<template>
    <GuestsLayout title="Home">
        <template #header>
            <h1 class="myPrimaryMainPageHeaderNotLoggedIn">
                Turn Emails into Revenue
            </h1>
            <p class="myPrimaryMainPageParagraphNotLoggedIn">
                Win new customers with the #1 email marketing and automations
                brand* that recommends ways to get more opens, clicks, and
                sales.
            </p>
        </template>

        <!-- people start -->
        <div class="bg-white py-24 sm:py-32">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto max-w-2xl lg:mx-0">
                    <h2 class="myTertiaryHeader">Our team</h2>
                    <p class="myPrimaryParagraph">
                        We’re a dynamic group of individuals who are passionate
                        about what we do and dedicated to delivering the best
                        results for our clients.
                    </p>
                </div>
                <ul
                    role="list"
                    class="mx-auto mt-20 grid max-w-2xl grid-cols-2 gap-x-8 gap-y-16 text-center sm:grid-cols-3 md:grid-cols-4 lg:mx-0 lg:max-w-none lg:grid-cols-5 xl:grid-cols-6"
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
            </div>
        </div>
        <!-- people end -->
    </GuestsLayout>
</template>
