[![Build Status](https://travis-ci.org/subfission/doodad.svg)](https://travis-ci.org/subfission/doodad)

# Doodad
Simple methods for common linux system calls

## Installation

    $ composer require "subfission/doodad" : "dev-master"

## Usage
    $doodad = new Subfission\Doodad();
    $return_object = $doodad->ignoreErrors()->execute('ls');
    print_r( $return_object->getOutput());
    
If you would like to view output as html ready content, use this to 
get a ready-for-web implementation.

    echo $return_object->getOutputPretty($class_name)
    
Note this is not sanitized from XSRF attacks.    

### Other Options

getReturnVal()
getStatusCode()