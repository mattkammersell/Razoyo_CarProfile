# Razoyo_CarProfile Module

## Description

The Razoyo_CarProfile module allows customers to choose from a predefined list of cars and save their selection to their profile. This module adds a new menu item "My Car" to the customer dashboard and displays information about the selected car.

## Installation

To install the Razoyo_CarProfile module, follow these steps:

### 1. Add the Repository

Add the GitHub repository to your Composer configuration:

```bash
composer config repositories.razoyo_car_profile vcs https://github.com/mattkammersell/Razoyo_CarProfile
```

### 2. Require the module
```bash
composer require razoyo/module-car-profile
```

### 3. Install the module

```bash
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento setup:static-content:deploy
bin/magento cache:clean
```
