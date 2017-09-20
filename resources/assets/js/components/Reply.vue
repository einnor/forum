<template>
    <div :id="'reply-'+id" class="panel panel-default">
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
                <div class="form-group">
                    <textarea class="form-control" v-model="body"></textarea>
                </div>

                <button class="btn-xs btn-primary" @click="update">Update</button>
                <button class="btn-xs btn-link" @click="editing = false">Cancel</button>
            </div>
            <div v-else v-text="body"></div>
        </div>

        <div class="panel-footer level" v-if="canUpdate">
            <button class="btn btn-xs mr-1" @click="editing = true">Edit</button>
            <button class="btn btn-danger btn-xs mr-1" @click="destroy">Delete</button>
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
                });

                this.editing = false;

                flash('Updated!');
            },

            destroy: function() {
                axios.delete('/replies/' + this.data.id);

                this.$emit('deleted', this.data.id);
            }
        }
    }
</script>
