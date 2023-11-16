<script setup>
import { onMounted, ref } from "vue";

const chart = ref(null);

onMounted(() => {
    chart.value.outerHTML = "<p>Hello world. Display Charts here.</p>";
});
</script>

<template>
    <div ref="chart"></div>
</template>

<style scoped>
#chart {
    padding: 20px;
}

table {
    width: 100%;
    height: 400px;
}

.charttitle {
    text-align: center;
}

.bars td {
    vertical-align: bottom;
}

.bars div:hover {
    opacity: 0.6;
}

.legend {
    vertical-align: bottom;
    padding-left: 20px;
    text-align: left;
}

.legbox {
    display: block;
    clear: both;
}

.xaxisname {
    margin: 5px;
    color: #fff;
    font-size: 77%;
    padding: 5px;
    float: left;
}

/*Flat UI colors*/

.one {
    background: #16a085;
}

.two {
    background: #2ecc71;
}

.three {
    background: #27ae60;
}

.four {
    background: #3498db;
}

.five {
    background: #2980b9;
}

.six {
    background: #9b59b6;
}

.seven {
    background: #8e44ad;
}

.eight {
    background: #34495e;
}

.nine {
    background: #2c3e50;
}

.ten {
    background: #22313f;
}

.eleven {
    background: #f1c40f;
}

.twelve {
    background: #f39c12;
}

.thirteen {
    background: #e67e22;
}

.fourteen {
    background: #d35400;
}

.fifteen {
    background: #e74c3c;
}

.sixteen {
    background: #c0392b;
}

.seventeen {
    background: #ecf0f1;
}

.seventeen.clouds {
    color: #bdc3c7;
}

.eighteen {
    background: #bdc3c7;
}

.nineteen {
    background: #95a5a6;
}

.twenty {
    background: #7f8c8d;
}
</style>
