/* global window, document */
if ( !window._babelPolyfill ) {
  require( 'babel-polyfill' );
}
import $ from 'jquery';


class Admin {
  constructor() {
  };
}

window.LockdownWP = new Admin();
