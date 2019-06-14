const app = {
    timeZone: Intl.DateTimeFormat().resolvedOptions().timeZone, 
    data: {},
    GET: $_GET,
    getData(data = {}){
        for (let i in app.GET){
            data[i] =  app.GET[i]
        }
        return data
    },
    post: function (data, callback) {
        if (typeof data.controller === 'undefined') return false
        if (typeof data.db === 'undefined') data.db = $_GET['db']
        var jqxhr = $.post('index.php', data, function (respond, status, xhr, dataType) {
            let data = null
            // La respuesta puede ser json o html 
            try {
                // comprobamos si es json
                data = JSON.parse(respond);
                // la respuesta es JSON
                echo (data)
                // Imprimimos mensaje de error si lo hay 
                if(data.success == false && exist(data.mens)) app.mens.error(data.mens)
                
            } catch(e) {
                // la respuesta es HTML
                html = $(respond)
                app.sections.load(html.attr('id'), html)

            } finally {
                let resp = data ? data.data : null
                typeof callback == "function" && callback(resp)
            }
        })
    },
    get: function (data) {
        if (typeof data.controller === 'undefined') return false;
        $.get('index.php', app.getData(data), function (html) {
            // Cargamos la seccion en diferentes lugares dependiendo en que zona nos encontramos
            $container = ($('main').length != 0) ? $('main') : $('body')
            $container
                .find('section').hide().end()
                .append(html);
            
        }, 'html');
    },
    loadSync: function (name, callback) {
        var s = document.createElement("script");
        s.onload = callback;
        s.src = name;
        document.querySelector("body").appendChild(s);
    },
    loadAsync: function (src, callback) {
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
    },
    mens: {
        error(mens){
            alert(mens);
        }
    },
    sections: {
        toggle(section, callback) {
            let $mainSection = $('section')
            if($('#appadmin').length || $('#appuser').length ){
                $mainSection = $('section').find('section')
            } 
            $mainSection.fadeOut('fast', function () {
                $('section#' + section).fadeIn()
                typeof callback === 'function' && callback()
            })
        },
        load(section = '', html = jQuery){
            // Comprueba que  la seccion existe o no 
            if($('section#' + section).length){
               // Si existe oculta todas menos la solicitada
               app.sections.toggle(section);
           }else{
               // Cargammos el codigo html
               this.toggle(section, function(){
                   html.appendTo('body')
               })
           }
        },
        show(section){
            // Comprueba que  la seccion existe o no 
            if($('section#' + section).length){
                // Si existe oculta todas menos la solicitada
                app.sections.toggle(section)
            }else{
                // Manda una petición para la nueva vista
                app.get({
                    controller: section,
                    action: 'view'
                });
            }
        }
    },
    form: {
        verify($this){
            var $this = $this
            let type  = $this.get(0).tagName, 
                _verify = function($this){
                    let mens = '', r = true
                    if($('#' + $this.attr('for')).val() != $this.val()){
                       mens = $this.attr('tile-error') || "¡Los campos no coinciden!"
                       r = false
                    } 

                    $this.get(0).setCustomValidity(mens)
                    return r
                }
            switch(type){
                case 'INPUT': return _verify($this)
                case 'FORM': 
                    // Vrerificamos si es un formulario
                    let success = true
                    $this.find('.verify').each(function(){
                        if(!_verify($(this))){
                            $(this).get(0).reportValidity()
                            success = false
                        } 
                    })
                    return success
            }
        }
    },
    formToObject (form){
        var obj = {};
        var elements = form.querySelectorAll("input, select, textarea");
        for (var i = 0; i < elements.length; ++i) {
            var element = elements[i];
            var name = element.name;
            var value = element.value;
            if (name) {
                obj[name] = value;
            }
        }
        return obj;
    },
    formToJSONString (form){
        var obj = {};
        var elements = form.querySelectorAll("input, select, textarea");
        for (var i = 0; i < elements.length; ++i) {
            var element = elements[i];
            var name = element.name;
            var value = element.value;
            if (name) {
                obj[name] = value;
            }
        }
        return JSON.stringify(obj);
    },
    clock(){ 
        momentoActual = new Date() 
        hora = momentoActual.getHours() 
        minuto = momentoActual.getMinutes() 
        segundo = momentoActual.getSeconds() 

        str_segundo = new String (segundo) 
        if (str_segundo.length == 1) 
            segundo = "0" + segundo 

        str_minuto = new String (minuto) 
        if (str_minuto.length == 1) 
            minuto = "0" + minuto 

        str_hora = new String (hora) 
        if (str_hora.length == 1) 
            hora = "0" + hora 

        horaImprimible = hora + " : " + minuto + " : " + segundo 

        $('.clock').val(horaImprimible) 

        setTimeout("app.clock()",1000) 
    } 
  
};