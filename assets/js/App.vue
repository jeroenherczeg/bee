<template>
    <div id="app">
        <h1>header</h1>
        <router-view></router-view>
    </div>
</template>

<script>
    export default {
        ready () {
            console.log('Ready to kick ass!');
        }
    }
</script>

<style>

</style>
