// Comportamientos generales de nuestros componentes de la aplicacion
// Nuevo comportamiento de los links
$(document)
.on('click', 'a', function (e) {
    e.preventDefault();
    var section = $(this).attr('href'), data = {};
    // Comprobamos si tiene parametros get 
    // Significa que se solicita redirección
    if(section.indexOf('?') != -1){
        let arr = section.split('?')
        let get = arr[1].split('=')
        location.href ="?" + get[0] + "=" + get[1];
    } else {
        // Comprueba que  la seccion existe o no 
        if($('section#' + section).length){
            // Si existe oculta todas menos la solicitada
            app.sections.toggle(section)
        }else{
            // Manda una petición de la nueva vista
            data.controller = section
            data.action = 'view' 
            app.get(data);
        }
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
.on('submit', 'form', function (e, i) {
    e.preventDefault()
    let get = app.getData(); 
    $this = $(this)
    // Filtro bloqueo reenvio 
    if($(this).hasClass('sending')) return false 
    // Mostramos spinner
    $(this).addClass('sending').find('.spinner').hide().removeClass('hidden').fadeIn()
    let data = app.toObject(e.currentTarget);
    if(exist(data.password)){ 
        data.password = sha256(data.password)
    }
    // Envio de datos
    app.post({
        db: get['db'],
        controller: $this.attr('controller'),
        action: $this.attr('action'),
        data: JSON.stringify(data)
    },
        function(){
            // Ocultamos spinner
            $this.removeClass('sending').find('.spinner').fadeOut()
        }
    )
})
// FIN DE FORMULARIOS
.on('change', 'select', function () {
    var selclass = 'valid';
    if ($(this).val() != "")
        $(this).addClass(selclass)
    else
        $(this).removeClass(selclass)
})
.on('keypress', 'input.keyEnterOut, select.keyEnterOut', function (e) {
    if(e.key == 'Enter') {
        let ind = $(".keyEnterOut").index(this)
        $(".keyEnterOut").eq(ind + 1).focus()
    }
})