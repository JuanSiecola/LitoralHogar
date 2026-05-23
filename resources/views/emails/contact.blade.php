<x-mail::message>
# Nueva consulta recibida

Alguien se contactó desde el sitio web. Aquí están sus datos:

<x-mail::panel>
**Nombre:** {{ $data['name'] }}
**Email:** {{ $data['email'] }}
**Teléfono:** {{ $data['phone'] }}
</x-mail::panel>

@if(!empty($data['property_id']))
<x-mail::panel>
 **Propiedad consultada:** #{{ $data['property_id'] }}
</x-mail::panel>
@endif

**Mensaje:**

{{ $data['message'] }}

<x-mail::button :url="'mailto:' . $data['email']" color="primary">
Responder a {{ $data['name'] }}
</x-mail::button>

_Enviado desde LitoralHogar — {{ now()->setTimezone('America/Montevideo')->format('d/m/Y H:i') }} hs_

</x-mail::message>