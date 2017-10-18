let user = window.App.user;

let authorizations = {
    updateReply: function (reply) {
        return reply.user_id === user.id;
    }
};

module.exports = authorizations;