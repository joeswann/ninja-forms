define([], function() {
    var controller = Marionette.Object.extend( {
        initialize: function() {
            this.listenTo( nfRadio.channel( 'number' ), 'init:model', this.maybeMinDefault );
            this.listenTo( nfRadio.channel( 'number' ), 'keyup:field', this.validateMinMax );
        },

        maybeMinDefault: function( model ) {

            if( '' == model.get( 'value' ) ){
                var min = model.get( 'num_min' );
                model.set( 'value', min );
            }
        },

        validateMinMax: function( el, model ) {
            var $el = jQuery( el );
            var value = parseInt( $el.val() );
            var min = $el.attr( 'min' );
            var max = $el.attr( 'max' );
            var step = $el.attr( 'step' );

            if( min && value < min ){
                nfRadio.channel( 'fields' ).request( 'add:error', model.get( 'id' ), 'number-min', nfi18n.fieldNumberNumMinError );
            } else {
                nfRadio.channel( 'fields' ).request( 'remove:error', model.get( 'id' ), 'number-min' );
            }

            if ( max && value > max ){
                nfRadio.channel( 'fields' ).request( 'add:error', model.get( 'id' ), 'number-max', nfi18n.fieldNumberNumMaxError );
            } else {
                nfRadio.channel( 'fields' ).request( 'remove:error', model.get( 'id' ), 'number-max' );
            }

            if( value && 0 !== value % step ){
                nfRadio.channel( 'fields' ).request( 'add:error', model.get( 'id' ), 'number-step', nfi18n.fieldNumberIncrementBy + step );
            } else {
                nfRadio.channel( 'fields' ).request( 'remove:error', model.get( 'id' ), 'number-step' );
            }
        }

    });

    return controller;
} );