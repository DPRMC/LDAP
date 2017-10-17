<?php
namespace DPRMC\Ldap\Exceptions;

class AuthenticationFailed extends LdapException {

    public function __construct( $message = "", $code = 0, \Throwable $previous = null ) {
        parent::__construct( $message, $code, $previous );
    }
}