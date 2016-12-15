#!/bin/sh -v
./vote-create-tables.sh
./vote-init-tables.sh
./vote-newcamp.php camp-test.txt
./vote-fetch.php

