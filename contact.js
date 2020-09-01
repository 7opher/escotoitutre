		/* Validation of the form and its sending
         * Validation du formulaire et son envoi 
         
         1 - Lorsque le formulaire avec l'id #contact-form est soumis, nous faisons la demande POST au script contact.php.
         2 - En cas de succès de la demande, nous travaillons avec l' objet JSON qui est renvoyé par le script PHP. L'objet n'a que deux propriétés - type et message
         3 - Nous utilisons type et message pour construire le message visible pour l'utilisateur - en cas d'erreur, nous affichons alert-danger, en cas de succès, nous affichonsalert-success
         4 - Nous affichons le message , réinitialisons les entrées de formulaire et return false;empêchons que le formulaire habituel soit envoyé.
         */
		
$(function () {

    // [En] init the validator
    // [Fr] init le validateur
    

    $('#contact-form').validator();


    // [En] when the form is submitted
    // [Fr] quand le formulaire est soumis
    $('#contact-form').on('submit', function (e) {

        // [En] if the validator does not prevent form submit
        // [Fr] si le validateur n'empêche pas la soumission du formulaire
        if (!e.isDefaultPrevented()) {
            var url = "contact.php";

            // [En] POST values in the background the the script URL
            // [Fr] POST les valeurs en arrière-plan du script URL
            $.ajax({
                type: "POST",
                url: url,
                data: $(this).serialize(),
                success: function (data)
                {
                    // [En] data = JSON object that contact.php returns
                    // [Fr] data = objet JSON renvoyé par contact.php

                    // [En] we recieve the type of the message: success x danger and apply it to the 
                    // [Fr] nous recevons le type du message: success x danger and apply it to the
                    var messageAlert = 'alert-' + data.type;
                    var messageText = data.message;

                    // [En] let's compose Bootstrap alert box HTML
                    // [Fr] composons la boîte d'alerte Bootstrap HTML
                    var alertBox = '<div class="alert ' + messageAlert + ' alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + messageText + '</div>';
                    
                    // [En] If we have messageAlert and messageText
                    // [Fr] Si nous avons messageAlert et messageText
                    if (messageAlert && messageText) {
                        // [En] inject the alert to .messages div in our form
                        // [Fr] injecter l'alerte à .messages div dans notre formulaire
                        $('#contact-form').find('.messages').html(alertBox);
                        // [En] empty the form
                        // [Fr] vider le formulaire
                        $('#contact-form')[0].reset();
                    }
                }
            });
            return false;
        }
    })
});