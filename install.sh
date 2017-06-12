#!/bin/bash
clear
echo "Olá $USER precione qualquer tecla para instalar o LAMP".
read $tecla
    clear
    echo "Digite a sua senha de usuário root"
    sudo apt-get upadet &&
    sudo apt-get install apache2 &&
    sudo apt-get install php5 php5-mbstring php5-common php5-curl php5-json libapache2-mod-php5 &&
    clear
    echo "Agora vamos fazer a instalação no Banco de Dados MySQL, no final da instalação digite a sua senha de root do banco de dados."
    sleep 2	
    sudo apt-get install mysql-server &&
    sudo apt-get install libapache2-mod-auth-mysql php5-mysql &&
    sudo /etc/init.d/apache2 restart
    clear

echo "Obrigado $USER, até a próxima!"
