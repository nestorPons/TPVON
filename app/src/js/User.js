class User{
    constructor(){
        this.id = null
        this.nombre = null
    }
    getAll(){
        return app.data.users
    }
}
