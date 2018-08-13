<template>
    <form class="form">
        <div v-if="userIsTyping" style="padding-bottom:6px">
            <span style="font-weight: 800;">{{ userIsTyping.name }}</span> is typing...
        </div>
        <div v-else style="padding: 13px;"></div>
        <textarea
            id="body"
            cols="28"
            rows="5"
            class="form-input"
            @keydown="typing"
            v-model="body"
            :placeholder=placeholder
            :disabled="disableTrigger === true"
        >
        </textarea>
        <span class="notice">
            Hit Enter to send a message
        </span>
    </form>
</template>

<script>

    import Event from '../event.js';

    export default {
        data() {
            return {
                body: null,
                placeholder: null,
                disableTrigger: false,
                userIsTyping: false,
                typingTimer: false,
            }
        },
        computed: {
            channel() {
                return window.Echo.private('chat');
            }
        },
        mounted(){
            setTimeout(this.checkUser, 0);

            this.channel.listenForWhisper('user_typing', (e) => {
                this.userIsTyping = e;

                if(this.typingTimer)
                    clearTimeout(this.typingTimer);

                this.typingTimer = setTimeout(() => {
                    this.userIsTyping = false;
                }, 800);
            });
        },
        methods: {
            checkUser() {
                if(! Laravel.user.authenticated) {
                    this.placeholder = "Please login to use chat.";
                    this.disableTrigger = true;
                }
            },
            typing(e) {
                if(e.keyCode === 13 && !e.shiftKey) {
                    e.preventDefault();
                    this.sendMessage();
                }else {
                    this.channel.whisper('user_typing', {
                        name: Laravel.user.name
                    });
                }
            },
            sendMessage() {
                if(!this.body || this.body.trim() === '') {
                    return;
                }
                let messageObj = this.buildMessage();
                Event.$emit('added_message', messageObj);
                axios.post('/message', {
                    body: this.body.trim()
                }).catch(() => {
                    console.log('failed');
                });
                this.body = null;
            },
            buildMessage() {
                return {
                    id: Date.now(),
                    body: this.body.trim(),
                    selfMessage: true,
                    user: {
                        name: Laravel.user.name
                    }
                }
            }
        }
    }
</script>

<style>
    .form {
        padding: 8px;
    }
    .form-input {
        width: 100%;
        border: 1px solid #d3e0e9;
        padding: 5px 10px;
        outline: none;
        resize: none;
    }
    .notice {
        color: #aaa
    }
</style>