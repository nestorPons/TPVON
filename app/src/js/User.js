class User{
    constructor(id){
        this.id = id
        this.validation = false
        this.search()
    }
    lastTicket(){
        this.validation = false
        tpv.request('getLast', this.id, r => {
            this.validation = true
            r && (this.lastTicket = r.fecha) 
        })
    }
    search(){
        let u = DB.get('usuarios','id',this.id)[0]
        this.nombre = u.nombre 
        this.promos = u.promos
        this.lastTicket()
    }
    validate(){
        return this.validation 
    }
}