#!/bin/bash

CUR_DIR=$PWD
SELF_DIR=`dirname $0`

cd $SELF_DIR/xsl
php ../build.php $CUR_DIR
