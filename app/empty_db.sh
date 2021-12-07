#!/bin/bash

IFS=$'\n'

for table in `mysql --user=$WMORESOLUTIONS_USER --password=$WMORESOLUTIONS_PWD --host=$WMORESOLUTIONS_HOST $WMORESOLUTIONS_DB -N -e 'SHOW TABLES'`; done
    echo "DROP TABLE $table"
    mysql --user=$WMORESOLUTIONS_USER  --password=$WMORESOLUTIONS_PWD --host=$WMORESOLUTIONS_HOST $WMORESOLUTIONS_DB -e "DROP TABLE $table"
done