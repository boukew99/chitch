#!/usr/bin/env php

# Help
Commands are meant to be run like `command/<command_name>.php` from the project root directory.

Available commands:
<?= implode(
  "\n",
  array_map(fn($command) => "- `" . basename($command, '.php') . "`", glob("command/*.php")),
) ?>
