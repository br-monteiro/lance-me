#!/bin/bash
clear
echo "Olá $USER O servidor embutido do PHP sera executado em http://localhost:8000/".
php -S localhost:8000 -t public/
