<template>
  <label :for="id" class="form-label">{{ titulo }}</label>
  <slot></slot>
  <div :id="idHelp" class="form-text">{{ textoAjuda }}</div>
</template>

<script>
export default {
  props: ["id", "titulo", "idHelp", "textoAjuda"],
};
</script>
