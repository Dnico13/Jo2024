<?php
function emailEtMotDePasseSontRemplis($email, $password) {
    return !empty($email) && !empty($password);
}

function motDePasseEstValide($mdp, $hash) {
    return password_verify($mdp, $hash);
}

function codeEstValide($code, $attendu, $expiration) {
    return $code === $attendu && time() < $expiration;
}
