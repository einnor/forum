export default {

    data() {
        return {
            items: []
        }
    },

    methods: {
        add: function(item) {
            this.items.push(item);

            this.$emit('added');
        },
        remove: function(index) {
            this.items.splice(index, 1);

            this.$emit('removed');

            flash('Reply was deleted!');
        },
    }
}