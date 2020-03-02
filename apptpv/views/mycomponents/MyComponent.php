<component>
    <script>
        class MyComponent {
            constructor(id) {
                try {
                    this.cont = (typeof id == 'string') ?
                        document.getElementById(id) :
                        id;
                        if(this.cont == null) throw "contenedor nulo"; 
                } catch (e) {
                    console.warn('No se ha podido crear la funcionalidad del componente ' + id);
                    console.warn(e);
                }
            }
        }
    </script>
</component>