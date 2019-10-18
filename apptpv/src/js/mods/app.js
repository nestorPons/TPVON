const app = {
    timeZone    : Intl.DateTimeFormat().resolvedOptions().timeZone, 
    GET         : $_GET,
    // Peticiones con datos 
    post (data, callback, error = true) {
        if (typeof data.controller === 'undefined'){ 
            this.mens.error({success :false, error: 'No se ha asignado  controlador'})
            return false
        }
        if (typeof data.db === 'undefined') data.db = $_GET['db']

        $.post('index.php', data, (respond, status, xhr, dataType) => {
            let data = null
            // La respuesta puede ser json o html 
            try {
                echo('JSON response...')
                // comprobamos si es json
                data = JSON.parse(respond);
                // la respuesta es JSON
                console.log(data)
                
                // Imprimimos mensaje de error si lo hay 
                if(error)
                if( (isEmpty(data.success) || 
                    data.success == false || 
                    data.success == 0 ) && 
                    exist(data.mens)) {
                        this.mens.error(data.mens||'No se ha podido rehalizar la petición!')
                        return false
                    }    
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
        .fail(fn => {
            this.mens.error( "Fallo de conexión \n No se pudo guardar los datos" );
            typeof callback == "function" && callback(null, 0)
          })
    },
    // Carga de zonas por método get
    get (data) {
        if (typeof data.controller === 'undefined') return false;

        for (let i in this.GET) data[i] = this.GET[i]

        $.get('index.php', data, html => {
            // Cargamos la seccion en diferentes lugares dependiendo en que zona nos encontramos
            $container = ($('main').length != 0) ? $('main') : $('body')
            $container
                .find('section').hide().end()
                .append(html)

            // Inicializamos el método de carga del objeto
            if(app[data.controller] != undefined )
                if(exist(app[data.controller].load)) app[data.controller].load()
            this.sections.inicialize(data.controller) 
        }, 'html');
    },
    loadSync (name, callback) {
        var s = document.createElement("script");
        s.onload = callback;
        s.src = name;
        document.querySelector("body").appendChild(s);
    },
    loadAsync (src, callback) {
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
            alert('ERROR!! \n' + mens);
        },
        confirm(mens){
            return confirm(mens) 
        }
    },
    sections    : {
        active  :  null,
        last    : null,
        toggle(section, callback) {

            if ($('section#'+section).is(':visible')) return 
            let $mainSection = $('section')
            if($('#appadmin').length || $('#appuser').length ){
                $mainSection = $('section').find('section')
            } 
            $mainSection.fadeOut('fast', f => {
                $('section#' + section).fadeIn()
                typeof callback === 'function' && callback()
                this.inicialize(section)
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
            this.last = this.active
            // Comprueba que  la seccion existe o no 
            if($('section#' + section).length){
                // Si existe oculta todas menos la solicitada
                app.sections.toggle(section)
            }else{

                // Manda una petición para la nueva vista
                app.get({
                    controller: section,
                    action: 'view'
                }, f => {
                    this.inicialize(section)
                })
            }
            this.onblur()
        },
        // Comportamiento de la sección activa al cargarse 
        inicialize(section){
            if (section == 'appadmin') section = 'tpv'
            this.active = section
            
            let activeZone = app[this.active]
            if(activeZone){
                // Cargamos los botones de herramientas
                typeof activeZone.buttons != 'undefined' &&
                typeof activeZone.buttons == 'object' && 
                menu.buttons.show(activeZone.buttons)
    
                // Se cargan 
                typeof activeZone.open != 'undefined' &&
                typeof activeZone.open == 'function' && 
                activeZone.open()
            }
        },
        // Comportamiento de los botones de herramientas según la seccion que esté activa
        next(){
            typeof app[this.active].next == 'function' && app[this.active].next()
        },
        prev(){
            typeof app[this.active].prev == 'function' && app[this.active].prev()
        },
        del(){
            typeof app[this.active].del == 'function' && app[this.active].del()
        },
        add(){
            typeof app[this.active].add == 'function' && app[this.active].add()
        },
        print(){
            typeof app[this.active].print == 'function' && app[this.active].print()
        },
        filter(){
            typeof app[this.active].filter == 'function' && app[this.active].filter()
        },       
        search(){

        },
        onblur(){
            if(app[this.last] != undefined && typeof app[this.last].onblur == 'function'){
                app[this.last].onblur(f => {
                    app[this.last].change = false
                })
            }
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
                    ? ((element.checked) ? element.value : element.getAttribute('default') || 0)
                    : element.value

            if (name) obj[name] = value
        }
        return obj
    },
    formToJSONString (form){
        return JSON.stringify(this.formToObject(form));
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

        horaImprimible = hora + " : " + minuto  

        $('.clock').val(horaImprimible) 

        //setTimeout("app.clock()",1000) 
    },
    loadDataToForm(data, form){
        if(data == undefined) return false
        var els = form.getElementsByTagName('input')
        for(const el of els){
            if(el.attributes != undefined && el.hasAttribute('name')) {
                if(el.type == 'checkbox') {
                    el.checked = data[el.attributes.name.value] > el.getAttribute('default') 
                } else el.value = data[el.attributes.name.value]
            }
        }
        els = form.getElementsByTagName('select')
        for(let i in els){
            const el = els[i]
            if(el.attributes != undefined) el.value = data[el.attributes.name.value]
        }
        return form
    }, 
    date : {
        current(){
            let f = new Date();
            return this.actual() + ' ' + f.getHours() + ':' + f.getMinutes() + ':' + f.getSeconds()
        }, 
        actual(){
            let f = new Date();
            return(f.getDate() + "/" + (f.getMonth() +1) + "/" + f.getFullYear());
        },  
        now(arg = ''){
            let f = new Date(), 
                d = f.getDate().toString().padStart(2, '0'), 
                m = (f.getMonth() +1).toString().padStart(2, '0'),
                y = f.getFullYear().toString(),
                h = f.getHours().toString().padStart(2, '0'),
                n = f.getMinutes().toString().padStart(2, '0'),
                s = f.getSeconds().toString().padStart(2, '0')
                
            switch(arg){
                case 'date': 
                    return d + "/" + m + "/" + y
                case 'hour':
                    return h + ":" + n
                case 'sql':
                    return y + '-' + m + '-' + d + ' ' + h + ':' + n + ':' + s
                default: 
                    return d + "/" + m + "/" + y + ' ' + h + ":" + n
            }
        },
        format(date, format){
            if(typeof date !== 'string') return false
            let d, m , a , h, n, s,
                f = date.split(' '),
                fecha = f[0],
                horario = f[1]

            // Si tiene horas ... 
            if(horario){   
                let x = horario.split(':')
                h = x[0]
                min = x[1]
                s = x[2]
            }
            if (fecha.indexOf("/")>0){
                let arr = fecha.split('/');
                d = ("0" + arr[0]).slice (-2);
                m = ("0" + arr[1]).slice (-2);
                a = arr[2];
            }else if(fecha.indexOf("-")>0){
                let arr  = fecha.split('-');
                d = ("0" + arr[2]).slice (-2);
                m = ("0" + arr[1]).slice (-2);
                a = arr[0];
            }else if(fecha.length==4){
                d = fecha.substr(2);
                m = fecha.substr(0,2);
                a =  fechaActual('y');
            }else if(fecha.length==8){
                d = fecha.substr(6,2);
                m = fecha.substr(4,2);
                a = fecha.substr(0,4);
            }
            switch(format) {
                case 'sql'      : return a + '-' + m + '-' + d
                case 'datetime' : return a + '-' + m + '-' + d + ' ' + h + ':' + min
                case 'print'    : return d + '/' + m + '/' + a
                case 'md'       : return m + d 
                case 'id'       : return a + m + d
                case 'day'      : return d
                case 'month'    : return m
                case 'year'     : return a
                case 'hour'     : return h + ':' + min || false
                default         : return new Date(a, m-1, d, h, min, s)
            }

        },
        diff: function (f1,f2){
            
            let d1 = new Date(this.format(f1,'sql')).getTime(),
                d2 = new Date(this.format(f2,'sql')).getTime(),
                diff = d2 - d1;

                return (diff/(1000*60*60*24) );
         },
    }
}
const DB = {
    storage : [],
    current : 0, 
    table   : null,
    key(table, key, value){
        this.get(table)
        .then(d => {    
            
        });
    },
    get(table = this.table , key, value, filter){
        return new Promise((resolve, reject) => {
            const _equalValues = function(el){
                let k = (typeof el[key] === 'string') ? el[key].toLowerCase().trim() : el[key],
                    v = (typeof value === 'string') ? value.toLowerCase().trim() : value

                if(k) return typeof k === 'number' ? k == v : k.includes(v);
                else return false;
            }
            if(table == undefined){
                // Si no le paso un indice me devuelve todos los nombres de tablas
                resolve(this.storage);
            }else{
                // Si no se pasan key o value devolvemos todos los registros            
                if(key == undefined || value == undefined )resolve(this.storage[table]);
                else resolve(this.storage[table].filter((el) => {
                    if (filter) {
                        if(filter.indexOf('==') != -1){
                            let arr = filter.split('==');
                            return _equalValues(el) && el[arr[0].trim()] == arr[1].trim();
                        }
                        else if(filter.indexOf('>') != -1){
                            let arr = filter.split('>');
                            return _equalValues(el) && el[arr[0].trim()] > arr[1].trim();       
                        }
                        else if(filter.indexOf('<') != -1){
                            let arr = filter.split('<');
                            return _equalValues(el) && el[arr[0].trim()] < arr[1].trim();                    
                        };      
                    }
                    else return _equalValues(el);
                })) || reject(false);
            };
        });
    },
    set(table = this.table, data, key, value){
        return new Promise( (resolve, reject) => {
            if(key){
                let i = this.storage[table].findIndex(el=>{
                    return el[key] == value
                })
                if(i == -1)
                    this.storage[table].push(data)
                else
                    this.storage[table][i] = data
            } else {
                //inicializa
                if ( typeof this.storage[table] == 'undefined') this.storage[table] = []
                // Guarda datos en formato array
                for(let i in data){
                    this.storage[table].push(data[i])
                } 
            }
            resolve(this.storage[table]);
        })
    },
    last(table = this.table){
        return this.get(table).then( d => d[d['length'] - 1 ]);
    },
    lastId(table = this.table){
        return this.get(table).then( d => d[d['length'] -1 ].id);
    },
    loadIndex(index){
        if( this.storage[index] != undefined ){
            this.current = index;
            return this.storage[index];
        };
    },
    next(){
        return this.loadIndex(this.current + 1);
    },
    prev(){
        return this.loadIndex(this.current - 1);
    }
}