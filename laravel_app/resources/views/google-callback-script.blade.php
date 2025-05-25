<!DOCTYPE html>
<html>
<head>
    <title>Google Auth Callback</title>
    <script>
        window.onload = function() {
            if (window.opener) {
                // Asegúrate de que window.location.origin es el origen de tu frontend Angular
                window.opener.postMessage(
                    {
                        type: 'google-auth-callback',
                        status: '{{ $status }}', // 'success' o 'error'
                        message: '{{ $message }}'
                    },
                    window.location.origin // O el origen específico de tu frontend: 'http://localhost:4200'
                );
            }
            window.close();
        };
    </script>
</head>
<body>
<p>Procesando autenticación de Google...</p>
<p>Esta ventana se cerrará automáticamente.</p>
</body>
</html>
