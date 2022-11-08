# Ads Board

Ads Board is a simple PHP/Symfony web application for adding, searching and diplaying advertisements.

## Installation

In order to use an application locally, you need to:
- Install Composer globally (https://getcomposer.org/doc/00-intro.md)
- Install a web browser - I recommend Google Chrome version 106 or higher
- Install XAMPP (https://www.apachefriends.org/download.html), preferably version for PHP 7.4.x
- Put xampp folder on your C: disk
- Download this Git repository (for example as ZIP archive, then unpack it)
- Rename the main folder of this repository to ads-board
- Copy and paste whole ads-board folder into C:/xampp/htdocs
- Rename ".template.env" file to ".env" (in root folder)
- Make sure that in .env file you have set production environment:

    <code>APP_ENV=prod</code>
- Generate a random 256-bit secret key (for example here: https://www.allkeysgenerator.com/Random/Security-Encryption-Key-Generator.aspx) and set APP_SECRET variable in .env file, for example:

    <code>APP_SECRET=VkYp3s6v9y$B&E)H@McQeThWmZq4t7w!</code>
- Open windows command line (Win + R shortcut, type "cmd" and click OK)
- If you are on other disk than C:, switch to C: by typing "C:" and clicking ENTER
- Now we're going to install all necessary packages, create a database and all necessary tables. Type or paste following commands into the terminal and click ENTER after every of them:
    ```
    composer install
    php bin/console doctrine:database:create
    php bin/console doctrine:migrations:migrate
    ```
- Run XAMPP panel (xampp/xampp_start.exe)
- Start Apache and MySQL services
- Go to your web browser and enter http://localhost/ads-board/public/
- You are on the main page of the application (route "/"). To move around the application use buttons and links or paste the desired route to URL after "/public/" part and click ENTER.

## Main features

- Adding ads (with product name, price and description)
- "Add ad" form validation
- Searching ads (with possibility to filter results by price and description)
- Displaying an ad by ID
- REST API (detailed info in "REST API features" section)

## REST API features

- Instructions how to use API (in Polish) are on route /api
- Generating a random API token, using AJAX
- Authorization to prevent unauthorized usage of an API
- Adding ads
- Searching ads (with filters by price and description)
- Getting an ad by ID

## Unit tests

In order to run unit tests you have to:
- set in .env file a development environment:
    <code>APP_ENV=dev</code>
- run command:
    ```
    composer install
    ```
- run tests:
    ```
    bin/phpunit
    ```
    
## [PL] Co można jeszcze ulepszyć w aplikacji
- Dodać panel rejestracji, logowania i panel użytkownika, gdzie użytkownik generowałby sobie token do API - z ograniczeniem czasowym (expiration date)
- Dodać front-endową (JS/jQuery) walidację formularza dodawania ogłoszeń
- Dodać front-endową i back-endową walidację formularza wyszukiwania ogłoszeń
- Dodać więcej walidacji w ApiControllerze, szczególnie przy dodawaniu ogłoszeń
- Dodać paginację w wyszukiwarce ogłoszeń
- Poprawki wizualne - np. szerokości inputów w formularzach
- Dodać więcej unit testów

## Technologies used

- PHP 7.4.29
- Symfony 5.4.15
