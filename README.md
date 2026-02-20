# Make Commands
A package providing overrides for the base Laravel make commands to scaffold out files in adherance to the PHP Archetype domain model and organization standards.

## Installation
```bash
composer require aryeo/make-commands
```

## Usage

The package is meant to scaffold all relevant files when creating domain models or endpoints. There are 2 primary commands:

### make:model

Example

```sh
php artisan make:model Post
```

This command will scaffold out the following files:

+ App/Models/Posts
    + Post.php  // model
    + Factory.php  // model factory
    + Builder.php  // eloquent builder
    + ServiceProvider.php  // domain model service provider
    + PostTest.php  // model test
    + Posts.php  // model collection

The model will automatically contain the PHP attributes for registering the collection, eloquent builder and factory.

### make:controller

Example

```sh
php artisan make:controller
```

This command will guide you through several prompts to determine the API version and domain model the endpoint is for

1. API Version

The first prompt is to select an API version. The logic will automatically look for folders in `app/Http/Api` with names of `V(0-9)` and present them as options. If none are found, a `V1` folder will automatically be created without prompting. A create new version option will be present and will increment based on the latest `V(0-9)` folder name.

2. Domain Model

The next prompt will look at all the domain model folders in `app/Models` and prompt you to select which domain model the endpoint is for.

3. Endpoint Type

There are 2 types of endpoints that are supported in the PHP Archetype:

- REST
- Action

REST are the standard CRUD endpoints along with a Search endpoint. Action endpoints will live in an `actions/` directory and are for endpoints that trigger more complicated logic.

4. Endpoint

There are several types of endpoints to choose from:

- Index
- Store
- Show
- Update
- Delete
- Search

Once completed, the following classes will be made. Let's use the following choices as an example:

1. V1
2. Post
3. REST
4. Index, Show, Update

+ App/Http/Api/V1/Posts
    + Index/
        + Controller.php
        + Request.php
        + ControllerTest.php
    + Show/
        + Controller.php
        + Request.php
        + ControllerTest.php
    + Update/
        + Controller.php
        + Request.php
        + ControllerTest.php

If `Action` was selected, let's use the following choices as an example:

1. V1
2. Post
3. Action
4. PayInvoice

+ App/Http/Api/V1/Posts/Actions/
    + PayInvoice/
        + Controller.php
        + Request.php
        + ControllerTest.php


