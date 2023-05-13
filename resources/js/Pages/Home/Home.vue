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
            <div>
                <h1 class="myPrimaryMainPageHeaderNotLoggedIn">
                    Turn Emails into Revenue
                </h1>
                <p class="myPrimaryMainPageParagraphNotLoggedIn">
                    Win new customers with the #1 email marketing and
                    automations brand* that recommends ways to get more opens,
                    clicks, and sales.
                </p>
            </div>
        </template>

        <!-- chimp start -->
        <div class="myPrimarySectionGuests w-screen bg-gray-100">
            <h2 class="myTertiaryHeader">Latest Users</h2>
            <p class="myPrimaryParagraph">
                As new users join, they bring innovative ideas, unique skills,
                and diverse experiences that enrich our platform. Connect with
                them, explore their work, and celebrate the fresh energy they
                contribute to our collaborative space.
            </p>
            <div
                class="myPrimarySectionGuests flex md:flex-row flex-col w-screen justify-center"
            >
                <div>
                    <img
                        class="mx-auto h-96 w-full"
                        src="https://eep.io/images/yzco4xsimv0y/33f3mgIWbzKjIpIRJi2f5v/6bd36bba38b7f6a94d587605ab9a7d75/Desktop_Feature_Cards_1_Automations.jpeg?w=630&fm=webp&q=70"
                        alt=""
                    />
                </div>
                <div>
                    <img
                        class="mx-auto h-96 w-full"
                        src="https://eep.io/images/yzco4xsimv0y/33f3mgIWbzKjIpIRJi2f5v/6bd36bba38b7f6a94d587605ab9a7d75/Desktop_Feature_Cards_1_Automations.jpeg?w=630&fm=webp&q=70"
                        alt=""
                    />
                </div>
                <div>
                    <img
                        class="mx-auto h-96 w-full"
                        src="https://eep.io/images/yzco4xsimv0y/33f3mgIWbzKjIpIRJi2f5v/6bd36bba38b7f6a94d587605ab9a7d75/Desktop_Feature_Cards_1_Automations.jpeg?w=630&fm=webp&q=70"
                        alt=""
                    />
                </div>
                <div>
                    <img
                        class="mx-auto h-96 w-full"
                        src="https://eep.io/images/yzco4xsimv0y/33f3mgIWbzKjIpIRJi2f5v/6bd36bba38b7f6a94d587605ab9a7d75/Desktop_Feature_Cards_1_Automations.jpeg?w=630&fm=webp&q=70"
                        alt=""
                    />
                </div>
            </div>
        </div>
        <!-- chimp end -->

        <!-- people start -->
        <div
            class="myPrimarySectionGuests myPrimarySectionGuests w-screen bg-red-50"
        >
            <h2 class="myTertiaryHeader">Latest Users</h2>
            <p class="myPrimaryParagraph">
                As new users join, they bring innovative ideas, unique skills,
                and diverse experiences that enrich our platform. Connect with
                them, explore their work, and celebrate the fresh energy they
                contribute to our collaborative space.
            </p>
            <ul
                role="list"
                class="myPrimarySectionGuests mx-auto grid max-w-2xl grid-cols-2 gap-x-8 gap-y-16 text-center sm:grid-cols-3 md:grid-cols-4 lg:mx-0 lg:max-w-none lg:grid-cols-5 xl:grid-cols-6"
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
                    <p class="myPrimaryParagraph mt-2">Business Relations</p>
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
                    <p class="myPrimaryParagraph mt-2">Front-end Developer</p>
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
                    <p class="myPrimaryParagraph mt-2">Director of Product</p>
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
                    <p class="myPrimaryParagraph mt-2">Principal Designer</p>
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
                    <p class="myPrimaryParagraph mt-2">VP, User Experience</p>
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
                    <p class="myPrimaryParagraph mt-2">VP, Human Resources</p>
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
        <!-- people end -->
    </GuestsLayout>
</template>
