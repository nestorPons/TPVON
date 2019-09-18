// Funciones para desarrollo
var echo = function ($arg) { return console.log($arg); };
// Funcion para recorrer dom al estilo jq
var $ = function ($arg) {
    if ($arg === void 0) { $arg = ''; }
    // $('.mi').style.background = '#000'
    var selected = document.querySelectorAll($arg);
    return (selected.length > 1)
        ? selected
        : selected[0];
};
// Cargas de js
var loadSync = function (name, callback) {
    var s = document.createElement("script");
    s.onload = callback;
    s.src = name;
    document.querySelector("body").appendChild(s);
};
var loadAsync = function (src, callback) {
    if (callback === void 0) { callback = null; }
    var script = document.createElement('script');
    script.src = src;
    if (callback !== null) {
        if (script.readyState) { // IE, incl. IE9
            script.onreadystatechange = function () {
                if (script.readyState == "loaded" || script.readyState == "complete") {
                    script.onreadystatechange = null;
                    typeof callback == "function" && callback();
                }
            };
        }
        else {
            script.onload = function () {
                typeof callback == "function" && callback();
            };
        }
    }
    document.getElementsByTagName('body')[0].appendChild(script);
};
