
// Obtener elementos
const calendarioDiv = document.getElementById('calendario');
const horaSelect = document.getElementById('hora');
const fechaSeleccionadaInput = document.getElementById('fecha-seleccionada');

let fechaActual = new Date();
let mesActual = fechaActual.getMonth();
let anioActual = fechaActual.getFullYear();

function generarCalendario(mes, anio) {
    calendarioDiv.innerHTML = ''; // Limpia calendario anterior

    // Crea elemento para mostrar  mes y año actual
    const mesAnioElement = document.createElement('h3');
    const opcionesMes = { month: 'long', year: 'numeric' };
    mesAnioElement.textContent = new Date(anio, mes, 1).toLocaleDateString('es-ES', opcionesMes);
    calendarioDiv.appendChild(mesAnioElement);

    // Crea  tabla para los días del calendario
    const tablaCalendario = document.createElement('table');
    calendarioDiv.appendChild(tablaCalendario);

    // Crea fila de encabezado con los días de la semana
    const encabezadoFila = document.createElement('tr');
    const diasSemana = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
    for (const dia of diasSemana) {
        const celda = document.createElement('th');
        celda.textContent = dia;
        encabezadoFila.appendChild(celda);
    }
    tablaCalendario.appendChild(encabezadoFila);

    // Obtener el primer día del mes y el número de días en el mes
    const primerDia = new Date(anio, mes, 1);
    const ultimoDia = new Date(anio, mes + 1, 0);
    const diasEnMes = ultimoDia.getDate();
    const diaSemanaPrimerDia = primerDia.getDay(); // 0 para Domingo, 1 para Lunes, ...

    
    let filaActual = document.createElement('tr');
    
    for (let i = 0; i < diaSemanaPrimerDia; i++) {
        filaActual.appendChild(document.createElement('td'));
    }

    for (let dia = 1; dia <= diasEnMes; dia++) {
        const celda = document.createElement('td');
        celda.textContent = dia;
        celda.style.cursor = 'pointer'; 

        const mesFormato = (mes + 1).toString().padStart(2, '0');
        const diaFormato = dia.toString().padStart(2, '0');
        const fechaFormato = `${anio}-${mesFormato}-${diaFormato}`;

        celda.addEventListener('click', () => {
            console.log('Clic en día:', fechaFormato, 'Valor input fecha:', fechaSeleccionadaInput.value);
            fechaSeleccionadaInput.value = fechaFormato;
            cargarHorariosDisponibles(fechaFormato);
            const celdasCalendario = tablaCalendario.querySelectorAll('td');
            celdasCalendario.forEach(c => c.classList.remove('seleccionado'));
            celda.classList.add('seleccionado');
        });

        filaActual.appendChild(celda);

        
        if ((dia + diaSemanaPrimerDia) % 7 === 0 || dia === diasEnMes) {
            tablaCalendario.appendChild(filaActual);
            filaActual = document.createElement('tr');
        }
    }

    
    const botonAnterior = document.createElement('button');
    botonAnterior.textContent = '< Anterior';
    botonAnterior.addEventListener('click', () => {
        mesActual--;
        if (mesActual < 0) {
            mesActual = 11;
            anioActual--;
        }
        generarCalendario(mesActual, anioActual);
    });
    calendarioDiv.prepend(botonAnterior);

    const botonSiguiente = document.createElement('button');
    botonSiguiente.textContent = 'Siguiente >';
    botonSiguiente.addEventListener('click', () => {
        mesActual++;
        if (mesActual > 11) {
            mesActual = 0;
            anioActual++;
        }
        generarCalendario(mesActual, anioActual);
    });
    calendarioDiv.appendChild(botonSiguiente);
}


function cargarHorariosDisponibles(fecha) {
    horaSelect.innerHTML = '<option value="" disabled selected>Seleccionar hora</option>';

    if (fecha) {
        fetch(`php/obtener_horarios.php?fecha=${fecha}`)
            .then(response => response.json())
            .then(data => {
                if (data && Array.isArray(data)) {
                    data.forEach(hora => {
                        const option = document.createElement('option');
                        option.value = hora;
                        option.textContent = hora;
                        horaSelect.appendChild(option);
                    });
                } else if (data && data.error) {
                    console.error('Error al obtener horarios:', data.error);
                    horaSelect.innerHTML = '<option value="" disabled selected>No hay horarios disponibles</option>';
                } else {
                    console.error('Respuesta inesperada al obtener horarios:', data);
                    horaSelect.innerHTML = '<option value="" disabled selected>Error al cargar horarios</option>';
                }
            })
            .catch(error => {
                console.error('Error en la petición AJAX:', error);
                horaSelect.innerHTML = '<option value="" disabled selected>Error de conexión</option>';
            });
    }
}


generarCalendario(mesActual, anioActual);
