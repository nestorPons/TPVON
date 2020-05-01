import pymysql

# Conectar con base de datos
conexion = pymysql.connect(host="localhost", 
                           user="root", 
                           passwd="test", 
                           database="app")
cursor = conexion.cursor()

# Recuperar registros de la tabla 'Usuarios'
registros = "SELECT * FROM tickets;"

# Mostrar registros
cursor.execute(registros)
filas = cursor.fetchall()
for fila in filas:
   string = "UPDATE tickets SET total=12 WHERE id="+str(fila[0])
   cursor.execute(string)
   print(fila)

# Finalizar 
conexion.commit()
conexion.close()
