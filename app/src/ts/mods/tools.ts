// Funcion para desarrollo
var echo = ($arg: string): void => console.log($arg)


// Funcion para recorrer dom al estilo jq
var $ = function( $arg: string = '' ) : any{
    // $('.mi').style.background = '#000'
    let selected = document.querySelectorAll($arg)

    return (selected.length > 1 )
        ? selected 
        : selected[0];
}