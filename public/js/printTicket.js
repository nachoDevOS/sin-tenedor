async function printTicket(url, sale, fallbackUrl) {
    // alert(sale);
    // alert(url);
    const printServiceUrl = url;
    toastr.options.escapeHtml = false;

    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), 2000); // Timeout de 2 segundos

    try {
        // Intenta alcanzar el servicio. 'no-cors' es para una simple verificación de conectividad.
        await fetch(printServiceUrl, { signal: controller.signal, mode: 'no-cors' });
        clearTimeout(timeoutId);

        console.log(`✅ El servicio de impresión en ${printServiceUrl} está ACTIVO.`);

        // Construir el array de detalles para el servicio de impresión
        const details = sale.sale_details.map(item => {
            // alert(item.item_sale.name);
            const quantity = parseFloat(item.quantity);
            return {
                quantity: quantity % 1 === 0 ? parseInt(quantity) : quantity,
                product: item.item_sale.name,
                total: parseFloat(item.amount)
            };
        });

        // Construir el objeto de datos para enviar
        const data = {
            template: 'ticket',
            sale_number: sale.ticket,
            sale_type: sale.typeSale,
            details: details,
            date: sale.created_at,
        };

        // Enviar los datos al servicio de impresión
        await fetch(`${printServiceUrl}/print`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify(data)
        });
        console.log('✅ Datos enviados al servicio de impresión correctamente.');
        toastr.success('Imprimiendo ticket...', '<i class="fa fa-print"></i> Exito...');
    } catch (error) {
        clearTimeout(timeoutId);
        console.error(`❌ No se pudo conectar al servicio de impresión en ${printServiceUrl}. Imprimiendo desde el navegador.`, error.message);
        console.log('Abriendo ventana de impresión del navegador...');
        // alert(sale.id);
        window.open(`${fallbackUrl}/${sale.id}`, "Recibo", `width=700, height=700`)
        toastr.warning('No se pudo conectar al servicio de impresión. Usando impresión del navegador.', '<i class="fa fa-exclamation-triangle"></i> Advertencia');
        // window.print(); // Si el servicio falla, usa la impresión del navegador como respaldo.
    }
}