# [proophgen](https://pilsniak.com/proophgen/)

[![GitHub (Pre-)Release Date](https://img.shields.io/github/release-date-pre/mmp4k/proophgen.svg?style=flat-square)]()
[![Travis](https://img.shields.io/travis/mmp4k/proophgen.svg?style=flat-square)]()
[![Coveralls github](https://img.shields.io/coveralls/github/mmp4k/proophgen.svg?style=flat-square)]()
[![Packagist](https://img.shields.io/packagist/v/pilsniak/proophgen.svg?style=flat-square)]()
[![GitHub release](https://img.shields.io/github/release/mmp4k/proophgen/all.svg?style=flat-square)]()
[![Packagist](https://img.shields.io/packagist/l/pilsniak/proophgen.svg?style=flat-square)]()
[![GitHub last commit](https://img.shields.io/github/last-commit/mmp4k/proophgen.svg?style=flat-square)]()

Why developers love CRUD? Because it's easy to automate work around it. Why developers hate DDD/CQRS? Boilerplates.

Using this small app `proophgen` and single 15th lines `yaml` file you can generate a project that contains **40 files** (with phpspec tests!) and start coding. No more boilerplates.

You can also use singe command to create your ValueObject, Command.

## Table of Contents 

* [Examples](#examples)  
* [Create single ValueObject](#create-single-valueobject)
* [Create single Command](#create-single-command)  
* [Installation](#installation)

## Examples

This is your `yaml`

```
valueObjects:
  - Model\ValueObject\Mail
  - Model\ValueObject\Name
  - Model\ValueObject\Password
commands:
  - Model\Command\RegisterUser
  - Model\Command\LoginUser
aggregateRoots:
  Model\User:
    - !UserRegistered
  Model\Identity:
    - !EmailIdentityCreated
    - UserToIdentityAssigned
    - UserLogged
```

There is your command to run:

```
$ proophgen do
```

And there is your result (in v0.1.2):

```
./src/Infrastructure/Identity/EventSourced.php
./src/Infrastructure/Identity/InMemory.php
./src/Infrastructure/User/EventSourced.php
./src/Infrastructure/User/InMemory.php
./src/Model/Command/LoginUser.php
./src/Model/Command/RegisterUser.php
./src/Model/CommandHandler/LoginUserHandler.php
./src/Model/CommandHandler/RegisterUserHandler.php
./src/Model/Identity/Event/EmailIdentityCreated.php
./src/Model/Identity/Event/UserLogged.php
./src/Model/Identity/Event/UserToIdentityAssigned.php
./src/Model/Identity/Exception/IdentityNotFound.php
./src/Model/Identity.php
./src/Model/IdentityRepository.php
./src/Model/User/Event/UserRegistered.php
./src/Model/User/Exception/UserNotFound.php
./src/Model/User.php
./src/Model/UserRepository.php
./src/Model/ValueObject/Mail.php
./src/Model/ValueObject/Name.php
./src/Model/ValueObject/Password.php
./spec/Infrastructure/Identity/EventSourcedSpec.php
./spec/Infrastructure/Identity/InMemorySpec.php
./spec/Infrastructure/User/EventSourcedSpec.php
./spec/Infrastructure/User/InMemorySpec.php
./spec/Model/Command/LoginUserSpec.php
./spec/Model/Command/RegisterUserSpec.php
./spec/Model/CommandHandler/LoginUserHandlerSpec.php
./spec/Model/CommandHandler/RegisterUserHandlerSpec.php
./spec/Model/Identity/Event/EmailIdentityCreatedSpec.php
./spec/Model/Identity/Event/UserLoggedSpec.php
./spec/Model/Identity/Event/UserToIdentityAssignedSpec.php
./spec/Model/Identity/Exception/IdentityNotFoundSpec.php
./spec/Model/IdentitySpec.php
./spec/Model/User/Event/UserRegisteredSpec.php
./spec/Model/User/Exception/UserNotFoundSpec.php
./spec/Model/UserSpec.php
./spec/Model/ValueObject/MailSpec.php
./spec/Model/ValueObject/NameSpec.php
./spec/Model/ValueObject/PasswordSpec.php
```

## Create single ValueObject

You need to run that command:

```
proophgen vo Model/ValueObject/FirstName
```

As a result you should to see something similar to:

```
Creating files:
[v] ./src/Model/ValueObject/FirstName.php
[v] ./spec/Model/ValueObject/FirstNameSpec.php
```

## Create single Command

You need to run that command:

```
proophgen c Model/Command/RemoveUser 
```

As a result you should to see something similar to:

```
Creating files:
[v] ./src/Model/Command/RemoveUser.php
[v] ./src/Model/CommandHandler/RemoveUserHandler.php
[v] ./spec/Model/Command/RemoveUserSpec.php
[v] ./spec/Model/CommandHandler/RemoveUserHandlerSpec.php
```

## Installation

Go to [releases page on github](https://github.com/mmp4k/proophgen/releases) and download `proophgen.phar`.