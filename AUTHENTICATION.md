# ToDo & CO - Authentication - How it Works

This file explains how work the ToDo & Co Authentication system.

Realized by Adrien PIERRARD - [(see GitHub)](https://github.com/WizBhoo)

Supported by Antoine De Conto - OCR Mentor.

Special thanks to Rui TEIXEIRA and Yann LUCAS for PR Reviews.

---

## Introduction

As we use Symfony as framework of the ToDo & Co App, our authentication system is based on the Symfony's security component.

In Symfony, the security system contains two major mechanisms :

*   Authentication who defines who the user is, managed by the firewall.
*   Authorization who determines if a visitor, or a User has the right to access certain resources. It comes after the firewall, managed by Symfony through access control.

In this documentation we will explain how both mechanism works in our App to understand which file you can modify if you want to change the way the App is secured.

## Authentication

### 1. Users

*   A ToDo & Co App User is represented by the entity class `User`, which implements the `UserInterface`.
*   This Class is a Doctrine entity, so Users are stored in database and are represented by their username attribute.
*   Open the file `config/packages/security.yaml` to see this information, defined under the key `providers`.
*   It is not permitted to store in DataBase a User password in clear plaintext. So we need to encrypt it.
*   To do that, we need to define the encoder to use in the `security.yaml` file under the key `encoders`.
*   In our case, you will see that the algorithm used id bcrypt encoder.

### 2. Firewall

Thanks to the firewall, we can manage access to all of our routes.

In the `security.yaml` file, you will see that we defined certain parameters under the key `main` such as :

*   The behavior to adopt regarding non-authenticated visitor (`anonymous`) with the value `lazy`. This tells Symfony to only load the user (and start the session) if the application actually access the user object. In other words, all our urls / actions that don't need the user will be public and cacheable, improving the performance of our application.
*   The `http_basic` key is here only to allow a client to be simply authenticated in our functional tests.
*   The `provider` used as explained above.
*   The `guard` which is the authenticator used represented here by a LoginFormAuthenticator Class.
*   At the end, the route used to logout, and the preferred route (/login) to redirect a User non-authenticated who want to access to a protected url.

### 3. Manage your code regarding authentication

*   By default, Symfony security is used to define whether a user is authenticated.

*   You can use some attributes to check it like for example :
    *   `IS_AUTHENTICATED_ANONYMOUSLY` which means all users, even those who are not authenticated.  
    *   `IS_AUTHENTICATED_FULLY` which means authenticated users during the current session.

*   Use those attributes in the `security.yaml` file to secure url patterns via the `access_control` key.

*   It is possible to use these attributes in a controller, via the `$this->isGranted('IS_AUTHENTICATED_FULLY')` method.

*   It is also possible to use them from a Twig view `{% if is_granted ('IS_AUTHENTICATED_FULLY')%}`.

Note that once a User is authenticated, you can access to him in a controller via the `$this->getUser()` method or in a Twig view via the global `app.user`.

## Authorization

As mentioned above, you can define who has the right to access certain resources.

### 1. Roles

*   We choose to determine two roles to assign to our Users, based on the rights we wanted to assign them.
*   So our Users, stored in DB can have a `ROLE_USER` or a `ROLE_ADMIN`.
*   You can create other roles. The role's name is free, however, it must always start with ROLE_ for Symfony's security to recognize it.

### 2. Access Control

*   These roles are used to secure url patterns under the `access_control` key in the security.yml file.
*   Also, in controller actions, or in a Twig views when needed.
*   In the ToDo & Co App, we decided to use Voters. For more information about Voters, please read the documentation provided in the section [More About](#more-about) below.

### 3. Role Hierarchy

*   To simplify your url patterns, you can define a role hierarchy under the key `role_hierarchy` key of the security.yml file.
*   Yes you can ! Because it's possible to inherit roles.
*   In our case for example, to access to /tasks/* routes, the ROLE_ADMIN also need to have ROLE_USER rights.

## More About

*   [The Symfony Security Component](https://symfony.com/doc/4.4/components/security.html)
*   [The Symfony Authentication System](https://symfony.com/doc/4.4/components/security/authentication.html)
*   [Symfony Voters](https://symfony.com/doc/4.4/components/security/authorization.html#voters)
*   [The Firewall and Authorization](https://symfony.com/doc/4.4/components/security/firewall.html)

---

## Contact

Thanks in advance for Star contribution

Any question / trouble ?

Please send me an [e-mail](mailto:apierrard.contact@gmail.com) ;-)
