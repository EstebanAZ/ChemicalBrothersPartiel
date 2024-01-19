// import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
// import './styles/app.css';

import $ from 'jquery';

require('bootstrap');

$(document).ready(function(){
    $(".mark-as-read").click(function(e){
        console.log('click');
        e.preventDefault();
        var notificationId = $(this).data('id');
        $.ajax({
            url: '/api/notification/'+ notificationId +'/read', // Remplacez par l'URL de votre API
            type: 'POST',
            success: function(result){
                // Supprimez l'élément de la page
                $('#notification-' + notificationId).remove();

                // Décrémentez le nombre de notifications non lues
                var unreadCount = $('#unread-count').text();
                unreadCount = parseInt(unreadCount) - 1;
                $('#unread-count').text(unreadCount);
            }
        });
    });
});