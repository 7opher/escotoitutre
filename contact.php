<?php
/*  
 *  Espace de configuration
 */

// [En] e-mail address that will be in the From field of the e-mail.
// [Fr] adresse e-mail qui sera dans le champ De de l'e-mail.
$from = '<contact@escotoiture.fr>';

// [En] an email address that will receive the email with the output of the form
// [Fr] une adresse email qui va recevoir l'email avec la sortie du formulaire
$sendTo = '<contact@escotoiture.fr>';

// [En] subject of the email
// [Fr] sujet de l'email
$subject = 'Nouveau message du formulaire de contact';

// [En] form field names and their translations.
// [En] array variable name => Text to appear in the email
// [Fr] les noms de champs de formulaire et leurs traductions.
// [Fr] nom de variable du tableau => Texte à afficher dans l'e-mail
$fields = array('name' => 'Nom', 'surname' => 'Prenom', 'phone' => 'Telephone', 'email' => 'Email', 'message' => 'Message'); 

// [En] message that will be displayed when everything is OK :)
// [Fr] message qui sera affiché quand tout va bien :)
$okMessage = 'Formulaire de contact soumis avec succès. Merci, je reviendrai vers vous bientôt!';

// [En] If something goes wrong, we will display this message.
// [Fr] Si quelque chose ne va pas, nous afficherons ce message.
$errorMessage = 'Une erreur s\'est produite lors de la soumission du formulaire. Veuillez réessayer plus tard';

/*
 *  FAISONS L'ENVOI
 
    1 - Si le tableau POST dans lequel les valeurs de formulaire sont stockées n'est pas vide, continuez. Sinon, si  (count($_POST) == 0), envoie un message d'erreur
    2 - Ensuite, nous commençons à créer le contenu du message électronique dans une variable $emailText.
    3 - Nous parcourons le $_POST (le tableau contenant toutes les valeurs envoyées via la requête POST).
    4 - Si nous découvrons que la clé de l'élément de tableau $_POST existe aussi dans notre tableau $fields, nous inclurons le texte du message dans $emailText. 
    5 - Nous envoyons l'e-mail via la fonction mail() interne PHP. Nous ajoutons des en-têtes importants à l’e-mail en utilisant le tableau $headers (encodage, depuis l’en-tête, répondre à, etc.)
    6 - Nous créons une variable $responseArray à envoyer en tant que réponse JSON à notre index.html. Le $responseArray sera géré par notre fonction JavaScript et affiché sous la forme d'une boîte d'alerte Bootstrap.
    7 - Si la demande est arrivée via AJAX (vous vérifiez ceci en PHP en utilisant la condition if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')), nous envoyons la réponse JSON.  Sinon, nous affichons simplement le message (ce devrait être un cas rare - par exemple pour les utilisateurs avec JavaSript désactivé)
 */

// [En]if you are not debugging and don't need error reporting, turn this off by error_reporting(0);
// [Fr] si vous n'êtes pas en train de déboguer et n'avez pas besoin de rapport d'erreurs, désactivez-le en error_reporting(0);
error_reporting(E_ALL & ~E_NOTICE);

try
{

    if(count($_POST) == 0) throw new \Exception('Form is empty');
            
    $emailText = "Vous avez un nouveau message de votre formulaire de contact\n=============================\n";

    foreach ($_POST as $key => $value) {
        // [En] If the field exists in the $fields array, include it in the email
        // [Fr] Si le champ existe dans le tableau $ fields, incluez-le dans l'email
        if (isset($fields[$key])) {
            $emailText .= "$fields[$key]: $value\n";
        }
    }

    // [En] All the neccessary headers for the email.
    // [Fr] Tous les en-têtes nécessaires pour l'email.
    $headers = array('Content-Type: text/plain; charset="UTF-8";',
        'From: ' . $from,
        'Reply-To: ' . $from,
        'Return-Path: ' . $from,
    );
    
    // [En] Send email
    // [Fr]Envoyer un email
    mail($sendTo, $subject, $emailText, implode("\n", $headers));

    $responseArray = array('type' => 'success', 'message' => $okMessage);
}
catch (\Exception $e)
{
    $responseArray = array('type' => 'danger', 'message' => $errorMessage);
}


// [En] if requested by AJAX request return JSON response
// [Fr] si la demande AJAX le demande, renvoie une réponse JSON
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $encoded = json_encode($responseArray);

    header('Content-Type: application/json');

    echo $encoded;
}
// [En] else just display the message
// [Fr] sinon il suffit d'afficher le message
else {
    echo $responseArray['message'];
}

?>
