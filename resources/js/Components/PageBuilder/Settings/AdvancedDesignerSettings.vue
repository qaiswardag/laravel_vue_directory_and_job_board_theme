<script setup>
import { ref, computed, watch, onMounted } from "vue";
import { useStore } from "vuex";

const store = useStore();

const reactiveGetElement = ref(null);

const getElement = computed(() => {
    return store.getters["pageBuilderState/getElement"];
});

const getComponent = computed(() => {
    return store.getters["pageBuilderState/getComponent"];
});
const getComponents = computed(() => {
    return store.getters["pageBuilderState/getComponents"];
});

const current = ref("element");

const getElementOuterHTML = computed(() => {
    if (getElement.value === null) return;
    return getElement.value.outerHTML ? getElement.value.outerHTML : null;
});

watch(getElementOuterHTML, (newComponent) => {
    console.log("watch ran 2");
    reactiveGetElement.value = newComponent;
});

const updateCurrentTab = function (tab) {
    current.value = tab;
};
</script>

<template>
    <div class="my-8">
        <div
            class="flex items-left flex-col myPrimaryGap border-myPrimaryMediumGrayColor"
        >
            <h4 class="myFourthHeader">Selected HTML</h4>
            <p class="myPrimaryParagraph text-xs">
                Overview of Selected Element, Component, and Components. This
                section provides real-time updates based on your HTML selection.
            </p>

            <!-- Types - start -->
            <div>
                <p class="my-4">er: {{ JSON.stringify(getElement) }}</p>
                <h4 class="myPrimaryParagraph text-sm pb-2">Types</h4>
                <div class="text-white overflow-hidden bg-gray-900 max-w-2xl">
                    <div class="flex bg-gray-800/40 ring-1 ring-white/5">
                        <div
                            class="-mb-px flex text-xs font-medium text-myPrimaryMediumGrayColor"
                        >
                            <div class="px-4 py-4 text-white">Types</div>
                        </div>
                    </div>
                    <div class="px-4 pb-8 pt-4 text-white text-xs">
                        <p
                            v-if="
                                getElement === null || getElement === undefined
                            "
                            class="text-xs pb-2"
                        >
                            <span
                                >Element type:
                                {{ JSON.stringify(getElement) }}
                            </span>
                        </p>
                        <p
                            v-if="
                                getElement !== null && getElement !== undefined
                            "
                            class="text-xs pb-2"
                        >
                            <span>Element type: </span>
                            <span>
                                {{ typeof getElement }}
                            </span>
                        </p>
                        <p
                            v-if="
                                getComponent === null ||
                                getComponent === undefined
                            "
                            class="text-xs pb-2"
                        >
                            <span>Component type: </span>
                            {{ JSON.stringify(getComponent) }}
                        </p>
                        <p
                            v-if="
                                getComponent !== null &&
                                getComponent !== undefined
                            "
                            class="text-xs pb-2"
                        >
                            <span>Component type: </span>
                            <span>
                                {{
                                    Array.isArray(getComponent) === true
                                        ? "array"
                                        : typeof getComponent
                                }}
                            </span>
                        </p>
                        <p class="text-xs pb-2">
                            <span>Components: </span>
                            <span>
                                {{
                                    Array.isArray(getComponents) === true
                                        ? "array"
                                        : typeof getComponents
                                }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
            <!-- Types - end -->
            <!-- Code Block Component - start-->
            <div>
                <h4 class="myPrimaryParagraph text-sm pb-2">Content</h4>
                <div class="overflow-hidden bg-gray-900 max-w-2xl">
                    <div class="flex bg-gray-800/40 ring-1 ring-white/5">
                        <div
                            class="-mb-px flex text-xs font-medium text-myPrimaryMediumGrayColor"
                        >
                            <div
                                @click="updateCurrentTab('element')"
                                class="px-4 py-4 cursor-pointer"
                                :class="[
                                    current === 'element' ? 'text-white' : '',
                                ]"
                            >
                                Element
                            </div>
                            <div
                                @click="updateCurrentTab('component')"
                                class="px-4 py-4 cursor-pointer"
                                :class="[
                                    current === 'component' ? 'text-white' : '',
                                ]"
                            >
                                Component
                            </div>
                            <div
                                @click="updateCurrentTab('components')"
                                class="px-4 py-4 cursor-pointer"
                                :class="[
                                    current === 'components'
                                        ? 'text-white'
                                        : '',
                                ]"
                            >
                                Components
                                {{
                                    Array.isArray(getComponents) &&
                                    getComponents.length
                                }}
                            </div>
                        </div>
                    </div>
                    <div class="px-4 pb-8 pt-4 text-white text-xs break-all">
                        <div v-if="current === 'element'">
                            <div v-if="!reactiveGetElement">
                                <p class="pb-2">
                                    {{
                                        reactiveGetElement === null
                                            ? "NULL"
                                            : typeof reactiveGetElement
                                    }}
                                </p>
                            </div>
                            <div v-if="reactiveGetElement">
                                <p class="whitespace-pre-line leading-5">
                                    {{ reactiveGetElement }}
                                </p>
                            </div>
                        </div>
                        <div v-if="current === 'component'">
                            <div v-if="!getComponent">
                                <p class="pb-2">
                                    {{
                                        getComponent === null
                                            ? "NULL"
                                            : typeof getComponent
                                    }}
                                </p>
                            </div>
                            <div v-if="getComponent">
                                <p class="pb-2">
                                    Component ID:
                                    {{ getComponent?.id }}
                                </p>
                                <p class="whitespace-pre-line leading-5 mt-4">
                                    Component HTML:<br />
                                    {{ getComponent?.html_code }}
                                </p>
                            </div>
                        </div>
                        <div v-if="current === 'components'">
                            <div v-if="!getComponents">
                                <p class="pb-2">
                                    {{
                                        getComponents === null
                                            ? "NULL"
                                            : typeof getComponents
                                    }}
                                </p>
                            </div>
                            <div v-if="getComponents">
                                <div
                                    class="border-b border-white mb-4 pb-4 last:border-none"
                                    v-for="component in getComponents"
                                    :key="component.id"
                                >
                                    <p class="pb-2">
                                        Component ID:
                                        {{ component.id }}
                                    </p>

                                    <p
                                        class="whitespace-pre-line leading-5 mt-4"
                                    >
                                        Component HTML:<br />
                                        {{ component.html_code }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Code Block Component - end-->
        </div>
    </div>
</template>
