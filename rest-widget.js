jQuery( document ).ready( function( $ ) {

	$.ajax({
  url: "https://stevenword.com/wp-json/wp/v2/posts",
  beforeSend: function( xhr ) {
    xhr.overrideMimeType( "text/plain; charset=x-user-defined" );
  }
})
  .done(function( data ) {
    if ( console && console.log ) {
      console.log( "Sample of data:", data.slice( 0, 100 ) );
    }
  });

});
