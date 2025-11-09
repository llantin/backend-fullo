<!-- resources/views/emails/purchase_details.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de tu Compra</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 20px; border-radius: 8px;">
        <h2 style="text-align: center; color: #2c3e50;">Gracias por tu compra, {{ $purchaseDetails['person']['name'] }}!</h2>
        <p>Tu pedido ha sido procesado con éxito. A continuación, los detalles de tu compra:</p>
        
        <h3 style="color: #34495e;">Código de Recibo: {{ $purchaseDetails['receipt']['receipt_code'] }}</h3>
        <p><strong>Descripción:</strong> {{ $purchaseDetails['receipt']['description'] }}</p>

        <h3 style="color: #34495e;">Detalles del Pedido</h3>
        <ul style="list-style-type: none; padding: 0;">
            @foreach($purchaseDetails['receipt_details'] as $item)
                <li style="padding: 10px 0; border-bottom: 1px solid #eee;">
                    <strong>{{ $item['item_name'] }}</strong> (x{{ $item['quantity'] }}) - S/ {{ $item['subtotal'] }}
                </li>
            @endforeach
        </ul>

        <h3 style="color: #34495e;">Total Pagado</h3>
        <p>S/ {{ number_format($purchaseDetails['totalAmount'], 2) }}</p>

        <h3 style="color: #34495e;">Tipo de Entrega</h3>
        <p><strong>{{ $purchaseDetails['delivery']['tipo_entrega'] === 'tienda' ? 'Recojo en Tienda' : 'Delivery' }}</strong></p>

        <h3 style="color: #34495e;">Dirección de Entrega</h3>
        <p>{{ $purchaseDetails['delivery']['direccion'] }}</p>
        <p><strong>Referencia:</strong> {{ $purchaseDetails['delivery']['referencia'] }}</p>

        <p style="text-align: center; font-size: 14px; color: #7f8c8d;">Si tienes alguna pregunta, no dudes en contactarnos.</p>
        
        <p style="text-align: center; font-size: 12px; color: #bdc3c7;">Gracias por tu compra. ¡Te esperamos pronto!</p>
    </div>
</body>
</html>
