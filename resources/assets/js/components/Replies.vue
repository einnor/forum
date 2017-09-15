<template>
    <div>
        <div v-for="(reply, index) in items">
            <reply :data="reply" @deleted="remove(index)"></reply>
        </div>

        <new-reply :endpoint="endpoint" @created="add"></new-reply>
    </div>
</template>

<script>

    import Reply from './Reply.vue';
    import NewReply from './NewReply.vue';

    export default {
        components: { Reply, NewReply },

        props: ['data'],


        data() {
            return {
                items: this.data,
                endpoint: location.pathname + '/replies',
            }
        },

        methods: {
            add: function(reply) {
                this.items.push(reply);

                this.$emit('added');
            },
            remove: function(index) {
                this.items.splice(index, 1);

                this.$emit('removed');

                flash('Reply was deleted!');
            },
        }
    }
</script>
