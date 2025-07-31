<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="color-scheme" content="light">
    <meta name="supported-color-schemes" content="light">
    <title>{{ config('app.name') }}</title>
    <style type="text/css">
        /* Estilos generales para el cuerpo del correo */
        body {
            font-family: Arial, sans-serif; /* Tipo de letra similar al de la imagen */
            font-size: 16px; /* Tamaño de letra base */
            line-height: 1.5;
            color: #333333; /* Color de texto general: gris oscuro */
            background-color: #f7f7f7; /* Color de fondo ligero */
            margin: 0;
            padding: 0;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        /* Contenedor principal del correo */
        .wrapper {
            width: 100%;
            table-layout: fixed;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            background-color: #f7f7f7;
        }
        /* Contenido central del correo */
        .main-content {
            width: 100%;
            max-width: 600px; /* Ancho máximo similar al de la imagen */
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px; /* Bordes ligeramente redondeados */
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); /* Sombra suave */
            padding: 30px; /* Espaciado interno */
            box-sizing: border-box; /* Para que el padding no afecte el width */
        }
        /* Estilo para el header (logo) */
        .header-logo-container {
            text-align: center;
            padding-bottom: 35px; /* Más espacio entre el logo y el título */
        }
        .header-logo-container img {
            max-width: 150px; /* Tamaño del logo similar al de la imagen */
            height: auto;
        }
        /* Estilos para el título principal del mensaje */
        .title {
            font-size: 26px; /* Tamaño de letra del título principal */
            color: #2c3e50; /* Color de texto: gris oscuro, no morado */
            margin-bottom: 25px;
            text-align: center;
            font-weight: bold;
        }
        /* Estilos para los encabezados de sección (Datos del Paciente, Detalles de la Solicitud) */
        .section-title {
            font-size: 18px; /* Tamaño de letra para subtítulos */
            color: #2c3e50; /* Color de texto: gris oscuro, no morado */
            margin-top: 20px;
            margin-bottom: 10px;
            font-weight: bold;
        }
        /* Estilos para los párrafos generales */
        p {
            font-size: 16px; /* Tamaño de letra para párrafos */
            color: #333333; /* Color de texto general: gris oscuro */
            margin-bottom: 10px;
        }
        /* Estilos para las listas de detalles */
        .detail-list {
            list-style-type: none; /* Sin viñetas */
            padding: 0;
            margin: 0;
        }
        .detail-list li {
            margin-bottom: 8px; /* Espacio entre ítems de la lista */
            font-size: 15px; /* Tamaño de letra para los detalles */
            color: #555555; /* Color ligeramente más claro para los valores */
        }
        .detail-list strong {
            color: #333333; /* Negrita para las etiquetas */
            /* width: 150px; Esto puede causar problemas en algunos clientes de correo. */
            /* display: inline-block; Si no se alinea, se puede volver a considerar, pero puede afectar la responsividad. */
        }
        /* Estilos para el pie de página */
        .footer {
            text-align: center;
            margin-top: 35px;
            font-size: 13px; /* Tamaño de letra del pie de página */
            color: #888888;
            padding-top: 20px;
            border-top: 1px solid #eeeeee; /* Línea divisoria suave */
        }

        /* Responsive styles */
        @media only screen and (max-width: 620px) {
            .main-content {
                width: auto !important;
                margin: 0 10px !important;
                padding: 20px !important;
            }
            .title {
                font-size: 22px !important;
            }
            .section-title {
                font-size: 17px !important;
            }
        }
    </style>
</head>
<body>
    <table class="wrapper" role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="center" valign="top">
                <table class="main-content" role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td class="header-logo-container">
                            {{-- Ruta a tu logo de Cedicaf. Asegúrate de que el nombre del archivo es correcto. --}}
                            <img src="{{ $message->embed(public_path('imagenes/logo.png')) }}" style="max-width: 150px; height: auto; display: block; margin: 0 auto;">
                        </td>
                    </tr>

                    <tr>
                        <td align="center">
                            <h1 class="title">Solicitud de cita confirmada</h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 0 0 20px 0;">
                            <p>Hola {{ $pacienteNombre ?? 'Usuario' }},</p>
                            <p>Su solicitud de cita ha sido agendada exitosamente. Aquí tienes los detalles:</p>

                            <h3 class="section-title">Datos del Paciente:</h3>
                            <ul class="detail-list">
                                <li><strong>Nombre Completo:</strong> {{ $pacienteNombre ?? '' }} {{ $pacienteApellido ?? '' }}</li>
                                <li><strong>Número de Identificación:</strong> {{ $pacienteIdentificacion ?? '' }}</li>
                                <li><strong>Celular:</strong> {{ $pacienteCelular ?? '' }}</li>
                           
                            </ul>

                            <h3 class="section-title">Detalles de la Solicitud:</h3>
                            <ul class="detail-list">
                    
        
                             <li><strong>Numero de Solicitud:</strong> {{ $idSolicitud ?? 'No disponible' }}</li>
    <li><strong>Fecha Agendada:</strong> {{ $fecha ?? 'No asignada' }}</li>
    <li><strong>Hora Agendada:</strong> {{ $hora ?? 'No asignada' }}</li>
   <li><strong>Procedimiento:</strong> {{ $procedimiento ?? 'No disponible' }}</li>
    <li><strong>Nombre de la Sede:</strong> {{ $sedeNombre }}</li>
    <li><strong>Dirección:</strong> {{ $sedeDireccion }}</li>
    <li><strong>Ciudad:</strong> {{ $ciudadNombre }}</li>
     <li><strong>Observación:</strong> {{ $comentario ?? 'Ninguno' }}</li>


                            
                            </ul>

                            <p>Si tienes alguna pregunta, no dudes en contactarnos.</p>

                        
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
