const READONLY = "readonly";
const READWRITE = "readwrite";
class Conn{
    constructor(db, table = ''){
        this.table = table
        this.data = []
        this.nameDB = db
        this.db = null
        this.objectStore = {}
        this.version = undefined
        this.indexedDB = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB;
        this.startDB(()=> (table != '') && this.addObjectStore(table)) // For load version 
    } 
    startDB(callback){
        let request = this.indexedDB.open(this.nameDB, this.version) 
        request.onupgradeneeded = (e) => {
            console.log('onupgradeneeded...')
            let db = request.result
            if (Object.keys(this.objectStore).length !== 0 ){
                let storeName = this.objectStore.store.name
                try{
                    let obj = db.createObjectStore(storeName,{
                        keyPath: this.objectStore.store.key, 
                        autoIncrement : this.objectStore.store.autoIncrement
                    })
                    for(let i in this.objectStore.fieldIndexed){
                        var index = this.objectStore.fieldIndexed[i]
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
        request.onsuccess = (e) => {

            this.db  = request.result
            this.version = this.db.version
            
            typeof callback === 'function' && callback()
            this.db.close()
        }
        request.onerror = (e) => console.log(e)
    }
    /** 
     *  Add "tables" to bd 
     *  @params:
     *   store => {'tableX', key: 'id', autoIncrement: true}
     *   fieldIndexed => [{nameField: 'field', unique: false }]
    */
    addObjectStore(table, key = 'id', autoIncrement = true, fieldIndexed = []){
        this.table = table
        if(this.db != null) this.db.close()
        this.version++
        this.objectStore.store = {
            name: table || this.table || 'table' + this.countTables, 
            key: key || 'id',
            autoIncrement: autoIncrement || true
        }
        this.objectStore.fieldIndexed = fieldIndexed

        this.startDB()
    }
    put(data = {}){ 
        this.startDB(()=>{
            const obj = this._openStorage(READWRITE)
                obj.put(data)
                    .onerror = (e) =>  alert(request.error.name + '\n\n' + request.error.message)
                    .oncomplete = (e)=> console.log('Put success')
        })      
    }
    delete(key){
        this.startDB(()=>{
            const obj = this._openStorage(READWRITE)
                obj.delete(key)
                    .onerror = (e) =>  alert(request.error.name + '\n\n' + request.error.message)
                    .oncomplete = (e)=> console.log('delete success')
        })     
    }
    get(key){ 
        this.data = []
        this.startDB(()=>{
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
        return this.db
                .transaction(this.table, type)
                .objectStore(this.table)
    }
}