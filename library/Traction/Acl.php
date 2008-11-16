<?php

class Traction_Acl extends Zend_Acl {

    public function __construct( Zend_Auth $auth )
    {

        // Resources

        // We will need resources for

        // Authentication
        $this->add( new Zend_Acl_Resource( 'auth' ) );

        // User Management
        $this->add( new Zend_Acl_Resource( 'users' ) );

        // Data Management
        $this->add( new Zend_Acl_Resource( 'data' ) );
/*
        // Report Management
        $this->add( new Zend_Acl_Resource( 'reports' ) );

        // Report Viewing


        // Client Code Management

        // Client Management
        $this->add( new Zend_Acl_Resource( 'clients' ) );

        // Roles

        // We will need roles for
*/
        // Guest
        $this->addRole( new Zend_Acl_Role( 'guest' ) );

        // Client
        $this->addRole( new Zend_Acl_Role( 'client' ), 'guest' );

        // Consultant
        $this->addRole( new Zend_Acl_Role( 'consultant'), 'client' );

        // Admin
        $this->addRole( new Zend_Acl_Role( 'admin' ), 'consultant' );

        // Assign Access Rules
        $this->allow( 'guest', 'auth' );
        $this->allow( 'guest', 'data', 'upload' );

        // Client rules (nothing yet)

        // Consultant Rules
        $this->allow( 'consultant', 'data' );

        // Admin Rules
     //   $this->allow( 'admin', 'data' );

    }

}