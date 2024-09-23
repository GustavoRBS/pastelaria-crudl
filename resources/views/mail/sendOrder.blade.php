<!DOCTYPE html>
<html>
<head>
    <title>Detalhes do Seu Pedido</title>
</head>
<body>
    <h1>Obrigado pelo seu pedido!</h1>
    <p>Seu pedido foi criado com sucesso.</p>

    <p>ID do Pedido: {{ $orderDetails->first()->order_id }}</p>
    <p>ID do Cliente: {{ $orderDetails->first()->client_id }}</p>

    <p>Produtos:</p>
    <ul>
        @foreach ($orderDetails as $order)
            <li>{{ $order->product->name }} (ID: {{ $order->product->id }})</li>
        @endforeach
    </ul>
    
    <p>Agradecemos pelo seu negócio!</p>
</body>
</html>
