/* global Function */

var Login = {

    $form : $('form'),

    $username : $('input[type=text]'),

    $password : $('input[type=password]'),

    $button : $('form button'),

    load: function() {

        Login.initUI();

    },

    initUI: function() {

        Login.$form.submit(function(e) {

            e.preventDefault();

            let values = {

                username: Login.$username.val(),

                password: Login.$password.val()

            };

            let interval = Function.interval('start', Login.$button);

            $.post('/login/login', values,function(data) {

                Function.interval('stop', Login.$button, interval, 'Giriş Yap');

                Function.info(data.result, data.message, Login.$button, '/');

            }, 'JSON');

        });

    }

};

/*
 * Initialling
 */
$(function() {

    Login.load();

});