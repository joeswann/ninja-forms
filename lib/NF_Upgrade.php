<?php if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'wp_ajax_ninja_forms_ajax_migrate_database', 'ninja_forms_ajax_migrate_database' );
function ninja_forms_ajax_migrate_database(){
    $migrations = new NF_Database_Migrations();
    $migrations->nuke( true, true );
    $migrations->migrate();
    echo json_encode( array( 'migrate' => 'true' ) );
    wp_die();
}

add_action( 'wp_ajax_ninja_forms_ajax_import_form', 'ninja_forms_ajax_import_form' );
function ninja_forms_ajax_import_form(){
    $import = stripslashes( $_POST[ 'import' ] ); // TODO: How to sanitize serialized string?
    $form_id = ( isset( $_POST[ 'formID' ] ) ) ? absint( $_POST[ 'formID' ] ) : '';

    Ninja_Forms()->form()->import_form( $import, $form_id, TRUE );

    if( isset( $_POST[ 'flagged' ] ) && $_POST[ 'flagged' ] ){
        $form = Ninja_Forms()->form( $form_id )->get();
        $form->update_setting( 'lock', TRUE );
        $form->save();
    }


    echo json_encode( array( 'export' => $_POST[ 'import' ], 'import' => $import ) );
    wp_die();
}

add_action( 'wp_ajax_ninja_forms_ajax_import_fields', 'ninja_forms_ajax_import_fields' );
function ninja_forms_ajax_import_fields(){
    $fields = stripslashes( $_POST[ 'fields' ] ); // TODO: How to sanitize serialized string?
    $fields = maybe_unserialize( $fields );

    foreach( $fields as $field ) {
        Ninja_Forms()->form()->import_field( $field, $field[ 'id' ], TRUE );
    }

    echo json_encode( array( 'export' => $_POST[ 'fields' ], 'import' => $fields ) );
    wp_die();
}