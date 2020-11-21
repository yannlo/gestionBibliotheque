<?php 
if(isset($_POST["mail"])AND isset($_POST["message"])){
    $message ='<p> mail: '.$_POST['mail'].'<br/>'. $_POST["message"] .'</p>';
    $message = str_replace("\n.", "\n..", $message);

    $header="MIME-Version: 1.0\r\n";
    $header.='From:"PrimFX.com"<support@primfx.com>'."\n";
    $header.='Content-Type:text/html; charset="uft-8"'."\n";
    $header.='Content-Transfer-Encoding: 8bit';
    
    $message='
    <html>
        <body>
            <div align="center">
                <img src="http://www.primfx.com/mailing/banniere.png"/>
                <br />
                J\'ai envoyé ce mail avec PHP !
                <br />
                <img src="http://www.primfx.com/mailing/separation.png"/>
            </div>
        </body>
    </html>
    ';
    
    mail("ehui.yann729@gmail.com", "Salut tout le monde !", $message, $header);
    $_SESSION["send_message"] = 1;
    header("Location: index.php");
    exit();
}else{
    // header("Location: index.php");
    exit();
}




?>