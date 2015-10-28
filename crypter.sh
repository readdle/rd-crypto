#!/usr/bin/env bash

DIRECTORY=$(cd `dirname $0` && pwd)

USAGE_MESSAGE="Usage: $0 encrypt|decrypt secret value"

if [ $# -ne 3 ]; then
    echo $USAGE_MESSAGE
    exit 2
fi

if [ "$1" != "encrypt" ] && [ "$1" != "decrypt" ]
then
    echo $USAGE_MESSAGE
    exit 2
fi

if [ "$1" == "encrypt" ]; then
    node $DIRECTORY/lib/bin/encrypt.js $2 $3
else
    node $DIRECTORY/lib/bin/decrypt.js $2 $3
fi
