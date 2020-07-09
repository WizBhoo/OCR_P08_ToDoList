# ToDo & CO - Contributing Guidelines

This file describes how to contribute to ToDo & Co App improvement.

Realized by Adrien PIERRARD - [(see GitHub)](https://github.com/WizBhoo)

Supported by Antoine De Conto - OCR Mentor.

Special thanks to Rui TEIXEIRA and Yann LUCAS for PR Reviews.

-------------------------------------------------------------------------------------------------------------------------------------

## Introduction

Welcome web adventurer !

Here you are because you want to contribute to this project. Very good !

Before that, please, read the code of conduct below and follow instructions.

### 1. Code of Conduct

*   Be welcoming ! Adopt the appropriate behavior.
*   Use an inclusive language to stay respectful in any situation.
*   Respect all points of view and be cool with less experienced developers.
*   Accept to receive criticism and if you have one to do, do it for a constructive way.
*   No one is the King here, keep in mind that is a Teamwork.
*   Focus your intention on what is the best for the community.

### 2. Prerequisites

*   Have installed the project in local by following [README.md](README.md) instructions.

### 3. Testing

#### Continuous Integration (CI)

*   Unit & Functional tests are automated through the CI via GitHub Action thanks to PHPUnit.
*   So when you create a Pull Request or if you push a commit on develop, CI comes into action to test your code.
*   You have nothing to do but please, do not modify the `.env.test` file. The CI needs it to load tests fixtures in db.
*   If you want to test your change in local, follow instruction below.
*   The App code coverage is > 70%. Ensure to stay upper to this lvl.
*   The CI generate a code coverage report available in Codacy (see README badges).

#### PHPUnit in local

*   You will not be able to merge a PR if CI fail to pass tests.
*   A good practice to adopt is to test your code in local before pushing it to the repository.
*   To launch tests in local you need to load data fixtures in a test database.
*   First, create a `.env.test.local` file.
*   Inside, just setup the DATABASE_URL environment variable with your local db credentials.
*   Ensure your local environment is running.
*   From your terminal, go to the project directory and tape those command line :

With your own local environment :

```console
php bin/console doctrine:database:create --env=test
php bin/console doctrine:migrations:migrate --env=test
php bin/console doctrine:fixtures:load --env=test
```

Using my Docker Stack :

```console
make sh
cd symfony/
php bin/console doctrine:database:create --env=test
php bin/console doctrine:migrations:migrate --env=test
php bin/console doctrine:fixtures:load --env=test
```

*   Well done ! DataBase for Tests is ready !
*   So now, to run tests, you just have to tape in your terminal : `./bin/phpunit`.
*   If you want to generate a local code coverage report, use instead :

```console
./bin/phpunit --coverage-html <add a location where you want to save the report like var/code-coverage>
```

*   Find the report in your project directory and open it with your browser.

### 4. Code Quality

*   As you could see in the README file, the code Quality is monitored by Codacy.
*   You will not be able to merge a PR if Codacy analysis fail.
*   By contributing to this project, please ensure to maintain a Grad A quality level.
*   Codacy is not enough, as today the integrated lint tool like phpcs is not fully adapt to symfony project.
*   So you can ensure a great quality lvl regarding code lint by using phpcs in local.
*   We follow some PSR rules, so please respect at the minimum PSR-1, PSR-2 and PSR-4.
*   In addition, we try to respect as much as possible the SOLID principles.

## How to do your changes

*   First, click on the link to access to the [Project Improvement Plan](https://github.com/WizBhoo/OCR_P08_ToDoList/projects/2).
*   Check if your change is not already cover by an ongoing issue.
*   If not, create your own issue to discuss around it.
*   When the issue is well define, you can now do your changes or add a new feature.
*   The project deployment branch is "master", so please, never commit on it.
*   The main branch to contribute is "develop", but your are not alone to contribute !
*   So don't forget to create a new branch from develop.
*   By convention give a name to your branch following this schema : feature/<name of your change link to issue's title>.
*   Before your first commit, check the git log to retrieve the commit message schema to respect.
*   Do your change, test your code, check the quality !

If all is green, well done ! You can push your branch and submit a pull request !
Now you just have to wait for it to be reviewed and accepted by the team.

*   If Codacy or the CI fail, please fix your bugs, commit the fix and push it.

## About Symfony

This application develop with Symfony framework, please check the [Symfony Documentation](https://symfony.com/doc/4.4/best_practices.html) to follow best practices.

-------------------------------------------------------------------------------------------------------------------------------------

## Contact

Thanks in advance for Star contribution

Any question / trouble ?

Please send me an [e-mail](mailto:apierrard.contact@gmail.com) ;-)
