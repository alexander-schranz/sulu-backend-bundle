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

First create doctrine entity 

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

## 3. Create Manager

Create the manager for your entity.
The Manager will load, create, delete your entities.
So you need to implement the CRUD functions there.
The `AbstractBackendManager` have a default implementation for:

 - `get`
 - `getBy`
 - `countBy` 
 
When you want to overwrite them all you can also just implement the `ManagerInterface`.

```php
<?php

namespace YourBundle\Manager

use L91\Sulu\Bundle\BackendBundle\Manager\AbstractBackendManager;
use YourBundle\Entity\Vehicle;

class VehicleManager extends AbstractBackendManager
{
    protected $entityManager;

    protected $vehicleRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        VehicleRepository $vehicleRepository
    ) {
        $this->entityManager = $entityManager;
        $this->vehicleRepository = $vehicleRepository;
    }

    public function save($data, $locale = null, $id = null)
    {
        // TODO: create update a  entity and return it
    }
    
    public function delete($id, $locale = null)
    {
        // TODO: delete a entity
    }
    
    protected function getRepository()
    {
        return $this->vehicleRepository;
    }
}
```

**Register Manager**

```yml
services:
    your.manager.vehicle:
        class: YourBundle\Manager\VehicleManager
        arguments:
            - '@doctrine.orm.default_entity_manager'
            - '@your.repository.vehicle'
```


## 4. Controller

Create your Controller and extend it from the AbstractRestController.
You need to implement `getManager` and `getSecurityContext` method.

The Controller come with:

 - `getAction`
 - `cgetAction`
 - `putAction`
 - `postAction`
 - `deleteAction`
 - `fieldAction`

```php
<?php

namespace YourBundle\Controller

use L91\Sulu\Bundle\BackendBundle\Controller\AbstractRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use YourBundle\Entity\Vehicle;

class VehicleController extends AbstractRestController implements ClassResourceInterface
{
    /**
     * @return \YourBundle\Manager\VehicleManager
     */
    public function getManager()
    {
        return $this->get('your_bundle.manager.vehicle');
    }
    
    /**
     * {@inheritdoc}
     */
    public function getSecurityContext()
    {
        return 'your.vehicles';
    }
    
    /**
     * @return DoctrineCaseFieldDescriptor[]
     */
    public function getFieldDescriptors($locale, $filters)
    {
        // TODO: return the fieldDescriptors for the list view
    }

    /**
     * @return string
     */
    public function getModelClass()
    {
        return Vehicle:class;
    }
}
```

**Register Controller**

```yml
your.vehicle:
    type: rest
    resource: YourBundle\Controller\VehicleController
```

## 5. Admin Navigation

TODO