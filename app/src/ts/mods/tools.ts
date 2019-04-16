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
        fetch('/index.php')
        .then(function(response: any){
            console.log('response.ok: ', response.ok)
            if(response.ok) {
                response.text().then(function(txt: string){
                console.log('muestro respuesta: ', txt)
                });
            } else {
                console.log('muestor error', 'status code: ' + response.status)
            }
        })
        .catch(function(){
            console.log('muestor error', 'status code: 000')
        })
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
     }
 }