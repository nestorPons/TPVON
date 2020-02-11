class User{
    constructor(id){
        this.id = id
        this.validation = false
        this.search()
    }
    lastTicket(){
        this.validation = false
        app.tpv.request('getLast', this.id, r => {
            this.validation = true
            r && (this.lastTicket = r.fecha) 
        })
    }
    search(){
        DB.get('usuarios','id',this.id)
            .then(d => d[0])
            .then(u => {
                this.nombre = u.nombre 
                this.promos = u.promos
                this.lastTicket()
            })
    }
    validate(){
        return this.validation 
    }
}