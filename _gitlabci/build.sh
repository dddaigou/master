#!/bin/bash
function buildconfdir()
{
    if [ "$ConfigDir" ]
    then
        mkdir -p $ConfigDir
        chmod 777 $ConfigDir
    fi

}

if [ ! -d ${ProjectDir} ]
then
    echo Building .... ProjectDir
    cp -ap $CI_PROJECT_DIR ${ProjectDir}
    cd ${ProjectDir}
    if [ "$CI_BUILD_REF_NAME" == "master" ]
    then
        git checkout $CI_BUILD_REF_NAME
    else
        git checkout origin/$CI_BUILD_REF_NAME -b $CI_BUILD_REF_NAME
    fi
    buildconfdir
else
    cd ${ProjectDir}
    if [ -z "`git branch --list $CI_BUILD_REF_NAME|grep \*`" ]
    then
        echo Current branch is "`git branch --list $CI_BUILD_REF_NAME`"
        echo Now will checkout to $CI_BUILD_REF_NAME
        if [ "$CI_BUILD_REF_NAME" == "master" ]
        then
            git checkout $CI_BUILD_REF_NAME
        else
            git checkout origin/$CI_BUILD_REF_NAME -b $CI_BUILD_REF_NAME
        fi
        git pull
    else
        echo Current branch is $CI_BUILD_REF_NAME
        git pull
    fi
    buildconfdir
fi