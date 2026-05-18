<template>
  <!-- Reutilizar el componente de form del panel inmobiliaria -->
  <AgenteLayout>
    <PropiedadFormTabs
      :propiedad="propiedad"
      :submit-route="propiedad ? route('agente.propiedades.update', propiedad.id) : route('agente.propiedades.store')"
    />
  </AgenteLayout>
</template>