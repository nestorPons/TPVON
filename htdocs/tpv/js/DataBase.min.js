const READONLY = "readonly";
const READWRITE = "readwrite";
class DataBase{
    constructor(namedb, tables = []){
        this.tables = tables
        this.data = []
        this.db = namedb
        this.conn = null
        this.objectStore = {}
        this.version = undefined
        this.indexedDB = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB;
        /* this.openConn() // For load version
            .then(e => (table != '') && this.addObjectStore(table)) */

    } 
    openConn(){
        
        if(this.conn != null) this.conn.close()

        const dataBase = this.indexedDB.open(this.db, this.version) 

        return new Promise((resolve, reject)=>{

            // Creamos la BD y sus tablas
            dataBase.onupgradeneeded = (e) => {
                console.log('onupgradeneeded...')
                const arrObjectStore = this.addObjectStore()
                let db = dataBase.result
                // Creamos un contenedor por cada "tabla"
                for (let i in arrObjectStore ){
                    var objectStore = arrObjectStore[i]
                    let storeName = objectStore.store.name
                    try{
                        let obj = db.createObjectStore(storeName,{
                            keyPath: objectStore.store.key, 
                            autoIncrement : objectStore.store.autoIncrement
                        })
                        for(let i in objectStore.fieldIndexed){
                            var index = objectStore.fieldIndexed[i]
                            var name = index.nameField || 'field' + i
                            obj.createIndex('by_' + name, name, { 
                                unique : index.unique || false
                            });
                        }
                    } catch (e) {
                        console.warn(e)
                    } finally {
                        this.objectStore = {}
                    }
                } 
            }
            dataBase.onsuccess = (e) => {
                console.log('onsuccess...')
                this.conn  = dataBase.result
                this.version = dataBase.result.version
                resolve(dataBase.result)
                this.conn.close()
            }
            dataBase.onerror = (e) => reject(e)
        })
    }
    /** 
     *  Add "tables" to bd 
     *  @params:
     *   store => {'tableX', key: 'id', autoIncrement: true}
     *   fieldIndexed => [{nameField: 'field', unique: false }]
     */
    addObjectStore( key = 'id', autoIncrement = true, fieldIndexed = []){
        if(this.conn != null) this.conn.close()
        let objectStore = []
        this.version++
        for(let i in this.tables){
            let os = {}
            var table = this.tables[i]
            os.store = {
                name: table, 
                key: key || 'id',
                autoIncrement: autoIncrement || true
            }
            os.fieldIndexed = fieldIndexed
            objectStore.push(os)
        }
        return objectStore
    }
    // Método ingreso de datos a la base datos 
    put(table, data){ 
        this.table = table
        const self = this

        this.openConn()
            .then(function(conn){
                const obj = self._openStorage(READWRITE)
                obj.add(data)
                    .oncomplete = (e)=> console.log('Put success!!')
                    .onerror = (e) =>  alert(dataBase.error.name + '\n\n' + dataBase.error.message)
            })
            .catch(e => console.warn(e))
    }
    del(key){
        this.openConn(()=>{
            const obj = this._openStorage(READWRITE)
                obj.delete(key)
                    .onerror = (e) =>  alert(dataBase.error.name + '\n\n' + dataBase.error.message)
                    .oncomplete = (e)=> console.log('delete success')
        })     
    }
    get(key){ 
        this.data = []
        this.openConn(()=>{
            const obj = this._openStorage(READONLY)
                obj.openCursor(key).onsuccess = (e) =>{
                    var result = e.target.result;
                    if (result === null) return
                    this.data.push(result.value)
                    //this.data.push(result.value)
                    result.continue()
                }
            })  
        return this.data
    }
    _openStorage(type){
        echo('_openStorage...')
 
        return this.conn
                .transaction([this.table], type)
                .objectStore(this.table)
    }
}