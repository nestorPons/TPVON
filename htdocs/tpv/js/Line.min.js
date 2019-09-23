class Line{
    constructor(id, articulo, cantidad, precio, dto) {
        this.id = id
        this.articulo = articulo 
        this.cantidad = cantidad||0
        this.precio = precio||0
        this.dto = dto||0
        this.amo = this.total()
    }
    total(){
        return this.cantidad * this.precio * (1 - this.dto / 100)
    }
}