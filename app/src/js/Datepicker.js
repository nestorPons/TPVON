class Datepicker{
    constructor (id){
        this.id = id
        this.date = new Date
        this.el = document.getElementById(this.id)
    
        this.dp = datepicker(this.el, {
            position: 'bl',
            startDay: 1, 
            customDays: [ 'Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
            customMonths: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            onSelect: function(el, date) {
                app.loadDate(date.getDate())
            }
        })
        this.loadEvents()
        this.print()
    }
    loadDate(d){
        this.date.setDate(d)        
        this.print()
    }
    print(){
        this.el.value = this.date.toLocaleDateString()
    }
    loadEvents(){
        document.getElementById('arrow-next_' + this.id)
            .addEventListener("click", ()=> this.loadDate(this.date.getDate() + 1));
        document.getElementById('arrow-previous_' + this.id)
        .addEventListener("click", ()=> this.loadDate(this.date.getDate() - 1));
    }
}