Feature Flags Bundle
====================

A small bundle that provides basic tools for hosting a Symfony-based application.

Installation
------------

Create the env var in your `.env`:

```dotenv
HOSTING_TIER=development
```

Then create the config in `config/packages/hosting.yaml`:

```yaml
hosting:
    # add a unique identifier of your project installation here
    installation: 'ohai' 
    hosting_tier: '%env(HOSTING_TIER)%'
```

Then install the package:

```bash
composer require 21torr/hosting
```
