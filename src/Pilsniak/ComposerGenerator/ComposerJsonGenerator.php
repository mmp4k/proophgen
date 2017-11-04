<?php

namespace Pilsniak\ComposerGenerator;

use Pilsniak\ProophGen\Model\FileToSave;

class ComposerJsonGenerator
{
    public function generate(): FileToSave
    {
        return new FileToSave('composer.json', $this->content());
    }

    protected function content(): string
    {
        return "{
  \"require\": {
    \"prooph/service-bus\": \"^6.1\",
    \"prooph/event-sourcing\": \"^5.2\",
    \"prooph/event-store\": \"^7.2\",
    \"prooph/pdo-event-store\": \"^1.5\",
    \"prooph/event-store-bus-bridge\": \"^3.0\",
    \"prooph/snapshotter\": \"^2.0\",
    \"prooph/pdo-snapshot-store\": \"^1.3\",
    \"ramsey/uuid\": \"^3.7\"
  },
  \"require-dev\": {
    \"phpunit/phpunit\": \"^6.4\",
    \"phpspec/phpspec\": \"^4.2\"
  },
  \"autoload\": {
    \"psr-0\": {
      \"\": \"src/\",
      \"tests\": \"tests/\"
    }
  }
}
";
    }
}
