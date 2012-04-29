#! /usr/bin/env bash

directory="/dev/shm/shelljson";
root_node="twitter"

if [ -d "${directory}/${root_node}" ]; then
    rm -r "${directory}/${root_node}"
fi

data=$(wget 'https://twitter.com/users/show/syranez.json'  -q -O -);

php ./shelljson.php --root="${root_node}" --directory="${directory}" "${data}";

if [ ! $? -eq 0 ]; then
    echo "error: $?";
    exit $?;
fi

echo $(cat "${directory}/${root_node}/status/text");

rm -r "${directory}/${root_node}"
