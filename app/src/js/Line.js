class Line{
    constructor(id, cod, des, qua, pri, dto) {
        this.success = false
        if (isEmpty(cod)) return false;
        this.id = id
        this.cod = cod 
        this.des = des||''
        this.qua = qua||0
        this.pri = pri||0
        this.dto = dto||0
        this.success = true
    }
    amount(){
        return this.pri * this.qua
    }
}