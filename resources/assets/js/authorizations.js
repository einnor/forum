let user = window.App.user;

let authorizations = {
    owns: function (model, prop = 'user_id') {
        return model[prop] === user.id;
    },

    isAdmin: function() {
        return ['JohnDoe', 'RonnieNyaga'].includes(user.name);
    }
};

module.exports = authorizations;