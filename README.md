# wmo-resolutions-php
WMO Resolutions PHP app. Please describe the basic functionality of the app here.

## Running the application

A test-version of the application can be run locally using the stack defined in docker-compose.yml

```
docker-compose -f "docker-compose.yml" up --build -d
```

## Data

The data for the wmo-resolutions app is stored in a MySQL-DB. The docker-compose-stack contains an will spin up an (old) version of the DB to test again. New data can be imported using MyPhpAdmin (included in the stack). 

