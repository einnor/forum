<template>
    <button :class="classes" @click.prevent="subscribe">Subscribe</button>
</template>

<script>

    export default {

        props: ['active'],

        computed: {
            classes() {
                return ['btn', this.active ? 'btn-primary' : 'btn-default'];
            }
        },

        methods: {
            subscribe: function() {
                axios[(this.active ? 'delete' : 'post')](location.pathname + '/subscriptions')
                    .then((response) => {

                        this.active = ! this.active;

                        flash('Subscribed!');
                    });
            }
        }
    }
</script>
