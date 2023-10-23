<script setup>
import { useStore } from "vuex";
import { vueFetch } from "use-lightweight-fetch";
import SmallUniversalSpinner from "@/Components/Loaders/SmallUniversalSpinner.vue";
import { onMounted } from "vue";

const props = defineProps({
    pathList: {
        required: true,
    },
});

const store = useStore();

// get images
const {
    handleData: handleGetList,
    fetchedData: fetchedDataList,
    isError: isErrorList,
    error: errorList,
    errors: errorsList,
    isLoading: isLoadingList,
    isSuccess: isSuccessList,
} = vueFetch();

const getList = function () {
    handleGetList(route(props.pathList));
};

onMounted(() => {
    getList();
});
</script>

<template>
    <div>
        <!-- Loading # start -->
        <template v-if="isLoadingList">
            <SmallUniversalSpinner
                width="w-8"
                height="h-8"
                border="border-4"
            ></SmallUniversalSpinner>
        </template>
        <!-- Loading # end -->

        <div class="mt-20">
            <template v-if="!isLoadingList && !isErrorList && isSuccessList">
                <p>List er: {{ JSON.stringify(fetchedDataList) }}</p>
            </template>
        </div>
    </div>
</template>
