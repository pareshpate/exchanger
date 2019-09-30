# Exchange

## Installation Steps

```bash
git clone git@github.com:pareshpate/exchanger.git
cd exchanger/
```
update the database details from .env file ("DATABASE_URL=mysql://root:@localhost:3306/exchanger")

```bash
composer install
bin/console doctrine:migrations:migrate
```

To load the currency data using the below command,
```bash
bin/console doctrine:fixtures:load
```

To run the unit test case
```bash
bin/phpunit tests/Controller/RatesControllerTest.php
```

Please make sure that you have access to the following API https://api.exchangeratesapi.io/. 