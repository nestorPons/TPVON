<component>
    <script>
        class MyComponent {
            constructor(id) {
                try {
                    this.cont = (typeof id == 'string') ?
                        document.getElementById(id) :
                        id;
                    if(this.cont == null) throw "contenedor nulo"; 

                    this.el = null;
                } catch (e) {
                    console.warn('No se ha podido crear la funcionalidad del componente ' + id);
                    console.warn(e);
                }
            }
            value(value){
                if (value != undefined) this.el.value = value ; 
                return this.el.value;
            }
            caption(value){
                if (value != undefined) this.el.caption = value ; 
                return this.el.caption;
            }
        }
    </script>
</component>