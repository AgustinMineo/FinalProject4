// datepicker.js

document.addEventListener('DOMContentLoaded', () => {
    // Selecciona todos los campos de entrada de tipo 'date'
    const dateFields = document.querySelectorAll('input[type="date"]');
    const dateFormat = 'dd/mm/yyyy';
    
    // Obtén la fecha actual en formato YYYY-MM-DD
    const today = new Date().toISOString().split('T')[0];
    
    // Aplica la restricción de fecha mínima a todos los campos de fecha
    dateFields.forEach(field => {
        field.setAttribute('min', today )
        
    });
});
