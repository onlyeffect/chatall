<template>
    <div class="col-sm-8">
        <div class="panel panel-default">
            <div class="panel-heading">Chat</div>
            <div class="panel-body">
                <chat-messages-component></chat-messages-component>
                <chat-form-component></chat-form-component>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
