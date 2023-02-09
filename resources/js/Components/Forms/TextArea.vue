<script setup>
import { onMounted, ref } from "vue";

defineProps({
    modelValue: String,
});

defineEmits(["update:modelValue"]);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute("autofocus")) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <textarea
        ref="input"
        class="myPrimaryTextArea mt-1"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        placeholder="Write your thoughts here..."
    />
</template>
