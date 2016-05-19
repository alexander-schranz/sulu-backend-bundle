# SULU Backend Bundle

Inspired by doctrine crud generator.

A Sulu Bundle to make it easier to create a new Backend Bundle.

With this Bundle it should be possible to create a Backend Bundles without
the knowledge of husky the sulu javascript framework.

# Installation

```bash
composer require l91/sulu-backend-bundle
```

**Add Bundle to AdminKernel**

```php
$bundles[] = new L91\Sulu\Bundle\BackendBundle\L91SuluBackendBundle();
```

# Usage

As example we will create a API for an entity called Vehicle.

## 1. Create Entity

First create the doctrine entity with a `.orm.xml`.

## 2. Create Repository

Create a Repository for loading entities from the database.
The `BackendRepository` have a default implementation for them:

 - `findById`
 - `findAll`
 - `count`
 
Create the functions in your repository or extend from the BackendRepository.

```php
<?php

namespace YourBundle\Entity\Repository;

use L91\Sulu\Bundle\BackendBundle\Entity\Repository\BackendRepository;

class VehicleRepository extends BackendRepository
{
    // Add your custom repository functions here
}
```

**Register Repository**

```yml
services:
    your.repository.vehicle:
        class: YourBundle\Entity\VehicleRepository
        factory_service: doctrine.orm.default_entity_manager
        factory_method: getRepository
        arguments:
            - YourBundle\Entity\Vehicle
```

## 4. Generate Controller, Manager, Admin Navigation or Tab, JSBundle

You can easily generate them with following command:

```bash
app/console l91:sulu:backend:generate:crud YourBundle:Vehicle
```

Add `--extended` to have no requirements to this Bundle. This will generate a complete own Controller, Manager, ...
when use the extended generation you could remove this bundle from your requirements after you generated your bundle.

# Command List

You can also just generate a specific part with the following commands:

 - `app/console l91:sulu:backend:generate:controller`
 - `app/console l91:sulu:backend:generate:manager`
 - `app/console l91:sulu:backend:generate:admin`
 - `app/console l91:sulu:backend:generate:js`
 - `app/console l91:sulu:backend:generate:navigation-provider`