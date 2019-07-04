Example (Symfony) project
==========

## Wymagania
* PHP 7.1 albo wyżej (np. 7.1.4)

##Instalacja
* host `127.0.0.1    example.local`
* vhost

```
#################################### SAMPLE
<VirtualHost *:80>
	ServerName sample.local
	DocumentRoot "${INSTALL_DIR}/www/sample/web"
	<Directory "${INSTALL_DIR}/www/sample/web">
		 AllowOverride All
		 Order Allow,Deny
		 Allow from All
	</Directory>
</VirtualHost>
```

* `git clone https://github.com/piotrp321/example.git .` w folderze gdzie ma być projekt
*  w mysql `CREATE DATABASE example`
*  utworzenie plików konfiguracyjnych `parameters.yml` na podstawie odp. plików `parameters.yml.dist` w `app/config`
* `composer install` w głównym katalogu projektu, zaciąga zależności
* `php bin/console doctrine:schema:update --force` tworzy schemat bazy danych
* `php bin/console doctrine:fixtures:load` generuje dane testowe

Testowy uzytkownik do zalogowania to np
username1
password


## Zadanie pierwsze oparte na symfony command

* `php bin/console app:merge-json-files list tree saved` 1 parametr  - nazwa pliku z lista, 2 parametr  - nazwa pliku z drzewem, 3 parametr  - nazwa pliku wynikowego.


Wszystkie pliki json powinny być w katalogu src\AppBundle\Command\jsons




