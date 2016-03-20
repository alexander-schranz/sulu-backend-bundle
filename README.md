# SULU Backend Bundle

A Sulu Bundle to make it easier to create a new Backend Bundle.

With this Bundle it should be possible to create a Backend Bundles without
the knowledge of husky the sulu javascript framework.

# Installation

```bash
composer require l91/sulu-backend-bundle
```

# Usage

As example we will create a API for an entity called Vehicle.

## 1. Create Entity

First create the doctrine entity with a `.orm.xml`.

## 2. Create Repository

Create a Repository for loading entities from the database.
The `BackendRepository` have a default implementation for:

 - `get`
 - `getBy`
 - `countBy`

If you want to overwrite them all you can just implement the `BackendRepositoryInterface`. 

```
<?php

namespace YourBundle\Entity\Repository;

use L91\Sulu\Bundle\BackendBundle\Entity\Repository\BackendRepository;

class VehicleRepository extends BackendRepository
{
    // Add your custom repository functions here
}
```

**Register Repository**

```
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

```
app/console l91:sulu:backend:generate:crud YourBundle:Vehicle
```
