#!/bin/bash

# Directorio onde se encontran as imaxes
directorio_imaxes="/home/dev/proxecto/public/assets/img"

# Directorio onde se gardarán os backups
directorio_seguro="/srv/backups/backup_$(date +"%Y-%m-%d_%H-%M-%S")"

mkdir -p "$directorio_seguro"

# Copia todas as imaxes do directorio de imaxes
cp -R "$directorio_imaxes" "$directorio_seguro"

usuario="admin"
base_datos="proxecto"

# Usa o ficheiro mysql.cnf para que se logee o usuario no mySQL automáticamente
mysqldump --defaults-file=mysql.cnf -u $usuario $base_datos > "$directorio_seguro"/"base_datos_backup.sql"
