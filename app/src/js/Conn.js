class Connection{
    constructor(){
        this.indexedDB = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB;
    } 
    startDB(){
        this.dataBase = this.indexedDB.open(db);
    }
    exist(db){
        var dbExists = true;
        var request = window.indexeddb.open(db)
        request.onupgradeneeded = function (e){
            e.target.transaction.abort()
            dbExists = false
        }
        return dbExists
    }
}
var Conn = new Connection();