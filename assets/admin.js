jQuery(document).ready(function($) {
    
    // Agregar nueva etiqueta
    $('#add-tag').on('click', function() {
        const slug = $('#new-tag-slug').val().trim().toLowerCase();
        const bgColor = $('#new-tag-bg').val();
        const textColor = $('#new-tag-text').val();
        
        if (!slug) {
            alert('Por favor ingresa un slug para la etiqueta');
            return;
        }
        
        // Verificar si ya existe
        if ($(`input[value="${slug}"]`).length > 0) {
            alert('Esta etiqueta ya existe');
            return;
        }
        
        // Crear nueva fila
        const newRow = `
            <tr>
                <td><input type="text" name="portfolio_personalizado_opciones[tags_personalizados][${slug}][slug]" value="${slug}" readonly /></td>
                <td><input type="color" name="portfolio_personalizado_opciones[tags_personalizados][${slug}][bg_color]" value="${bgColor}" /></td>
                <td><input type="color" name="portfolio_personalizado_opciones[tags_personalizados][${slug}][text_color]" value="${textColor}" /></td>
                <td><button type="button" class="button remove-tag">Eliminar</button></td>
            </tr>
        `;
        
        $('#tags-list').append(newRow);
        
        // Limpiar inputs
        $('#new-tag-slug').val('');
        $('#new-tag-bg').val('#ddd6fe');
        $('#new-tag-text').val('#6c5ce7');
    });
    
    // Eliminar etiqueta
    $(document).on('click', '.remove-tag', function() {
        if (confirm('¿Estás seguro de eliminar esta etiqueta?')) {
            $(this).closest('tr').remove();
        }
    });
    
    // Preview de colores en tiempo real
    $('input[type="color"]').on('change', function() {
        const inputText = $(this).next('input[type="text"]');
        if (inputText.length) {
            inputText.val($(this).val());
        }
    });
    
});