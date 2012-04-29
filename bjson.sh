#! /usr/bin/env bash

URI="https://twitter.com/users/show/syranez.json"

# { in "" dürfen nicht verändert werden.
# Wie erkennen?
# e-z: String positions:
# "foo": {
# 
#    :\WHITE{
#    oder {
#
# Dabei: Die Anzahl 

wget -q "${URI}" -O - | sed 's/{/\n{\n/g' | sed 's/}/\n}\n/g' | sed 's/,"/\n"/g'

# cleanup
#
# foobar:      "lala"
#
# =>     sed 's/\: */\: /g'
# foobar:
#
# "lala"
# =>     ?

# property:
# foobar: "hallo"
# foobar: 23
# foobar: {
# foobar: [


tokenizer:


[,{\[] muss vor einem "foobar" stehen

# Alles in eine Zeile holen und dann zeichenweise gucken was los ist:

# Wenn {
   => parseObjekt
# Wenn [
   => parseArray
# Wenn "
   => parseProperty
# Wenn Character
   => parseProperty
# Ansonsten
   => fail


# {foobar: "hallo"}
In Objekt

# Found Character => parseProperty -> bis ":"
# Found " => parseProperty -> bis \"":"
    # Ignoriere Whitspace
    # Wenn " => parseStringProperty
    # Wenn Ziffer => parseNumberProperty
        # Ignoriere Whitspace
        # Wenn " => parseStringProperty
        # Wenn Ziffer => parseNumberProperty
        # Wenn {      => parseObjekt
        # Wenn [      => parseArray
    # Wenn {      => parseObjekt
    # Wenn [      => parseArray




