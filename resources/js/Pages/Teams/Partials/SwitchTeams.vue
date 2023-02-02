<script setup>
import ActionSection from "@/Components/ActionSection.vue";
import DropdownLink from "../../../Components/Dropdowns/DropdownLink.vue";
import NavLink from "../../../Components/Navbars/NavLink.vue";
import { router } from "@inertiajs/vue3";

//
// router.put(
//     route("current-team.update"),
//     {
//         team_id: team.id,
//     },
//     {
//         preserveState: false,
//     }
// );
//
//
//
//
const handleSwitchToTeam = (team) => {
    router.put(
        route("current-team.update"),
        {
            team_id: team.id,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                console.log("team switched");
            },
        }
    );
    //
    //
    //
    router.put(
        route("current-team.update"),
        {
            team_id: team.id,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                console.log("team switched");
            },
        }
    );
};
//
//
//
//
</script>

<template>
    <div>
        <ActionSection>
            <template #title> Switch team </template>

            <template #description> Switch team lorem ipsum</template>

            <template #content>
                <div class="max-w-xl text-sm text-gray-600">
                    Once a team is deleted, all of its resources and data will
                    be permanently deleted. Before deleting this team, please
                    download any data or information regarding this team that
                    you wish to retain.
                </div>
                <template
                    v-for="team in $page.props.user.all_teams"
                    :key="team.id"
                >
                    <form @submit.prevent="handleSwitchToTeam(team)">
                        <button
                            class="bg-gray-100 py-2 px-2 my-2 w-full rounded hover:bg-emerald-500 hover:text-white"
                            :class="{
                                'bg-emerald-500 text-white':
                                    team.id == $page.props.user.current_team_id,
                            }"
                        >
                            <div
                                v-if="$page.props.user.all_teams.length > 0"
                                class="flex items-center"
                            >
                                <svg
                                    v-if="
                                        team.id ===
                                        $page.props.user.current_team_id
                                    "
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    class="mr-2 h-5 w-5 text-white"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M4.5 12.75l6 6 9-13.5"
                                    />
                                </svg>

                                <svg
                                    v-if="
                                        team.id !==
                                        $page.props.user.current_team_id
                                    "
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    class="mr-2 h-5 w-5"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M6.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM12.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM18.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"
                                    />
                                </svg>
                                {{ team.name }} — team id: {{ team.id }}
                            </div>
                        </button>
                    </form>
                </template>
            </template>
        </ActionSection>
    </div>
</template>
