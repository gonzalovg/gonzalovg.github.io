<?php

//Codificación UTF-8 para todo el fichero.
header('Content-Type: text/html; charset=UTF-8');
// Recupero detalles del envío POST
$nombre = $_POST['nombre'];
$cp = $_POST['cp'];
$edad = $_POST['edad'];
echo $nombre;
// crea y envía el mensaje de correo electrónico
$para = 'gonver17@gmail.com';
$asunto = 'Nuevos Datos personalesss';
$mensaje = '<br> Datos Personales Recibidos desde la Web <br /><br /> ';
$mensaje .= 'Nombre: '.$nombre.' <br /> ';
$mensaje .= 'Dirección: '.$cp.'<br /> ';
$mensaje .= 'Edad: '.$edad.'<br /><br />';
//Así podríamos construir una estructura completa de página HTML si
//queremos que llegue con más forma
$cabeceras = 'MIME-Version: 1.0'."\r\n";
$cabeceras .= 'Content-type: text/html; charset=UTF-8'."\r\n";
$cabeceras .= 'From:emailtest@gonzaloverdugo.000webhostapp.com';
if (mail($para, $asunto, $mensaje, $cabeceras)) {
    echo '<span style="font-size:1.3em;color:white;background-
color:green;padding:5px;">Se acaba de enviar el correo, revisa tu bandeja

de entrada o la de SPAM antes de ponerte nervioso...</span>';
// header('Location: ok-email.php');
} else {
    echo '<span style="font-size:1.3em;color:white;background-
color:red;padding:5px;">Error al enviar mensaje</span>';

    // header('Location: error-email.php');
}
