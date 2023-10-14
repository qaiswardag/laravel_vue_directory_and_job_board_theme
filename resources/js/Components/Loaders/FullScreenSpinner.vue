<template>
    <teleport to="body">
        <div
            class="bg-red-400 flex justify-center items-center h-screen w-screen fixed inset-0 z-40"
        >
            <div
                class="inline-block h-10 w-10 animate-spin rounded-full border-4 border-solid border-current border-r-transparent"
                role="status"
            ></div>
        </div>
    </teleport>
</template>
