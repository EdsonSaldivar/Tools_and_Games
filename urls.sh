#!/bin/env bash

identificar_protocolo() {
    url="$1"

    # Expresión regular para identificar el protocolo en una URL
    protocolo_regex="^(https?|ftp):\/\/"

    if [[ $url =~ $protocolo_regex ]]; then
        protocolo="${BASH_REMATCH[1]}"

        echo "Protocolo: $protocolo"
    else
        echo "URL no válida: $url"
    fi
}


obtener_dominio() {
    url="$1"

    # Expresión regular para obtener el dominio de una URL
    dominio_regex="(https?:\/\/)?([a-zA-Z0-9\-]+\.)+([a-zA-Z]{2,})(\/\S*)?"

    if [[ $url =~ $dominio_regex ]]; then
        dominio="${BASH_REMATCH[3]}"

        echo "Dominio: $dominio"
    else
        echo "URL no válida: $url"
    fi
}

url=$1

identificar_protocolo "$url"
obtener_dominio "$url"

curl "$url" -H 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:123.0) Gecko/20100101 Firefox/123.0' | grep -o 'href="[^"#]*"' | awk -F '"' '{if ($2 !~ /^http/) print "'"$url"'" $2; else print $2}' | sort   
