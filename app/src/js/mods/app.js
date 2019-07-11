const app = {
    timeZone: Intl.DateTimeFormat().resolvedOptions().timeZone, 
    data: {
        storage: [],
        current: {}, 
        get(index , key, value, filter){
            const _equalValues = function(el){
                let k = (typeof el[key] === 'string') ? el[key].toLowerCase().trim() : el[key],
                    v = (typeof value === 'string') ? value.toLowerCase().trim() : value

                if(k) return typeof k === 'number' ? k == v : k.includes(v)
                else return false
             }
            // Si no se pasan key o value devolvemos todos los registros            
            if(key == undefined || value == undefined ){    
                return this.storage[index]
            }
            else return this.storage[index].filter((el) => {
                if (filter) {
                    if(filter.indexOf('==') != -1){
                        let arr = filter.split('==')
                        return _equalValues(el) && el[arr[0].trim()] == arr[1].trim()
                    }
                    else if(filter.indexOf('>') != -1){
                        let arr = filter.split('>')
                        return _equalValues(el) && el[arr[0].trim()] > arr[1].trim()         
                    }
                    else if(filter.indexOf('<') != -1){
                        let arr = filter.split('<')
                        return _equalValues(el) && el[arr[0].trim()] < arr[1].trim()                       
                    }
                    
                }
                else return _equalValues(el) 
            }) || false
        },
        set(index, data, key, value){
            
            if(key){
                let i = this.storage[index].findIndex(el=>{
                    return el[key] == value
                })
                if(i == -1)
                    this.storage[index].push(data)
                else
                    this.storage[index][i] = data
            } else {
                //inicializa
                if ( typeof this.storage[index] == 'undefined') this.storage[index] = []
                // Guarda datos en formato array
                for(let i in data){
                    this.storage[index].push(data[i])
                } 
            }


        }
    },
    GET: $_GET,
    // Peticiones con datos 
    post: function (data, callback) {
        if (typeof data.controller === 'undefined') return false
        if (typeof data.db === 'undefined') data.db = $_GET['db']

        var jqxhr = $.post('index.php', data, function (respond, status, xhr, dataType) {
            let data = null
            // La respuesta puede ser json o html 
            try {
                echo('JSON response...')
                // comprobamos si es json
                data = JSON.parse(respond);
                // la respuesta es JSON
                console.log(data)
                // Imprimimos mensaje de error si lo hay 
                if( isEmpty(data.success) || 
                    data.success == false || 
                    data.success == 0 && 
                    exist(data.mens)) 
                        app.mens.error(data.mens||'No se ha podido rehalizar la petición!')
                
            } catch(e) {
                echo('HTMLresponse...')
                // la respuesta es HTML
                html = $(respond)
                app.sections.load(html.attr('id'), html)

            } finally {
                let resp = data ? data.data : null, 
                    state = data ? data.success : false
                typeof callback == "function" && callback(resp, state)
            }
        })
    },
    // Carga de zonas por método get
    get: function (data) {
        let self = this
        if (typeof data.controller === 'undefined') return false;

        for (let i in this.GET) data[i] = this.GET[i]

        $.get('index.php', data, function (html) {
            // Cargamos la seccion en diferentes lugares dependiendo en que zona nos encontramos
            $container = ($('main').length != 0) ? $('main') : $('body')
            $container
                .find('section').hide().end()
                .append(html);
            // Inicializamos el método de carga del objeto
            if(exist(window[data.controller].load)) window[data.controller].load()
            self.sections.inicialize(data.controller) 
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
            return alert(mens);
        },
        confirm(mens, callback){
            return confirm(mens) && callback()
        }
    },
    sections: {
        active:  null, 
        toggle(section, callback) {
            let self = this
            if ($('section#'+section).is(':visible')) return 
            let $mainSection = $('section')
            if($('#appadmin').length || $('#appuser').length ){
                $mainSection = $('section').find('section')
            } 
            $mainSection.fadeOut('fast', function () {
                $('section#' + section).fadeIn()
                typeof callback === 'function' && callback()
                self.inicialize(section)
            })
        },
        load(section = '', html = jQuery){
            // Comprueba que  la seccion existe o no 
            if($('section#' + section).length){
               // Si existe oculta todas menos la solicitada
               this.toggle(section);
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
                let self = this
                // Manda una petición para la nueva vista
                app.get({
                    controller: section,
                    action: 'view'
                }, function(){
                    self.inicialize(section)
                });
            }
            
        },
        // Comportamiento de la sección activa al cargarse 
        inicialize(section){
            if (section == 'appadmin') section = 'tpv'
            this.active = section
            
            let activeZone = window[this.active]

            // Cargamos los botones de herramientas
            typeof activeZone.buttons != 'undefined' &&
            typeof activeZone.buttons == 'object' && 
            menu.buttons.show(activeZone.buttons)

            // Se cargan 
            typeof activeZone.open != 'undefined' &&
            typeof activeZone.open == 'function' && 
            activeZone.open()
        },
        // Comportamiento de los botones de herramientas según la seccion que esté activa
        next(){
            typeof window[this.active].next == 'function' && window[this.active].next()
        },
        prev(){
            typeof window[this.active].prev == 'function' && window[this.active].prev()
        },
        del(){
            typeof window[this.active].del == 'function' && window[this.active].del()
        },
        add(){
            typeof window[this.active].add == 'function' && window[this.active].add()
        },
        search(){

        }
    },
    form: {
        // Verificamo y si es erroneo nos muestra un mensaje con el atributo tile-error o un mensaje por defecto
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
        var elements = form.querySelectorAll("input, select, textarea")
        for (let i = 0; i < elements.length; ++i) {
            var element = elements[i],
                name = element.name,
                value = (element.type == 'checkbox' || element.type == 'radio' )
                    ? ((element.checked) ? element.value : element.dataset.default || 0)
                    : element.value

            if (name) obj[name] = value
        }
        return obj
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
    },
    
};