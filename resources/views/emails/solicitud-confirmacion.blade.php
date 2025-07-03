<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="color-scheme" content="light">
    <meta name="supported-color-schemes" content="light">
  
       <style type="text/css">
        /* Estilos generales para el cuerpo del correo */
        body {
            font-family: Arial, sans-serif; /* Tipo de letra similar al de la imagen */
            font-size: 16px; /* Tamaño de letra base */
            line-height: 1.5;
            color: #555555; /* Color de texto general: gris oscuro, ajustado para ser más claro */
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
        .header-logo-container { /* Renombrado para evitar conflicto si 'header' ya existe como clase de Blade */
            text-align: center;
            padding-bottom: 35px; /* AUMENTADO: Más espacio entre el logo y el título */
        }
        .header-logo-container img {
            max-width: 150px; /* Tamaño del logo similar al de la imagen */
            height: auto;
        }
        /* Estilos para el título principal del mensaje */
        .title {
            font-size: 26px; /* Tamaño de letra del título principal */
            color: #333333; /* Color de texto: gris oscuro, no morado, ajustado para ser más claro */
            margin-bottom: 25px;
            text-align: center;
            font-weight: bold; /* Negrita como en la imagen */
        }
        /* Estilos para los encabezados de sección (Datos del Paciente, Detalles de la Solicitud) */
        .section-title {
            font-size: 18px; /* Tamaño de letra para subtítulos */
            color: #333333; /* Color de texto: gris oscuro, no morado, ajustado para ser más claro */
            margin-top: 20px;
            margin-bottom: 10px;
            font-weight: bold;
        }
        /* Estilos para los párrafos generales */
        p {
            font-size: 16px; /* Tamaño de letra para párrafos */
            color: #555555; /* Color de texto general: gris oscuro, ajustado para ser más claro */
            margin-bottom: 10px;
        }
        /* Estilos para las listas de detalles */
        .detail-list {
            list-style-type: disc; /* Cambiado a disc para mostrar viñetas */
            padding-left: 20px; /* Añadido padding para que las viñetas sean visibles */
            margin: 0;
        }
        .detail-list li {
            margin-bottom: 8px; /* Espacio entre ítems de la lista */
            font-size: 15px; /* Tamaño de letra para los detalles */
            color: #555555; /* Color ligeramente más claro para los valores, ajustado */
        }
        .detail-list strong {
            color: #333333; /* Negrita para las etiquetas, ajustado */
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
                        <td class="header-logo-container"> {{-- Tu logo, usando asset() como en el código anterior, para replicar la carga --}}
                            {{-- Si quieres incrustarlo, este es el lugar para usar $message->embed() --}}
                            <img src="{{ $message->embed(public_path('imagenes/logo.png')) }}" style="max-width: 150px; height: auto; display: block; margin: 0 auto;">
                        </td>
                    </tr>

                    <tr>
                        <td align="center">
                            <h1 class="title">Solicitud de Cita Recibida</h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 0 0 20px 0;">
                            <p><strong>Hola {{ $pacienteNombre ?? 'Usuario' }},</strong></p> <p>Hemos recibido tu solicitud de cita con éxito. A continuación, un resumen de los detalles que nos proporcionaste:</p>

                            <h3 class="section-title">Datos del Paciente:</h3>
                            <ul class="detail-list">
                                <li><strong>Nombre Completo:</strong> {{ $pacienteNombre ?? '' }} {{ $pacienteApellido ?? '' }}</li>
                                <li><strong>Número de Identificación:</strong> {{ $pacienteIdentificacion ?? '' }}</li>
                                <li><strong>Celular:</strong> {{ $pacienteCelular ?? '' }}</li>
                            </ul>

                            <h3 class="section-title">Detalles de la Solicitud:</h3>
                            <ul class="detail-list">
                                <li><strong>Procedimiento Solicitado:</strong> {{ $pacienteProcedimiento ?? 'Ninguno' }}</li>
                                <li><strong>Estado Actual:</strong> {{ $estado ?? 'Pendiente' }}</li>
                                <li><strong>Observaciones:</strong> {{ $observaciones ?? 'Ninguna' }}</li> </ul>

                            <p>Nos pondremos en contacto contigo lo antes posible para confirmar y agendar tu cita.</p>
                            <p>Gracias por confiar en nosotros,</p>
                          
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>