// Funciones para desarrollo
var echo = ($arg: any): void => console.log($arg)

 // Funcion para recorrer dom al estilo jq
var $y = function($arg: string = ''): any{
    // $('.mi').style.background = '#000'
    let selected = document.querySelectorAll($arg)

    return (selected.length > 1 )
        ? selected 
        : selected[0];
} 

var app = {
     post(data: object){
        $.post('index.php', data, function(html){

        }, 'json')
     },
     get(data: any){
        if (typeof data.controller === 'undefined') return false
        $.get('index.php', data ,function(html){
            $('main')
                .find('section').hide().end()    
                .append(html)
        }, 'html')
      }, 
     loadSync( name: string , callback: any) {
        var s = document.createElement("script");
        s.onload = callback;
        s.src = name;
        document.querySelector("body").appendChild(s);
     },
     loadAsync(src: string, callback: any = null){
        var script: any = document.createElement('script');
        script.src = src;
    
        if(callback !== null){
            if (script.readyState) { // IE, incl. IE9
                script.onreadystatechange = function() {
                    if (script.readyState == "loaded" || script.readyState == "complete") {
                        script.onreadystatechange = null;
                    typeof callback == "function" && callback();
                    }
                };
            } else {
                script.onload = function() { // Other browsers
                    typeof callback == "function" && callback();
                };
            }
        }
        document.getElementsByTagName('body')[0].appendChild(script);
     },
     sections: {
         toggle(section: string){
            $('section').fadeOut()
            $('section#'+section).fadeIn()
         }
     },
     toJSONString( form: any ) {
         var obj = {};
         var elements = form.querySelectorAll( "input, select, textarea" );
         for( var i = 0; i < elements.length; ++i ) {
             var element = elements[i];
             var name = element.name;
             var value = element.value;
             
             if( name ) {
                 obj[name] = value;
             }
         }
    
         return JSON.stringify( obj );
     }

 }


