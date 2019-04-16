
//app.post({'controller':'micontrolador'})

// Nuevo comportamiento de los links
$('a').on('click',function(e){
    e.preventDefault();
    let section: string = $(this).attr('href')
    if($('section#'+section).length){ 
        app.sections.toggle(section);
    }
    app.get({
        controller: section,
        action: 'view'
    })
})
