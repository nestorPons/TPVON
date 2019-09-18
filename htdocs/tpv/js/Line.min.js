class Line{
    constructor(id, articulo, des, cantidad, precio, dto, amo, iva) {
        this.success = false
        if (isEmpty(articulo)) return false;
        this.id = id
        this.articulo = articulo 
        this.des = des||''
        this.cantidad = cantidad||0
        this.precio = precio||0
        this.dto = dto||0
        this.amo = amo||0
        this.success = true
        this.iva = iva || 21
    }
}