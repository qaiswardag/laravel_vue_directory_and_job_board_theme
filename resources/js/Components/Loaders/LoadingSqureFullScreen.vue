<template>
    <teleport to="body">
        <div
            class="bg-white flex justify-center items-center h-screen w-screen fixed inset-0 z-40"
        >
            <div class="absolute top-1/3">
                <button>
                    Loading..
                    <svg>
                        <rect x="1" y="1"></rect>
                    </svg>
                </button>
            </div>
        </div>
    </teleport>
</template>

<style scoped>
button {
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
    color: #333333;
    border: none;
    background: transparent;
    font-size: 1rem;
    line-height: 1.5rem;
    padding: 1rem 1.4rem;
}

button > svg {
    position: absolute;
    width: 100%;
    height: 100%;
}

button > svg > rect {
    fill: none;
    stroke: #333333;
    stroke-width: 2px;
    stroke-dasharray: 240 160 240 160;
    stroke-dashoffset: 0;
    animation: pathRect 1.2s linear infinite;
    width: calc(100% - 2px);
    height: calc(100% - 2px);
}

@keyframes pathRect {
    25% {
        stroke-dashoffset: 100;
    }

    50% {
        stroke-dashoffset: 200;
    }

    75% {
        stroke-dashoffset: 300;
    }

    100% {
        stroke-dashoffset: 400;
    }
}
</style>
