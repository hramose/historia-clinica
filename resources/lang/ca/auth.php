<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'failed' => 'Aquestes credencials no són correctes (potser l\'email o la contrasenya no estan ben escrits. Si no recorda la contrasenya podeu recuperar-la en el seguent enllaç: <a href="'.route('reset_password').'">Recuperar contrasenya</a>), no està registrat o el teu usuari no està verificat, si us plau, si és així adreceu-vos al mail que li vam enviar al seu moment per verificar el seu compte. Si no, pot sol·licitar de nou l\'email. <a href="sendverificationmail">Sol·licitar de nou</a>',
    'throttle' => 'Demasiados intentos de inicio de sesión. Por favor, inténtelo de nuevo en :seconds segundos',

];
