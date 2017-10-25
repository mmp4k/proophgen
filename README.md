# [proophgen](https://pilsniak.com/proophgen/)

[![GitHub (Pre-)Release Date](https://img.shields.io/github/release-date-pre/mmp4k/proophgen.svg?style=flat-square)]()
[![Travis](https://img.shields.io/travis/mmp4k/proophgen.svg?style=flat-square)]()
[![Coveralls github](https://img.shields.io/coveralls/github/mmp4k/proophgen.svg?style=flat-square)]()
[![Packagist](https://img.shields.io/packagist/v/pilsniak/proophgen.svg?style=flat-square)]()
[![GitHub release](https://img.shields.io/github/release/mmp4k/proophgen/all.svg?style=flat-square)]()
[![Packagist](https://img.shields.io/packagist/l/pilsniak/proophgen.svg?style=flat-square)]()
[![GitHub last commit](https://img.shields.io/github/last-commit/mmp4k/proophgen.svg?style=flat-square)]()

Why developers love CRUD? Because it's easy to automate work about it. Why developers hates DDD/CQRS? Boilerplates.

Using this small app `proophgen` and single 15th lines `yaml` file you can generate a project that contains more than 20 files and start coding. No more boilerplates.

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

And there is your result (in v0.1):

```
./Infrastructure/Identity/EventSourced.php
./Infrastructure/Identity/InMemory.php
./Infrastructure/User/EventSourced.php
./Infrastructure/User/InMemory.php
./Model/Command/LoginUser.php
./Model/Command/RegisterUser.php
./Model/CommandHandler/LoginUserHandler.php
./Model/CommandHandler/RegisterUserHandler.php
./Model/Identity/Event/EmailIdentityCreated.php
./Model/Identity/Event/UserLogged.php
./Model/Identity/Event/UserToIdentityAssigned.php
./Model/Identity/Exception/IdentityNotFound.php
./Model/Identity.php
./Model/IdentityRepository.php
./Model/User/Event/UserRegistered.php
./Model/User/Exception/UserNotFound.php
./Model/User.php
./Model/UserRepository.php
./Model/ValueObject/Mail.php
./Model/ValueObject/Name.php
./Model/ValueObject/Password.php
```

## Installation

Go to [releases page on github](https://github.com/mmp4k/proophgen/releases) and download `proophgen.phar`.