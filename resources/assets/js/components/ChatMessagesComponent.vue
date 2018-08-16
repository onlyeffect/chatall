<template>
    <div class="message-area" ref="message" @scroll="scrollLoading">
        <img v-if="lastPage > 0" class="img-responsive center-block" src="/storage/loading.gif" alt="loading">
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
                messages: [],
                lastPage: null,
            }
        },
        methods: {
            chatScrollBottom: function(){
                this.$refs.message.scrollTop = this.$refs.message.scrollHeight;
            },
            getLastPage: function(){
                axios.get(`/messages`).then((response) => {
                    this.lastPage = response.data.last_page;
                    this.loadMessages(this.lastPage, true);
                });
            },
            loadMessages: function(page, scrollBottom = false){
                if(page > 0){
                    axios.get(`/messages?page=${page}`).then((response) => {
                        this.messages = response.data.data.concat(this.messages);
                        this.lastPage--;
                        let messagesOnPage = response.data.to - response.data.from + 1;
                        if(messagesOnPage < 6){
                            this.loadMessages(page - 1, true);
                        }
                        if(scrollBottom === true){
                            setTimeout(this.chatScrollBottom, 0);
                        }
                    });
                }
            },
            scrollLoading: function(){
                if(this.$refs.message.scrollTop === 0){
                    this.loadMessages(this.lastPage);
                }
            },
        },
        mounted() {
            this.getLastPage();
            Event.$on('added_message', (message) => {
                this.messages.push(message);
                if(message.selfMessage === true){
                    setTimeout(this.chatScrollBottom, 0);
                }
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
    .img-responsive {
        margin-top:-20px;
        margin-bottom:5px;
        height: 55px;
    }
</style>