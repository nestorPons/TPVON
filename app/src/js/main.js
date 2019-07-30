// Comportamientos generales de nuestros componentes de la aplicacion
// Nuevo comportamiento de los links
$(document)
.on('click', 'a', function (e) {
    e.preventDefault();
    var section = $(this).attr('href')
    // Comprobamos si tiene parametros get 
    // Significa que se solicita redirección
    if(section.indexOf('?') != -1){
        let arr = section.split('?')
        let get = arr[1].split('=')
        location.href ="?" + get[0] + "=" + get[1];
    } else {
        app.sections.show(section)
    }
})
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
    app.mens.confirm('¿Seguro que desea eliminar el registro?' , fn =>{
        let $section = $(this).parents('section'),
            $form = $(this).parent('form'), 
            controller = $form.attr('controller')
            obj = window[$section.attr('id')]
       
        // Eliminamos el registro de la base datos local
        //obj.del()

 
        // Envio de datos para eliminar del servidor
        app.post({
            controller: controller,
            action: 'del',
            data: {id: obj.currentId}
        },
        function(r){
            exist(obj.del) && obj.del() 
        })
     })
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
            function(id){
                data.id = id // Se usa para nuevos registros con id autoincremental
                if(c = $this.attr('callback')) eval(c)
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

    if(exist(data.password)) data.password = sha256(data.password)
    
    // Validamos los datos antes de enviarlos 
    // Todos los validations tendrán que devolver con un objeto {success: ... , mens: ... , [code]....}
    if(v = $(this).attr('validation')){
        let r = eval(v)
        if(r.success) _send(data)
        else {
            _hideSpiner() 
            if(r.code) $this.find(`[name="${r.code}]`).addClass('invalid').focus()
            app.mens.error(r.mens)
        } 
    } else _send(data)

})
// FIN DE FORMULARIOS
.on('change', 'select', function () {
    var selclass = 'valid';
    if ($(this).val() != "")
        $(this).addClass(selclass)
    else
        $(this).removeClass(selclass)

    let p = $(this).parents('section').attr('id')
    window[p].change = true
})
.on('keyup', '.keyEnterOut', function (e) {
    e.preventDefault()
    if(e.key == 'Enter') {
        let ind = $(".keyEnterOut").index(this)
        if($(".keyEnterOut").eq(ind + 1).lenght)
            $(".keyEnterOut").eq(ind + 1).focus()
        else
            $(".keyEnterOut").eq(0).focus()
    }
})
.on('change', 'input', function(e){
    let p = $(this).parents('section').attr('id')
    window[p].change = true
})