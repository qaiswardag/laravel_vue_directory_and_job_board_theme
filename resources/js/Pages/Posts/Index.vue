<script setup>
import LoggedInLayout from "@/Layouts/LoggedInLayout.vue";
import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";
</script>

<template>
    <LoggedInLayout title="Manage Posts">
        <template #header>
            <h2 class="font-semibold text-xl leading-tight">Manage Posts</h2>
        </template>

        <div>
            <h1 class="myPrimayHeader">Create your first Post</h1>
            <p class="myPrimaryParagraph">
                Post List and Manage your posts here!
            </p>

            <p class="myPrimaryParagraph">Create a Post</p>

            <Link
                class="myPrimaryButton"
                type="button"
                :href="route('posts.create')"
            >
                Create a Post
            </Link>
        </div>
    </LoggedInLayout>
</template>
