//Eliminamos el jwt al inicio para nueva sessión
sessionStorage.removeItem('jwt');

// Comportamientos generales de nuestros componentes de la aplicacion
// Nuevo comportamiento de los links
$(document)
// FORMULARIOS
// Verificamos los campos de verificacion con clase verify y atributo for=[id del elemento a verificar]
.on('keyup', '.verify', function(){
    app.form.verify($(this))
})
.on('change', 'form', function (e, i) {
    app.form.verify($(this))
})
// Comportamiento general de eliminación de registro
// Para su correcto funcionamiento todos los obj js tienen que tener establecida la propiedad currentId
.on('click', '.fnDelete', function (e, i) {
    if(app.mens.confirm('¿Seguro que desea eliminar el registro?')){
        let $section = $(this).parents('section'),
            $form = $(this).parents('form'), 
            controller = $form.attr('controller')
            obj = app[$section.attr('id')]

        // Envio de datos para eliminar del servidor
        app.post({
            controller: controller,
            action: 'del',
            data: {id: obj.currentId}
        },
        function(d, r){
            if(r) exist(obj.del) && obj.del()
            else app.mens.error('No se pudo eliminar el registro')
        })
     }
})

// Comportamiento general de envio de formulario al servidor
.on('submit', 'form', function (e, i) {
    e.preventDefault()
    let $this = $(this),
        _send = function(data){
            // Envio de datos
            app.post({
                controller: $this.attr('controller'),
                action: $this.attr('action'),
                data: data
            },
            function(id, success){
                data.id = id // Se usa para nuevos registros con id autoincremental
                if(success && (c = $this.attr('callback'))) eval(c)
                _hideSpiner()
            } 
        )
        },
        _hideSpiner= function(){
            // Ocultamos spinner
            $this.removeClass('sending').find('.spinner').fadeOut()
            return true
            
        }
    // Filtro bloqueo reenvio 
    if($(this).hasClass('sending')) return false 
    // Mostramos spinner
    $(this).addClass('sending').find('.spinner').hide().removeClass('hidden').fadeIn()

    let data = app.formToObject(e.currentTarget);
    // Ciframos los campos password
    if(exist(data.password)) data.password = sha256(data.password)
    
    // Validamos los datos antes de enviarlos 
    // Todos los validations tendrán que devolver con un objeto {success: ... , mens: ... , [code]....}
    let fn = $(this).attr('validation')
    if(fn){
        let callback = r => {
            if(r.success) _send(data)
            else {
                _hideSpiner() 
                if(r.code) $this.find(`[name="${r.code}]`).addClass('invalid').focus()
                app.mens.error(r.mens)
            } 
        }
        eval(fn)
    } else {
        _send(data)
    }

})
// FIN DE FORMULARIOS
.on('change', 'input', function(e){
    $parent  = $(this).parents('section');
    const 
        p = $(this).parents('section').attr('id');
    if(window[p] != undefined) window[p].change = true;

})
// Comportamiento general de los links 
.on('click', 'm-a', function(e) {
    e.preventDefault();
    let section = $(this).attr('href')
    // Comprobamos si tiene parametros get 
    // Significa que se solicita redirección
    if (section.indexOf('?') != -1) {
        let arr = section.split('?')
        let get = arr[1].split('=')
        location.href = "?" + get[0] + "=" + get[1];
    } else {
        app.sections.show(section, fn => {
            // Si esta activo algún spinner lo ocutamos
            $(this).find('.spinner').addClass('hidden');
        })
    }
    if (typeof menu != 'undefined' && menu.search.state == 1) menu.search.close()
})