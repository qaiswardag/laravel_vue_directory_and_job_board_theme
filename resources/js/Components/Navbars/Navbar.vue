<script setup>
import GuestsNavbar from "./GuestsNavbar.vue";
import LoggedInNavbar from "./LoggedInNavbar.vue";
</script>

<template>
    <template v-if="$page.props.user">
        <LoggedInNavbar></LoggedInNavbar>
    </template>
    <template v-if="!$page.props.user">
        <GuestsNavbar></GuestsNavbar>
    </template>
</template>
