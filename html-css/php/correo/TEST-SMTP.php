<?php

if (mail('gonver17@gmail.com', 'Comprobando servicio de Correo -I', 'Mensaje de prueba desde el HOST', 'From: emailtest@gonzaloverdugo.000webhostapp.com')) {
    //Partes función mail//
    //Primer Argumento: email donde van a recibirse los datos
    //Segundo: La línea de asunto
    //Tercero: El Mensaje en sí mismo puede ser código html para envir una
    // página completamente maquetada, pero es importante revisar como llega la
    // codificación de caracteres desde php
    //Cuarto: "Cabeceras" que dan forma al email
    //Es posible colocar parámetros opcionales como un quinto argumento.
    //Pintamos dos mensajes con algo de estilo, uno para cada caso, es
    // decir, en el caso de que todo funcione, o bien si se produce un error

    echo '<span style="font-size:1.3em;color:white;background-color:green;padding:5px;">Se acaba de enviar el correo revisa tu bandeja de entrada o la de SPAM antes de ponerte nervioso...</span>';
} else {
    echo '<span style="font-size:1.3em;color:white;background-
color:red;padding:5px;">Error al enviar mensaje</span>';
}
