<template>
    <div class="message" v-bind:class="{ self: message.selfMessage }">
        <strong class="user">{{ message.user.name }}</strong>
        <p class="body">{{ message.body }}</p>
    </div>
</template>

<script>
    export default {
        props: ['message']
    }
</script>

<style>
    .user {
        font-weight: 800;
    }
    .body {
        margin-bottom: 0;
        white-space: pre-wrap;
    }
    .message {
        margin-bottom: 10px;
        padding: 10px;
        border: rgb(226, 226, 226) 0.5px solid;
    }
    .self {
        background-color: #f0f0f0;
    }
</style>