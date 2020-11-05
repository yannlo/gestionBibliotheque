<?php
function verified_session(){
        if(session_status() === PHP_SESSION_NONE)   
    {
        session_start();
    }
}
?>