# todo-laravel-vue

Se ha unificado el proyecto moviendo los archivos de vue dentro de laravel

## Funcionalidades

* Como usuario puede adicionar un item a la lista simplemente indicando un Título y opcionalmente(puede estar vacía) una descripción (Se crea con estado 'Pending')

* Como usuario puedo modificar el estado de un item:
  * 'Pending' a 'In Progress'
  * *'In Progress' a 'Completed'
  * In Progress' a 'Pending'
  * Una vez en estado 'Completed' no puede cambiarse

* Como usuario puedo eliminar un ítem de la lista independientemente de su estado
* Como usuario puedo ver un reporte/tabla donde se me muestre lo siguiente:
  * Tiempo promedio que llevan las tarea desde su creación a estar completadas
  * Promedio de tareas eliminadas/tareas creadas
