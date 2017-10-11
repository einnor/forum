<template>
    <div class="alert alert-flash"
         :class="'alert-'+level"
         role="alert"
         v-show="show"
         v-text="body"
    ></div>
</template>

<script>
    export default {
        props: ['message'],

        data() {
            return {
                body: this.message,
                level: 'success',
                show: false
            }
        },

        created() {
            if(this.message) {
                this.flash();
            }

            window.events.$on('flash', data => {
                this.flash(data);
            });
        },

        methods: {
            flash: function(data) {
                if(data) {
                    this.body = data.message;
                    this.level = data.level;
                }

                this.show = true;

                this.hide();
            },

            hide: function() {
                setTimeout(() => {
                    this.show = false;
                }, 3000);
            }
        }
    }
</script>

<style>
    .alert-flash {
        position: fixed;
        right: 25px;
        bottom: 25px;
    }
</style>
