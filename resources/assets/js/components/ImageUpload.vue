<template>
    <input type="file" accept="image/*" @change="onChange">
</template>

<script>

    export default {

        props: ['user'],

        data() {
            return {
                user: this.user,
                avatar: this.user.avatar_path,
            }
        },

        computed: {
            canUpdate() {
                return this.authorize(user => user.id === this.user.id);
            }
        },

        methods: {
            onChange: function(e) {
                if(! e.target.files.length) return;

                let file = e.target.files[0];

                let reader = new FileReader();

                reader.readAsDataURL(file);

                reader.onload = e => {
                    let src = e.target.result;

                    this.$emit('loaded', { src, file });
                };
            },
        }
    }
</script>
