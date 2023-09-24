<template>
  <div>
    <table class="table table-hover">
      <thead>
        <tr>
          <th scope="col" v-for="(t, key) in titulos" :key="key">
            {{ t.titulo }}
          </th>
          <th
            v-if="visualizar.visivel || actualizar.visivel || remover.visivel"
          ></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(obj, chave) in dadosFiltrados" :key="chave">
          <td v-for="(valor, chaveValor) in obj" :key="chaveValor">
            <span v-if="titulos[chaveValor].tipo == 'texto'">{{ valor }}</span>
            <span v-if="titulos[chaveValor].tipo == 'fk'">{{
              getFkNome(valor)
            }}</span>
            <span v-if="titulos[chaveValor].tipo == 'data'">{{
              $filters.formataDataTempo(valor)
            }}</span>
            <span v-if="titulos[chaveValor].tipo == 'imagem'">
              <img :src="'/storage/' + valor" width="30" height="30" />
            </span>
          </td>
          <td
            v-if="visualizar.visivel || actualizar.visivel || remover.visivel"
          >
            <button
              v-if="visualizar.visivel"
              class="btn btn-outline-primary btn-sm"
              :data-bs-toggle="visualizar.dataToggle"
              :data-bs-target="visualizar.dataTarget"
              @click="setStore(obj)"
            >
              Visualizar
            </button>
            <button
              v-if="actualizar.visivel"
              class="btn btn-outline-primary btn-sm"
              :data-bs-toggle="actualizar.dataToggle"
              :data-bs-target="actualizar.dataTarget"
              @click="setStore(obj)"
            >
              Actualizar
            </button>
            <button
              v-if="remover.visivel"
              class="btn btn-outline-danger btn-sm"
              :data-bs-toggle="remover.dataToggle"
              :data-bs-target="remover.dataTarget"
              @click="setStore(obj)"
            >
              Remover
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  props: ["dados", "dadosFk", "titulos", "visualizar", "actualizar", "remover"],
  methods: {
    setStore(obj) {
      (this.$store.state.transacao.status = ""),
        (this.$store.state.transacao.mensagem = ""),
        (this.$store.state.transacao.dados = ""),
        (this.$store.state.item = obj);
    },
    getFkNome(valor) {
      let fkNome = this.dadosFk.find((item) => item.id === valor);

      if (fkNome) {
        return fkNome.nome;
      }
    },
  },
  computed: {
    dadosFiltrados() {
      let campos = Object.keys(this.titulos);
      let dadosFiltrados = [];

      this.dados.map((item) => {
        let itemFiltrado = {};

        campos.forEach((campo) => {
          itemFiltrado[campo] = item[campo];
        });
        dadosFiltrados.push(itemFiltrado);
      });
      return dadosFiltrados;
    },
  },
};
</script>