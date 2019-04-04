# Modelo relacional de empresa




empresa(#id, nombre, NIF
usarios(#id, nombre, fecha_nacimiento, DNI,
telefonos(#id, -id_usuario, telefono
login(#id, token, fecha,
tickets(#id, 
lineas(#id, -id_ticket, dto, -id_articulo, 
articulos(#id, 
historial(#id, 