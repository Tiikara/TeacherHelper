﻿# Структура каталогов

* /(*корень*) - исходники проекта
* /css - папка с таблицами стилей
* /images - папка с изображениями
* /js - папка с исходниками JavaScript
* /libs - папка с дополнительными библиотеками на PHP.
* /tpl - папка с шаблонами страниц на HTML.
* /sql - папка с моделью базы данных

# Инструкция по установке

* Развернуть сервер локально (например сборки OpenServer(Рекомендуется)/Denwer или Apache), либо удаленно.
* Установить поддержку PHP 5.5+, MySQL 5.5+.
* Создать базу данных используя SQL скрипт /sql/schema.sql.
* Перенести файлы проекта в директорию сервера (OpenServer - /openserver/domains/localhost/, Denwer - denwer/home/localhost/).
* Прописать настройки подключения к базе данных в файле /csqldatabase.php.
* ...
* PROFIT!