# OCR_P08_ToDo & Co

OpenClassrooms - Training Course DA PHP/Symfony - Project P08 - Upgrade an existing Symfony project

My WebSite is Online and you can visit it : [APi - Site CV](https://adrien-pierrard.fr)

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/291aefecd42040b2b17d04870dfb18ba)](https://www.codacy.com/manual/WizBhoo/OCR_P08_ToDoList?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=WizBhoo/OCR_P08_ToDoList&amp;utm_campaign=Badge_Grade)
[![Codacy Badge](https://app.codacy.com/project/badge/Coverage/291aefecd42040b2b17d04870dfb18ba)](https://www.codacy.com/manual/WizBhoo/OCR_P08_ToDoList?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=WizBhoo/OCR_P08_ToDoList&amp;utm_campaign=Badge_Coverage)
![Main](https://github.com/WizBhoo/OCR_P08_ToDoList/workflows/Main/badge.svg)

## Version 1.0.1 - July 2020

*   This file explains how to install and run the project.
*   IDE used : PhpStorm.
*   I use a Docker Stack as personal local development environment, but you can use your own environment.
*   Both method to install the project are the described bellow.
*   A provided documentation is make to understand how work the authentication system.
*   Please, read [Authentication - How it works](AUTHENTICATION.md) if needed.
*   Want to contribute to this project ? Please read the [Contributing Guidelines](CONTRIBUTING.md) before.

---

Realized by Adrien PIERRARD - [(see GitHub)](https://github.com/WizBhoo)

Supported by Antoine De Conto - OCR Mentor

Special thanks to Rui TEIXEIRA and Yann LUCAS for PR Reviews

---

### How to install the project with your own local environment

What you need :

*   Symfony 4.4.*
*   PHP 7.2
*   MySQL 8 - (I use PHPMyAdmin)
*   Demo data provided through fixtures to load with Doctrine after DB creation
*   Tests are automated through the CI via GitHub Action.
*   More information about tests provided in [Contributing Guidelines](CONTRIBUTING.md) 

Follow each following steps :

*   First clone this repository from your terminal in your preferred project directory.

```console
https://github.com/WizBhoo/OCR_P08_ToDoList.git
```

*   You need to edit the ".env" file to add your credentials for Doctrine DB connection.
*   I recommend you to copy the ".env" file and setup your credentials in a ".env.local" file.
*   Launch your local environment.
*   From your terminal, go to the project directory and tape those command line :

```console
composer install
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
```

*   Well done ! Project installed ! So you just have to go to your localhost home page.

---

### How to install the project using my Docker Stack (recommended method)

*   My Docker stack provide a development environment ready to run a Symfony project.
*   Follow this link and read the README file to install it : [Docker Symfony](https://github.com/WizBhoo/docker_sf3_to_sf5)
*   Prerequisite : to have Docker and Docker-Compose installed on your machine - [Docker Install](https://docs.docker.com/install/)
*   Preferred Operating System to use it : Linux / Mac OSx

Once you have well installed my Docker Stack, follow each following steps :

*   From your terminal go to the symfony directory created by Docker.
*   Clone this repository inside.

```console
https://github.com/WizBhoo/OCR_P08_ToDoList.git
```

*   You need to edit the ".env" file to add your credentials for Doctrine DB connection.
*   I recommend you to copy the ".env" file and setup your credentials in a ".env.local" file.
*   From your terminal go to the Docker directory and launch Docker using those command lines :

```console
make build
make start or make up
```

<blockquote>
You can also use "make help" to see what "make" command are available.
</blockquote>

*   You can access to PHPMyAdmin using [pma.localhost](http://pma.localhost) but as already mentioned, you will create the DB and load data fixtures through command lines with Doctrine (See next steps).
*   From your terminal, always in the Docker directory, tape those command lines :

```console
make sh
cd symfony/
composer install
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
```

*   Well done ! Project installed ! So you just have to go to [mon-site.localhost](http://mon-site.localhost) home page.

---

### How to contribute to this project

*   This project takes part of my training course to become a developer. Data presented are only used for demonstration.
*   Initially, base project provided was developed under Symfony 3.1 and doesn't work as expected on a voluntary basis.
*   The goal was to analyse it, to test it and to refactor it in order to upgrade it.
*   Bugs have been identified and fixed, some new features have been implemented following ToDo & Co expectations.
*   A Quality code & App performance Audit has been conducted to establish the App technical debt inventory and define an improvement plan.
*   Some issues have been created from this plan, and you can contribute by working on it.
*   You can also suggest your own improvement issue to do and / or open an issue if you identify a bug.
*   Whatever the way you wish to contribute, please read the [Contributing Guidelines](CONTRIBUTING.md) before.
*   Click on the link to access to the [Project Improvement Plan](https://github.com/WizBhoo/OCR_P08_ToDoList/projects/2).

---

### Contact

Thanks in advance for Star contribution !

Any question / trouble ?

Please send me an [e-mail](mailto:apierrard.contact@gmail.com) ;-)
