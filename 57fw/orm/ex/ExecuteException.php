<?php
namespace Orm\Ex;

class ExecuteException extends OrmException {
    public function __construct($error, $qw) {
        parent::__construct('Execute error "' . $error . '" on query "' . $qw . '"');
    }
} 
