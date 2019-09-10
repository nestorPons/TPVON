
class Db {
    constructor(table, data){
        this.table = table 
        this.storage = []
        this.current = 0
        if(data != undefined) this.set(data)
    }
    key(key, value){
        this.get(this.table)
        .then(d => {
            
        })
    }
    get(key, value, filter){
        return new Promise((resolve, reject) => {
            const _equalValues = function(el){
                let k = (typeof el[key] === 'string') ? el[key].toLowerCase().trim() : el[key],
                    v = (typeof value === 'string') ? value.toLowerCase().trim() : value

                if(k) return typeof k === 'number' ? k == v : k.includes(v)
                else return false
            }
            if(this.table == undefined){
                // Si no le paso un indice me devuelve todos los nombres de tablas
                resolve(this.storage)
            }else{
                // Si no se pasan key o value devolvemos todos los registros            
                if(key == undefined || value == undefined ){    
                    resolve(this.storage)
                }
                else resolve(
                    this.storage.filter((el) => {
                    if (filter) {
                        if(filter.indexOf('==') != -1){
                            let arr = filter.split('==')
                            return _equalValues(el) && el[arr[0].trim()] == arr[1].trim()
                        }
                        else if(filter.indexOf('>') != -1){
                            let arr = filter.split('>')
                            return _equalValues(el) && el[arr[0].trim()] > arr[1].trim()         
                        }
                        else if(filter.indexOf('<') != -1){
                            let arr = filter.split('<')
                            return _equalValues(el) && el[arr[0].trim()] < arr[1].trim()                       
                        }
                        
                    }
                    else return _equalValues(el)
                })) || reject(false)
            } 
        })
    }
    set(data, key, value){
        return new Promise( (resolve, reject) => {
            if(key){
                let i = this.storage.findIndex(el=>{
                    return el[key] == value
                })
                if(i == -1)
                    this.storage.push(data)
                else
                    this.storage[i] = data
            } else {
                //inicializa
                if ( typeof this.storage == 'undefined') this.storage = []
                // Guarda datos en formato array
                for(let i in data){
                    this.storage.push(data[i])
                } 
            }
            resolve(this.storage)
        })
    }
    next(){
        return this.loadIndex(this.current + 1)
    }
    prev(){
        return this.loadIndex(this.current - 1)
    }
    loadIndex(index){
        if( this.storage[index] != undefined ){
            this.current = index
            return this.storage[index]
        }
    }
    last(){
        
        return this.get().then( d => {
            this.current = d['length'] - 1
            return d[d['length'] - 1 ]
        })  
    }
    lastId(){
        return this.get().then( d => d[d['length'] -1 ].id)
    }}