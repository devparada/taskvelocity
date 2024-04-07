if [ -z "$1" ]
    then
        echo "Inserte como argumento el nombre de la carpeta raíz en la que se aloja su app"
else
    folder=$1
    conf_filename="777-$folder.localhost.conf"
    conf_file="/etc/apache2/sites-available/$conf_filename"
    public_folder="/home/dev/proyecto/$folder/public"
    if [ -d "$public_folder" ]
    then    
        echo "Creando $1.localhost en fichero $conf_file"
        sudo echo "<VirtualHost *:80>
            ServerName $folder.localhost
            DocumentRoot /home/dev/proyecto/$folder/public
            <Directory /home/dev/proyecto/$folder/public/>
                Options +Indexes +FollowSymLinks +MultiViews
                AllowOverride All
                Require all granted
            </Directory>
            <FilesMatch \.php$>
                SetHandler \"proxy:unix:/var/run/php/php7.4-fpm.sock|fcgi://localhost\"
            </FilesMatch>
        </VirtualHost>" > $conf_file
        sudo a2ensite $conf_filename
        sudo systemctl restart apache2
        echo "http://$1.localhost"
    else
        echo "La carpeta $public_folder no existe"
    fi
fi
