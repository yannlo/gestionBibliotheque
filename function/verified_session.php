<?php

// Verifie si une session est en cour et si non la demarre

if(session_status() == PHP_SESSION_NONE)   
{
    session_start();
}

?>