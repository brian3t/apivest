#!/usr/bin/env bash

mysql -uvest -p"vTrapok)1" vest -e"UPDATE profile SET ai_point = ai_point + FLOOR( 1 + RAND( ) *100 );"