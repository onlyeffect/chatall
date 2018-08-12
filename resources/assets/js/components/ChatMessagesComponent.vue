<template>
    <div class="message-area" ref="message">
        <message-component 
            v-for="message in messages" 
            :key="message.id" 
            :message="message">
        </message-component>
    </div>
</template>

<script>
    import Event from '../event.js';

    export default {
        data() {
            return {
                messages: []
            }
        },
        methods: {
            chatScrollBottom: function(){
                this.$refs.message.scrollTop = this.$refs.message.scrollHeight;
            },
        },
        mounted() {
            axios.get('/messages').then((response) => {
                this.messages = response.data;
                setTimeout(this.chatScrollBottom, 0);
            });
            Event.$on('added_message', (message) => {
                this.messages.push(message);
                setTimeout(this.chatScrollBottom, 0);
            });
        }
    }
</script>

<style>
    .message-area {
        height: 400px;
        max-height: 400px;
        overflow-y: scroll;
        padding: 15px;
        border-bottom: 1px solid #eee;
    }
</style>