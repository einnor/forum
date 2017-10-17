<template>
    <div :id="'reply-'+id" class="panel" :class="isBest ? 'panel-success' : 'panel-default'">
        <div class="panel-heading">
            <div class="level">
                <h5 class="flex">
                    <a :href="'/profiles/'+ data.owner.name" v-text="data.owner.name"></a> said <span v-text="ago"></span>
                </h5>
                <div>
                    <favorite :reply="data"></favorite>
                </div>
            </div>

        </div>

        <div class="panel-body">
            <div v-if="editing">
                <form @submit="update">
                    <div class="form-group">
                        <textarea class="form-control" v-model="body" required></textarea>
                    </div>

                    <button class="btn-xs btn-primary" type="submit">Update</button>
                    <button class="btn-xs btn-link" @click="editing = false" type="button">Cancel</button>
                </form>

            </div>
            <div v-else v-html="body"></div>
        </div>

        <div class="panel-footer level">
            <div v-if="canUpdate">
                <button class="btn btn-xs mr-1" @click="editing = true">Edit</button>
                <button class="btn btn-danger btn-xs mr-1" @click="destroy">Delete</button>
            </div>
            <button class="btn btn-default btn-xs ml-a" @click="markBestReply" v-show="! isBest">Best Reply?</button>
        </div>
    </div>
</template>

<script>

    import Favorite from './Favorite.vue';

    import moment from 'moment'

    export default {

        props: ['data'],

        components: { Favorite },

        data() {
            return {
                id: this.data.id,
                editing: false,
                body: this.data.body,
                isBest: false
            }
        },

        computed: {
            signedIn() {
                return window.App.signedIn;
            },

            ago() {
                return moment(this.data.created_at).fromNow() + '...';
            },

            canUpdate() {
                return this.authorize(user => this.data.user_id == user.id);
            }
        },

        methods: {
            update: function() {
                axios.patch('/replies/' + this.data.id, {
                        body: this.body
                    })
                    .then()
                    .catch(error => {
                        flash(error.response.data, 'danger');
                    });

                this.editing = false;

                flash('Updated!');
            },

            destroy: function() {
                axios.delete('/replies/' + this.data.id);

                this.$emit('deleted', this.data.id);
            },

            markBestReply: function() {
                this.isBest = true;
            }
        }
    }
</script>
