#!/bin/bash
fission env create --name python-env --image fission/python-env
fission env create --name jvm-env --image fission/jvm-env --builder fission/jvm-env  --keeparchive --version 2
fission env create --name jvm-env-test --image fission/jvm-env --builder fission/jvm-env
fission fn create --name options-trigger --env python-env --code code/optionsHandler.py
fission fn create --name upload --env jvm-env --deploy code/d3cloud-1.0-SNAPSHOT-jar-with-dependencies.jar --entrypoint io.fission.Upload
fission fn create --name list --env jvm-env-test --deploy d3cloud-1.0-SNAPSHOT-jar-with-dependencies.jar --entrypoint io.fission.Upload
fission fn test --name list
fission httptrigger create --url /upload --method 'POST' --function upload --createingress
fission httptrigger create --url /upload --method 'OPTIONS' --function options-trigger --createingress
